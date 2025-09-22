<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SitemapService;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

class SitemapServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SitemapService $sitemapService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sitemapService = new SitemapService();
    }

    protected function tearDown(): void
    {
        // Clean up generated sitemap files
        if (File::exists(public_path('sitemap.xml'))) {
            File::delete(public_path('sitemap.xml'));
        }

        foreach (['fr', 'en', 'ar'] as $locale) {
            if (File::exists(public_path('sitemap-' . $locale . '.xml'))) {
                File::delete(public_path('sitemap-' . $locale . '.xml'));
            }
        }

        parent::tearDown();
    }

    public function test_generates_basic_sitemap()
    {
        // Create test data
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $product = Product::factory()->create();
        $product->categories()->attach($category);

        $this->sitemapService->generate();

        $this->assertFileExists(public_path('sitemap.xml'));

        $sitemapContent = File::get(public_path('sitemap.xml'));
        $this->assertStringContainsString('<urlset', $sitemapContent);
        $this->assertStringContainsString('<loc>' . url('/') . '</loc>', $sitemapContent);
    }

    public function test_generates_multilingual_sitemaps()
    {
        // Create test data
        $category = Category::factory()->create();
        $product = Product::factory()->create();
        $product->categories()->attach($category);

        $this->sitemapService->generateMultilingual();

        // Check that locale-specific sitemaps are created
        foreach (['fr', 'en', 'ar'] as $locale) {
            $this->assertFileExists(public_path('sitemap-' . $locale . '.xml'));
        }

        // Check sitemap index
        $this->assertFileExists(public_path('sitemap.xml'));
        $indexContent = File::get(public_path('sitemap.xml'));
        $this->assertStringContainsString('<sitemapindex', $indexContent);
        $this->assertStringContainsString('sitemap-fr.xml', $indexContent);
        $this->assertStringContainsString('sitemap-en.xml', $indexContent);
        $this->assertStringContainsString('sitemap-ar.xml', $indexContent);
    }

    public function test_only_includes_active_content()
    {
        // Create active and inactive content
        $activeCategory = Category::factory()->create(['is_active' => true]);
        $inactiveCategory = Category::factory()->create(['is_active' => false]);

        $activeBrand = Brand::factory()->create(['is_active' => true]);
        $inactiveBrand = Brand::factory()->create(['is_active' => false]);

        $activeProduct = Product::factory()->create(['is_active' => true]);
        $inactiveProduct = Product::factory()->create(['is_active' => false]);

        $this->sitemapService->generate();

        $sitemapContent = File::get(public_path('sitemap.xml'));

        // Should include active content
        $this->assertStringContainsString('/categorie/' . $activeCategory->slug, $sitemapContent);
        $this->assertStringContainsString('/marque/' . $activeBrand->slug, $sitemapContent);

        // Should not include inactive content
        $this->assertStringNotContainsString('/categorie/' . $inactiveCategory->slug, $sitemapContent);
        $this->assertStringNotContainsString('/marque/' . $inactiveBrand->slug, $sitemapContent);
    }
}
