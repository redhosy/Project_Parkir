
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use Auth;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $booking = Booking::findOrFail($request->booking_id);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'metode' => $request->metode,
            'jumlah' => $request->jumlah,
            'status' => 'selesai',
        ]);

        return redirect()->route('customer.payment')->with('success', 'Pembayaran berhasil');
    }
}
