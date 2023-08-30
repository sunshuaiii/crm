<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutProduct extends Model
{
    use HasFactory;

    protected $hidden = ['checkout_id', 'product_id'];

    public $timestamps = false;

    public function getCheckouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class);
    }

    // Define the relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }
}
