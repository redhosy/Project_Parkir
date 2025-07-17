
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\ParkingSlot;

class AdminController extends Controller
{
    public function index()
    {
        $slots = ParkingSlot::all();
        return view('admin.dashboard', compact('slots'));
    }

    public function listVehicles()
    {
        $vehicles = Vehicle::with('user')->get();
        return view('admin.vehicle_list', compact('vehicles'));
    }

    public function captureQR(Request $request)
    {
        $booking = Booking::where('qr_code', $request->input('qr_code'))->firstOrFail();
        $booking->update(['waktu_mulai' => now()]);
        return redirect()->back()->with('success', 'Waktu mulai parkir dicatat.');
    }
}
