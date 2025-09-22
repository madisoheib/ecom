<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if language is provided in URL
        if ($request->has('lang')) {
            $locale = $request->get('lang');

            // Validate the locale
            if (in_array($locale, ['en', 'fr', 'ar'])) {
                Session::put('locale', $locale);
                App::setLocale($locale);
            }
        } else {
            // Use session locale or default
            $locale = Session::get('locale', config('app.locale'));
            App::setLocale($locale);
        }

        // Debug: Add header to see current locale
        $response = $next($request);
        $response->headers->set('X-Current-Locale', App::getLocale());

        return $response;
    }
}