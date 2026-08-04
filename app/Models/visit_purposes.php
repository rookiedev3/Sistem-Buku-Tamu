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
}
