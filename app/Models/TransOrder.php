<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransOrder extends Model
{
    protected $table = 'trans_order';
    protected $guarded = []; // Mengizinkan semua kolom diisi

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id');
    }

    // Relasi ke User (Operator)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Perhatikan nama fungsinya. Jika di blade tertulis $order->details, gunakan nama fungsi details().
    
    public function details()
    {
        return $this->hasMany(TransOrderDetail::class, 'id_order', 'id');
    }

    // Relasi ke Log Pengambilan
    public function pickupLog()
    {
        return $this->hasOne(TransLaundryPickup::class, 'id_order', 'id');
    }
}