
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\NotificationLog;

class BotController extends Controller
{
    public function sendNotification(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        // Simulasi kirim ke WA/Telegram
        NotificationLog::create([
            'user_id' => $user->id,
            'via' => $request->via,
            'pesan' => $request->pesan,
            'status' => 'terkirim',
        ]);

        return response()->json(['status' => 'Notification sent']);
    }
}
