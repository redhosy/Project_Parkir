<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ParkingSlot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pemesan',
        'no_hp',
        'email',
        'merk_kendaraan',
        'warna_kendaraan',
        'license_plate',
        'jenis_kendaraan',
        'parking_slot_id',
        'qr_code',
        'booking_time',
        'entry_time',
        'exit_time',
        'status',
        'usage_count',
        'total_payment',
        'payment_status',
        'payment_method',
        'payment_time'
    ];

    protected $casts = [
        'booking_time' => 'datetime',
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
        'total_payment' => 'decimal:2', 
        'payment_time' => 'datetime'
    ];

    public function parkingSlot()
    {
        return $this->belongsTo(ParkingSlot::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}