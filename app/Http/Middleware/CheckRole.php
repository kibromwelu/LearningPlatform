<?php



namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoggedinDevices;

class CheckRole
{
    /**
     * Handle an incoming request.
     * 


     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        
        $token = Auth::getToken('token');
        $deviceId = $request->header('device_id');
        $user = Auth::user();
        $device = LoggedinDevices::where('id', $deviceId)->first();
        // dd($device);
        // if (!$device || $device->state != 'active') {
        //     return response()->json(['error'=>true, 'message' => 'Device is inactive'], 401);
        // }
        foreach ($roles as $role) {
            if ($user && $user->hasRole($role)) {
                return $next($request);
            }
        }
        return response()->json(['error' =>true, 'message' => 'Unauthorized access'], 403);
    }
}