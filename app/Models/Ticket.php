<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public function getSupportStaff(){
        return $this->belongsTo(SupportStaff::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
