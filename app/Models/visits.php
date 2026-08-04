<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class visits extends Model
{
    protected $table = 'visits';
    protected $primaryKey = 'id';
    protected $fillable = [
        'visit-code',
        'guest_id',
        'branch_id',
        'purpose_id',
        'source_id',
        'assigned_to',
        'scheduled_to', 
        'check_in_at', 
        'meeting_start_at', 
        'check_out_at', 
        'status', 
        'queue_number', 
        'meeting_result', 
        'potential_level', 
        'next_action', 
        'follow_up_at', 
        'is_converted_to_lead', 
        'created_by', 
        'updated_by'
        ];
}
