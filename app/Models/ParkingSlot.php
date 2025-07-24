<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use App\Models\ParkingRate;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParkingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'area',
        'status',
        'location_description',
        'slot_number',
        'parking_rate_id'
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'parking_slot_id');
    }
    
    /**
     * Get the parking rate associated with this parking slot.
     */
    public function parkingRate(): BelongsTo
    {
        return $this->belongsTo(ParkingRate::class);
    }
}