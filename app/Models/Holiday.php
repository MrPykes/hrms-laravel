<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_holiday',
        'date_holiday',
    ];
    protected $casts = [
    'date_holiday' => 'date', // Laravel will automatically convert it to Carbon
];
}
