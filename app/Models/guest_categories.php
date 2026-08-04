<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class guest_categories extends Model
{
    protected $table = 'guest_categories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'color',
    ];
}
