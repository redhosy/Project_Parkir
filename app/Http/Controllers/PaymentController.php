<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display a list of payments for the current user.
     */
    public function index()
    {
        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->id)
            ->whereNotNull('total_payment')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('customer.payment.index', compact('bookings'));
    }

    /**
     * Show payment form for a specific booking.
     */
    public function showForm($id)
    {
        $booking = Booking::findOrFail($id);
        return view('customer.payment.form', compact('booking'));
    }

    /**
     * Store a new payment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'metode' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
        ]);
        
        $booking = Booking::findOrFail($request->booking_id);

        // Verify payment amount matches booking total
        if ($booking->total_payment > $request->jumlah) {
            return back()->with('error', 'Jumlah pembayaran kurang dari total tagihan.');
        }
        
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'metode' => $request->metode,
            'jumlah' => $request->jumlah,
            'status' => 'selesai',
        ]);
        
        // Update booking payment status
        $booking->payment_status = 'paid';
        $booking->payment_method = $request->metode;
        $booking->payment_time = now();
        $booking->save();
        
        // Log payment
        Log::info("Payment processed for booking #{$booking->id}: Rp" . number_format($payment->jumlah, 0, ',', '.'));

        return redirect()->route('customer.payment')->with('success', 'Pembayaran berhasil');
    }
}
