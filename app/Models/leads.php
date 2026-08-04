<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class leads extends Model
{
    protected $table = 'leads';
    protected $fillable = [
        'guest_id',
        'visit_id',
        'owner_id',
        'status',
        'estimated_value'
    ];
}
