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
        'feedback',
        'marketing_staff_id'
    ];

    protected $casts = [
        'activity_date' => 'datetime',
        'feedback_date' => 'datetime',
    ];

    public function getMarketingStaff()
    {
        return $this->belongsTo(MarketingStaff::class);
    }
}
