<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;
use App\Services\AttendanceService;
use Carbon\Carbon;
use DateTime;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }
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
        $employee   = Employee::findOrFail($request->employee_id);

        // Prevent punch out without punch in
        if (empty($request->punch_in) && !empty($request->punch_out)) {
            Toastr::error('Cannot set punch out without punch in!', 'Error');
            return back();
        }

        $attendanceDate = $request->attendance_date;

        // -----------------------------
        // Normalize Punch Times
        // -----------------------------
        $normalizeTime = function ($time) {
            if (!$time) return null;
            return strlen($time) === 5 ? $time . ':00' : $time;
        };

        $punchIn  = $normalizeTime($request->punch_in);
        $punchOut = $normalizeTime($request->punch_out);

        // -----------------------------
        // Default shift config
        // -----------------------------
        $shiftStart   = '08:00:00';
        $shiftEnd     = '17:00:00';
        $lunchStart   = '12:00:00';
        $lunchEnd     = '13:00:00';
        $graceMinutes = 10;

        // -----------------------------
        // Helpers
        // -----------------------------
        $parse = function ($time, $date) {
            return Carbon::createFromFormat('Y-m-d H:i:s', "$date $time")->second(0);
        };

        $addDayIfBefore = function (Carbon $base, Carbon $t) {
            return $t->lessThan($base) ? $t->copy()->addDay() : $t;
        };

        // -----------------------------
        // Punch In
        // -----------------------------
        $attendance->punch_in = $punchIn;
        $punchInDT  = $parse($punchIn, $attendanceDate);
        $shiftStartDT = $parse($shiftStart, $attendanceDate);

        // -----------------------------
        // Late In (CORRECT)
        // -----------------------------
        $lateRaw = $shiftStartDT->diffInSeconds($punchInDT, false);
        $lateSeconds = $lateRaw > 0 ? max(0, $lateRaw - ($graceMinutes * 60)) : 0;
        $attendance->late_in = round($lateSeconds / 3600, 2);

        // -----------------------------
        // Punch Out
        // -----------------------------
        if (!$punchOut) {
            $attendance->punch_out = null;
            $attendance->production_hours = null;
            $attendance->break_hours = null;
            $attendance->overtime_hours = null;
            $attendance->early_out = null;
            $attendance->save();
            return;
        }

        $attendance->punch_out = $punchOut;
        $punchOutDT = $parse($punchOut, $attendanceDate);
        $punchOutDT = $addDayIfBefore($punchInDT, $punchOutDT);

        // -----------------------------
        // Shift & Lunch Windows
        // -----------------------------
        $shiftEndDT   = $addDayIfBefore($shiftStartDT, $parse($shiftEnd, $attendanceDate));
        $lunchStartDT = $parse($lunchStart, $attendanceDate);
        $lunchEndDT   = $addDayIfBefore($lunchStartDT, $parse($lunchEnd, $attendanceDate));

        // -----------------------------
        // Work Seconds
        // -----------------------------
        $workSeconds = max(0, abs(
            $punchInDT->diffInSeconds($punchOutDT, false)
        ));

        // -----------------------------
        // Lunch Overlap (FIXED ✅)
        // -----------------------------
        $breakSeconds = 0;

        if ($punchOutDT->greaterThan($lunchStartDT) && $punchInDT->lessThan($lunchEndDT)) {
            $breakStart = $punchInDT->greaterThan($lunchStartDT) ? $punchInDT : $lunchStartDT;
            $breakEnd   = $punchOutDT->lessThan($lunchEndDT) ? $punchOutDT : $lunchEndDT;

            $breakSeconds = max(0, $breakStart->diffInSeconds($breakEnd));
        }

        // -----------------------------
        // Production Hours (ACCURATE ✅)
        // -----------------------------
        $productionSeconds = max(0, $workSeconds - $breakSeconds);

        // -----------------------------
        // Early Out
        // -----------------------------
        $earlySeconds = 0;
        if ($punchOutDT->lessThan($shiftEndDT)) {
            $earlySeconds = $punchOutDT->diffInSeconds($shiftEndDT);
        }

        // -----------------------------
        // Overtime
        // -----------------------------
        $overtimeSeconds = 0;
        if ($punchOutDT->greaterThan($shiftEndDT)) {
            $overtimeSeconds = $shiftEndDT->diffInSeconds($punchOutDT);
        }

        // -----------------------------
        // Save Results
        // -----------------------------
        $attendance->early_out = $earlySeconds > 0 ? gmdate('H:i:s', $earlySeconds) : '00:00:00';
        $attendance->production_hours = round($productionSeconds / 3600, 2);
        
        $attendance->break_hours = round($breakSeconds / 3600, 2);
        $attendance->overtime_hours = round($overtimeSeconds / 3600, 2);

        // -----------------------------
        // Status
        // -----------------------------
        if ($attendance->punch_in && !$attendance->punch_out) {
            $attendance->status = $attendance->late_in > 0 ? 'late' : 'on_time';
        } elseif ($attendance->punch_in && $attendance->punch_out) {
            if ($attendance->production_hours < 4) {
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

        // -----------------------------
        // Sync Logs
        // -----------------------------
        $logPayload = [
            'punch_in'  => $attendance->punch_in,
            'punch_out' => $attendance->punch_out,
        ];

        $log = $attendance->logs()->latest()->first();
        $log ? $log->update($logPayload)
            : $attendance->logs()->create($logPayload);


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

    public function index(Request $request)
    {
        $month = $request->month ?: now()->month;
        $year  = $request->year ?: now()->year;

        $dates = range(1, Carbon::create($year, $month)->daysInMonth);

        $data = $this->attendanceService->getStatusEmployeeAttendance($request);

        $years = $this->attendanceService->getYears();

        return view('form.attendance', compact('data', 'dates', 'request','years'));
    }

    // attendance employee
    public function getEmployeeAttendance($id = null)
    {
        $user = Auth::user();
        // Determine allowed employee ID
        if (in_array($user->role_id, [1, 2])) {
            $id = $id ?? $user->employee_id;
        } else {
            $id = $user->employee_id;
        }
        $employee = Employee::where('id', $id)->first();
        $attendance = $this->attendanceService->getAttendance($id);
        $today = $this->attendanceService->getAttendanceToday($id);
        return view('form.attendanceemployee', compact('attendance','today','employee'));
    }
    public function timesheets($id = null){
        $user = Auth::user();
        $id = 4;
        $employee = Employee::where('id', $id)->with('position','department')->first();
        $data = $this->attendanceService->getBiWeeklyAttendance($employee, 11, 2025, '16-31');
        return view('form.timesheets', compact('data','employee'));

    }
}
