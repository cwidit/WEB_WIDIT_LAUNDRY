<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    // Sesuaikan nama tabel jika di database Anda namanya 'levels'
    protected $table = 'level'; 
    protected $guarded = [];

    // Relasi ke tabel User
    public function users()
    {
        return $this->hasMany(User::class, 'id_level', 'id');
    }
}