<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminScope
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('admin.scope:users')
     */
    public function handle(Request $request, Closure $next, $scope = null)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Master admin bypasses scopes
        if (method_exists($user, 'isMasterAdmin') && $user->isMasterAdmin()) {
            return $next($request);
        }

        // Must be an admin first
        if (! ($user->is_admin ?? false)) {
            abort(403);
        }

        if ($scope && method_exists($user, 'hasAdminScope') && $user->hasAdminScope($scope)) {
            return $next($request);
        }

        abort(403);
    }
}
