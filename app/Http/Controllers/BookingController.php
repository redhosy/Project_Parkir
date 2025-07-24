<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ParkingSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class BookingController extends Controller
{
    /**
     * Generate a QR Code for booking.
     * 
     * @return array QR code data including value and file path
     */
    public function generateQRCode()
    {
        $qrCodeValue = Str::uuid();
        $qrCodeService = new \App\Services\QRCodeService();
        $fileName = $qrCodeService->generateForBooking($qrCodeValue);
        $qrCodePath = 'qr-codes/' . $fileName;

        return [
            'qrCodeValue' => $qrCodeValue,
            'qrCodePath' => $qrCodePath
        ];
    }
    
    /**
     * Menampilkan form booking untuk pengguna umum.
     */
    public function create()
    {
        return view('booking.create');
    }

    /**
     * Menyimpan booking baru dari pengguna umum (tanpa login).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pemesan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'merk_kendaraan' => 'required|string|max:255',
            'warna_kendaraan' => 'required|string|max:255',
            'license_plate' => 'required|string|max:20|unique:bookings,license_plate',
            'jenis_kendaraan' => 'required|in:motor,mobil,truk',
            'parking_slot_id' => 'required|exists:parking_slots,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek ketersediaan slot parkir
        $parkingSlot = ParkingSlot::find($request->parking_slot_id);

        if (!$parkingSlot || $parkingSlot->status !== 'available') {
            return response()->json([
                'success' => false,
                'message' => 'Slot parkir tidak tersedia'
            ], 400);
        }

        if ($parkingSlot->type !== $request->jenis_kendaraan) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kendaraan tidak sesuai dengan slot'
            ], 400);
        }

        // Generate QR Code using service
        $qrCodeValue = Str::uuid();
        $qrCodeService = new \App\Services\QRCodeService();
        $fileName = $qrCodeService->generateForBooking($qrCodeValue);
        $qrCodePath = 'qr-codes/' . $fileName;

        // Waktu booking
        $intendedEntryTime = Carbon::now()->addMinutes(5);
        $intendedExitTime = $intendedEntryTime->copy()->addHours(1);

        // Simpan booking
        $booking = Booking::create([
            'user_id' => null, // Untuk guest booking
            'parking_slot_id' => $parkingSlot->id,
            'qr_code' => $qrCodeValue,
            'qr_code_path' => $qrCodePath,
            'intended_entry_time' => $intendedEntryTime,
            'intended_exit_time' => $intendedExitTime,
            'status' => 'pending',
            'payment_status' => 'pending',
            'nama_pemesan' => $request->nama_pemesan,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'merk_kendaraan' => $request->merk_kendaraan,
            'warna_kendaraan' => $request->warna_kendaraan,
            'license_plate' => $request->license_plate,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'booking_time' => Carbon::now(),
        ]);

        // Update status slot parkir
        $parkingSlot->update(['status' => 'booked']);

        // Kirim notifikasi Telegram
        $this->sendTelegramNotification($booking, $parkingSlot);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat',
            'data' => [
                'booking' => $booking,
                'qr_code_url' => asset('storage/' . $qrCodePath)
            ]
        ], 201);
    }

    /**
     * Mengirim notifikasi Telegram
     */
    protected function sendTelegramNotification(Booking $booking, ParkingSlot $parkingSlot)
    {
        try {
            $botToken = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');

            // Validasi konfigurasi
            if (empty($botToken)) {
                throw new \Exception('Telegram bot token tidak ditemukan di .env');
            }

            if (empty($chatId)) {
                throw new \Exception('Telegram chat ID tidak ditemukan di .env');
            }

            $message = "🚗 *BOOKING PARKIR BERHASIL* 🚗\n\n"
                     . "📅 *Tanggal:* " . now()->format('d/m/Y H:i') . "\n"
                     . "🆔 *Kode Booking:* `{$booking->qr_code}`\n"
                     . "📍 *Slot Parkir:* {$parkingSlot->code}\n"
                     . "🚘 *Kendaraan:* {$booking->merk_kendaraan} ({$booking->jenis_kendaraan})\n"
                     . "🔢 *Plat Nomor:* {$booking->license_plate}\n"
                     . "👤 *Pemesan:* {$booking->nama_pemesan}\n"
                     . "📱 *No HP:* {$booking->no_hp}\n"
                     . "⏰ *Estimasi Masuk:* " . $booking->intended_entry_time->format('H:i') . "\n\n"
                     . "Silakan tunjukkan QR Code saat check-in.";

            // Menggunakan HTTP Client langsung sebagai fallback
            $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);

            if ($response->failed()) {
                throw new \Exception('Gagal mengirim notifikasi: ' . $response->body());
            }

            Log::info('Notifikasi Telegram terkirim', [
                'booking_id' => $booking->id,
                'response' => $response->json()
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi Telegram: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'error' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Menampilkan detail booking dan QR code.
     */
    public function show($id)
    {
        $booking = Booking::with('parkingSlot')->findOrFail($id);
        return view('booking.show', compact('booking'));
    }

    /**
     * Menampilkan halaman riwayat booking.
     */
    public function history()
    {
        $bookings = Booking::latest()->paginate(10);
        return view('booking.history', compact('bookings'));
    }

    /**
     * API untuk mencari riwayat booking berdasarkan query (no_hp atau qr_code).
     */
    public function getHistory(Request $request)
    {
        $query = $request->input('query');

        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:3'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter pencarian tidak valid',
                'errors' => $validator->errors()
            ], 400);
        }

        $bookings = Booking::where('no_hp', 'like', "%{$query}%")
                         ->orWhere('qr_code', 'like', "%{$query}%")
                         ->orWhere('license_plate', 'like', "%{$query}%")
                         ->with('parkingSlot')
                         ->orderBy('created_at', 'desc')
                         ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    /**
     * API untuk check-in kendaraan
     */
    public function checkIn(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak valid untuk check-in'
            ], 400);
        }

        $booking->update([
            'status' => 'active', // Consistent with AdminController's scanEntry
            'actual_entry_time' => now()
        ]);

        $booking->parkingSlot()->update(['status' => 'occupied']);

        $this->sendTelegramNotification(
            $booking, 
            $booking->parkingSlot,
            "🚗 *KENDARAAN MASUK* 🚗\n\n"
            . "📍 Slot: {$booking->parkingSlot->code}\n"
            . "🔢 Plat: {$booking->license_plate}\n"
            . "⏱ Waktu: " . now()->format('H:i')
        );

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil'
        ]);
    }

    /**
     * API untuk check-out kendaraan
     */
    public function checkOut(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->status !== 'checked_in' && $booking->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Status booking tidak valid untuk check-out'
            ], 400);
        }

        $booking->update([
            'status' => 'completed',
            'actual_exit_time' => now()
        ]);

        $booking->parkingSlot()->update(['status' => 'available']);

        $this->sendTelegramNotification(
            $booking, 
            $booking->parkingSlot,
            "🚗 *KENDARAAN KELUAR* 🚗\n\n"
            . "📍 Slot: {$booking->parkingSlot->code}\n"
            . "🔢 Plat: {$booking->license_plate}\n"
            . "⏱ Durasi: " . $booking->actual_entry_time->diffForHumans(now(), true)
        );

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil'
        ]);
    }
}