
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'vehicle_id', 'slot_id', 'waktu_booking', 'waktu_mulai'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function slot()
    {
        return $this->belongsTo(ParkingSlot::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
