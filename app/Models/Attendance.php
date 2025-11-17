<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;
class Attendance extends Model
{
      use HasFactory;
    protected $table = 'attendance'; 
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'punch_in',
        'punch_out',
        'production_hours',
        'break_hours',
        'overtime_hours',
        'status'
    ];

    public function logs()
    {
        return $this->hasMany(AttendanceLog::class, 'attendance_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
