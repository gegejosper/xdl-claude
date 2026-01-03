<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;


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
    public function store(LoginRequest $request){

       // Validate device_id
            $request->validate([
                'device_id' => 'required|string'
            ]);

            $request->authenticate();

            $request->session()->regenerate();
            $user = Auth::user();

            //  Device already locked → logout immediately
            if ($user->device_id && $user->device_id !== $request->device_id) {

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

               throw ValidationException::withMessages([
                    'device' => 'This account is already logged in on another device.'
                ]);
            }

            //  First login → lock device
            if (!$user->device_id) {
                $user->update([
                    'device_id' => $request->device_id
                ]);
            }

            
            //  Save device in session
            session(['device_id' => $request->device_id]);

              $redirect_url = '/login'; // default fallback
                if ($user->hasRole('admin')) {
                    $redirect_url = '/panel/admin/';
                } 
                else if ($user->hasRole('superadmin')) {
                    $redirect_url = '/panel/admin/';
                } 
                else if ($user->hasRole('cashier')) {
                    $redirect_url = '/panel/cashier';
                }
                else {
                    $redirect_url = '/login';
                }

            //  AJAX support
            if ($request->expectsJson()) {
                return response()->json([
                    'redirect_url' => url($redirect_url)
                ]);
            }

        // Otherwise, normal Laravel redirect
        return redirect()->intended($redirect_url);
        }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        // reset device after logout
        if ($user) {
            $user->update(['device_id' => null]);
        }


        return redirect('/');
    }
}
