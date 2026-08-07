<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    public function notifications()
    {
        return $this->hasMany(\App\Models\notifications::class, 'user_id');
    }
}
