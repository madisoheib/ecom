<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();
        
        // Check for redirect
        $redirect = Redirect::active()
            ->where('from_url', '/' . $path)
            ->orWhere('from_url', $path)
            ->first();
        
        if ($redirect) {
            $redirect->incrementHits();
            return redirect($redirect->to_url, $redirect->status_code);
        }
        
        return $next($request);
    }
}