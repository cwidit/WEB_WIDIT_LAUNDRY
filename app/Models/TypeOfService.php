<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeOfService extends Model
{
    // Sesuaikan nama tabel di database
    protected $table = 'type_of_service'; 
    
    protected $fillable = [
        'service_name',
        'price',
        'description'
    ];
}