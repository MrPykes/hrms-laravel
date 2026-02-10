<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;

use App\Models\LeavesAdmin;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
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
 
        $pendingRequests = (new LeaveRequest)->getLeavePendingRequests();
        $leaves = LeaveRequest::with(['employee'])->get();
        $leave_types = LeaveType::all();
        return view('form.leaves',compact('leaves','pendingRequests','leave_types'));
    }
    // save record
    public function saveRecord(Request $request)
    {
        // Convert DD-MM-YYYY to Y-m-d
        $request->merge([
            'from_date' => Carbon::createFromFormat('d-m-Y', $request->from_date)->toDateString(),
            'to_date'   => Carbon::createFromFormat('d-m-Y', $request->to_date)->toDateString(),
        ]);

       
        // Validate
        $request->validate([
            'employee_id'   => 'required|integer|exists:employees,id',
            'leave_type'    => 'required|integer|exists:leave_types,id',
            'from_date'     => 'required|date|before_or_equal:to_date',
            'to_date'       => 'required|date|after_or_equal:from_date',
            'leave_reason'  => 'required|string|max:255',
            // 'approve_by'    => 'nullable|integer|exists:employees,id',
        ]);
    
        $from_date = Carbon::parse($request->from_date);
        $to_date   = Carbon::parse($request->to_date);
        $days      = $from_date->diffInDays($to_date) + 1;

        // Check leave availability
        if (!$this->hasAvailableLeave(1, $request->leave_type, $days)) {
            Toastr::error('Employee does not have enough leave balance.', 'Error');
            return redirect()->back()->withInput();
        }
  
        DB::beginTransaction();
        try {
  
            $leaves = new LeaveRequest();
            $leaves->employee_id   = $request->employee_id;
            $leaves->leave_type_id = $request->leave_type;
            $leaves->from_date     = $request->from_date;
            $leaves->to_date       = $request->to_date;
            $leaves->day           = $days;
            $leaves->reason  = $request->leave_reason;
            $leaves->status        = 'pending';
            // $leaves->approve_by    = $request->approve_by ?? 1;
            $leaves->save();
            DB::commit();
    
            Toastr::success('Leave created successfully :)', 'Success');
            return redirect()->back();
    
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Leave creation failed', [
                'data' => $request->all(),
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            Toastr::error('Add Leaves failed :)', 'Error');
            return redirect()->back()->withInput();
        }
    }
    
    // update record
    public function updateRecordLeave(Request $request)
    {
         // Convert DD-MM-YYYY to Y-m-d
        $request->merge([
            'from_date' => Carbon::createFromFormat('d-m-Y', $request->from_date)->toDateString(),
            'to_date'   => Carbon::createFromFormat('d-m-Y', $request->to_date)->toDateString(),       
        ]);

        // Validate
        $request->validate([
            'leave_type'    => 'required|integer|exists:leave_types,id',
            'from_date'     => 'required|date|before_or_equal:to_date',
            'to_date'       => 'required|date|after_or_equal:from_date',
            'leave_reason'  => 'required|string|max:255',
        ]);
        DB::beginTransaction();
        try {

            $from_date = new DateTime($request->from_date);
            $to_date = new DateTime($request->to_date);
            $day     = $from_date->diff($to_date);
            $days    = $day->d + 1; // include start date

            $update = [
                'id'           => $request->id,
                'leave_type_id'   => $request->leave_type,
                'from_date'    => $request->from_date,
                'to_date'      => $request->to_date,
                'day'          => $days,
                'reason' => $request->leave_reason,
                'status' => $request->status,
            ];      
            LeaveRequest::where('id',$request->id)->update($update);
            
            Toastr::success('Updated Leaves successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();

            // Log the error for debugging
            Log::error('Leave update failed', [
                'leave_id' => $request->id,
                'data' => $request->all(),
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            Toastr::error('Update Leaves fail :)','Error');
            return redirect()->back();
        }
    }

    // delete record
    public function deleteLeave(Request $request)
    {
        try {

            LeaveRequest::destroy($request->id);
            Toastr::success('Leaves admin deleted successfully :)','Success');
            return redirect()->back();
        
        } catch(\Exception $e) {

            DB::rollback();

             // Log the error for debugging
            Log::error('Leave deletion failed', [
                'leave_id' => $request->id,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            Toastr::error('Leaves admin delete fail :)','Error');
            return redirect()->back();
        }
    }

    // leaveSettings
    public function leaveSettings()
    {
        $leaveTypes = LeaveType::all();
        return view('form.leavesettings', compact('leaveTypes'));
    }

    // Save new leave type
    public function saveLeaveType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'number_of_leave' => 'required|integer|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $leaveType = new LeaveType();
            $leaveType->name = $request->name;
            $leaveType->number_of_leave = $request->number_of_leave;
            $leaveType->description = $request->description;
            $leaveType->save();

            DB::commit();
            Toastr::success('Leave type created successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Leave type creation failed', [
                'data' => $request->all(),
                'error_message' => $e->getMessage(),
            ]);
            Toastr::error('Failed to create leave type :)', 'Error');
            return redirect()->back();
        }
    }

    // Update leave type
    public function updateLeaveType(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:leave_types,id',
            'name' => 'required|string|max:255',
            'number_of_leave' => 'required|integer|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            LeaveType::where('id', $request->id)->update([
                'name' => $request->name,
                'number_of_leave' => $request->number_of_leave,
                'description' => $request->description,
            ]);

            DB::commit();
            Toastr::success('Leave type updated successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Leave type update failed', [
                'id' => $request->id,
                'data' => $request->all(),
                'error_message' => $e->getMessage(),
            ]);
            Toastr::error('Failed to update leave type :)', 'Error');
            return redirect()->back();
        }
    }

    // Delete leave type
    public function deleteLeaveType(Request $request)
    {
        DB::beginTransaction();
        try {
            LeaveType::destroy($request->id);

            DB::commit();
            Toastr::success('Leave type deleted successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Leave type deletion failed', [
                'id' => $request->id,
                'error_message' => $e->getMessage(),
            ]);
            Toastr::error('Failed to delete leave type :)', 'Error');
            return redirect()->back();
        }
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

    // leaves Employee
    public function leavesEmployee($id)
    {
        // $id = auth()->user()->employee_id;

        $leaves = LeaveRequest::with(['employee','leave_type'])
                                // ->where('employee_id', auth()->user()->id)                       
                                ->where('employee_id', $id)                       
                                ->get();
        $leave_types = LeaveType::all();
        $leaveBalances = (new LeaveRequest)->getLeaveBalances($id);
        $pendingRequests = (new LeaveRequest)->getLeavePendingRequests($id);
        return view('form.leavesemployee', compact('leaves', 'leaveBalances','leave_types', 'pendingRequests','id'));
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
    // approve leave
    public function approveLeave(Request $request)
    {
        try {
            LeaveRequest::where('id', $request->id)->update(['status' => 'approved']);
            Toastr::success('Leave approved successfully :)','Success');
            return redirect()->back();
        
        } catch(\Exception $e) {

            DB::rollback();

             // Log the error for debugging
            Log::error('Leave approval failed', [
                'leave_id' => $request->id,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            Toastr::error('Leave approval failed :)','Error');
            return redirect()->back();
        }
    }

    // decline leave
    public function declineLeave(Request $request)
    {
        try {
            LeaveRequest::where('id', $request->id)->update(['status' => 'declined']);
            Toastr::success('Leave declined successfully :)','Success');
            return redirect()->back();
        
        } catch(\Exception $e) {

            DB::rollback();

             // Log the error for debugging
            Log::error('Leave decline failed', [
                'leave_id' => $request->id,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            Toastr::error('Leave decline failed :)','Error');
            return redirect()->back();
        }
    }

    protected function hasAvailableLeave($employeeId, $leaveTypeId, $requestedDays)
{
    // Total allowed leave per type (you may fetch this from leave_types table)
    $leaveType = LeaveType::find($leaveTypeId);
    $totalAllowed = $leaveType->number_of_leave ?? 0;

    // Total used leaves for this employee and leave type
    $usedLeaves = LeaveRequest::where('employee_id', $employeeId)
        ->where('leave_type_id', $leaveTypeId)
        ->where('status', 'approved') // only approved leaves count
        ->sum('day');

    $remaining = $totalAllowed - $usedLeaves;

    return $requestedDays <= $remaining;
}


}
