namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'merk', 'warna', 'tipe', 'tarif'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
