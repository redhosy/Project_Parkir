<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'tarif',
        'area',
        'status',
        'location_description',
        'slot_number'
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'parking_slot_id');
    }
}