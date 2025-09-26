<?php

use App\Models\SiteSetting;
use App\Models\Language;
use App\Services\ThemeService;

if (!function_exists('site_title')) {
    function site_title(): string
    {
        return SiteSetting::getSetting('site_title', 'My Website');
    }
}

if (!function_exists('site_logo')) {
    function site_logo(): ?string
    {
        return SiteSetting::getSetting('site_logo');
    }
}

if (!function_exists('site_meta_description')) {
    function site_meta_description(): ?string
    {
        return SiteSetting::getSetting('meta_description');
    }
}

if (!function_exists('site_meta_keywords')) {
    function site_meta_keywords(): ?string
    {
        return SiteSetting::getSetting('meta_keywords');
    }
}

if (!function_exists('site_currency')) {
    function site_currency(): string
    {
        return SiteSetting::getSetting('default_currency', 'USD');
    }
}

if (!function_exists('site_primary_color')) {
    function site_primary_color(): string
    {
        return SiteSetting::getSetting('primary_color', '#000000');
    }
}

if (!function_exists('site_secondary_color')) {
    function site_secondary_color(): string
    {
        return SiteSetting::getSetting('secondary_color', '#FFD700');
    }
}

if (!function_exists('site_accent_color')) {
    function site_accent_color(): string
    {
        return SiteSetting::getSetting('accent_color', '#FFD700');
    }
}

if (!function_exists('site_colors')) {
    function site_colors(): array
    {
        return [
            'primary' => site_primary_color(),
            'secondary' => site_secondary_color(),
            'accent' => site_accent_color(),
        ];
    }
}

if (!function_exists('default_currency')) {
    function default_currency(): string
    {
        return SiteSetting::getSetting('default_currency', 'USD');
    }
}

if (!function_exists('primary_color')) {
    function primary_color(): string
    {
        return SiteSetting::getSetting('primary_color', '#000000');
    }
}

if (!function_exists('site_setting')) {
    function site_setting(string $key, $default = null)
    {
        return SiteSetting::getSetting($key, $default);
    }
}

if (!function_exists('site_description')) {
    function site_description(): ?string
    {
        return SiteSetting::getSetting('site_description');
    }
}

if (!function_exists('company_email')) {
    function company_email(): ?string
    {
        return SiteSetting::getSetting('company_email');
    }
}

if (!function_exists('company_phone')) {
    function company_phone(): ?string
    {
        return SiteSetting::getSetting('company_phone');
    }
}

if (!function_exists('company_address')) {
    function company_address(): ?string
    {
        return SiteSetting::getSetting('company_address');
    }
}

if (!function_exists('social_media_links')) {
    function social_media_links(): array
    {
        return [
            'facebook' => SiteSetting::getSetting('facebook_url'),
            'twitter' => SiteSetting::getSetting('twitter_url'),
            'instagram' => SiteSetting::getSetting('instagram_url'),
            'linkedin' => SiteSetting::getSetting('linkedin_url'),
            'youtube' => SiteSetting::getSetting('youtube_url'),
        ];
    }
}

if (!function_exists('theme_colors')) {
    function theme_colors(): array
    {
        return [
            'primary' => SiteSetting::getSetting('primary_color', '#000000'),
            'secondary' => SiteSetting::getSetting('secondary_color', '#FFD700'),
            'accent' => SiteSetting::getSetting('accent_color', '#FFD700'),
        ];
    }
}

