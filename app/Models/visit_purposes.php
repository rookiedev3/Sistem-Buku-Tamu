<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class visit_purposes extends Model
{
    protected $table = 'visit_purposes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
