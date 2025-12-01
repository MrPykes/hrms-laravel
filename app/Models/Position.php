<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\Employee;

class Position extends Model
{
  use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function employee()
    {
        return $this->hasMany(Employee::class);
    }

}
