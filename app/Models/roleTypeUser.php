<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class roleTypeUser extends Model
{
    use HasFactory;

    protected $table = 'role_type_users';
    public function role()
    {
        return $this->hasMany(User::class);
    }
}
