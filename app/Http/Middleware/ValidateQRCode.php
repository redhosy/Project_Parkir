<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Booking;

class ValidateQRCode
{
    public function handle($request, Closure $next)
    {
        $qrCode = $request->input('qr_code');
        
        if (!$booking = Booking::where('qr_code', $qrCode)->first()) {
            return response()->json(['error' => 'Invalid QR Code'], 404);
        }

        if ($booking->usage_count >= 2) {
            return response()->json(['error' => 'QR Code usage limit reached'], 403);
        }

        $request->merge(['booking' => $booking]);

        return $next($request);
    }
}