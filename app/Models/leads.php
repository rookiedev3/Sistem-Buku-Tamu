<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class leads extends Model
{
    protected $fillable = [
        'guest_id', 'visit_id', 'owner_id', 'status', 'estimated_value', 'follow_up_at',
    ];

    protected $casts = ['follow_up_at' => 'datetime'];

    public function guest()   { return $this->belongsTo(guests::class, 'guest_id'); }
    public function visit()   { return $this->belongsTo(visits::class, 'visit_id'); }
    public function followUps() { return $this->hasMany(follow_ups::class, 'lead_id')->orderBy('created_at', 'desc'); }
}