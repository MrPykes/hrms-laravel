<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use DateTime;
use App\Models\Employee;
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

        // find today's attendance
        $attendance = $employee->attendances()->where('attendance_date', $today)->first();

        try {
            if (!$attendance) {
                // punch in
                $employee->punchInOutAttendance([
                    'attendance_date' => $today,
                    'punch_in' => $timeNow,
                ]);
                Toastr::success('Punched IN', 'Success');
            } elseif ($attendance && empty($attendance->punch_out)) {
                // punch out
                $employee->punchInOutAttendance([
                    'attendance_date' => $today,
                    'punch_out' => $timeNow,
                ]);
                Toastr::success('Punched OUT', 'Success');
            } else {
                Toastr::warning('Already completed Punch IN and OUT today!', 'Warning');
            }
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
        }

        return back();
    }


}
