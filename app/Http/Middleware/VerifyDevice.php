<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserDevice;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;

class VerifyDevice 
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Skip superadmin
        if ($user->hasRole('superadmin')) {
            return $next($request);
        }

        if($user->restriction=== 'yes')
        {
            $cacheKey     = 'user_active_device_' . $user->id;
            $cachedDevice = Cache::get($cacheKey);
            $sessionDevice = session('device_id');

            // Get user's saved device from database
            $device = UserDevice::where('user_id', $user->id)->first();

            //  If session device missing → logout
            if (!$sessionDevice) {

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')
                    ->with('error', 'Session expired. Please login again.');
            }

            // If no device saved in DB → logout
            if (!$device) {

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')
                    ->with('error', 'No registered device found.');
            }

            //  Restore cache if expired
            if (!$cachedDevice) {
                Cache::put($cacheKey, $sessionDevice, now()->addDays(30));
                $cachedDevice = $sessionDevice;
            }

            //  Compare correctly
            if (
                $cachedDevice !== $sessionDevice || $device->device_id !== $sessionDevice) {

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')
                    ->with('error', 'You have been logged out. Device mismatch detected.');
            }

        }

        return $next($request);
    }

}
