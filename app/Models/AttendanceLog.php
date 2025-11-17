<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attendance;


class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'punch_in',
        'punch_out',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
