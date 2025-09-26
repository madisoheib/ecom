<?php

namespace App\Services;

use App\Models\AnalyticsSettings;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    public function getActiveSettings(): ?AnalyticsSettings
    {
        try {
            return Cache::remember('analytics_settings', 3600, function () {
                return AnalyticsSettings::getActive();
            });
        } catch (\Exception $e) {
            // Return null if table doesn't exist yet or other database errors
            return null;
        }
    }

    public function getGoogleAnalyticsScript(): string
    {
        $settings = $this->getActiveSettings();
        
        if (!$settings || !$settings->google_analytics_id) {
            return '';
        }

        $anonymizeIP = $settings->anonymize_ip ? "'anonymize_ip': true," : '';
        
        return "
        <!-- Google Analytics -->
        <script async src=\"https://www.googletagmanager.com/gtag/js?id={$settings->google_analytics_id}\"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{$settings->google_analytics_id}', {
                {$anonymizeIP}
                'cookie_flags': 'SameSite=None;Secure'
            });
        </script>
        ";
    }

    public function getGoogleTagManagerScript(): string
    {
        $settings = $this->getActiveSettings();
        
        if (!$settings || !$settings->google_tag_manager_id) {
            return '';
        }

        return "
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{$settings->google_tag_manager_id}');</script>
        <!-- End Google Tag Manager -->
        ";
    }

    public function getGoogleTagManagerNoScript(): string
    {
        $settings = $this->getActiveSettings();
        
        if (!$settings || !$settings->google_tag_manager_id) {
            return '';
        }

        return "
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id={$settings->google_tag_manager_id}\"
        height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        ";
    }

    public function getFacebookPixelScript(): string
    {
        $settings = $this->getActiveSettings();
        
        if (!$settings || !$settings->facebook_pixel_id) {
            return '';
        }

        return "
        <!-- Facebook Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{$settings->facebook_pixel_id}');
        fbq('track', 'PageView');
        </script>
        <noscript><img height=\"1\" width=\"1\" style=\"display:none\"
        src=\"https://www.facebook.com/tr?id={$settings->facebook_pixel_id}&ev=PageView&noscript=1\"
        /></noscript>
        <!-- End Facebook Pixel Code -->
        ";
    }

    public function trackEcommerceEvent(string $event, array $data = []): string
    {
        $settings = $this->getActiveSettings();
        
        if (!$settings || !$settings->track_ecommerce) {
            return '';
        }

        $eventData = json_encode($data);
        
        $script = '';
        
        // Google Analytics 4
        if ($settings->google_analytics_id) {
            $script .= "
            <script>
                gtag('event', '{$event}', {$eventData});
            </script>
            ";
        }
        
        // Facebook Pixel
        if ($settings->facebook_pixel_id) {
            $fbEvent = $this->mapToFacebookEvent($event);
            if ($fbEvent) {
                $script .= "
                <script>
                    fbq('track', '{$fbEvent}', {$eventData});
                </script>
                ";
            }
        }
        
        return $script;
    }

    private function mapToFacebookEvent(string $event): ?string
    {
        $mapping = [
            'purchase' => 'Purchase',
            'add_to_cart' => 'AddToCart',
            'view_item' => 'ViewContent',
            'begin_checkout' => 'InitiateCheckout',
            'add_to_wishlist' => 'AddToWishlist',
            'search' => 'Search',
            'view_item_list' => 'ViewCategory',
        ];

        return $mapping[$event] ?? null;
    }

    public function getAllScripts(): array
    {
        return [
            'head' => $this->getGoogleTagManagerScript() . $this->getGoogleAnalyticsScript() . $this->getFacebookPixelScript(),
            'body' => $this->getGoogleTagManagerNoScript(),
        ];
    }

    public function clearCache(): void
    {
        Cache::forget('analytics_settings');
    }
}