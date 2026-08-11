<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class guests extends Model
{
    use HasFactory;

    protected $table = 'guests';
    protected $fillable = [
        'guest_code',
        'name',
        'phone',
        'email',
        'company_name',
        'position',
        'is_vip',
        'address',
        'guest_category_id',
        'photo_path',
        'created_by',
    ];

    public function category()
    {
        return $this->belongsTo(guest_categories::class, 'guest_category_id');
    }

    public function visits()
    {
        return $this->hasMany(Visits::class, 'guest_id'); // 👈 Pastikan nama Model Visits sesuai
    }
}
