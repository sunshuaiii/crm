<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    public function getMarketingStaff(){
        return $this->belongsTo(MarketingStaff::class);
    }
}
