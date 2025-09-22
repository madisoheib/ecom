<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function it_can_create_a_brand()
    {
        $brandData = [
            'name' => 'Test Brand',
            'slug' => 'test-brand',
            'description' => 'This is a test brand',
            'website' => 'https://testbrand.com',
            'is_active' => true,
            'sort_order' => 1
        ];

        $brand = Brand::create($brandData);

        $this->assertInstanceOf(Brand::class, $brand);
        $this->assertEquals('Test Brand', $brand->name);
        $this->assertEquals('test-brand', $brand->slug);
        $this->assertTrue($brand->is_active);

        // For translatable fields, check the JSON format in database
        $this->assertDatabaseHas('brands', [
            'slug' => 'test-brand',
            'website' => 'https://testbrand.com',
            'is_active' => true,
            'sort_order' => 1
        ]);
    }

    /** @test */
    public function it_can_create_brand_with_translations()
    {
        $brandData = [
            'name' => [
                'en' => 'Test Brand',
                'fr' => 'Marque de Test',
                'ar' => 'علامة الاختبار'
            ],
            'description' => [
                'en' => 'Test brand description',
                'fr' => 'Description de la marque',
                'ar' => 'وصف العلامة التجارية'
            ],
            'slug' => 'test-brand',
            'is_active' => true
        ];

        $brand = Brand::create($brandData);

        $this->assertEquals('Test Brand', $brand->getTranslation('name', 'en'));
        $this->assertEquals('Marque de Test', $brand->getTranslation('name', 'fr'));
        $this->assertEquals('علامة الاختبار', $brand->getTranslation('name', 'ar'));
    }

    /** @test */
    public function it_can_have_products()
    {
        $brand = Brand::factory()->create();
        $product1 = Product::factory()->create(['brand_id' => $brand->id]);
        $product2 = Product::factory()->create(['brand_id' => $brand->id]);

        $this->assertEquals(2, $brand->products()->count());
        $this->assertTrue($brand->products->contains($product1));
        $this->assertTrue($brand->products->contains($product2));
    }

    /** @test */
    public function it_can_handle_logo_upload()
    {
        $brand = Brand::factory()->create();

        $logoFile = UploadedFile::fake()->image('logo.png', 200, 200);
        $brand->addMediaFromFile($logoFile)->toMediaCollection('brand_logo');

        $this->assertEquals(1, $brand->getMedia('brand_logo')->count());
        $this->assertStringContains('logo', $brand->getFirstMediaUrl('brand_logo'));
    }

    /** @test */
    public function it_generates_slug_automatically()
    {
        $brand = Brand::create([
            'name' => 'Auto Slug Brand',
            'is_active' => true
        ]);

        $this->assertEquals('auto-slug-brand', $brand->slug);
    }

    /** @test */
    public function it_can_sort_brands()
    {
        $brand1 = Brand::factory()->create(['sort_order' => 3]);
        $brand2 = Brand::factory()->create(['sort_order' => 1]);
        $brand3 = Brand::factory()->create(['sort_order' => 2]);

        $sortedBrands = Brand::orderBy('sort_order')->get();

        $this->assertEquals($brand2->id, $sortedBrands->first()->id);
        $this->assertEquals($brand1->id, $sortedBrands->last()->id);
    }

    /** @test */
    public function it_can_filter_active_brands()
    {
        Brand::factory()->create(['is_active' => true]);
        Brand::factory()->create(['is_active' => false]);
        Brand::factory()->create(['is_active' => true]);

        $activeBrands = Brand::where('is_active', true)->get();
        $inactiveBrands = Brand::where('is_active', false)->get();

        $this->assertEquals(2, $activeBrands->count());
        $this->assertEquals(1, $inactiveBrands->count());
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        Brand::create([]);
    }

    /** @test */
    public function it_can_count_products()
    {
        $brand = Brand::factory()->create();
        Product::factory()->count(5)->create(['brand_id' => $brand->id]);

        $brandWithCount = Brand::withCount('products')->find($brand->id);

        $this->assertEquals(5, $brandWithCount->products_count);
    }
}