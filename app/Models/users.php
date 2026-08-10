<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class users extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
        'activated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activated_at'      => 'datetime',
        'is_active'         => 'boolean',
        'password'          => 'hashed',
    ];

    public function notifications()
    {
        return $this->hasMany(\App\Models\notifications::class, 'user_id');
    }
}