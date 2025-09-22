<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EcommerceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating site settings...');
        $this->createSiteSettings();

        $this->command->info('Creating additional users...');
        $this->createUsers();

        $this->command->info('Creating brands...');
        $brands = $this->createBrands();

        $this->command->info('Creating categories...');
        $categories = $this->createCategories();

        $this->command->info('Creating products...');
        $this->createProducts($brands, $categories);

        $this->command->info('E-commerce data seeded successfully!');
    }

    private function createSiteSettings()
    {
        SiteSetting::create([
            'site_title' => 'Demo E-commerce Store',
            'site_description' => 'Welcome to our premium e-commerce platform where quality meets affordability. Discover thousands of products from top brands.',
            'meta_description' => 'Your one-stop shop for quality products at great prices',
            'meta_keywords' => 'ecommerce, shopping, products, online store',
            'default_currency' => 'USD',
            'primary_color' => '#3b82f6',
            'secondary_color' => '#64748b',
            'accent_color' => '#10b981',
            'company_email' => 'hello@demoestore.com',
            'company_phone' => '+1 (555) 123-4567',
            'company_address' => '123 Commerce Street, Shopping District, NY 10001',
            'facebook_url' => 'https://facebook.com/demoestore',
            'twitter_url' => 'https://twitter.com/demoestore',
            'instagram_url' => 'https://instagram.com/demoestore',
            'linkedin_url' => 'https://linkedin.com/company/demoestore',
            'youtube_url' => 'https://youtube.com/c/demoestore',
        ]);
    }

    private function createUsers()
    {
        User::factory()->count(10)->create();
    }

    private function createBrands()
    {
        $brandNames = [
            'TechNova', 'StyleCraft', 'SportMax', 'HomeEssentials', 'FashionForward',
            'GreenLife', 'ProTools', 'ComfortZone', 'EliteDesign', 'PureLiving'
        ];

        $brands = collect();

        foreach ($brandNames as $index => $name) {
            $brands->push(Brand::create([
                'name' => [
                    'en' => $name,
                    'fr' => $name . ' FR',
                    'ar' => $name . ' AR'
                ],
                'description' => [
                    'en' => "Leading brand in quality and innovation - {$name}",
                    'fr' => "Marque leader en qualité et innovation - {$name}",
                    'ar' => "العلامة التجارية الرائدة في الجودة والابتكار - {$name}"
                ],
                'slug' => \Illuminate\Support\Str::slug($name),
                'is_active' => true,
                'sort_order' => $index + 1,
            ]));
        }

        return $brands;
    }

    private function createCategories()
    {
        $categories = collect();

        // Main categories
        $mainCategories = [
            ['name' => 'Electronics', 'description' => 'Latest electronic gadgets and devices'],
            ['name' => 'Fashion', 'description' => 'Trendy clothing and accessories'],
            ['name' => 'Home & Garden', 'description' => 'Everything for your home and garden'],
            ['name' => 'Sports & Outdoors', 'description' => 'Sports equipment and outdoor gear'],
            ['name' => 'Books & Media', 'description' => 'Books, movies, and digital media'],
        ];

        foreach ($mainCategories as $index => $categoryData) {
            $category = Category::create([
                'name' => [
                    'en' => $categoryData['name'],
                    'fr' => $categoryData['name'] . ' FR',
                    'ar' => $categoryData['name'] . ' AR'
                ],
                'description' => [
                    'en' => $categoryData['description'],
                    'fr' => $categoryData['description'] . ' (français)',
                    'ar' => $categoryData['description'] . ' (عربي)'
                ],
                'slug' => \Illuminate\Support\Str::slug($categoryData['name']),
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
            $categories->push($category);
        }

        // Subcategories
        $subcategories = [
            ['parent' => 'Electronics', 'name' => 'Smartphones', 'description' => 'Latest smartphones and accessories'],
            ['parent' => 'Electronics', 'name' => 'Laptops', 'description' => 'High-performance laptops and notebooks'],
            ['parent' => 'Electronics', 'name' => 'Headphones', 'description' => 'Premium audio equipment'],

            ['parent' => 'Fashion', 'name' => 'Men\'s Clothing', 'description' => 'Stylish clothing for men'],
            ['parent' => 'Fashion', 'name' => 'Women\'s Clothing', 'description' => 'Trendy fashion for women'],
            ['parent' => 'Fashion', 'name' => 'Accessories', 'description' => 'Fashion accessories and jewelry'],

            ['parent' => 'Home & Garden', 'name' => 'Furniture', 'description' => 'Quality furniture for every room'],
            ['parent' => 'Home & Garden', 'name' => 'Kitchen', 'description' => 'Kitchen appliances and tools'],

            ['parent' => 'Sports & Outdoors', 'name' => 'Fitness', 'description' => 'Fitness equipment and gear'],
            ['parent' => 'Sports & Outdoors', 'name' => 'Outdoor Gear', 'description' => 'Equipment for outdoor adventures'],
        ];

        foreach ($subcategories as $index => $subCategoryData) {
            $parentCategory = $categories->first(function ($cat) use ($subCategoryData) {
                return $cat->name === $subCategoryData['parent'];
            });

            if ($parentCategory) {
                Category::create([
                    'name' => [
                        'en' => $subCategoryData['name'],
                        'fr' => $subCategoryData['name'] . ' FR',
                        'ar' => $subCategoryData['name'] . ' AR'
                    ],
                    'description' => [
                        'en' => $subCategoryData['description'],
                        'fr' => $subCategoryData['description'] . ' (français)',
                        'ar' => $subCategoryData['description'] . ' (عربي)'
                    ],
                    'slug' => \Illuminate\Support\Str::slug($subCategoryData['name']),
                    'parent_id' => $parentCategory->id,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        return Category::all();
    }

    private function createProducts($brands, $categories)
    {
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with Pro camera system and A17 Pro chip',
                'short_description' => 'Premium smartphone with advanced features',
                'price' => 999.99,
                'compare_price' => 1099.99,
                'cost_price' => 750.00,
                'sku' => 'IPH15P-128-TI',
                'barcode' => '194253715801',
                'stock_quantity' => 50,
                'quantity' => 50,
                'weight' => 0.221,
                'category' => 'Smartphones',
                'brand' => 'TechNova',
                'is_featured' => true,
            ],
            [
                'name' => 'MacBook Pro 16"',
                'description' => 'Professional laptop with M3 Pro chip and stunning Liquid Retina XDR display',
                'short_description' => 'High-performance laptop for professionals',
                'price' => 2499.99,
                'compare_price' => 2699.99,
                'cost_price' => 1800.00,
                'sku' => 'MBP16-M3P-512',
                'barcode' => '195949115702',
                'stock_quantity' => 25,
                'quantity' => 25,
                'weight' => 2.15,
                'category' => 'Laptops',
                'brand' => 'TechNova',
                'is_featured' => true,
            ],
            [
                'name' => 'AirPods Pro 2nd Gen',
                'description' => 'Premium wireless earbuds with active noise cancellation',
                'short_description' => 'Wireless earbuds with noise cancellation',
                'price' => 249.99,
                'compare_price' => 279.99,
                'cost_price' => 150.00,
                'sku' => 'APP2-USB-C',
                'barcode' => '194253396710',
                'stock_quantity' => 100,
                'quantity' => 100,
                'weight' => 0.061,
                'category' => 'Headphones',
                'brand' => 'TechNova',
                'is_featured' => false,
            ],
            [
                'name' => 'Classic Cotton T-Shirt',
                'description' => 'Comfortable 100% cotton t-shirt in various colors',
                'short_description' => 'Premium cotton t-shirt for everyday wear',
                'price' => 29.99,
                'compare_price' => 39.99,
                'cost_price' => 15.00,
                'sku' => 'TSH-COT-BLU-L',
                'barcode' => '123456789012',
                'stock_quantity' => 200,
                'quantity' => 200,
                'weight' => 0.15,
                'category' => 'Men\'s Clothing',
                'brand' => 'FashionForward',
                'is_featured' => false,
            ],
            [
                'name' => 'Yoga Mat Pro',
                'description' => 'Professional-grade yoga mat with superior grip and cushioning',
                'short_description' => 'Non-slip yoga mat for all skill levels',
                'price' => 79.99,
                'compare_price' => 99.99,
                'cost_price' => 40.00,
                'sku' => 'YGM-PRO-PUR',
                'barcode' => '234567890123',
                'stock_quantity' => 75,
                'quantity' => 75,
                'weight' => 1.2,
                'category' => 'Fitness',
                'brand' => 'SportMax',
                'is_featured' => true,
            ],
            [
                'name' => 'Coffee Maker Deluxe',
                'description' => 'Programmable coffee maker with built-in grinder and thermal carafe',
                'short_description' => 'Premium coffee maker for perfect brew',
                'price' => 199.99,
                'compare_price' => 249.99,
                'cost_price' => 120.00,
                'sku' => 'CFM-DLX-BLK',
                'barcode' => '345678901234',
                'stock_quantity' => 40,
                'quantity' => 40,
                'weight' => 4.5,
                'category' => 'Kitchen',
                'brand' => 'HomeEssentials',
                'is_featured' => false,
            ],
            [
                'name' => 'Ergonomic Office Chair',
                'description' => 'Comfortable office chair with lumbar support and adjustable height',
                'short_description' => 'Professional office chair for comfort',
                'price' => 299.99,
                'compare_price' => 399.99,
                'cost_price' => 180.00,
                'sku' => 'OFC-ERG-GRY',
                'barcode' => '456789012345',
                'stock_quantity' => 30,
                'quantity' => 30,
                'weight' => 18.5,
                'category' => 'Furniture',
                'brand' => 'ComfortZone',
                'is_featured' => true,
            ],
            [
                'name' => 'Wireless Charging Pad',
                'description' => 'Fast wireless charging pad compatible with all Qi-enabled devices',
                'short_description' => 'Convenient wireless charging solution',
                'price' => 39.99,
                'compare_price' => 49.99,
                'cost_price' => 20.00,
                'sku' => 'WCP-FAST-BLK',
                'barcode' => '567890123456',
                'stock_quantity' => 150,
                'quantity' => 150,
                'weight' => 0.3,
                'category' => 'Electronics',
                'brand' => 'TechNova',
                'is_featured' => false,
            ],
        ];

        foreach ($products as $productData) {
            $category = $categories->first(function ($cat) use ($productData) {
                return $cat->name === $productData['category'];
            });

            $brand = $brands->first(function ($b) use ($productData) {
                return $b->name === $productData['brand'];
            });

            if ($category && $brand) {
                $product = Product::create([
                    'name' => [
                        'en' => $productData['name'],
                        'fr' => $productData['name'] . ' (FR)',
                        'ar' => $productData['name'] . ' (AR)'
                    ],
                    'description' => [
                        'en' => $productData['description'],
                        'fr' => $productData['description'] . ' (français)',
                        'ar' => $productData['description'] . ' (عربي)'
                    ],
                    'short_description' => [
                        'en' => $productData['short_description'],
                        'fr' => $productData['short_description'] . ' (français)',
                        'ar' => $productData['short_description'] . ' (عربي)'
                    ],
                    'slug' => \Illuminate\Support\Str::slug($productData['name']),
                    'price' => $productData['price'],
                    'compare_price' => $productData['compare_price'],
                    'cost_price' => $productData['cost_price'],
                    'sku' => $productData['sku'],
                    'barcode' => $productData['barcode'],
                    'stock_quantity' => $productData['stock_quantity'],
                    'quantity' => $productData['quantity'],
                    'track_quantity' => true,
                    'allow_backorder' => false,
                    'weight' => $productData['weight'],
                    'brand_id' => $brand->id,
                    'is_active' => true,
                    'is_featured' => $productData['is_featured'],
                    'is_digital' => false,
                    'views_count' => rand(10, 1000),
                    'sales_count' => rand(5, 50),
                ]);

                // Attach categories
                $product->categories()->attach([$category->id]);

                // Also attach parent category if exists
                if ($category->parent_id) {
                    $product->categories()->attach([$category->parent_id]);
                }
            }
        }

        // Create additional random products
        Product::factory()->count(20)->create([
            'brand_id' => fn() => $brands->random()->id,
        ])->each(function ($product) use ($categories) {
            // Attach random categories
            $randomCategories = $categories->random(rand(1, 3));
            $product->categories()->attach($randomCategories->pluck('id'));
        });
    }
}