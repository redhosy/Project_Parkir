
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\ParkingSlot;
use Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $slots = ParkingSlot::all();
        return view('customer.dashboard', compact('slots'));
    }

    public function profile()
    {
        return view('customer.profile', ['user' => Auth::user()]);
    }

    public function vehicles()
    {
        $vehicles = Auth::user()->vehicles;
        return view('customer.vehicles', compact('vehicles'));
    }
}
