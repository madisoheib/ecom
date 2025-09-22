<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;

class SitemapService
{
    public function generate(): void
    {
        $sitemap = Sitemap::create();
        
        // Add static pages
        $sitemap->add(
            Url::create('/')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );
        
        // Add categories
        Category::where('is_active', true)->get()->each(function ($category) use ($sitemap) {
            $sitemap->add(
                Url::create('/categorie/' . $category->slug)
                    ->setLastModificationDate($category->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        });
        
        // Add brands
        Brand::where('is_active', true)->get()->each(function ($brand) use ($sitemap) {
            $sitemap->add(
                Url::create('/marque/' . $brand->slug)
                    ->setLastModificationDate($brand->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7)
            );
        });
        
        // Add products
        Product::where('is_active', true)->with('categories')->get()->each(function ($product) use ($sitemap) {
            $category = $product->categories->first();
            $categorySlug = $category ? $category->slug : 'produits';
            
            $sitemap->add(
                Url::create('/produit/' . $categorySlug . '/' . $product->slug)
                    ->setLastModificationDate($product->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.6)
            );
        });
        
        $sitemap->writeToFile(public_path('sitemap.xml'));
    }

    public function generateMultilingual(): void
    {
        $locales = ['fr', 'en', 'ar'];
        
        foreach ($locales as $locale) {
            $sitemap = Sitemap::create();
            
            // Add localized URLs
            $sitemap->add(
                Url::create('/' . $locale)
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(1.0)
            );
            
            // Add categories with locale
            Category::where('is_active', true)->get()->each(function ($category) use ($sitemap, $locale) {
                $sitemap->add(
                    Url::create('/' . $locale . '/categorie/' . $category->slug)
                        ->setLastModificationDate($category->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8)
                );
            });
            
            // Add products with locale
            Product::where('is_active', true)->with('categories')->get()->each(function ($product) use ($sitemap, $locale) {
                $category = $product->categories->first();
                $categorySlug = $category ? $category->slug : 'produits';
                
                $sitemap->add(
                    Url::create('/' . $locale . '/produit/' . $categorySlug . '/' . $product->slug)
                        ->setLastModificationDate($product->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.6)
                );
            });
            
            $sitemap->writeToFile(public_path('sitemap-' . $locale . '.xml'));
        }
        
        // Create index sitemap
        $this->createSitemapIndex();
    }

    protected function createSitemapIndex(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        
        $locales = ['fr', 'en', 'ar'];
        foreach ($locales as $locale) {
            $xml .= '  <sitemap>' . PHP_EOL;
            $xml .= '    <loc>' . url('/sitemap-' . $locale . '.xml') . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . Carbon::now()->toW3cString() . '</lastmod>' . PHP_EOL;
            $xml .= '  </sitemap>' . PHP_EOL;
        }
        
        $xml .= '</sitemapindex>';
        
        file_put_contents(public_path('sitemap.xml'), $xml);
    }
}