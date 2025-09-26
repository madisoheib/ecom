<?php

namespace App\Http\Middleware;

use App\Services\ThemeService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectThemeCSS
{
    public function __construct(
        private ThemeService $themeService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only inject CSS for HTML responses
        if ($response->headers->get('Content-Type') !== null && 
            !str_contains($response->headers->get('Content-Type'), 'text/html')) {
            return $response;
        }
        
        $content = $response->getContent();
        
        if ($content && str_contains($content, '</head>')) {
            $themeCSS = $this->themeService->getCssVariables();
            $styleTag = "<style id='dynamic-theme'>\n{$themeCSS}\n</style>\n";
            
            $content = str_replace('</head>', $styleTag . '</head>', $content);
            $response->setContent($content);
        }
        
        return $response;
    }
}
