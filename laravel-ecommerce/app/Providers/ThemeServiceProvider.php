<?php

namespace App\Providers;

use App\Services\ThemeService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ThemeService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                $themeService = app(ThemeService::class);
                $view->with([
                    'activeTheme' => $themeService->getActiveTheme(),
                    'themeCSS' => $themeService->getCssVariables(),
                ]);
            } catch (\Exception $e) {
                // Silently fail if database tables don't exist yet
                $view->with([
                    'activeTheme' => null,
                    'themeCSS' => '',
                ]);
            }
        });
    }
}
