<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create images directory if it doesn't exist
        $imagesPath = public_path('images/sliders');
        if (!File::exists($imagesPath)) {
            File::makeDirectory($imagesPath, 0755, true);
        }

        // Clear existing sliders
        Slider::truncate();
        $this->command->info('Cleared existing sliders');

        $sliders = [
            [
                'title' => [
                    'en' => 'Luxury Perfumes Collection',
                    'fr' => 'Collection de Parfums de Luxe',
                    'ar' => 'مجموعة العطور الفاخرة'
                ],
                'subtitle' => [
                    'en' => 'Discover Exquisite Fragrances',
                    'fr' => 'Découvrez des Fragrances Exquises',
                    'ar' => 'اكتشف العطور الرائعة'
                ],
                'description' => [
                    'en' => 'Immerse yourself in our curated collection of premium perfumes from renowned brands',
                    'fr' => 'Plongez-vous dans notre collection de parfums premium de marques renommées',
                    'ar' => 'انغمس في مجموعتنا المختارة من العطور الفاخرة من العلامات التجارية المشهورة'
                ],
                'button_text' => 'Shop Perfumes',
                'button_url' => '/produits',
                'image_path' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=1920&h=500&fit=crop&crop=center',
                'background_color' => '#1a1a1a',
                'text_color' => '#ffffff',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => [
                    'en' => 'Exclusive Fragrances Sale',
                    'fr' => 'Vente de Fragrances Exclusives',
                    'ar' => 'تخفيضات العطور الحصرية'
                ],
                'subtitle' => [
                    'en' => 'Up to 30% Off',
                    'fr' => 'Jusqu\'à 30% de Réduction',
                    'ar' => 'خصم يصل إلى 30%'
                ],
                'description' => [
                    'en' => 'Indulge in our signature scents at unbeatable prices. Your perfect fragrance awaits',
                    'fr' => 'Offrez-vous nos parfums signature à des prix imbattables. Votre fragrance parfaite vous attend',
                    'ar' => 'استمتع بعطورنا المميزة بأسعار لا تُقاوم. عطرك المثالي في انتظارك'
                ],
                'button_text' => 'Shop Sale',
                'button_url' => '/produits?sale=1',
                'image_path' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=1920&h=500&fit=crop&crop=center',
                'background_color' => '#2d3748',
                'text_color' => '#ffffff',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => [
                    'en' => 'Premium Jewelry Collection',
                    'fr' => 'Collection de Bijoux Premium',
                    'ar' => 'مجموعة المجوهرات المميزة'
                ],
                'subtitle' => [
                    'en' => 'Timeless Elegance',
                    'fr' => 'Élégance Intemporelle',
                    'ar' => 'أناقة خالدة'
                ],
                'description' => [
                    'en' => 'Discover handcrafted jewelry pieces that complement your unique style and personality',
                    'fr' => 'Découvrez des pièces de bijoux artisanales qui complètent votre style et personnalité uniques',
                    'ar' => 'اكتشف قطع المجوهرات المصنوعة يدوياً التي تكمل أسلوبك وشخصيتك الفريدة'
                ],
                'button_text' => 'View Jewelry',
                'button_url' => '/produits?category=jewelry',
                'image_path' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=1920&h=500&fit=crop&crop=center',
                'background_color' => '#4a5568',
                'text_color' => '#ffffff',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => [
                    'en' => 'Beauty & Cosmetics',
                    'fr' => 'Beauté et Cosmétiques',
                    'ar' => 'الجمال ومستحضرات التجميل'
                ],
                'subtitle' => [
                    'en' => 'Professional Quality',
                    'fr' => 'Qualité Professionnelle',
                    'ar' => 'جودة مهنية'
                ],
                'description' => [
                    'en' => 'Transform your beauty routine with our premium cosmetics and skincare products',
                    'fr' => 'Transformez votre routine beauté avec nos cosmétiques et produits de soin premium',
                    'ar' => 'حول روتين جمالك مع مستحضرات التجميل ومنتجات العناية المميزة'
                ],
                'button_text' => 'Shop Beauty',
                'button_url' => '/produits?category=beauty',
                'image_path' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=1920&h=500&fit=crop&crop=center',
                'background_color' => '#e53e3e',
                'text_color' => '#ffffff',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => [
                    'en' => 'New Arrivals',
                    'fr' => 'Nouvelles Arrivées',
                    'ar' => 'وصل حديثاً'
                ],
                'subtitle' => [
                    'en' => 'Fresh & Trending',
                    'fr' => 'Frais et Tendance',
                    'ar' => 'جديد ومتداول'
                ],
                'description' => [
                    'en' => 'Be among the first to experience our latest collection featuring modern and classic styles',
                    'fr' => 'Soyez parmi les premiers à découvrir notre dernière collection avec des styles modernes et classiques',
                    'ar' => 'كن من بين الأوائل لتجربة مجموعتنا الأحدث التي تضم أساليب حديثة وكلاسيكية'
                ],
                'button_text' => 'Discover New',
                'button_url' => '/produits?sort=newest',
                'image_path' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920&h=500&fit=crop&crop=center',
                'background_color' => '#38a169',
                'text_color' => '#ffffff',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $sliderData) {
            $slider = Slider::create($sliderData);
            $this->command->info("Created slider: {$slider->getTranslation('title', 'en')}");
        }

        $this->command->info('Sample sliders created successfully!');
        $this->command->info('You can now manage these sliders from the admin dashboard.');
        $this->command->info('Note: Images are currently using Unsplash URLs - you can upload custom images from the dashboard.');
    }
}
