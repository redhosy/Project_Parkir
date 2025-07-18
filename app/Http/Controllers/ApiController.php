<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ParkingSlot;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function checkIn(Request $request)
    {
        $request->validate(['qr_code' => 'required']);

        $booking = Booking::where('qr_code', $request->qr_code)->firstOrFail();

        if ($booking->usage_count >= 2) {
            return response()->json(['error' => 'QR code usage limit reached'], 403);
        }

        $booking->update([
            'entry_time' => now(),
            'status' => 'checked_in',
            'usage_count' => $booking->usage_count + 1
        ]);

        $booking->parkingSlot->update(['status' => 'occupied']);

        return response()->json(['success' => 'Check-in successful']);
    }

    public function checkOut(Request $request)
    {
        $request->validate(['qr_code' => 'required']);

        $booking = Booking::where('qr_code', $request->qr_code)->firstOrFail();

        $booking->update([
            'exit_time' => now(),
            'status' => 'checked_out',
            'usage_count' => $booking->usage_count + 1
        ]);

        $booking->parkingSlot->update(['status' => 'available']);

        return response()->json(['success' => 'Check-out successful']);
    }
}