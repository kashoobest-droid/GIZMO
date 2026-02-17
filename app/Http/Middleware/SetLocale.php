<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session first, then cookie, or use default
        $locale = session('locale') 
            ?? request()->cookie('locale') 
            ?? config('app.locale', 'en');

        // Validate that it's a supported locale
        $supportedLocales = ['en', 'ar'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.locale', 'en');
        }

        // Set in session if not already set
        if (!session()->has('locale')) {
            session(['locale' => $locale]);
        }

        // Set the application locale
        app()->setLocale($locale);

        $response = $next($request);

        // Attach locale cookie to response
        $response->cookie(
            cookie('locale', $locale, 525600 * 60)
        );

        return $response;
    }
}
