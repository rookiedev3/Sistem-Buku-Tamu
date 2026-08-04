<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lead_sources extends Model
{
    protected $table = 'lead_sources';
    protected $primaryKey = 'id'; 
    protected $fillable = [
        'name'
    ];
}
