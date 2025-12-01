<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\LeaveType;

class LeaveRequest extends Model
{
    use HasFactory;
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approve_by');
    }
    public function leave_type()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function getLeaveBalances($employeeId){
        $leaveType = LeaveType::all();
        
        $remainingLeaves = [];
        foreach ($leaveType as $type) {

            $usedLeaves = LeaveRequest::where('employee_id', $employeeId)
                            ->where('status', 'approved')
                            ->where('leave_type_id', $type->id)
                            ->sum('day'); // sum directly, no need to groupBy
                            $remainingLeaves[$type->name] = $type->number_of_leave - ($usedLeaves ?? 0); 
        }
        return $remainingLeaves;
    }

    public function getLeavePendingRequests($employeeId = null){
        return LeaveRequest::where('status', 'pending')
        ->when($employeeId, function ($query) use ($employeeId) {
            return $query->where('employee_id', $employeeId);
        })
        ->get();
    }


}
