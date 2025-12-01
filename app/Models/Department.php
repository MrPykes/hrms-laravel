<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Position;
class Department extends Model
{
    use HasFactory;

     protected $fillable = [
        'name',
        'description',
    ];

    public function designation()
    {
        return $this->hasMany(Position::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
