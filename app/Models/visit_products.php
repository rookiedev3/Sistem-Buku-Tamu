<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class visit_products extends Model
{
    protected $table = 'visit_products';
    protected $fillable = [
        'visit_id',
        'product_id',
    ];
}
