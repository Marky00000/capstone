<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'booking_id',
        'service_id',
        'lot_area',
        'total_cost',
        'description',
        'project_status',
    ];

    /**
     * Get the service associated with the project.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the booking associated with the project.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
