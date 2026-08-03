<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];
}
