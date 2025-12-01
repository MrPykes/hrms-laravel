<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DateTime;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class AttendanceController extends Controller
{
    public function punchInOut1(Request $request)
    {
        $request->validate([
            'employeeId' => 'required',
        ]);

        $employee = Employee::findOrFail($request->employeeId);

        $today     = now()->format('Y-m-d');
        $timeNow   = now()->format('H:i:s');

        // Check today's attendance
        $attendance = $employee->attendances()
            ->where('attendance_date', $today)
            ->first();

        // ------------ LOGIC -------------
        // If no attendance today → Punch IN
        if (!$attendance) {
            $data = [
                'employee_id'     => $employee->id,
                'attendance_date' => $today,
                'punch_in'        => $timeNow,
            ];

            $employee->punchInOutAttendance($data);

            Toastr::success('Punched IN successfully', 'Success');
            return back();
        }

        // If already punched in but not punched out → Punch OUT
        if ($attendance && !$attendance->punch_out) {
            $data = [
                'employee_id'     => $employee->id,
                'attendance_date' => $today,
                'punch_out'       => $timeNow,
            ];

            $employee->punchInOutAttendance($data);

            Toastr::success('Punched OUT successfully', 'Success');
            return back();
        }

        // If both punch_in and punch_out already exist
        Toastr::warning('Already completed Punch IN and OUT today!', 'Warning');
        return back();
    }

    public function punchInOut(Request $request)
    {
        $request->validate(['employeeId' => 'required']);
        $employee = Employee::findOrFail($request->employeeId);

        $today = now()->format('Y-m-d');
        $timeNow = now()->format('H:i:s');
        $dt = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        // Get current user for activity log
        $user = Auth::user();
        $userName = $user ? $user->name : 'System';
        $userEmail = $user ? $user->email : 'system@example.com';

        // find today's attendance
        $attendance = $employee->attendances()->where('attendance_date', $today)->first();

        try {
            if (!$attendance) {
                // punch in
                $employee->punchInOutAttendance([
                    'attendance_date' => $today,
                    'punch_in' => $timeNow,
                ]);
                
                // Create activity log for punch in
                $activityLog = [
                    'name' => $employee->name ?? $userName,
                    'email' => $employee->email ?? $userEmail,
                    'description' => 'has punched in at ' . $timeNow,
                    'date_time' => $todayDate,
                ];
                DB::table('activity_logs')->insert($activityLog);
                
                Toastr::success('Punched IN', 'Success');
            } elseif ($attendance && !empty($attendance->punch_in) && empty($attendance->punch_out)) {
                // punch out - only if punch_in exists and is valid
                // Double-check punch_in is valid before attempting punch out
                
                $employee->punchInOutAttendance([
                    'attendance_date' => $today,
                    'punch_out' => $timeNow,
                    'employee_id' => $request->employeeId,
                ]);
                
                // Create activity log for punch out
                $activityLog = [
                    'name' => $employee->name ?? $userName,
                    'email' => $employee->email ?? $userEmail,
                    'description' => 'has punched out at ' . $timeNow,
                    'date_time' => $todayDate,
                ];
                DB::table('activity_logs')->insert($activityLog);
                
                Toastr::success('Punched OUT', 'Success');
            } else {
                Toastr::warning('Already completed Punch IN and OUT today!', 'Warning');
            }
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            \Log::error('Punch In/Out Error: ' . $e->getMessage(), [
                'employee_id' => $request->employeeId,
                'today' => $today,
                'attendance' => $attendance ? $attendance->toArray() : null,
                'trace' => $e->getTraceAsString()
            ]);
        }

        return back();
    }

    public function updateAttendance(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendance,id',
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'punch_in' => 'required|date_format:H:i',
            'punch_out' => 'nullable|date_format:H:i',
        ]);

        try {
            $attendance = Attendance::findOrFail($request->attendance_id);
            $employee = Employee::findOrFail($request->employee_id);

            // Prevent punch out without punch in
            if (empty($request->punch_in) && !empty($request->punch_out)) {
                Toastr::error('Cannot set punch out without punch in!', 'Error');
                return back();
            }

            $attendanceDate = $request->attendance_date;
            
            // Format punch_in: ensure H:i:s format
            $punchInTime = $request->punch_in;
            if (strlen($punchInTime) == 5) { // H:i format
                $punchIn = $punchInTime . ':00';
            } elseif (strlen($punchInTime) == 8) { // Already H:i:s format
                $punchIn = $punchInTime;
            } else {
                $punchIn = $punchInTime . ':00'; // Default: add seconds
            }
            
            // Format punch_out: ensure H:i:s format or null
            $punchOut = null;
            $punchOutTime = $request->input('punch_out'); // Use input() to safely get the value
            if (!empty($punchOutTime) && trim($punchOutTime) !== '') {
                if (strlen($punchOutTime) == 5) { // H:i format
                    $punchOut = $punchOutTime . ':00';
                } elseif (strlen($punchOutTime) == 8) { // Already H:i:s format
                    $punchOut = $punchOutTime;
                } else {
                    $punchOut = $punchOutTime . ':00'; // Default: add seconds
                }
            }

            // Default shift settings (same as in Employee model)
            $shiftStart = '08:00:00';
            $shiftEnd = '17:00:00';
            $lunchStart = '12:00:00';
            $lunchEnd = '13:00:00';
            $graceMinutes = 10;

            // Helper functions
            $parse = function($time, $refDate) {
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
                
                try {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $refDate . ' ' . $time);
                } catch (\Exception $e) {
                    // Fallback: try with H:i format
                    try {
                        $shortTime = substr($time, 0, 5);
                        if (strlen($shortTime) < 5) {
                            throw new \Exception("Invalid time format: {$time}");
                        }
                        return Carbon::createFromFormat('Y-m-d H:i', $refDate . ' ' . $shortTime);
                    } catch (\Exception $e2) {
                        throw new \Exception("Invalid time format: {$time}");
                    }
                }
            };

            $addDayIfBefore = function(Carbon $start, Carbon $end) {
                if ($end->lessThanOrEqualTo($start)) {
                    return $end->copy()->addDay();
                }
                return $end;
            };

            // Update punch in
            $attendance->punch_in = $punchIn;

            // Compute late_in with grace
            $shiftStartDT = $parse($shiftStart, $attendanceDate);
            $punchInDT = $parse($punchIn, $attendanceDate);
            $graceSeconds = $graceMinutes * 60;
            $diffSeconds = $punchInDT->diffInSeconds($shiftStartDT, false);
            $lateSeconds = max(0, $diffSeconds - $graceSeconds);
            $attendance->late_in = round($lateSeconds / 3600, 2);

            // Update punch out and compute production/overtime
            if ($punchOut && !empty(trim($punchOut))) {
                $attendance->punch_out = $punchOut;
                try {
                    $punchOutDT = $parse($punchOut, $attendanceDate);
                } catch (\Exception $e) {
                    Toastr::error('Invalid punch out time format: ' . $e->getMessage(), 'Error');
                    return back();
                }
                $punchOutDT = $addDayIfBefore($punchInDT, $punchOutDT);

                // Shift boundaries
                $shiftEndDT = $parse($shiftEnd, $attendanceDate);
                $shiftEndDT = $addDayIfBefore($shiftStartDT, $shiftEndDT);

                // Lunch window
                $lunchStartDT = $parse($lunchStart, $attendanceDate);
                $lunchEndDT = $parse($lunchEnd, $attendanceDate);
                $lunchEndDT = $addDayIfBefore($lunchStartDT, $lunchEndDT);

                // Adjust shift end if needed
                if ($punchInDT->greaterThan($shiftStartDT) && $shiftEndDT->lessThanOrEqualTo($shiftStartDT)) {
                    $shiftEndDT = $shiftEndDT->copy()->addDay();
                }

                // Work seconds (total between in & out)
                $workSeconds = $punchOutDT->diffInSeconds($punchInDT);

                // Break seconds: overlap between [punchIn, punchOut] and [lunchStart, lunchEnd]
                $breakSeconds = 0;
                if ($lunchEndDT->lessThanOrEqualTo($lunchStartDT)) {
                    $lunchEndDT = $lunchEndDT->copy()->addDay();
                }
                $overlapStart = max($punchInDT->timestamp, $lunchStartDT->timestamp);
                $overlapEnd = min($punchOutDT->timestamp, $lunchEndDT->timestamp);
                if ($overlapEnd > $overlapStart) {
                    $breakSeconds = $overlapEnd - $overlapStart;
                }

                // Production hours = work - break
                $productionSeconds = max(0, $workSeconds - $breakSeconds);

                // Early out
                $earlySeconds = 0;
                if ($punchOutDT->lessThan($shiftEndDT)) {
                    $earlySeconds = $shiftEndDT->diffInSeconds($punchOutDT, false);
                    if ($earlySeconds < 0) $earlySeconds = abs($earlySeconds);
                }
             
                // Overtime
                $overtimeSeconds = 0;
                if ($punchOutDT->greaterThan($shiftEndDT)) {
                    $overtimeSeconds = $punchOutDT->diffInSeconds($shiftEndDT);
                }

                $attendance->early_out = $earlySeconds > 0 ? gmdate('H:i:s', $earlySeconds) : '00:00:00';
                $attendance->production_hours = round($productionSeconds / 3600, 2);
                $attendance->break_hours = round($breakSeconds / 3600, 2);
                $attendance->overtime_hours = $overtimeSeconds > 0 ? round($overtimeSeconds / 3600, 2) : 0.00;
            } else {
                // If no punch out, reset computed fields
                $attendance->punch_out = null;
                $attendance->production_hours = null;
                $attendance->break_hours = null;
                $attendance->overtime_hours = null;
                $attendance->early_out = null;
            }
            
            // Compute status (simplified version)
            if ($attendance->punch_in && !$attendance->punch_out) {
                $attendance->status = ($attendance->late_in > 0) ? 'late' : 'on_time';
            } elseif ($attendance->punch_in && $attendance->punch_out) {
                $prodHours = $attendance->production_hours ?? 0;
                if ($prodHours < 4) {
                    $attendance->status = 'half_day';
                } elseif ($attendance->late_in > 0) {
                    $attendance->status = 'late';
                } elseif ($attendance->overtime_hours > 0) {
                    $attendance->status = 'overtime';
                } else {
                    $attendance->status = 'on_time';
                }
            } else {
                $attendance->status = 'absent';
            }

            $attendance->save();

            // --- Sync attendance log with the updated times ---
            $logPayload = [
                'punch_in'  => $attendance->punch_in,
                'punch_out' => $attendance->punch_out,
            ];

            $existingLog = $attendance->logs()->latest('id')->first();

            if ($existingLog) {
                $existingLog->update($logPayload);
            } elseif ($attendance->punch_in || $attendance->punch_out) {
                $attendance->logs()->create($logPayload);
            }

            Toastr::success('Attendance updated successfully. Production and overtime hours computed automatically.', 'Success');
        } catch (\Exception $e) {
            Log::error('Error updating attendance', [
                'attendance_id' => $request->attendance_id,
                'employee_id' => $request->employee_id,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            Toastr::error('Error updating attendance: ' . $e->getMessage(), 'Error');
        }

        return back();
    }

}
