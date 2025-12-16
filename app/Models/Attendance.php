<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;
class Attendance extends Model
{
    Use HasFactory;
    protected $table = 'attendance'; 
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'punch_in',
        'punch_out',
        'late_in',
        'early_out',
        'production_hours',
        'break_hours',
        'overtime_hours',
        'status'
    ];
    protected $casts = [
        'attendance_date' => 'date',
    ];


    public function logs()
    {
        return $this->hasMany(AttendanceLog::class, 'attendance_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getPayableOvertimeHoursAttribute()
    {
        return ($this->overtime_hours * 60) > 50
            ? $this->overtime_hours
            : 0;
    }

    public function getFormattedAttendanceDateAttribute()
    {
        return Carbon::parse($this->attendance_date)->format('d-m-Y');
    }

}
