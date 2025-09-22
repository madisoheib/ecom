<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SeoService;
use App\Models\Product;
use App\Models\Brand;
use App\Models\SeoMeta;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SeoService $seoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seoService = new SeoService();
    }

    public function test_generates_default_meta_tags_when_no_model_provided()
    {
        $meta = $this->seoService->generateMetaTags();

        $this->assertIsArray($meta);
        $this->assertArrayHasKey('title', $meta);
        $this->assertArrayHasKey('description', $meta);
        $this->assertArrayHasKey('canonical', $meta);
        $this->assertArrayHasKey('robots', $meta);
        $this->assertEquals('Accueil | Laravel E-commerce', $meta['title']);
        $this->assertEquals('index, follow', $meta['robots']);
    }

    public function test_generates_breadcrumbs_correctly()
    {
        $items = [
            ['name' => 'Category', 'url' => '/category'],
            ['name' => 'Product', 'url' => '/product'],
        ];

        $breadcrumbs = $this->seoService->generateBreadcrumbs($items);

        $this->assertCount(3, $breadcrumbs);
        $this->assertEquals('Accueil', $breadcrumbs[0]['name']);
        $this->assertEquals('Category', $breadcrumbs[1]['name']);
        $this->assertEquals('Product', $breadcrumbs[2]['name']);
        $this->assertEquals(1, $breadcrumbs[0]['position']);
        $this->assertEquals(2, $breadcrumbs[1]['position']);
        $this->assertEquals(3, $breadcrumbs[2]['position']);
    }

    public function test_generates_breadcrumb_schema()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => '/', 'position' => 1],
            ['name' => 'Category', 'url' => '/category', 'position' => 2],
        ];

        $schema = $this->seoService->generateBreadcrumbSchema($breadcrumbs);

        $this->assertEquals('https://schema.org', $schema['@context']);
        $this->assertEquals('BreadcrumbList', $schema['@type']);
        $this->assertCount(2, $schema['itemListElement']);
        $this->assertEquals('ListItem', $schema['itemListElement'][0]['@type']);
        $this->assertEquals(1, $schema['itemListElement'][0]['position']);
        $this->assertEquals('Home', $schema['itemListElement'][0]['name']);
    }

    public function test_generates_organization_schema()
    {
        $schema = $this->seoService->generateOrganizationSchema();

        $this->assertEquals('https://schema.org', $schema['@context']);
        $this->assertEquals('Organization', $schema['@type']);
        $this->assertArrayHasKey('name', $schema);
        $this->assertArrayHasKey('url', $schema);
        $this->assertArrayHasKey('contactPoint', $schema);
    }
}
