<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class visits extends Model
{
    protected $table = 'visits';

    protected $primaryKey = 'id';

    protected $fillable = [
        'visit_code',
        'guest_id',
        'branch_id',
        'purpose_id',
        'source_id',
        'assigned_to',
        'scheduled_at',
        'notes',
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
        'updated_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'check_in_at' => 'datetime',
        'meeting_start_at' => 'datetime',
        'check_out_at' => 'datetime',
        'follow_up_at' => 'datetime',
        'is_converted_to_lead' => 'boolean',
    ];

    // Relasi ke User yang menjadi PIC
    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(guests::class, 'guest_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(branches::class, 'branch_id');
    }

    public function purpose(): BelongsTo
    {
        return $this->belongsTo(visit_purposes::class, 'purpose_id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(lead_sources::class, 'source_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(users::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(users::class, 'created_by');
    }

    public function followUps()
    {
        return $this->hasMany(follow_ups::class, 'visit_id')->latest();
    }

    public function lead()
    {
        return $this->hasOne(leads::class, 'visit_id');
    }
    public function latestFollowUp()
    {
        // Beritahu Laravel bahwa primary key-nya adalah 'follow_up_id'
        return $this->hasOne(follow_ups::class, 'visit_id', 'id')->latestOfMany('follow_up_id');
    }

    public function products()
    {
        return $this->belongsToMany(products::class, 'visit_products', 'visit_id', 'product_id');
    }

    protected static function booted()
    {
        static::updating(function ($visit) {
            if (auth()->check()) {
                $visit->updated_by = auth()->id();
            }
        });
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function visits()
    {
        // 🟢 PERBAIKAN: Menggunakan visits::class (huruf kecil sesuai nama class)
        return $this->hasMany(visits::class, 'guest_id'); 
    }
}
