<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ParkingSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Display customer dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $activeBookings = Booking::where('user_id', $user->id)
                            ->whereIn('status', ['pending', 'confirmed', 'active'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
        
        $completedBookingsCount = Booking::where('user_id', $user->id)
                                    ->where('status', 'completed')
                                    ->count();
        
        $availableSlots = ParkingSlot::where('status', 'available')->count();
        
        return view('customer.dashboard', compact('user', 'activeBookings', 'completedBookingsCount', 'availableSlots'));
    }

    /**
     * Show customer's booking history.
     */
    public function bookingHistory()
    {
        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
                        
        return view('customer.booking-history', compact('bookings'));
    }

    /**
     * Show customer's active bookings.
     */
    public function activeBookings()
    {
        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->id)
                        ->whereIn('status', ['pending', 'confirmed', 'active'])
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
        return view('customer.active-bookings', compact('bookings'));
    }
    
    /**
     * Show a form to create a new booking as a logged-in customer.
     */
    public function createBooking()
    {
        $availableSlots = ParkingSlot::where('status', 'available')
                            ->orderBy('type')
                            ->orderBy('code')
                            ->get()
                            ->groupBy('type');
                            
        return view('customer.create-booking', compact('availableSlots'));
    }
    
    /**
     * Store a new booking for a logged-in customer.
     */
    public function storeBooking(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'parking_slot_id' => 'required|exists:parking_slots,id',
            'merk_kendaraan' => 'required|string|max:255',
            'warna_kendaraan' => 'required|string|max:255',
            'license_plate' => 'required|string|max:20|unique:bookings,license_plate',
            'jenis_kendaraan' => 'required|in:motor,mobil,truk',
            'intended_entry_time' => 'nullable|date|after_or_equal:now',
            'intended_exit_time' => 'nullable|date|after:intended_entry_time',
        ]);
        
        // Check if the slot is still available
        $parkingSlot = ParkingSlot::find($request->parking_slot_id);
        if (!$parkingSlot || $parkingSlot->status !== 'available') {
            return back()->with('error', 'Slot parkir tidak tersedia.');
        }
        
        if ($parkingSlot->type !== $request->jenis_kendaraan) {
            return back()->with('error', 'Jenis kendaraan tidak sesuai dengan slot parkir.');
        }
        
        // Generate QR code using the service defined in BookingController
        $bookingController = new BookingController();
        $qrData = $bookingController->generateQRCode();
        
        // Set entry and exit times
        $entryTime = $request->intended_entry_time ? Carbon::parse($request->intended_entry_time) : Carbon::now()->addMinutes(30);
        $exitTime = $request->intended_exit_time ? Carbon::parse($request->intended_exit_time) : $entryTime->copy()->addHours(1);
        
        // Create booking
        $booking = Booking::create([
            'user_id' => $user->id,
            'parking_slot_id' => $parkingSlot->id,
            'nama_pemesan' => $user->name,
            'no_hp' => $user->phone,
            'email' => $user->email,
            'merk_kendaraan' => $request->merk_kendaraan,
            'warna_kendaraan' => $request->warna_kendaraan,
            'license_plate' => $request->license_plate,
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'qr_code' => $qrData['qrCodeValue'],
            'qr_code_path' => $qrData['qrCodePath'],
            'intended_entry_time' => $entryTime,
            'intended_exit_time' => $exitTime,
            'status' => 'pending',
            'payment_status' => 'pending',
            'booking_time' => Carbon::now(),
        ]);
        
        // Update slot status
        $parkingSlot->update(['status' => 'booked']);
        
        // Send notification
        $bookingController->sendTelegramNotification($booking, $parkingSlot);
        
        return redirect()->route('customer.bookings.show', $booking->id)
            ->with('success', 'Booking berhasil dibuat. Silakan tunjukkan QR Code saat masuk.');
    }
    
    /**
     * Show details of a specific booking.
     */
    public function showBooking($id)
    {
        $user = Auth::user();
        $booking = Booking::where('user_id', $user->id)
                    ->where('id', $id)
                    ->with('parkingSlot')
                    ->firstOrFail();
                    
        return view('customer.show-booking', compact('booking'));
    }
    
    /**
     * Cancel a booking.
     */
    public function cancelBooking($id)
    {
        $user = Auth::user();
        $booking = Booking::where('user_id', $user->id)
                    ->where('id', $id)
                    ->where('status', 'pending')
                    ->firstOrFail();
        
        $booking->update(['status' => 'cancelled']);
        $booking->parkingSlot->update(['status' => 'available']);
        
        return redirect()->route('customer.bookings')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}