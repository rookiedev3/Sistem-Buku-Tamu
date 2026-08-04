<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notifications extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'read_at'
    ];
}
