<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class visit_status_logs extends Model
{
    protected $table = 'visit_status_logs';
    protected $fillable = [
        'visit_id',
        'old_status',
        'new_status',
        'changed_by',
        'changed_at'
    ];
}
