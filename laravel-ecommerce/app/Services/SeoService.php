<?php

namespace App\Services;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class SeoService
{
    protected array $defaultMeta = [
        'title_suffix' => ' | Laravel E-commerce',
        'default_description' => 'Découvrez notre boutique en ligne avec les meilleures offres',
        'og_type' => 'website',
        'twitter_card' => 'summary_large_image',
    ];

    public function generateMetaTags(?Model $model = null, $locale = null): array
    {
        $locale = $locale ?: app()->getLocale();
        $meta = [];

        // Handle Product models with multilingual SEO
        if ($model instanceof \App\Models\Product) {
            return $this->generateProductMetaTags($model, $locale);
        }

        // Handle other models with seoMeta relationship
        if ($model && method_exists($model, 'seoMeta')) {
            $seoMeta = $model->seoMeta;

            if ($seoMeta) {
                $meta['title'] = $this->formatTitle($seoMeta->meta_title);
                $meta['description'] = $seoMeta->meta_description;
                $meta['keywords'] = $seoMeta->meta_keywords;
                $meta['canonical'] = $seoMeta->canonical_url ?? URL::current();
                $meta['robots'] = $seoMeta->robots;

                // Open Graph
                $meta['og:title'] = $seoMeta->og_title ?? $seoMeta->meta_title;
                $meta['og:description'] = $seoMeta->og_description ?? $seoMeta->meta_description;
                $meta['og:image'] = $seoMeta->og_image ? asset($seoMeta->og_image) : null;
                $meta['og:url'] = $meta['canonical'];
                $meta['og:type'] = $this->defaultMeta['og_type'];

                // Twitter Card
                $meta['twitter:card'] = $seoMeta->twitter_card ?? $this->defaultMeta['twitter_card'];
                $meta['twitter:title'] = $seoMeta->twitter_title ?? $seoMeta->meta_title;
                $meta['twitter:description'] = $seoMeta->twitter_description ?? $seoMeta->meta_description;
                $meta['twitter:image'] = $seoMeta->twitter_image ? asset($seoMeta->twitter_image) : $meta['og:image'];

                // Custom meta tags
                if ($seoMeta->custom_meta) {
                    $meta = array_merge($meta, $seoMeta->custom_meta);
                }
            }
        }

        // Set defaults if not set
        $meta['title'] = $meta['title'] ?? $this->formatTitle('Accueil');
        $meta['description'] = $meta['description'] ?? $this->defaultMeta['default_description'];
        $meta['canonical'] = $meta['canonical'] ?? URL::current();
        $meta['robots'] = $meta['robots'] ?? 'index, follow';

        return $meta;
    }

    public function generateProductMetaTags(\App\Models\Product $product, $locale = null): array
    {
        $locale = $locale ?: app()->getLocale();

        $title = $product->getMetaTitle($locale);
        $description = $product->getMetaDescription($locale);
        $keywords = $product->getMetaKeywords($locale);
        $canonicalUrl = $product->getCanonicalUrl();

        $meta = [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'canonical' => $canonicalUrl,
            'robots' => $product->index_follow ? 'index, follow' : 'noindex, nofollow',

            // Open Graph
            'og:title' => $title,
            'og:description' => $description,
            'og:url' => $canonicalUrl,
            'og:type' => 'product',
            'og:site_name' => config('app.name'),

            // Twitter Card
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $title,
            'twitter:description' => $description,
        ];

        // Add product-specific meta tags
        if ($product->brand) {
            $meta['product:brand'] = $product->brand->name;
        }

        if ($product->sku) {
            $meta['product:retailer_item_id'] = $product->sku;
        }

        $meta['product:price:amount'] = $product->price;
        $meta['product:price:currency'] = config('app.currency', 'EUR');
        $meta['product:availability'] = $product->stock_quantity > 0 ? 'in stock' : 'out of stock';

        return $meta;
    }

    protected function formatTitle(?string $title): string
    {
        if (empty($title)) {
            return 'Accueil' . $this->defaultMeta['title_suffix'];
        }
        
        return $title . $this->defaultMeta['title_suffix'];
    }

    public function generateBreadcrumbs(array $items = []): array
    {
        $breadcrumbs = [
            [
                'name' => 'Accueil',
                'url' => url('/'),
                'position' => 1,
            ],
        ];
        
        $position = 2;
        foreach ($items as $item) {
            $breadcrumbs[] = [
                'name' => $item['name'],
                'url' => $item['url'] ?? null,
                'position' => $position++,
            ];
        }
        
        return $breadcrumbs;
    }

    public function generateBreadcrumbSchema(array $breadcrumbs): array
    {
        $items = [];
        
        foreach ($breadcrumbs as $breadcrumb) {
            $item = [
                '@type' => 'ListItem',
                'position' => $breadcrumb['position'],
                'name' => $breadcrumb['name'],
            ];
            
            if ($breadcrumb['url']) {
                $item['item'] = $breadcrumb['url'];
            }
            
            $items[] = $item;
        }
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    public function generateProductSchema($product): array
    {
        if ($product instanceof \App\Models\Product) {
            return $product->generateSchemaMarkup();
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => strip_tags($product->description),
            'sku' => $product->sku,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand?->name,
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => url('/product/' . $product->slug),
                'priceCurrency' => 'EUR',
                'price' => $product->price,
                'availability' => $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            ],
        ];
    }

    public function generateOrganizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => config('app.url'),
            'logo' => asset('images/logo.png'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+33-1-234-567',
                'contactType' => 'customer service',
                'availableLanguage' => ['French', 'English', 'Arabic'],
            ],
        ];
    }
}