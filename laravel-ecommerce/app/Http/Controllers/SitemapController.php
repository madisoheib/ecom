<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Response;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = $this->generateSitemap();

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'inline; filename="sitemap.xml"'
        ]);
    }

    private function generateSitemap(): string
    {
        $urls = [];

        // Homepage
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => Carbon::now()->toISOString(),
            'changefreq' => 'daily',
            'priority' => '1.0'
        ];

        // Products index
        $urls[] = [
            'loc' => route('products.index'),
            'lastmod' => Carbon::now()->toISOString(),
            'changefreq' => 'daily',
            'priority' => '0.9'
        ];

        // Categories index
        $urls[] = [
            'loc' => route('categories.index'),
            'lastmod' => Carbon::now()->toISOString(),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        // Brands index
        $urls[] = [
            'loc' => route('brands.index'),
            'lastmod' => Carbon::now()->toISOString(),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        // Categories
        Category::where('is_active', true)->chunk(100, function ($categories) use (&$urls) {
            foreach ($categories as $category) {
                $urls[] = [
                    'loc' => route('categories.show', $category->slug),
                    'lastmod' => $category->updated_at->toISOString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7'
                ];
            }
        });

        // Brands
        Brand::where('is_active', true)->chunk(100, function ($brands) use (&$urls) {
            foreach ($brands as $brand) {
                $urls[] = [
                    'loc' => route('brands.show', $brand->slug),
                    'lastmod' => $brand->updated_at->toISOString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6'
                ];
            }
        });

        // Products
        Product::where('is_active', true)
            ->with('categories')
            ->chunk(100, function ($products) use (&$urls) {
                foreach ($products as $product) {
                    $category = $product->categories->first();
                    $urls[] = [
                        'loc' => route('products.show', [
                            'categorySlug' => $category ? $category->slug : 'produits',
                            'productSlug' => $product->slug
                        ]),
                        'lastmod' => $product->updated_at->toISOString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.8'
                    ];
                }
            });

        return $this->buildXml($urls);
    }

    private function buildXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>' . PHP_EOL;

        return $xml;
    }
}