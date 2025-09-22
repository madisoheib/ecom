<?php

namespace App\Helpers;

use App\Services\SeoService;
use Illuminate\Support\HtmlString;

class SeoHelper
{
    public static function renderMetaTags($model = null): HtmlString
    {
        $seoService = app(SeoService::class);
        $meta = $seoService->generateMetaTags($model);
        
        $html = '';
        
        // Basic meta tags
        if (isset($meta['title'])) {
            $html .= '<title>' . e($meta['title']) . '</title>' . PHP_EOL;
        }
        
        if (isset($meta['description'])) {
            $html .= '<meta name="description" content="' . e($meta['description']) . '">' . PHP_EOL;
        }
        
        if (isset($meta['keywords'])) {
            $html .= '<meta name="keywords" content="' . e($meta['keywords']) . '">' . PHP_EOL;
        }
        
        if (isset($meta['canonical'])) {
            $html .= '<link rel="canonical" href="' . $meta['canonical'] . '">' . PHP_EOL;
        }
        
        if (isset($meta['robots'])) {
            $html .= '<meta name="robots" content="' . $meta['robots'] . '">' . PHP_EOL;
        }
        
        // Open Graph tags
        if (isset($meta['og:title'])) {
            $html .= '<meta property="og:title" content="' . e($meta['og:title']) . '">' . PHP_EOL;
        }
        
        if (isset($meta['og:description'])) {
            $html .= '<meta property="og:description" content="' . e($meta['og:description']) . '">' . PHP_EOL;
        }
        
        if (isset($meta['og:image'])) {
            $html .= '<meta property="og:image" content="' . $meta['og:image'] . '">' . PHP_EOL;
        }
        
        if (isset($meta['og:url'])) {
            $html .= '<meta property="og:url" content="' . $meta['og:url'] . '">' . PHP_EOL;
        }
        
        if (isset($meta['og:type'])) {
            $html .= '<meta property="og:type" content="' . $meta['og:type'] . '">' . PHP_EOL;
        }
        
        // Twitter Card tags
        if (isset($meta['twitter:card'])) {
            $html .= '<meta name="twitter:card" content="' . $meta['twitter:card'] . '">' . PHP_EOL;
        }
        
        if (isset($meta['twitter:title'])) {
            $html .= '<meta name="twitter:title" content="' . e($meta['twitter:title']) . '">' . PHP_EOL;
        }
        
        if (isset($meta['twitter:description'])) {
            $html .= '<meta name="twitter:description" content="' . e($meta['twitter:description']) . '">' . PHP_EOL;
        }
        
        if (isset($meta['twitter:image'])) {
            $html .= '<meta name="twitter:image" content="' . $meta['twitter:image'] . '">' . PHP_EOL;
        }
        
        return new HtmlString($html);
    }

    public static function renderBreadcrumbSchema(array $breadcrumbs): HtmlString
    {
        $seoService = app(SeoService::class);
        $schema = $seoService->generateBreadcrumbSchema($breadcrumbs);
        
        $html = '<script type="application/ld+json">' . PHP_EOL;
        $html .= json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $html .= PHP_EOL . '</script>';
        
        return new HtmlString($html);
    }

    public static function renderSchema(array $schema): HtmlString
    {
        $html = '<script type="application/ld+json">' . PHP_EOL;
        $html .= json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $html .= PHP_EOL . '</script>';
        
        return new HtmlString($html);
    }

    public static function renderHreflangTags(string $currentLocale, array $alternates = []): HtmlString
    {
        $html = '';
        
        // Current language
        $html .= '<link rel="alternate" hreflang="' . $currentLocale . '" href="' . url()->current() . '">' . PHP_EOL;
        
        // Alternate languages
        foreach ($alternates as $locale => $url) {
            if ($locale !== $currentLocale) {
                $html .= '<link rel="alternate" hreflang="' . $locale . '" href="' . $url . '">' . PHP_EOL;
            }
        }
        
        // X-default
        $html .= '<link rel="alternate" hreflang="x-default" href="' . config('app.url') . '">' . PHP_EOL;
        
        return new HtmlString($html);
    }
}