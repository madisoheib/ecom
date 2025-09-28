<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Get supported locales
        $supportedLocales = ['en', 'fr', 'ar'];
        $defaultLocale = 'en';

        // Get locale from URL
        $locale = $request->segment(1);

        // Check if the locale is supported
        if (in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        } else {
            // If no locale in URL or unsupported, use session or default
            $sessionLocale = Session::get('locale', $defaultLocale);
            if (in_array($sessionLocale, $supportedLocales)) {
                App::setLocale($sessionLocale);
            } else {
                App::setLocale($defaultLocale);
                Session::put('locale', $defaultLocale);
            }
        }

        return $next($request);
    }
}