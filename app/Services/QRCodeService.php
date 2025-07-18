<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    public function generateForBooking($uniqueId)
    {
        $content = config('app.url') . "/api/validate/$uniqueId";
        $fileName = "booking_$uniqueId.svg";
        $path = "qr-codes/$fileName";
        
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate($content);
        
        Storage::disk('public')->put($path, $qrCode);
        
        return $fileName;
    }
}