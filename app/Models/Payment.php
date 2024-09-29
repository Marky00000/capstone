<?php

// app/Models/Payment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'project_id',
        'payment_type',       // Payment type (initial, midterm, final)
        'payment_status',     // Payment status (approve, decline)
        'payment_method',     // Payment method (cash, gcash, bank_transfer)
        'amount',             // Payment amount
        'payment_image',      // Path for payment image
        'remarks',
        'total_paid',     
    ];

    // Define the possible payment methods
    public static function getPaymentMethods()
    {
        return ['cash', 'gcash', 'bank_transfer'];
    }

    // Define the possible payment statuses
    public static function getPaymentStatuses()
    {
        return ['approve', 'decline'];
    }

    // Relationship with the Project model
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Example of a custom method to check if payment is approved
    public function isApproved()
    {
        return $this->payment_status === 'approve';
    }

    // Example of a custom method to check if payment is declined
    public function isDeclined()
    {
        return $this->payment_status === 'decline';
    }
}
