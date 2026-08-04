<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class branches extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke users (satu branch punya banyak user)
     */
    public function users()
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Relasi ke visits (satu branch punya banyak visit)
     */
    // public function visits()
    // {
    //     return $this->hasMany(Visit::class, 'branch_id');
    // }
}
