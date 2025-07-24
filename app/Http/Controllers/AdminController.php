<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ParkingSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard admin.
     */
    public function dashboard()
    {
        $availableSlotsCount = ParkingSlot::where('status', 'available')->count();
        $bookedSlotsCount = ParkingSlot::where('status', 'booked')->count();
        $occupiedSlotsCount = ParkingSlot::where('status', 'occupied')->count();
        $totalSlotsCount = ParkingSlot::count();

        return view('admin.dashboard', compact(
            'availableSlotsCount',
            'bookedSlotsCount',
            'occupiedSlotsCount',
            'totalSlotsCount'
        ));
    }
    
    /**
     * Menampilkan halaman validasi QR code.
     */
    public function validasiQr()
    {
        return view('admin.validasi-qr');
    }
    
    /**
     * Menampilkan halaman riwayat parkir.
     */
    public function riwayatParkir(Request $request)
    {
        $query = Booking::with('parkingSlot')->orderBy('created_at', 'desc');
        
        // Apply search filter
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('nama_pemesan', 'like', "%{$search}%")
                  ->orWhere('license_plate', 'like', "%{$search}%")
                  ->orWhere('merk_kendaraan', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->input('status'))) {
            $query->where('status', $request->input('status'));
        }
        
        // Apply date filter
        if ($request->has('date') && !empty($request->input('date'))) {
            $date = $request->input('date');
            $query->whereDate('created_at', $date);
        }
        
        $bookings = $query->paginate(15);
        
        return view('admin.riwayat-parkir', compact('bookings'));
    }

    /**
     * API untuk scan masuk oleh admin.
     */
    public function scanEntry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
        }

        // Use QRCodeService for validation
        $qrCodeService = new \App\Services\QRCodeService();
        $validationResult = $qrCodeService->validateEntryQRCode($request->qr_code);
        
        if (!$validationResult['valid']) {
            return response()->json(['message' => $validationResult['message']], 400);
        }
        
        $booking = $validationResult['booking'];

        $parkingSlot = $booking->parkingSlot;

        if ($parkingSlot->status === 'occupied') {
            return response()->json(['message' => 'Slot parkir ini sudah terisi oleh kendaraan lain.'], 400);
        }

        // Menggunakan 'entry_time' sesuai database Anda
        $booking->entry_time = Carbon::now();
        $booking->check_in_count += 1;
        $booking->status = 'active';
        $booking->save();

        $parkingSlot->status = 'occupied';
        $parkingSlot->save();

        try {
            $telegramChatId = env('TELEGRAM_CHAT_ID');
            $message = "✅ Kendaraan masuk ke slot parkir! ✅\n\n" .
                "Kode Booking: `{$booking->qr_code}`\n" .
                "Slot Parkir: *{$parkingSlot->code}* ({$parkingSlot->location_description})\n" .
                "Waktu Masuk: " . Carbon::parse($booking->entry_time)->format('d M Y H:i');
            Telegram::sendMessage([
                'chat_id' => $telegramChatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi Telegram masuk: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Validasi masuk berhasil! Portal terbuka.',
            'booking_status' => $booking->status,
            'parking_slot_status' => $parkingSlot->status,
        ], 200);
    }

    /**
     * API untuk scan keluar oleh admin.
     */
    public function scanExit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
        }

        // Use QRCodeService for validation
        $qrCodeService = new \App\Services\QRCodeService();
        $validationResult = $qrCodeService->validateExitQRCode($request->qr_code);
        
        if (!$validationResult['valid']) {
            return response()->json(['message' => $validationResult['message']], 400);
        }
        
        $booking = $validationResult['booking'];

        $parkingSlot = $booking->parkingSlot;

        $entryTime = Carbon::parse($booking->entry_time);
        $exitTime = Carbon::now();
        $durationInMinutes = $exitTime->diffInMinutes($entryTime);
        $durationInHours = $durationInMinutes / 60;
        
        // Use the ParkingRate model to calculate cost
        $calculatedCost = \App\Models\ParkingRate::getRateForDuration(
            $booking->jenis_kendaraan, 
            $durationInHours
        );

        $booking->exit_time = $exitTime;
        $booking->check_out_count += 1;
        $booking->total_payment = $calculatedCost; 
        $booking->status = 'completed';
        $booking->payment_status = 'paid';
        $booking->save();

        // Make sure to update slot status correctly
        $parkingSlot->status = 'available';
        $parkingSlot->save();

        try {
            $telegramChatId = env('TELEGRAM_CHAT_ID');
            $message = "👋 Kendaraan keluar dari slot parkir! 👋\n\n" .
                "Kode Booking: `{$booking->qr_code}`\n" .
                "Slot Parkir: *{$parkingSlot->code}* ({$parkingSlot->location_description})\n" .
                "Waktu Masuk: " . Carbon::parse($booking->entry_time)->format('d M Y H:i') . "\n" .
                "Waktu Keluar: " . Carbon::parse($booking->exit_time)->format('d M Y H:i') . "\n" .
                "Total Biaya: *Rp " . number_format($calculatedCost, 0, ',', '.') . "*\n\n" . // Gunakan calculatedCost di pesan
                "Terima kasih telah menggunakan SmartPark!";
            Telegram::sendMessage([
                'chat_id' => $telegramChatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi Telegram keluar: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Validasi keluar berhasil! Total biaya: Rp ' . number_format($calculatedCost, 0, ',', '.') . '. Portal terbuka.',
            'booking_status' => $booking->status,
            'parking_slot_status' => $parkingSlot->status,
            'total_cost' => $calculatedCost, 
        ], 200);
    }
}
