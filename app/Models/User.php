<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\LockableTrait;
use App\Models\Employee;
use App\Models\roleTypeUser;
use App\Models\userType;

use TaylorNetwork\UsernameGenerator\FindSimilarUsernames;
use TaylorNetwork\UsernameGenerator\GeneratesUsernames;
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use LockableTrait;
    use FindSimilarUsernames;
	use GeneratesUsernames;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'status_id',
        'role_id',
        'password',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function role()
    {
        return $this->belongsTo(roleTypeUser::class);
    }
    public function status()
    {
        return $this->belongsTo(userType::class);
    }

    public function isActive()
    {
        return $this->status_id === 1;
    }
}
