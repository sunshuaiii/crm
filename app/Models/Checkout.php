<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime',
    ];

    public $timestamps = false;

    public function getCustomer(){
        return $this->belongsTo(Customer::class);
    }

    public function getCustomerCoupon(){
        return $this->hasOne(CustomerCoupon::class);
    }

    public function getProducts(){
        return $this->belongsToMany(Product::class);
    }
}
