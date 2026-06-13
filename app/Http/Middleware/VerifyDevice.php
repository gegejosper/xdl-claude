<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Cache;

class VerifyDevice
{
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

        // Only enforce device restriction
        if ($user->restriction === 'yes') {

            $sessionDevice = session('device_id');

            // No device stored in session
            if (empty($sessionDevice)) {
                return $this->logout($request, 'Session device not found.');
            }

            // Verify exact device still exists
            $device = UserDevice::where('user_id', $user->id)
                ->where('device_id', $sessionDevice)
                ->first();

            // Device deleted by admin or no longer exists
            if (!$device) {

                Cache::forget('user_active_device_' . $user->id);

                return $this->logout(
                    $request,
                    'Your registered device has been removed. Please contact the administrator.'
                );
            }

            // Verify cache
            $cacheKey = 'user_active_device_' . $user->id;
            $cachedDevice = Cache::get($cacheKey);

            if (!$cachedDevice) {
                Cache::put($cacheKey, $sessionDevice, now()->addDays(30));
            } elseif ($cachedDevice !== $sessionDevice) {

                Cache::forget($cacheKey);

                return $this->logout(
                    $request,
                    'Device mismatch detected.'
                );
            }
        }

        return $next($request);
    }

    private function logout(Request $request, string $message)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('error', $message);
    }
}