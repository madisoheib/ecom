<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Services\GoogleAnalyticsService::class, function ($app) {
            return new \App\Services\GoogleAnalyticsService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Blade directive for translation
        \Illuminate\Support\Facades\Blade::directive('t', function ($expression) {
            return "<?php echo \App\Helpers\TranslationHelper::translate($expression); ?>";
        });
    }
}
