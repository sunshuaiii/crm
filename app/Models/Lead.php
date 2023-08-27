<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'contact',
        'email',
        'gender',
        'status',
        'activity',
        'marketing_staff_id'
    ];

    public function getMarketingStaff()
    {
        return $this->belongsTo(MarketingStaff::class);
    }
}
