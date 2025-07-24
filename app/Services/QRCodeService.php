<?php

namespace App\Services;

use App\Models\Booking;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Generate a QR code for a booking
     * 
     * @param string $uniqueId The unique identifier for the booking
     * @return string The generated file name
     */
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
    
    /**
     * Validate a booking QR code for entry
     *
     * @param string $qrCode The QR code to validate
     * @return array Result of validation with status and message
     */
    public function validateEntryQRCode($qrCode)
    {
        $booking = Booking::where('qr_code', $qrCode)->first();
        
        if (!$booking) {
            return [
                'valid' => false,
                'message' => 'QR Code tidak valid atau booking tidak ditemukan.'
            ];
        }
        
        if ($booking->check_in_count >= 1) {
            return [
                'valid' => false,
                'message' => 'QR Code ini sudah digunakan untuk masuk.'
            ];
        }
        
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return [
                'valid' => false,
                'message' => 'Booking belum dikonfirmasi atau tidak aktif/sudah selesai.'
            ];
        }
        
        return [
            'valid' => true,
            'booking' => $booking
        ];
    }
    
    /**
     * Validate a booking QR code for exit
     *
     * @param string $qrCode The QR code to validate
     * @return array Result of validation with status and message
     */
    public function validateExitQRCode($qrCode)
    {
        $booking = Booking::where('qr_code', $qrCode)->first();
        
        if (!$booking) {
            return [
                'valid' => false,
                'message' => 'QR Code tidak valid atau booking tidak ditemukan.'
            ];
        }
        
        if ($booking->check_in_count === 0) {
            return [
                'valid' => false,
                'message' => 'Kendaraan belum masuk ke parkiran dengan QR Code ini.'
            ];
        }
        
        if ($booking->check_out_count >= 1) {
            return [
                'valid' => false,
                'message' => 'QR Code ini sudah digunakan untuk keluar.'
            ];
        }
        
        if (!in_array($booking->status, ['active', 'checked_in'])) {
            return [
                'valid' => false,
                'message' => 'Booking tidak dalam status aktif (sudah keluar atau dibatalkan).'
            ];
        }
        
        return [
            'valid' => true,
            'booking' => $booking
        ];
    }
}