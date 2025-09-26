<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Chanel',
            'Dior',
            'Tom Ford',
            'Gucci',
            'Versace',
            'Armani',
            'Dolce & Gabbana',
            'Burberry',
            'Calvin Klein',
            'Hugo Boss',
            'Givenchy',
            'Paco Rabanne',
            'Yves Saint Laurent',
            'Hermès',
            'Creed'
        ];

        // Create brands
        foreach ($brands as $brandName) {
            Brand::firstOrCreate(['name' => $brandName], [
                'description' => "Luxury fragrance brand " . $brandName,
                'is_active' => true,
                'sort_order' => 0
            ]);
        }

        $categories = [
            'Parfums Homme' => 'Fragrances masculines sophistiquées',
            'Parfums Femme' => 'Fragrances féminines élégantes',
            'Parfums Unisexe' => 'Fragrances pour tous',
            'Parfums de Niche' => 'Créations exclusives et rares',
            'Eaux de Toilette' => 'Fragrances légères pour le quotidien',
            'Eaux de Parfum' => 'Concentrations intenses et durables'
        ];

        // Create categories
        foreach ($categories as $categoryName => $description) {
            Category::firstOrCreate(['name' => $categoryName], [
                'description' => $description,
                'is_active' => true,
                'sort_order' => 0
            ]);
        }

        $perfumeData = [
            // Chanel
            [
                'name' => 'Chanel N°5',
                'brand' => 'Chanel',
                'category' => 'Parfums Femme',
                'price' => 120.00,
                'compare_price' => 150.00,
                'description' => 'Le parfum iconique de Chanel, symbole d\'élégance et de sophistication. Un bouquet floral aldéhydé intemporel avec des notes de ylang-ylang, rose et jasmin.',
                'short_description' => 'Parfum iconique aux notes florales aldéhydées',
                'sku' => 'CHN-005-100',
                'stock_quantity' => 50,
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1541643600914-78b084683601?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=500&h=500&fit=crop'
                ]
            ],
            [
                'name' => 'Chanel Coco Mademoiselle',
                'brand' => 'Chanel',
                'category' => 'Parfums Femme',
                'price' => 110.00,
                'compare_price' => 135.00,
                'description' => 'Une fragrance moderne et audacieuse qui révèle la personnalité libre et passionnée de la femme Chanel. Notes d\'orange, jasmin et patchouli.',
                'short_description' => 'Fragrance moderne aux notes orientales fraîches',
                'sku' => 'CHN-COCO-100',
                'stock_quantity' => 35
            ],

            // Dior
            [
                'name' => 'Dior Sauvage',
                'brand' => 'Dior',
                'category' => 'Parfums Homme',
                'price' => 95.00,
                'compare_price' => 115.00,
                'description' => 'Une composition fraîche et moderne inspirée par les grands espaces. Bergamote de Calabre, poivre rose et ambroxan pour une masculinité sauvage.',
                'short_description' => 'Fragrance masculine fraîche et moderne',
                'sku' => 'DIO-SAU-100',
                'stock_quantity' => 40,
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1563170351-be82bc888aa4?w=500&h=500&fit=crop'
                ]
            ],
            [
                'name' => 'Dior J\'adore',
                'brand' => 'Dior',
                'category' => 'Parfums Femme',
                'price' => 105.00,
                'compare_price' => 130.00,
                'description' => 'Un parfum floral lumineux célébrant la femme et sa beauté. Ylang-ylang, rose de Damas et jasmin sambac dans une harmonie précieuse.',
                'short_description' => 'Bouquet floral lumineux et précieux',
                'sku' => 'DIO-JAD-100',
                'stock_quantity' => 30
            ],

            // Tom Ford
            [
                'name' => 'Tom Ford Black Orchid',
                'brand' => 'Tom Ford',
                'category' => 'Parfums Unisexe',
                'price' => 180.00,
                'compare_price' => 220.00,
                'description' => 'Une fragrance luxuriante et sensuelle mêlant orchidée noire, truffe, ylang-ylang et cassis. L\'essence même de la sophistication moderne.',
                'short_description' => 'Fragrance luxuriante aux notes d\'orchidée noire',
                'sku' => 'TF-BO-100',
                'stock_quantity' => 25,
                'is_featured' => true
            ],
            [
                'name' => 'Tom Ford Oud Wood',
                'brand' => 'Tom Ford',
                'category' => 'Parfums de Niche',
                'price' => 250.00,
                'compare_price' => 300.00,
                'description' => 'Un parfum précieux centré sur l\'oud, essence rare et mystérieuse. Palissandre, cardamome et santal pour une expérience olfactive unique.',
                'short_description' => 'Essence rare d\'oud et de bois précieux',
                'sku' => 'TF-OW-100',
                'stock_quantity' => 15
            ],

            // Gucci
            [
                'name' => 'Gucci Guilty',
                'brand' => 'Gucci',
                'category' => 'Parfums Femme',
                'price' => 85.00,
                'compare_price' => 105.00,
                'description' => 'Une fragrance rebelle et séduisante aux notes de mandarine, géranium et patchouli. L\'expression de la liberté et de l\'audace.',
                'short_description' => 'Fragrance rebelle aux notes florales épicées',
                'sku' => 'GUC-GUI-100',
                'stock_quantity' => 45
            ],

            // Versace
            [
                'name' => 'Versace Eros',
                'brand' => 'Versace',
                'category' => 'Parfums Homme',
                'price' => 75.00,
                'compare_price' => 95.00,
                'description' => 'Un parfum masculin passionné inspiré de la mythologie grecque. Menthe, pomme verte et fèves tonka dans une composition virile.',
                'short_description' => 'Fragrance masculine passionnée et virile',
                'sku' => 'VER-ERO-100',
                'stock_quantity' => 55
            ],

            // Calvin Klein
            [
                'name' => 'Calvin Klein Eternity',
                'brand' => 'Calvin Klein',
                'category' => 'Parfums Femme',
                'price' => 60.00,
                'compare_price' => 75.00,
                'description' => 'Un classique intemporel aux notes florales blanches. Freesia, muguet et narcisse pour un parfum pur et romantique.',
                'short_description' => 'Classique floral blanc et romantique',
                'sku' => 'CK-ETE-100',
                'stock_quantity' => 60
            ],

            // Hugo Boss
            [
                'name' => 'Hugo Boss The Scent',
                'brand' => 'Hugo Boss',
                'category' => 'Parfums Homme',
                'price' => 70.00,
                'compare_price' => 85.00,
                'description' => 'Une fragrance captivante aux notes de gingembre, lavande et cuir. L\'essence de la séduction masculine moderne.',
                'short_description' => 'Fragrance captivante aux notes de cuir',
                'sku' => 'HB-SCE-100',
                'stock_quantity' => 40
            ],

            // Paco Rabanne
            [
                'name' => 'Paco Rabanne 1 Million',
                'brand' => 'Paco Rabanne',
                'category' => 'Parfums Homme',
                'price' => 80.00,
                'compare_price' => 100.00,
                'description' => 'Le parfum de la réussite et de l\'ambition. Pamplemousse, menthe et cuir blanc dans un flacon en forme de lingot d\'or.',
                'short_description' => 'Parfum de la réussite aux notes dorées',
                'sku' => 'PR-1M-100',
                'stock_quantity' => 35,
                'is_featured' => true
            ],

            // Yves Saint Laurent
            [
                'name' => 'YSL Black Opium',
                'brand' => 'Yves Saint Laurent',
                'category' => 'Parfums Femme',
                'price' => 90.00,
                'compare_price' => 110.00,
                'description' => 'Un parfum addictif aux notes de café noir, vanille et fleur d\'oranger. L\'incarnation de la féminité rock et glamour.',
                'short_description' => 'Parfum addictif aux notes de café et vanille',
                'sku' => 'YSL-BO-100',
                'stock_quantity' => 25
            ],

            // Armani
            [
                'name' => 'Armani Acqua di Gio',
                'brand' => 'Armani',
                'category' => 'Parfums Homme',
                'price' => 65.00,
                'compare_price' => 80.00,
                'description' => 'Une fragrance aquatique inspirée par la Méditerranée. Bergamote, néroli et notes marines pour une fraîcheur absolue.',
                'short_description' => 'Fragrance aquatique méditerranéenne',
                'sku' => 'ARM-ADG-100',
                'stock_quantity' => 50
            ],

            // Givenchy
            [
                'name' => 'Givenchy L\'Interdit',
                'brand' => 'Givenchy',
                'category' => 'Parfums Femme',
                'price' => 95.00,
                'compare_price' => 115.00,
                'description' => 'Un parfum qui défie les conventions avec ses notes de fleur d\'oranger, jasmin et tubéreuse. L\'audace à l\'état pur.',
                'short_description' => 'Parfum audacieux aux fleurs blanches',
                'sku' => 'GIV-INT-100',
                'stock_quantity' => 30
            ],

            // Creed
            [
                'name' => 'Creed Aventus',
                'brand' => 'Creed',
                'category' => 'Parfums de Niche',
                'price' => 320.00,
                'compare_price' => 380.00,
                'description' => 'Un parfum de légende inspiré par Napoléon. Ananas, cassis, bouleau et mousses de chêne dans une composition royale.',
                'short_description' => 'Parfum de légende aux notes fruitées boisées',
                'sku' => 'CRE-AVE-100',
                'stock_quantity' => 12,
                'is_featured' => true
            ]
        ];

        foreach ($perfumeData as $data) {
            $brand = Brand::where('name', $data['brand'])->first();
            $category = Category::where('name', $data['category'])->first();

            // Skip if brand or category not found
            if (!$brand || !$category) {
                $this->command->error("Skipping {$data['name']}: Brand or category not found");
                continue;
            }

            $product = Product::create([
                'name' => $data['name'],
                'brand_id' => $brand->id,
                'price' => $data['price'],
                'compare_price' => $data['compare_price'] ?? null,
                'description' => $data['description'],
                'short_description' => $data['short_description'],
                'sku' => $data['sku'],
                'stock_quantity' => $data['stock_quantity'],
                'is_active' => true,
                'is_featured' => $data['is_featured'] ?? false,
                'weight' => 0.5,
                'views_count' => rand(10, 500),
                'sales_count' => rand(1, 50),
                // SEO fields
                'meta_title' => [
                    'fr' => $data['name'] . ' - Parfum de Luxe',
                    'en' => $data['name'] . ' - Luxury Perfume'
                ],
                'meta_description' => [
                    'fr' => substr($data['short_description'], 0, 155),
                    'en' => substr($data['short_description'], 0, 155)
                ],
                'focus_keyword' => strtolower(str_replace(' ', '-', $data['name'])),
                'index_follow' => true
            ]);

            // Attach to category
            $product->categories()->attach($category->id);

            // Add product images if provided
            if (isset($data['images'])) {
                foreach ($data['images'] as $index => $imageUrl) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imageUrl,
                        'alt_text' => $data['name'] . ' - Image ' . ($index + 1),
                        'sort_order' => $index,
                        'is_primary' => $index === 0
                    ]);
                }
            } else {
                // Default placeholder image
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'https://images.unsplash.com/photo-1588405748880-12d1d2a59d75?w=500&h=500&fit=crop',
                    'alt_text' => $data['name'] . ' - Image principale',
                    'sort_order' => 0,
                    'is_primary' => true
                ]);
            }
        }

        $this->command->info('Perfume products seeded successfully!');
    }
}
