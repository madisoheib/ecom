<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;

class TranslatableSlugSeeder extends Seeder
{
    public function run(): void
    {
        $this->populateProductSlugs();
        $this->populateCategorySlugs();
        $this->populateBrandSlugs();
    }

    private function populateProductSlugs(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            $slugTranslations = [];

            // Generate slugs for each language manually to avoid complex queries during seeding
            foreach (['en', 'fr', 'ar'] as $locale) {
                $name = $product->getTranslation('name', $locale);

                if ($locale === 'ar') {
                    // For Arabic, use transliteration or keep English slug
                    $slug = $this->transliterateArabic($name) ?: str($product->getTranslation('name', 'en'))->slug();
                } else {
                    $slug = str($name)->slug();
                }

                $slugTranslations[$locale] = $slug;
            }

            $product->update(['slug_translations' => $slugTranslations]);

            $this->command->info("Updated product: {$product->name} with slugs: " . json_encode($slugTranslations));
        }
    }

    private function transliterateArabic(string $text): string
    {
        $arabicToLatin = [
            'ا' => 'a', 'ب' => 'b', 'ت' => 't', 'ث' => 'th', 'ج' => 'j',
            'ح' => 'h', 'خ' => 'kh', 'د' => 'd', 'ذ' => 'dh', 'ر' => 'r',
            'ز' => 'z', 'س' => 's', 'ش' => 'sh', 'ص' => 's', 'ض' => 'd',
            'ط' => 't', 'ظ' => 'z', 'ع' => 'a', 'غ' => 'gh', 'ف' => 'f',
            'ق' => 'q', 'ك' => 'k', 'ل' => 'l', 'م' => 'm', 'ن' => 'n',
            'ه' => 'h', 'و' => 'w', 'ي' => 'y', 'ى' => 'a'
        ];

        $transliterated = strtr($text, $arabicToLatin);
        return str($transliterated)->slug();
    }

    private function populateCategorySlugs(): void
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            $slugTranslations = [];

            // Generate slugs for each language manually to avoid complex queries
            foreach (['en', 'fr', 'ar'] as $locale) {
                $name = $category->getTranslation('name', $locale);

                if ($locale === 'ar') {
                    // For Arabic, use transliteration or keep English slug
                    $slug = $this->transliterateArabic($name) ?: str($category->getTranslation('name', 'en'))->slug();
                } else {
                    $slug = str($name)->slug();
                }

                $slugTranslations[$locale] = $slug;
            }

            $category->update(['slug_translations' => $slugTranslations]);

            $this->command->info("Updated category: {$category->name} with slugs: " . json_encode($slugTranslations));
        }
    }

    private function populateBrandSlugs(): void
    {
        $brands = Brand::all();

        foreach ($brands as $brand) {
            // For brands, we'll keep the same slug for all languages since brand names are typically universal
            $slugTranslations = [
                'en' => $brand->slug,
                'fr' => $brand->slug,
                'ar' => $brand->slug
            ];

            $brand->update(['slug_translations' => $slugTranslations]);

            $this->command->info("Updated brand: {$brand->name} with slugs: " . json_encode($slugTranslations));
        }
    }
}