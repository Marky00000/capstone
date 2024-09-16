<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'initial_payment',
        'mid_term_payment',
        'final_payment',
        'remarks',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
