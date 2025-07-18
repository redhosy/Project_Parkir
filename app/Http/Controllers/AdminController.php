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

        $recentBookings = Booking::with('parkingSlot')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'availableSlotsCount',
            'bookedSlotsCount',
            'occupiedSlotsCount',
            'totalSlotsCount',
            'recentBookings'
        ));
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

        $booking = Booking::where('qr_code', $request->qr_code)->first();

        if (!$booking) {
            return response()->json(['message' => 'QR Code tidak valid atau booking tidak ditemukan.'], 404);
        }

        if ($booking->check_in_count >= 1) {
            return response()->json(['message' => 'QR Code ini sudah digunakan untuk masuk.'], 400);
        }

        if ($booking->status !== 'pending' && $booking->status !== 'confirmed') {
            return response()->json(['message' => 'Booking belum dikonfirmasi atau tidak aktif/sudah selesai.'], 400);
        }

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

        $booking = Booking::where('qr_code', $request->qr_code)->first();

        if (!$booking) {
            return response()->json(['message' => 'QR Code tidak valid atau booking tidak ditemukan.'], 404);
        }

        if ($booking->check_in_count === 0) {
            return response()->json(['message' => 'Kendaraan belum masuk ke parkiran dengan QR Code ini.'], 400);
        }
        if ($booking->check_out_count >= 1) {
            return response()->json(['message' => 'QR Code ini sudah digunakan untuk keluar.'], 400);
        }

        if ($booking->status !== 'active') {
            return response()->json(['message' => 'Booking tidak dalam status aktif (sudah keluar atau dibatalkan).'], 400);
        }

        $parkingSlot = $booking->parkingSlot;

        $entryTime = Carbon::parse($booking->entry_time);
        $exitTime = Carbon::now();
        $durationInMinutes = $exitTime->diffInMinutes($entryTime);
        $calculatedCost = 0; // Ubah nama variabel agar tidak bingung

        $hourlyRate = $parkingSlot->tarif;
        $firstHourCost = $hourlyRate;
        $subsequentHourCost = $hourlyRate * 0.6;

        if ($durationInMinutes <= 60) {
            $calculatedCost = $firstHourCost;
        } else {
            $additionalHours = ceil(($durationInMinutes - 60) / 60);
            $calculatedCost = $firstHourCost + ($additionalHours * $subsequentHourCost);
        }

        $booking->exit_time = $exitTime;
        $booking->check_out_count += 1;
        $booking->total_payment = $calculatedCost; 
        $booking->status = 'completed';
        $booking->payment_status = 'paid';
        $booking->save();

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
