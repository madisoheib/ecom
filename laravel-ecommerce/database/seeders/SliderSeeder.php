<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'title' => [
                    'en' => 'Luxury Perfumes Collection',
                    'fr' => 'Collection de Parfums de Luxe',
                    'ar' => 'مجموعة العطور الفاخرة'
                ],
                'subtitle' => [
                    'en' => 'Discover Exquisite Fragrances',
                    'fr' => 'Découvrez des fragrances exquises',
                    'ar' => 'اكتشف العطور الرائعة'
                ],
                'description' => [
                    'en' => 'Immerse yourself in our curated collection of premium perfumes from renowned brands',
                    'fr' => 'Plongez-vous dans notre collection de parfums premium de marques renommées',
                    'ar' => 'انغمس في مجموعتنا المختارة من العطور الفاخرة من العلامات التجارية المشهورة'
                ],
                'button_text' => 'Shop Perfumes',
                'button_url' => '/produits',
                'image_path' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=1920&h=800&fit=crop',
                'background_color' => '#000000',
                'text_color' => '#ffffff',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => [
                    'en' => 'Exclusive Fragrances - 30% Off',
                    'fr' => 'Fragrances Exclusives - 30% de Réduction',
                    'ar' => 'عطور حصرية - خصم 30%'
                ],
                'subtitle' => [
                    'en' => 'Limited Time Offer',
                    'fr' => 'Offre à durée limitée',
                    'ar' => 'عرض لفترة محدودة'
                ],
                'description' => [
                    'en' => 'Indulge in our signature scents at unbeatable prices. Your perfect fragrance awaits',
                    'fr' => 'Offrez-vous nos parfums signature à des prix imbattables. Votre fragrance parfaite vous attend',
                    'ar' => 'استمتع بعطورنا المميزة بأسعار لا تُقاوم. عطرك المثالي في انتظارك'
                ],
                'button_text' => 'Shop Sale',
                'button_url' => '/produits?sale=1',
                'image_path' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=1920&h=800&fit=crop',
                'background_color' => '#FFC845',
                'text_color' => '#000000',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => [
                    'en' => 'New Perfume Arrivals',
                    'fr' => 'Nouveaux Parfums Arrivés',
                    'ar' => 'عطور جديدة وصلت'
                ],
                'subtitle' => [
                    'en' => 'Fresh Scents Just Launched',
                    'fr' => 'Nouvelles fragrances viennent d\'être lancées',
                    'ar' => 'عطور جديدة تم إطلاقها للتو'
                ],
                'description' => [
                    'en' => 'Be among the first to experience our latest perfume collection featuring modern and classic scents',
                    'fr' => 'Soyez parmi les premiers à découvrir notre dernière collection de parfums avec des senteurs modernes et classiques',
                    'ar' => 'كن من بين الأوائل لتجربة مجموعة عطورنا الأحدث التي تضم عطور حديثة وكلاسيكية'
                ],
                'button_text' => 'Discover New',
                'button_url' => '/produits?sort=newest',
                'image_path' => 'https://images.unsplash.com/photo-1594736797933-d0301ba2fe65?w=1920&h=800&fit=crop',
                'background_color' => '#000000',
                'text_color' => '#ffffff',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $sliderData) {
            Slider::create($sliderData);
        }

        $this->command->info('Sample sliders created successfully!');
    }
}
