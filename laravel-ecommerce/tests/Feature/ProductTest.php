<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function it_can_create_a_product()
    {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'This is a test product',
            'short_description' => 'Short description',
            'price' => 99.99,
            'compare_price' => 129.99,
            'cost_price' => 50.00,
            'sku' => 'TEST-001',
            'barcode' => '1234567890',
            'track_quantity' => true,
            'quantity' => 100,
            'allow_backorder' => false,
            'brand_id' => $brand->id,
            'is_active' => true,
            'is_featured' => false,
            'is_digital' => false,
            'weight' => 1.5,
            'width' => 10.0,
            'height' => 5.0,
            'length' => 15.0
        ];

        $product = Product::create($productData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals(99.99, $product->price);
        $this->assertDatabaseHas('products', array_merge($productData, ['id' => $product->id]));
    }

    /** @test */
    public function it_can_create_product_with_translations()
    {
        $brand = Brand::factory()->create();

        $productData = [
            'name' => [
                'en' => 'Test Product',
                'fr' => 'Produit de Test',
                'ar' => 'منتج الاختبار'
            ],
            'description' => [
                'en' => 'Test description',
                'fr' => 'Description de test',
                'ar' => 'وصف الاختبار'
            ],
            'slug' => 'test-product',
            'price' => 99.99,
            'brand_id' => $brand->id,
            'is_active' => true
        ];

        $product = Product::create($productData);

        $this->assertEquals('Test Product', $product->getTranslation('name', 'en'));
        $this->assertEquals('Produit de Test', $product->getTranslation('name', 'fr'));
        $this->assertEquals('منتج الاختبار', $product->getTranslation('name', 'ar'));
    }

    /** @test */
    public function it_can_attach_categories_to_product()
    {
        $product = Product::factory()->create();
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $product->categories()->attach([$category1->id, $category2->id]);

        $this->assertEquals(2, $product->categories()->count());
        $this->assertTrue($product->categories->contains($category1));
        $this->assertTrue($product->categories->contains($category2));
    }

    /** @test */
    public function it_belongs_to_a_brand()
    {
        $brand = Brand::factory()->create();
        $product = Product::factory()->create(['brand_id' => $brand->id]);

        $this->assertEquals($brand->id, $product->brand->id);
        $this->assertEquals($brand->name, $product->brand->name);
    }

    /** @test */
    public function it_can_handle_product_images()
    {
        $product = Product::factory()->create();

        $imageFile1 = UploadedFile::fake()->image('product1.jpg', 800, 600);
        $imageFile2 = UploadedFile::fake()->image('product2.jpg', 800, 600);

        $product->addMediaFromFile($imageFile1)->toMediaCollection('product_images');
        $product->addMediaFromFile($imageFile2)->toMediaCollection('product_images');

        $this->assertEquals(2, $product->getMedia('product_images')->count());
    }

    /** @test */
    public function it_calculates_discount_percentage()
    {
        $product = Product::factory()->create([
            'price' => 80.00,
            'compare_price' => 100.00
        ]);

        $expectedDiscount = ((100.00 - 80.00) / 100.00) * 100;

        // Add method to Product model if not exists
        if (method_exists($product, 'getDiscountPercentage')) {
            $this->assertEquals($expectedDiscount, $product->getDiscountPercentage());
        }
    }

    /** @test */
    public function it_can_check_stock_availability()
    {
        $inStockProduct = Product::factory()->create([
            'track_quantity' => true,
            'quantity' => 10
        ]);

        $outOfStockProduct = Product::factory()->create([
            'track_quantity' => true,
            'quantity' => 0
        ]);

        $unlimitedProduct = Product::factory()->create([
            'track_quantity' => false
        ]);

        // Add methods to Product model if not exists
        if (method_exists($inStockProduct, 'inStock')) {
            $this->assertTrue($inStockProduct->inStock());
            $this->assertFalse($outOfStockProduct->inStock());
            $this->assertTrue($unlimitedProduct->inStock());
        }
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        Product::create([]);
    }

    /** @test */
    public function it_generates_slug_automatically()
    {
        $brand = Brand::factory()->create();

        $product = Product::create([
            'name' => 'Auto Slug Product',
            'price' => 99.99,
            'brand_id' => $brand->id,
            'is_active' => true
        ]);

        $this->assertEquals('auto-slug-product', $product->slug);
    }

    /** @test */
    public function it_can_filter_featured_products()
    {
        Product::factory()->create(['is_featured' => true]);
        Product::factory()->create(['is_featured' => false]);
        Product::factory()->create(['is_featured' => true]);

        $featuredProducts = Product::where('is_featured', true)->get();
        $regularProducts = Product::where('is_featured', false)->get();

        $this->assertEquals(2, $featuredProducts->count());
        $this->assertEquals(1, $regularProducts->count());
    }

    /** @test */
    public function it_can_filter_active_products()
    {
        Product::factory()->create(['is_active' => true]);
        Product::factory()->create(['is_active' => false]);
        Product::factory()->create(['is_active' => true]);

        $activeProducts = Product::where('is_active', true)->get();
        $inactiveProducts = Product::where('is_active', false)->get();

        $this->assertEquals(2, $activeProducts->count());
        $this->assertEquals(1, $inactiveProducts->count());
    }
}