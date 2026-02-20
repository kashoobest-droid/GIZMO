<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocaleController extends Controller
{
    /**
     * Set the application locale
     */
    public function setLocale($locale)
    {
        // Validate locale
        $availableLocales = ['en', 'ar'];
        
        if (!in_array($locale, $availableLocales)) {
            return redirect('/')->with('error', 'Invalid language selection');
        }

        // Set locale in session
        session(['locale' => $locale]);

        // Also set the app locale
        app()->setLocale($locale);

        // If user is authenticated, save their locale preference to database
        if (Auth::check()) {
            Auth::user()->update(['locale' => $locale]);
        }

        // Store in cookie for persistence (expires in 1 year)
        cookie()->queue(
            cookie('locale', $locale, 525600 * 60) // 1 year in minutes
        );

        // Get the referer or default to home
        $referer = request()->header('referer');

        if ($referer && str_starts_with($referer, config('app.url'))) {
            return redirect($referer)->cookie('locale', $locale, 525600 * 60);
        }

        return redirect('/')->cookie('locale', $locale, 525600 * 60);
    }
}
