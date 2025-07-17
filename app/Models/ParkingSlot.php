
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingSlot extends Model
{
    use HasFactory;

    protected $fillable = ['kode_slot', 'status']; // status: kosong, terisi

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }
}
