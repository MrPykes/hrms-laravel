<?php

namespace App\Models;
use App\Models\Department;
use App\Models\Position;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
            'name',
            'email',
            'birth_date',
            'gender',
            'role',
            'department_id',
            'position_id',
            ];
    protected $casts = [
        'birth_date' => 'date',
        'join_date'  => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function attendanceWithLog()
    {
        // return $this->hasMany(Attendance::class);
        return $this->hasMany(Attendance::class)->with('logs');
    }

    public function attendanceForMonth($month = null, $year = null)
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        // Get start and end of month
        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();

        // Stop filling if month is current month
        $today = Carbon::now();

        // If viewing current month, end at today instead of end of month
        if ($start->isSameMonth($today)) {
            $end = $today->copy();
        }

        // Build date list
        $dates = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates[$date->format('Y-m-d')] = null;
        }

        // Get all attendance for this employee in that month
        $attendances = $this->attendanceWithLog()
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->get()
            ->keyBy('attendance_date');

        // Merge attendance data into date list
        foreach ($dates as $date => $value) {
            if (isset($attendances[$date])) {
                $dates[$date] = $attendances[$date];
            }else{
                //check if holiday
                $holiday = Holiday::where('date_holiday', $date)->first();
                if($holiday){
                    $dates[$date] = 'Holiday';
                }else{
                    // check if on leave
                    $leaveRequest = LeaveRequest::where('employee_id', $this->id)
                        ->whereDate('from_date', '<=', $date)
                        ->whereDate('to_date', '>=', $date)
                        ->where('status', 'approved')
                        ->first();
                    $dates[$date] = $leaveRequest ? $leaveRequest->leave_type->name : null;
                }
            }
        }
        return $dates;
    }

    public function getAttendancePerEmployee($employeeId, $month = null, $year = null)
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        return $this->attendanceWithLog()
            ->where('employee_id', $employeeId)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->get();
    }
    public function punchInOutAttendance1($data)
    {
        // Always update or create the attendance entry for the day
        $attendance = $this->attendances()->updateOrCreate(
            [
                'employee_id'     => $this->id,
                'attendance_date' => $data['attendance_date'],
            ],
            $data
        );

         // Prepare log data (only non-null fields)
        $logData = [];

        if (!empty($data['punch_in'])) {
            $logData['punch_in'] = $data['punch_in'];
        }

        if (!empty($data['punch_out'])) {
            $logData['punch_out'] = $data['punch_out'];
        }

        // Only create a log if punch_in or punch_out is present
        if (!empty($logData)) {
            $attendance->logs()->create($logData);
        }
        return $attendance;
    }

    /**
     * Punch in/out and compute everything (grace, status, night-shift support, logs).
     *
     * $data expects:
     *  - attendance_date (Y-m-d)
     *  - punch_in (H:i:s) OR punch_out (H:i:s)
     *
     * $options can contain:
     *  - shift_start (H:i:s) default "09:00:00"
     *  - shift_end   (H:i:s) default "18:00:00"
     *  - lunch_start (H:i:s) default "12:00:00"
     *  - lunch_end   (H:i:s) default "13:00:00"
     *  - grace_minutes (int) default 10
     *  - half_day_minutes (int) default 240 (4 hours)
     */
    public function punchInOutAttendance(array $data, array $options = [])
    {
        $defaults = [
            'shift_start' => '08:00:00',
            'shift_end' => '17:00:00',
            'lunch_start' => '12:00:00',
            'lunch_end' => '13:00:00',
            'grace_minutes' => 10,
            'half_day_minutes' => 240,
        ];
        $opts = array_merge($defaults, $options);

        // ensure attendance_date
        $attendanceDate = $data['attendance_date'];

        // Get or create attendance row for the date
        $attendance = $this->attendances()->updateOrCreate(
            [
                'employee_id' => $this->id,
                'attendance_date' => $attendanceDate,
            ],
            [] // don't pass $data here so we don't accidentally overwrite
        );

        // Prevent punch out before punch in
        if (!empty($data['punch_out']) && empty($attendance->punch_in)) {
            throw new \Exception("Cannot punch out before punching in.");
        }

        // ---------- Helpers to parse times (handles spans across midnight) ----------
        $parse = function(string $time, string $refDate) {
            if (empty($time) || strlen($time) < 5) {
                throw new \Exception("Invalid time format: {$time}");
            }
            
            // Ensure time is in proper format
            $timeParts = explode(':', $time);
            if (count($timeParts) < 2) {
                throw new \Exception("Invalid time format: {$time}");
            }
            
            // If time is H:i format, convert to H:i:s
            if (count($timeParts) == 2) {
                $time = $time . ':00';
            }
            
            // time may be "H:i:s"
            try {
                return Carbon::createFromFormat('Y-m-d H:i:s', $refDate . ' ' . $time);
            } catch (\Exception $e) {
                // Fallback: try with H:i format
                try {
                    $shortTime = substr($time, 0, 5);
                    if (strlen($shortTime) >= 5) {
                        return Carbon::createFromFormat('Y-m-d H:i', $refDate . ' ' . $shortTime);
                    }
                } catch (\Exception $e2) {
                    // Ignore fallback error
                }
                throw new \Exception("Invalid time format: {$time}");
            }
        };

        $addDayIfBefore = function(Carbon $start, Carbon $end) {
            // If end <= start, assume end is next day -> add 1 day
            if ($end->lessThanOrEqualTo($start)) {
                return $end->copy()->addDay();
            }
            return $end;
        };

        // ---------- APPLY punch_in if present and not already set ----------

        if (!empty($data['punch_in']) && empty($attendance->punch_in)) {
            $attendance->punch_in = $data['punch_in'];
            
            // compute late_in with grace
            $shiftStartDT = $parse($opts['shift_start'], $attendanceDate);
            $punchInDT = $parse($data['punch_in'], $attendanceDate);

            // Calculate late time: if punchIn > shiftStart + grace, then late
            // diffInSeconds returns positive if punchIn is after shiftStart
            $graceSeconds = $opts['grace_minutes'] * 60;
            $diffSeconds = $punchInDT->diffInSeconds($shiftStartDT);
            $lateSeconds = max(0, $diffSeconds - $graceSeconds);
            
            // Convert seconds to decimal hours
            $lateHours = round($lateSeconds / 3600, 2);
            $attendance->late_in = $lateHours;  // decimal value, e.g., 0.00, 0.25, etc.
            $this->setAttendanceStatus($attendance, $opts);
        }

        // ---------- APPLY punch_out if present and not already set ----------
        if (!empty($data['punch_out']) && empty($attendance->punch_out)) {
            // Validate punch_in exists before processing punch_out
            if (empty($attendance->punch_in)) {
                throw new \Exception("Cannot punch out: punch in time is missing or invalid.");
            }
            
            // $attendance = Attendance::where('employee_id', $data['employee_id'])->where('attendance_date', $data['attendance_date'])->first();

            $attendance->punch_out = $data['punch_out'];

            // Build Carbon datetimes for computations.
            // Handle possible overnight: if punch_out <= punch_in then treat as next day.
            try {
                $punchInDT = $parse($attendance->punch_in, $attendanceDate);
                $punchOutDT = $parse($data['punch_out'], $attendanceDate);
            } catch (\Exception $e) {
                throw new \Exception("Error parsing punch times: " . $e->getMessage());
            }
            $punchOutDT = $addDayIfBefore($punchInDT, $punchOutDT);

            // Shift boundaries may be overnight as well:
            $shiftStartDT = $parse($opts['shift_start'], $attendanceDate);
            $shiftEndDT = $parse($opts['shift_end'], $attendanceDate);
            $shiftEndDT = $addDayIfBefore($shiftStartDT, $shiftEndDT);

            // Break (lunch) window. If lunch is overnight it will be adjusted similarly.
            $lunchStartDT = $parse($opts['lunch_start'], $attendanceDate);
            $lunchEndDT = $parse($opts['lunch_end'], $attendanceDate);
            $lunchEndDT = $addDayIfBefore($lunchStartDT, $lunchEndDT);

            // If punch_in is on previous day relative to shift start, adjust shift dt accordingly.
            // (This covers some night shift edge cases where punch_in is after midnight but shift started before midnight.)
            if ($punchInDT->greaterThan($shiftStartDT) && $shiftEndDT->lessThanOrEqualTo($shiftStartDT)) {
                // when shiftEnd <= shiftStart (overnight shift), ensure shiftEnd is next day relative to shiftStart
                $shiftEndDT = $shiftEndDT->copy()->addDay();
            }

            // Work seconds (total between in & out)
            $workSeconds = $punchOutDT->diffInSeconds($punchInDT);

            // Break seconds: compute overlap between [punchIn, punchOut] and [lunchStart, lunchEnd]
            $breakSeconds = 0;
            $workStart = $punchInDT;
            $workEnd = $punchOutDT;

            // make sure lunch window is expressed in same timeline as work (handle overnight)
            if ($lunchEndDT->lessThanOrEqualTo($lunchStartDT)) {
                $lunchEndDT = $lunchEndDT->copy()->addDay();
            }
            // If lunch window might be before punchIn (night shift), shift lunch window forward/backwards
            // We will check overlap across the timeline by considering both lunch window and lunch window+1day.
            $possibleLunchWindows = [$lunchStartDT->copy()->setTimezone('UTC'), $lunchStartDT->copy()->addDay(), $lunchStartDT->copy()->subDay()];
            // Simpler method: compute overlap using max(start) min(end) for the canonical lunch window
            $overlapStart = max($workStart->timestamp, $lunchStartDT->timestamp);
            $overlapEnd = min($workEnd->timestamp, $lunchEndDT->timestamp);
            if ($overlapEnd > $overlapStart) {
                $breakSeconds = $overlapEnd - $overlapStart;
            } else {
                $breakSeconds = 0;
            }

            // Production hours is work minus break
            $productionSeconds = max(0, $workSeconds - $breakSeconds);

            // Early out: if punch_out < shift_end => early_out = shift_end - punch_out
            $earlySeconds = 0;
            if ($punchOutDT->lessThan($shiftEndDT)) {
                $earlySeconds = $shiftEndDT->diffInSeconds($punchOutDT, false);
                if ($earlySeconds < 0) $earlySeconds = abs($earlySeconds);
            }

            // Late in already set at punch in; if not present set it now (edge-case where in set earlier)
            if (empty($attendance->late_in)) {
                $graceSeconds = $opts['grace_minutes'] * 60;
                $lateSeconds = max(0, $punchInDT->diffInSeconds($shiftStartDT, false) - $graceSeconds);
                $attendance->late_in = $lateSeconds > 0 ? gmdate('H:i:s', $lateSeconds) : '00:00:00';   
            }
           

            // Overtime: if punch_out > shift_end => overtime = punch_out - shift_end
            $overtimeSeconds = 0;
            if ($punchOutDT->greaterThan($shiftEndDT)) {
                $overtimeSeconds = $punchOutDT->diffInSeconds($shiftEndDT, false);
            }

            dd($productionSeconds,round($productionSeconds / 3600, 2));
            $attendance->early_out = $earlySeconds > 0 ? gmdate('H:i:s', $earlySeconds) : '00:00:00';
            $attendance->production_hours = round($productionSeconds / 3600, 2);
            $attendance->break_hours = round($breakSeconds / 3600, 2);
            $attendance->overtime_hours = $overtimeSeconds > 0 ? round($overtimeSeconds / 3600, 2) : 0.00;
            // $attendance->save();

        }

      // ---------- compute status after all fields computed ----------
        
        $attendance->save();

         // --- Sync attendance log with the updated times ---
        $logData = [];
        if (!empty($data['punch_in'])) {
            $logData['punch_in'] = $data['punch_in'];
        }
        if (!empty($data['punch_out'])) {
            $logData['punch_out'] = $data['punch_out'];
        }
        // if (!empty($logData)) {
        //     $attendance->logs()->create($logData);
        // }
      
        //  $logPayload = [
        //     'punch_in'  => $data['punch_in'],
        //     'punch_out' => $data['punch_out'],
        // ];

        $existingLog = $attendance->logs()->latest('id')->first();

        if ($existingLog) {
            $existingLog->update($logData);
        } elseif ($attendance->punch_in || $attendance->punch_out) {
            $attendance->logs()->create($logData);
        }
       
        return $attendance;
    }

    /**
     * Compute and set attendance status (on_time, late, early_out, overtime, half_day)
     */
    protected function setAttendanceStatus($attendance, array $opts = [])
    {
        // defaults if not provided
        $shiftStart = $opts['shift_start'] ?? '08:00:00';
        $shiftEnd = $opts['shift_end'] ?? '17:00:00';
        $halfDayMinutes = $opts['half_day_minutes'] ?? 240;
        $graceMinutes = $opts['grace_minutes'] ?? 10;

        // Ensure we have punch_in and punch_out to compute full status
        $punchIn = $attendance->punch_in;
        $punchOut = $attendance->punch_out;

        // default
        $status = 'absent';

        if ($punchIn && !$punchOut) {
            // Only in: consider as 'on_time' or 'late_in' (but not complete day)
            // check if late
            $punchInDT = Carbon::createFromFormat('Y-m-d H:i:s', $attendance->attendance_date . ' ' . $punchIn);
            $shiftStartDT = Carbon::createFromFormat('Y-m-d H:i:s', $attendance->attendance_date . ' ' . $shiftStart);
            $lateSeconds = max(0, $punchInDT->diffInSeconds($shiftStartDT) - ($graceMinutes * 60));

            $status = $lateSeconds > 0 ? 'late' : 'on_time';
            // half-day if production <= threshold (can't compute production without punch_out)
        } elseif ($punchIn && $punchOut) {
            // full day, compute from production_hours
            $prod = $attendance->production_hours ?? '00:00:00';
            $prodMinutes = $this->timeToMinutes($prod);

            if ($prodMinutes < $halfDayMinutes) {
                $status = 'half_day';
            } else {
                // check late
                $late = ($attendance->late_in ?? 0) > 0;
                $early = ($attendance->early_out ?? 0) > 0;
                $overtime = ($attendance->overtime_hours ?? 0) > 0;

                if ($late) {
                    $status = 'late';
                } elseif ($early) {
                    $status = 'early_out';
                } elseif ($overtime) {
                    $status = 'overtime';
                } else {
                    $status = 'on_time';
                }
            }
        } else {
            $status = 'absent';
        }

        $attendance->status = $status;
    }

    /**
     * Convert "H:i:s" to integer minutes
     */
    protected function timeToMinutes(string $hms)
    {
        if (!$hms || $hms === '00:00:00') return 0;
        list($h, $m, $s) = explode(':', $hms);
        return ((int)$h * 60) + (int)$m + round($s / 60);
    }

    /**
     * Produce a summary for a date range (daily/weekly/monthly).
     * returns an array with totals and per-day statuses
     *
     * $periodStart and $periodEnd are Carbon or Y-m-d strings
     */
    public function attendanceSummary($periodStart, $periodEnd, array $opts = [])
    {
        $start = $periodStart instanceof Carbon ? $periodStart->copy() : Carbon::parse($periodStart);
        $end = $periodEnd instanceof Carbon ? $periodEnd->copy() : Carbon::parse($periodEnd);
        $end->endOfDay();

        $attendances = $this->attendances()
            ->whereBetween('attendance_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->get();

        $summary = [
            'range_start' => $start->toDateString(),
            'range_end' => $end->toDateString(),
            'days' => $attendances->map(function($a) {
                return [
                    'date' => $a->attendance_date,
                    'punch_in' => $a->punch_in,
                    'punch_out' => $a->punch_out,
                    'production_hours' => $a->production_hours,
                    'break_hours' => $a->break_hours,
                    'overtime_hours' => $a->overtime_hours,
                    'late_in' => $a->late_in ?? '00:00:00',
                    'early_out' => $a->early_out ?? '00:00:00',
                    'status' => $a->status ?? null,
                ];
            })->all()
        ];

        // totals
        $totalProductionSeconds = 0;
        $totalBreakSeconds = 0;
        $totalOvertimeSeconds = 0;
        $counts = ['on_time'=>0,'late'=>0,'early_out'=>0,'overtime'=>0,'half_day'=>0,'absent'=>0];

        foreach ($attendances as $a) {
            $totalProductionSeconds += $this->hmsToSeconds($a->production_hours ?? '00:00:00');
            $totalBreakSeconds += $this->hmsToSeconds($a->break_hours ?? '00:00:00');
            $totalOvertimeSeconds += $this->hmsToSeconds($a->overtime_hours ?? '00:00:00');
            $st = $a->status ?? 'absent';
            if (!isset($counts[$st])) $counts[$st] = 0;
            $counts[$st]++;
        }

        $summary['totals'] = [
            'production' => gmdate('H:i:s', $totalProductionSeconds),
            'break' => gmdate('H:i:s', $totalBreakSeconds),
            'overtime' => gmdate('H:i:s', $totalOvertimeSeconds),
        ];
        $summary['counts'] = $counts;

        return $summary;
    }

    protected function hmsToSeconds($hms)
    {
        if (!$hms || $hms === '00:00:00') return 0;
        [$h,$m,$s] = explode(':', $hms);
        return ((int)$h)*3600 + ((int)$m)*60 + ((int)$s);
    }

    function timeToDecimal($time) {
        list($hours, $minutes, $seconds) = explode(':', $time);
        return $hours + ($minutes / 60) + ($seconds / 3600);
    }
    // ... other model methods ...



}
