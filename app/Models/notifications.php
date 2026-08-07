<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notifications extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'read_at'
    ];

    // Scope untuk mengambil notifikasi yang BELUM dibaca
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 🟢 Helper Function Statis untuk mengirim notifikasi dengan mudah
    public static function send($userId, $type, $title, $body)
    {
        return self::create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'body'    => $body,
        ]);
    }
}