if (!function_exists('generate_theme_css')) {
    function generate_theme_css(): string
    {
        $colors = theme_colors();
        $primary = $colors['primary'];
        $secondary = $colors['secondary'];
        $accent = $colors['accent'];

        // Convert hex to RGB for CSS custom properties
        $primaryRgb = hex_to_rgb($primary);
        $secondaryRgb = hex_to_rgb($secondary);
        $accentRgb = hex_to_rgb($accent);

        return "
        :root {
            --color-primary: {$primary};
            --color-primary-rgb: {$primaryRgb};
            --color-secondary: {$secondary};
            --color-secondary-rgb: {$secondaryRgb};
            --color-accent: {$accent};
            --color-accent-rgb: {$accentRgb};
            --color-primary-50: " . lighten_color($primary, 0.9) . ";
            --color-primary-100: " . lighten_color($primary, 0.8) . ";
            --color-primary-200: " . lighten_color($primary, 0.6) . ";
            --color-primary-300: " . lighten_color($primary, 0.4) . ";
            --color-primary-400: " . lighten_color($primary, 0.2) . ";
            --color-primary-500: {$primary};
            --color-primary-600: " . darken_color($primary, 0.1) . ";
            --color-primary-700: " . darken_color($primary, 0.2) . ";
            --color-primary-800: " . darken_color($primary, 0.3) . ";
            --color-primary-900: " . darken_color($primary, 0.4) . ";
        }

        .bg-primary { background-color: var(--color-primary) !important; }
        .bg-primary-50 { background-color: var(--color-primary-50) !important; }
        .bg-primary-100 { background-color: var(--color-primary-100) !important; }
        .bg-primary-600 { background-color: var(--color-primary-600) !important; }
        .bg-primary-700 { background-color: var(--color-primary-700) !important; }
        .bg-secondary { background-color: var(--color-secondary) !important; }
        .text-primary { color: var(--color-primary) !important; }
        .text-primary-600 { color: var(--color-primary-600) !important; }
        .text-primary-700 { color: var(--color-primary-700) !important; }
        .text-secondary { color: var(--color-secondary) !important; }
        .border-primary { border-color: var(--color-primary) !important; }
        .border-primary-200 { border-color: var(--color-primary-200) !important; }
        .border-primary-300 { border-color: var(--color-primary-300) !important; }
        .border-secondary { border-color: var(--color-secondary) !important; }
        .hover\\:bg-primary:hover { background-color: var(--color-primary) !important; }
        .hover\\:bg-primary-50:hover { background-color: var(--color-primary-50) !important; }
        .hover\\:bg-primary-700:hover { background-color: var(--color-primary-700) !important; }
        .hover\\:bg-secondary:hover { background-color: var(--color-secondary) !important; }
        .hover\\:text-primary:hover { color: var(--color-primary) !important; }
        .hover\\:text-primary-600:hover { color: var(--color-primary-600) !important; }
        .hover\\:text-secondary:hover { color: var(--color-secondary) !important; }
        .hover\\:border-primary-200:hover { border-color: var(--color-primary-200) !important; }
        .hover\\:border-secondary:hover { border-color: var(--color-secondary) !important; }
        .hover\\:shadow-secondary\\/20:hover { box-shadow: 0 10px 15px -3px rgba(255, 215, 0, 0.2), 0 4px 6px -2px rgba(255, 215, 0, 0.1) !important; }
        .focus\\:ring-primary:focus { --tw-ring-color: var(--color-primary) !important; }
        .focus\\:ring-primary-300:focus { --tw-ring-color: var(--color-primary-300) !important; }
        .bg-gradient-primary { background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-700) 100%) !important; }
        .bg-gradient-primary-accent { background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%) !important; }
        ";
    }
}

if (!function_exists('hex_to_rgb')) {
    function hex_to_rgb(string $hex): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "{$r}, {$g}, {$b}";
    }
}

if (!function_exists('lighten_color')) {
    function lighten_color(string $hex, float $percent): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = min(255, $r + (255 - $r) * $percent);
        $g = min(255, $g + (255 - $g) * $percent);
        $b = min(255, $b + (255 - $b) * $percent);

        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }
}

if (!function_exists('darken_color')) {
    function darken_color(string $hex, float $percent): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, $r * (1 - $percent));
        $g = max(0, $g * (1 - $percent));
        $b = max(0, $b * (1 - $percent));

        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }
}

if (!function_exists('active_languages')) {
    function active_languages()
    {
        try {
            return Language::where('is_active', true)->get();
        } catch (\Exception $e) {
            // Return default languages if table doesn't exist yet
            return collect([
                (object)['code' => 'en', 'name' => ['en' => 'English'], 'native_name' => 'English'],
                (object)['code' => 'fr', 'name' => ['fr' => 'Français'], 'native_name' => 'Français'],
                (object)['code' => 'ar', 'name' => ['ar' => 'العربية'], 'native_name' => 'العربية'],
            ]);
        }
    }
}

if (!function_exists('get_language_flag')) {
    function get_language_flag($langCode): string
    {
        $flags = [
            'en' => '🇺🇸',
            'fr' => '🇫🇷',
            'ar' => '🇸🇦',
            'ca' => '🇨🇦', // Canada
            'dz' => '🇩🇿', // Algeria  
            'ae' => '🇦🇪', // UAE
        ];
        
        return $flags[$langCode] ?? '🌐';
    }
}

