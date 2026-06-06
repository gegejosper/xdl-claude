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
        $request->validate([
            'device_id' => 'required|string',
        ]);

        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        $deviceId = $request->device_id;

        // Only enforce for non-superadmin users
        if (!$user->hasRole('superadmin') && $user->restriction === 'yes') {

            // Get existing active device for this user
            $existingDevice = UserDevice::where('user_id', $user->id)
                ->first();

            // If user already has another active device
            if ($existingDevice && $existingDevice->device_id !== $deviceId) {

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return response()->json([
                    'message' => 'This account is already logged in on another device.'
                ], 423);
            }

            // Save/update current device
            UserDevice::updateOrCreate(
                [
                    'user_id'   => $user->id,
                    'device_id' => $deviceId,
                ],
                [
                    'device_browser'    => $request->device_browser,
                    'device_os'         => $request->device_os,
                    'device_resolution' => $request->device_resolution,
                    'status'            => 'active',
                ]
            );

            // Cache active device
            Cache::forever('user_active_device_' . $user->id, $deviceId);
        }

        session(['device_id' => $deviceId]);

        if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
            $redirect_url = '/panel/dashboard/';
        } elseif ($user->hasRole('cashier')) {
            $redirect_url = '/panel/cashier';
        } else {
            $redirect_url = '/login';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'redirect_url' => url($redirect_url)
            ]);
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