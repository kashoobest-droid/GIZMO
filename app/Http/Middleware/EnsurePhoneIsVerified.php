<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePhoneIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (! $user) {
            return $next($request);
        }

        // If phone already verified, continue
        if ($user->phone_verified_at) {
            return $next($request);
        }

        // Allow verification routes and logout
        $allowedRouteNames = [
            'verify.send', 'verify.check', 'profile.phone.send', 'profile.phone.confirm', 'logout', 'verify.show'
        ];

        if ($request->route() && in_array($request->route()->getName(), $allowedRouteNames)) {
            return $next($request);
        }

        // Redirect to the OTP verification page (preserve phone in session)
        return redirect()->route('verify.show')->with('phone', $user->phone);
    }
}
