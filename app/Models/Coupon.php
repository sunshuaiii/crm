<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'discount',
        'redemption_points',
        'conditions',
    ];

    public function getCustomers(){
        return $this->belongsToMany(Customer::class);
    }
}
