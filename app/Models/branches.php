<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class branches extends Model
{
    protected $table = 'branches';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'is_active'
    ];
}
