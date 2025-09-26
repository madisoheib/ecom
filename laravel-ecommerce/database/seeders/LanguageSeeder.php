<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'code' => 'en',
                'name' => [
                    'en' => 'English',
                    'fr' => 'Anglais',
                    'ar' => 'الإنجليزية'
                ],
                'native_name' => 'English',
                'is_active' => true
            ],
            [
                'code' => 'fr',
                'name' => [
                    'en' => 'French',
                    'fr' => 'Français',
                    'ar' => 'الفرنسية'
                ],
                'native_name' => 'Français',
                'is_active' => true
            ],
            [
                'code' => 'ar',
                'name' => [
                    'en' => 'Arabic',
                    'fr' => 'Arabe',
                    'ar' => 'العربية'
                ],
                'native_name' => 'العربية',
                'is_active' => true
            ]
        ];

        foreach ($languages as $languageData) {
            Language::create($languageData);
        }
    }
}
