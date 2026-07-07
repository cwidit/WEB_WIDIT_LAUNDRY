<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransOrderDetail extends Model
{
    protected $table = 'trans_order_detail';
    protected $guarded = [];

    // Relasi kembali ke data induk transaksi
    public function transOrder()
    {
        return $this->belongsTo(TransOrder::class, 'id_order', 'id');
    }

    // Relasi ke data Master Layanan
    public function typeOfService()
    {
        return $this->belongsTo(TypeOfService::class, 'id_service', 'id');
    }
}