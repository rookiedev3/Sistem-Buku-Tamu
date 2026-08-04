<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'is_active'
    ];
}
