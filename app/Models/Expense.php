<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';

     protected $fillable = [
        'item',
        'purchase_from',
        'purchase_date',
        'purchased_by',
        'amount',
        'paid_by',
        'status',
        'remarks',
    ];

    public function purchaser()
    {
        return $this->belongsTo(Employee::class, 'purchased_by');
    }
}
