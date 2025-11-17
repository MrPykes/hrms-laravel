<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\LeavesAdmin;
use App\Models\Attendance;
use App\Models\Employee;
use DB;
use Carbon\Carbon;
use DateTime;

class LeavesController extends Controller
{
    // leaves
    public function leaves()
    {
        $leaves = DB::table('leaves_admins')
                    ->join('users', 'users.rec_id', '=', 'leaves_admins.rec_id')
                    ->select('leaves_admins.*', 'users.position','users.name','users.avatar')
                    ->get();

        return view('form.leaves',compact('leaves'));
    }
    // save record
    public function saveRecord(Request $request)
    {
        $request->validate([
            'leave_type'   => 'required|string|max:255',
            'from_date'    => 'required|string|max:255',
            'to_date'      => 'required|string|max:255',
            'leave_reason' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $from_date = new DateTime($request->from_date);
            $to_date = new DateTime($request->to_date);
            $day     = $from_date->diff($to_date);
            $days    = $day->d;

            $leaves = new LeavesAdmin;
            $leaves->rec_id        = $request->rec_id;
            $leaves->leave_type    = $request->leave_type;
            $leaves->from_date     = $request->from_date;
            $leaves->to_date       = $request->to_date;
            $leaves->day           = $days;
            $leaves->leave_reason  = $request->leave_reason;
            $leaves->save();
            
            DB::commit();
            Toastr::success('Create new Leaves successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Add Leaves fail :)','Error');
            return redirect()->back();
        }
    }

    // edit record
    public function editRecordLeave(Request $request)
    {
        DB::beginTransaction();
        try {

            $from_date = new DateTime($request->from_date);
            $to_date = new DateTime($request->to_date);
            $day     = $from_date->diff($to_date);
            $days    = $day->d;

            $update = [
                'id'           => $request->id,
                'leave_type'   => $request->leave_type,
                'from_date'    => $request->from_date,
                'to_date'      => $request->to_date,
                'day'          => $days,
                'leave_reason' => $request->leave_reason,
            ];

            LeavesAdmin::where('id',$request->id)->update($update);
            DB::commit();
            
            DB::commit();
            Toastr::success('Updated Leaves successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Update Leaves fail :)','Error');
            return redirect()->back();
        }
    }

    // delete record
    public function deleteLeave(Request $request)
    {
        try {

            LeavesAdmin::destroy($request->id);
            Toastr::success('Leaves admin deleted successfully :)','Success');
            return redirect()->back();
        
        } catch(\Exception $e) {

            DB::rollback();
            Toastr::error('Leaves admin delete fail :)','Error');
            return redirect()->back();
        }
    }

    // leaveSettings
    public function leaveSettings()
    {
        return view('form.leavesettings');
    }

    // attendance admin
    public function saveAttendance(Request $request)
    {
        $request->validate([
            'employeeId'     => 'required',
            // 'attendance_date' => 'required',
        ]);

        dd($request->all());
        DB::beginTransaction();
        try {

            $attendance = Attendance::getAttendancePerEmployee($request->employeeId);
            $attendance->employee_id     = $request->employeeId;
            $attendance->attendance_date = $request->attendance_date;
            $attendance->punch_in        = $request->punch_in;
            $attendance->punch_out       = $request->punch_out;
            $attendance->production_hours= $request->production_hours;
            $attendance->break_hours     = $request->break_hours;
            $attendance->overtime_hours  = $request->overtime_hours;
            $attendance->status          = $request->status;
            $attendance->save();
            
            DB::commit();
            Toastr::success('Attendance saved successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Save Attendance fail :)','Error');
            return redirect()->back();
        }
    }
    public function attendanceIndex(Request $request)
    {
   
        $month  = $request->month ?? Carbon::now()->month;
        $year  = $request->year ?? Carbon::now()->year;
     
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $dates = range(1, $daysInMonth);

        $employees = Employee::with(['department', 'position'])
                ->where(function ($query) use ($request) {
                    if ($request->filled('name')) {
                        $query->where('name', 'like', "%{$request->name}%");
                    }
                })
                ->get();
        $data = $employees->map(function ($employee) use ($month, $year) {
            return [
                'employee' => $employee,
                'attendance' => $employee->attendanceForMonth($month, $year),
            ];
        });

    return view('form.attendance', compact('data','dates','request'));
    }

    // attendance employee
    public function AttendanceEmployee($id = null)
    {

         $user = auth()->user();

        // Check if admin
        if ($user->role === 'Administrator') {
            // Admin: can view any employee by id
            $employeeId = $id ?? 2; // fallback if not passed
        } else {
            // Normal user: can only see their own data
            $employeeId = $user->id;
        }
        $name = Employee::where('id', $employeeId)->value('name');
        $attendances = Attendance::with('logs')
                                // ->where('employee_id', auth()->user()->id)
                                ->where('employee_id', $employeeId)
                                ->orderBy('attendance_date', 'desc')
                                // ->where('attendance_date', now())
                                ->get();
        $today = Attendance::with('logs')
                                // ->where('employee_id', auth()->user()->id)
                                ->where('employee_id', $employeeId)
                                ->where('attendance_date', now()->format('Y-m-d'))
                                ->first();                       
        return view('form.attendanceemployee', compact('attendances', 'today', 'name','employeeId'));
    }

    // leaves Employee
    public function leavesEmployee()
    {
        return view('form.leavesemployee');
    }

    // shiftscheduling
    public function shiftScheduLing()
    {
        return view('form.shiftscheduling');
    }

    // shiftList
    public function shiftList()
    {
        return view('form.shiftlist');
    }
}
