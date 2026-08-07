<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class follow_ups extends Model
{
    protected $table = 'follow_ups';
    protected $fillable = [
        'lead_id',      // <-- WAJIB ditambah, ini yang bikin riwayat gak kesimpan
        'visit_id',
        'assigned_to',
        'due_at',
        'result',
        'status',
    ];

    public function visit()
    {
        return $this->belongsTo(visits::class, 'visit_id');
    }
}

