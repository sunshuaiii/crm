<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function getCheckouts(){
        return $this->belongsToMany(Checkout::class);
    }

    public function checkoutProducts()
    {
        return $this->hasMany(CheckoutProduct::class);
    }
}
