<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class activity_logs extends Model
{
    protected $table = 'activity_logs';
    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'payload'
    ];
}
