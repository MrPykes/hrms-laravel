<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceService
{
   public function getAttendance(?int $id = null)
    {
        $attendanceQuery = Attendance::with(['logs', 'employee'])
            ->where('employee_id', $id)
            ->orderBy('attendance_date', 'desc')
            ->get();
        return $attendanceQuery;
    }

    public function getAttendanceToday(?int $id = null){
         $attendanceQuery = Attendance::with(['logs', 'employee'])
            ->where('employee_id', $id)
            ->whereDate('attendance_date', now())
                ->first();
        return $attendanceQuery;
    }

    public function getEmployeeDetailsByName(string $name){
        $name = Employee::with(['department', 'position'])
                ->where('name', 'like', "%{$name}%")
                ->first();
    }

    public function getStatusEmployeeAttendance($request){

        $month = $request->month ?: now()->month;
        $year  = $request->year ?: now()->year;
        $data = Employee::with(['department', 'position'])
                ->when($request->filled('name'), fn($q) => $q->where('name', 'like', "%{$request->name}%"))
                ->when($request->filled('id'), fn($q) => $q->where('id', $request->id))
                ->get()
                ->map(fn($employee) => [
                    'employee' => $employee,
                    'attendance' => $employee->attendanceForMonth($month, $year),
                ]);
        return $data;
    }
    public function getStatusAttendance(Employee $employee, $month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year  = $year ?? now()->year;

        $start = Carbon::create($year, $month, 1);
        $end   = $start->isSameMonth(now()) ? now() : $start->copy()->endOfMonth();

        // Build dates collection
        $dates = collect();
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dates[$date->format('Y-m-d')] = null;
        }

        // Fetch attendances keyed by date
        $attendances = $employee->attendanceWithLog()
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->get()
            ->keyBy(fn($att) => $att->attendance_date->format('Y-m-d'));

        // Fetch holidays and leaves once
        $holidays = Holiday::whereBetween('date_holiday', [$start, $end])
            ->pluck('date_holiday')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->toArray();

        $leaves = LeaveRequest::with('leave_type')
            ->where('employee_id', $this->id)
            ->where('status', 'approved')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('from_date', [$start, $end])
                ->orWhereBetween('to_date', [$start, $end]);
            })
            ->get();

        // Merge data
        foreach ($dates as $date => $value) {
            if (isset($attendances[$date])) {
                $dates[$date] = $attendances[$date];
            } elseif (in_array($date, $holidays)) {
                $dates[$date] = 'Holiday';
            } else {
                $leave = $leaves->first(fn($l) => $date >= $l->from_date->format('Y-m-d') && $date <= $l->to_date->format('Y-m-d'));
                $dates[$date] = $leave ? $leave->leave_type->name : null;
            }
        }

        return $dates->toArray();
    }
    public function getBiWeeklyAttendance(Employee $employee,int $month = null,int $year = null, string $cutoff = 'full') {
        $month = $month ?? now()->month;
        $year  = $year ?? now()->year;

        // Define month start & end
        $monthStart = Carbon::create($year, $month, 1);
        $monthEnd   = $monthStart->copy()->endOfMonth();

        // Define the bi-weekly periods
        if ($cutoff === '1-15') {
            $start = $monthStart->copy();
            $end   = Carbon::create($year, $month, 15);
        } elseif ($cutoff === '16-31') {
            $start = Carbon::create($year, $month, 16);
            $end   = $monthEnd->copy();
        } else {
            // full month
            $start = $monthStart->copy();
            $end   = $monthEnd->copy();
        }

        // Generate dates range
        $dates = collect();
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $dates[$d->format('Y-m-d')] = null;
        }

        // Fetch attendances keyed by date
        $attendances = $employee->attendanceWithLog()
            ->whereBetween('attendance_date', [$start, $end])
            ->get()
            ->keyBy(fn($att) => Carbon::parse($att->attendance_date)->format('Y-m-d'));

        // Holidays
        $holidays = Holiday::whereBetween('date_holiday', [$start, $end])
            ->pluck('date_holiday')
            // ->where('status','approved')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        // Leaves
        $leaves = LeaveRequest::with('leave_type')
            ->where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('from_date', [$start, $end])
                ->orWhereBetween('to_date', [$start, $end])
                ->orWhere(function ($q) use ($start, $end) {
                    // leave covers entire range
                    $q->where('from_date', '<=', $start)
                        ->where('to_date', '>=', $end);
                });
            })
            // ->join('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            // ->pluck('leave_types.name');
            ->get();

        // Merge attendance, holiday, leave
        // foreach ($dates as $date => $value) {
        //     if (isset($attendances[$date])) {
        //         $dates[$date] = $attendances[$date];
        //     } elseif (in_array($date, $holidays)) {
        //         $dates[$date] = 'Holiday';
        //     } else {
        //         $leave = $leaves->first(function ($l) use ($date) {
        //             return $date >= Carbon::parse($l->from_date)->format('Y-m-d')
        //                 && $date <= Carbon::parse($l->to_date)->format('Y-m-d');
        //         });
        //         $dates[$date] = $leave ? $leave->leave_type->name : null;
        //     }
        // }

        foreach ($dates as $date => $value) {

            $attendance = $attendances[$date] ?? null;
            $isHoliday  = in_array($date, $holidays);
            

            $leave = $leaves->first(function ($l) use ($date) {
                return $date >= Carbon::parse($l->from_date)->format('Y-m-d')
                    && $date <= Carbon::parse($l->to_date)->format('Y-m-d');
            });
                  $dates[$date] = [
                    'attendance' => $attendance,
                    'is_holiday'    => (bool) $isHoliday,
                    'is_leave'      => $leave,
                ];
        }
        return $dates;
    }
    public function getYears(){
        return Attendance::selectRaw('YEAR(attendance_date) as year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');
    }

    public function handlePunchIn(array $data, array $options = [])
    {
        $opts = $this->mergeOptions($options);
        $attendanceDate = $data['attendance_date'];

        $attendance = Attendance::updateOrCreate(
            [
                'employee_id' => $data['id'],
                'attendance_date' => $attendanceDate,
            ],
            []
        );

        if ($attendance->punch_in) {
            return $attendance; // already punched in
        }

        $attendance->punch_in = $data['punch_in'];

        $shiftStart = $this->parseTime($opts['shift_start'], $attendanceDate);
        $punchIn    = $this->parseTime($data['punch_in'], $attendanceDate);

        $lateSeconds = max(
            0,
            $punchIn->diffInSeconds($shiftStart) - ($opts['grace_minutes'] * 60)
        );

        $attendance->late_in = round($lateSeconds / 3600, 2);
        $attendance->save();

        return $attendance;
    }

    public function handlePunchOut(array $data, array $options = [])
    {
        $opts = $this->mergeOptions($options);
        $attendanceDate = $data['attendance_date'];

        $attendance = Attendance::where('attendance_date', $attendanceDate)
        ->where('employee_id' ,$data['id'])                
        ->first();

        if (!$attendance) {
            throw new \Exception('Attendance record not found for punch out');
        }

        if ($attendance->punch_out) {
            return $attendance;
        }

        $attendance->punch_out = $data['punch_out'];

        $punchIn  = $this->parseTime($attendance->punch_in, $attendanceDate);
        $punchOut = $this->parseTime($data['punch_out'], $attendanceDate);

        if ($punchOut->lessThanOrEqualTo($punchIn)) {
            $punchOut->addDay();
        }

        $shiftStart = $this->parseTime($opts['shift_start'], $attendanceDate);
        $shiftEnd   = $this->parseTime($opts['shift_end'], $attendanceDate);
        if ($shiftEnd->lessThanOrEqualTo($shiftStart)) {
            $shiftEnd->addDay();
        }

        $workSeconds = $punchOut->diffInSeconds($punchIn);

        $breakSeconds = $this->calculateLunchBreak(
            $punchIn,
            $punchOut,
            $opts,
            $attendanceDate
        );

        $productionSeconds = max(0, $workSeconds - $breakSeconds);

        $attendance->production_hours = round($productionSeconds / 3600, 2);
        $attendance->break_hours      = round($breakSeconds / 3600, 2);
        $attendance->overtime_hours   = $punchOut->gt($shiftEnd)
            ? round($punchOut->diffInSeconds($shiftEnd) / 3600, 2)
            : 0;

        $attendance->early_out = $punchOut->lt($shiftEnd)
            ? round($shiftEnd->diffInSeconds($punchOut) / 3600, 2)
            : 0;

        $this->computeStatus($attendance, $opts);

        $attendance->save();

        return $attendance;
    }

    protected function computeStatus($attendance, array $opts)
    {
        if (!$attendance->punch_in) {
            $attendance->status = 'absent';
            return;
        }

        if (!$attendance->punch_out) {
            $attendance->status = $attendance->late_in > 0 ? 'late' : 'on_time';
            return;
        }

        $minutesWorked = ($attendance->production_hours ?? 0) * 60;

        if ($minutesWorked < $opts['half_day_minutes']) {
            $attendance->status = 'half_day';
        } elseif ($attendance->late_in > 0) {
            $attendance->status = 'late';
        } elseif ($attendance->early_out > 0) {
            $attendance->status = 'early_out';
        } elseif ($attendance->overtime_hours > 0) {
            $attendance->status = 'overtime';
        } else {
            $attendance->status = 'on_time';
        }
    }

    protected function mergeOptions(array $options)
    {
        return array_merge([
            'shift_start' => '08:00:00',
            'shift_end' => '17:00:00',
            'lunch_start' => '12:00:00',
            'lunch_end' => '13:00:00',
            'grace_minutes' => 10,
            'half_day_minutes' => 240,
        ], $options);
    }

    protected function parseTime(string $time, string $date)
    {
        return Carbon::createFromFormat('Y-m-d H:i', "$date " . substr($time, 0, 5));
    }

    protected function calculateLunchBreak($start, $end, $opts, $date)
    {
        $lunchStart = $this->parseTime($opts['lunch_start'], $date);
        $lunchEnd   = $this->parseTime($opts['lunch_end'], $date);

        if ($lunchEnd->lessThanOrEqualTo($lunchStart)) {
            $lunchEnd->addDay();
        }

        $overlapStart = max($start->timestamp, $lunchStart->timestamp);
        $overlapEnd   = min($end->timestamp, $lunchEnd->timestamp);

        return $overlapEnd > $overlapStart ? $overlapEnd - $overlapStart : 0;
    }


}
