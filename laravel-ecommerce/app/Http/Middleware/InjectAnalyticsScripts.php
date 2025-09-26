<?php

namespace App\Http\Middleware;

use App\Services\AnalyticsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectAnalyticsScripts
{
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only inject scripts for HTML responses
        if ($response->headers->get('Content-Type') !== null && 
            !str_contains($response->headers->get('Content-Type'), 'text/html')) {
            return $response;
        }
        
        $content = $response->getContent();
        
        if ($content && $this->shouldInjectScripts($request)) {
            $scripts = $this->analyticsService->getAllScripts();
            
            // Inject head scripts
            if ($scripts['head'] && str_contains($content, '</head>')) {
                $content = str_replace('</head>', $scripts['head'] . '\n</head>', $content);
            }
            
            // Inject body scripts
            if ($scripts['body'] && str_contains($content, '<body')) {
                $content = preg_replace('/(<body[^>]*>)/', '$1\n' . $scripts['body'], $content, 1);
            }
            
            $response->setContent($content);
        }
        
        return $response;
    }
    
    private function shouldInjectScripts(Request $request): bool
    {
        // Don't inject scripts for admin routes or API routes
        return !$request->is('admin/*') && !$request->is('api/*');
    }
}