if (!function_exists('get_theme_css_variables')) {
    function get_theme_css_variables(): string
    {
        try {
            $themeService = app(ThemeService::class);
            return $themeService->getCssVariables();
        } catch (\Exception $e) {
            return '';
        }
    }
}

if (!function_exists('get_user_country_from_ip')) {
    function get_user_country_from_ip($ip = null): array
    {
        try {
            $ip = $ip ?: request()->ip();
            $location = geoip($ip);
            
            return [
                'country_code' => $location->iso_code,
                'country_name' => $location->country,
                'city' => $location->city,
                'state' => $location->state_name,
            ];
        } catch (\Exception $e) {
            // Fallback to default if GeoIP fails
            return [
                'country_code' => 'CA',
                'country_name' => 'Canada',
                'city' => 'Toronto',
                'state' => 'Ontario',
            ];
        }
    }
}

if (!function_exists('get_cities_for_country')) {
    function get_cities_for_country($countryCode): array
    {
        $cities = [
            'CA' => [
                'Toronto' => 'Toronto',
                'Montreal' => 'Montréal',
                'Vancouver' => 'Vancouver',
                'Calgary' => 'Calgary',
                'Ottawa' => 'Ottawa',
                'Edmonton' => 'Edmonton',
                'Quebec City' => 'Québec',
                'Winnipeg' => 'Winnipeg',
                'Hamilton' => 'Hamilton',
                'London' => 'London',
                'Kitchener' => 'Kitchener',
                'Halifax' => 'Halifax',
                'Victoria' => 'Victoria',
                'Windsor' => 'Windsor',
                'Saskatoon' => 'Saskatoon',
                'Regina' => 'Regina',
            ],
            'US' => [
                'New York' => 'New York',
                'Los Angeles' => 'Los Angeles',
                'Chicago' => 'Chicago',
                'Houston' => 'Houston',
                'Phoenix' => 'Phoenix',
                'Philadelphia' => 'Philadelphia',
                'San Antonio' => 'San Antonio',
                'San Diego' => 'San Diego',
                'Dallas' => 'Dallas',
                'San Jose' => 'San Jose',
                'Austin' => 'Austin',
                'Jacksonville' => 'Jacksonville',
                'Fort Worth' => 'Fort Worth',
                'Columbus' => 'Columbus',
                'Charlotte' => 'Charlotte',
                'San Francisco' => 'San Francisco',
                'Indianapolis' => 'Indianapolis',
                'Seattle' => 'Seattle',
                'Denver' => 'Denver',
                'Boston' => 'Boston',
            ],
            'FR' => [
                'Paris' => 'Paris',
                'Marseille' => 'Marseille',
                'Lyon' => 'Lyon',
                'Toulouse' => 'Toulouse',
                'Nice' => 'Nice',
                'Nantes' => 'Nantes',
                'Montpellier' => 'Montpellier',
                'Strasbourg' => 'Strasbourg',
                'Bordeaux' => 'Bordeaux',
                'Lille' => 'Lille',
                'Rennes' => 'Rennes',
                'Reims' => 'Reims',
                'Le Havre' => 'Le Havre',
                'Saint-Étienne' => 'Saint-Étienne',
                'Toulon' => 'Toulon',
                'Grenoble' => 'Grenoble',
            ],
            'UK' => [
                'London' => 'London',
                'Birmingham' => 'Birmingham',
                'Manchester' => 'Manchester',
                'Glasgow' => 'Glasgow',
                'Liverpool' => 'Liverpool',
                'Leeds' => 'Leeds',
                'Sheffield' => 'Sheffield',
                'Edinburgh' => 'Edinburgh',
                'Bristol' => 'Bristol',
                'Cardiff' => 'Cardiff',
                'Belfast' => 'Belfast',
                'Newcastle' => 'Newcastle',
                'Nottingham' => 'Nottingham',
                'Southampton' => 'Southampton',
                'Portsmouth' => 'Portsmouth',
            ]
        ];

        return $cities[$countryCode] ?? [];
    }
}

if (!function_exists('get_countries_list')) {
    function get_countries_list(): array
    {
        return [
            'CA' => 'Canada',
            'US' => 'United States',
            'FR' => 'France', 
            'UK' => 'United Kingdom',
            'DE' => 'Germany',
            'ES' => 'Spain',
            'IT' => 'Italy',
            'BE' => 'Belgium',
            'CH' => 'Switzerland',
            'AU' => 'Australia',
            'other' => 'Other'
        ];
    }
}