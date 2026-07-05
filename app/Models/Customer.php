<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
protected $table = 'customer';
 protected $fillable = [
    'customer_name',
    'phone',
    'address'
 ];

 public function transOrders()
{
    return $this->hasMany(TransOrder::class, 'id_customer');
}

public function laundryPickups()
{
    return $this->hasMany(TransLaundryPickup::class, 'id_customer');
}

}
