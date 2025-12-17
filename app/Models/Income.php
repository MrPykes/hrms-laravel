<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

     protected $fillable = [
        'client_name',
        'amount',
        'status',
        'payroll_start_date',
        'payroll_end_date',
        'account'
    ];
    protected $casts = [
        'payroll_start_date' => 'date',
        'payroll_end_date' => 'date',
    ];
}
