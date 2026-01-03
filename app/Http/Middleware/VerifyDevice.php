<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
{
    if (auth()->check()) {
        if (
            auth()->user()->device_id &&
            session('device_id') &&
            auth()->user()->device_id !== session('device_id')
        ) {
            Auth::logout();
            return redirect('/login')->withErrors([
                'device' => 'Device mismatch detected.'
            ]);
        }
     }

        return $next($request);
    }

}
