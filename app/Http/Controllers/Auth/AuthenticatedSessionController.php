<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use App\Models\UserDevice;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\UserDeviceController;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(route('dashboard', absolute: false));
    // }
    public function store(LoginRequest $request) 
    {
           // Validate device_id
            $request->validate([
                'device_id' => 'required|string',
            ]);

            $request->authenticate();
            $request->session()->regenerate();

            $user = Auth::user();
            $deviceId = $request->device_id;

            // Only enforce single-device login for non-superadmins
            if (!$user->hasRole('superadmin')) {

                if($user->restriction === 'yes'){
                    $cachedDevice = Cache::get('user_active_device_' . $user->id);
                        //dd($cachedDevice . ' vs ' . $deviceId. ' - '. $user->id);
                        if ($cachedDevice && $cachedDevice !== $deviceId) {

                            Auth::logout();
                            $request->session()->invalidate();
                            $request->session()->regenerateToken();

                            return response()->json([
                                'message' => 'This account is already logged in on another device.'
                            ], 423);
                        }

                        // Create OR update same device (no duplicate rows)
                        UserDevice::updateOrCreate(
                            [
                                'user_id'   => $user->id,
                                'device_id' => $request->device_id,   // <-- unique combo
                            ],
                            [
                                'device_browser'    => $request->device_browser,
                                'device_os'         => $request->device_os,
                                'device_resolution' => $request->device_resolution,
                                'status'            => 'active',
                            ]
                        );

                }
            }

            // Save device in session
            session(['device_id' => $request->device_id]);

            // Determine redirect URL
            if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
                $redirect_url = '/panel/dashboard/';
            } else if ($user->hasRole('cashier')) {
                $redirect_url = '/panel/cashier';
            } else {
                $redirect_url = '/login';
            }

            // Return JSON if AJAX, otherwise normal redirect
            if ($request->expectsJson()) {
                return response()->json(['redirect_url' => url($redirect_url)]);
            }

            return redirect()->intended($redirect_url);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            Cache::forget('user_active_device_' . Auth::id());
        }
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
