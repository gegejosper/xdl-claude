<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

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

        // If it's an AJAX request, return JSON instead of a redirect
        if ($request->expectsJson()) {
            return response()->json(['redirect_url' => url($redirect_url)]);
        }

        // Otherwise, normal Laravel redirect
        return redirect()->intended($redirect_url);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
