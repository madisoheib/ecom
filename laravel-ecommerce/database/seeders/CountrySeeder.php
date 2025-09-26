<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get references to currencies and languages

        $currencies = [
            'CAD' => Currency::where('code', 'CAD')->first()->id,
            'DZD' => Currency::where('code', 'DZD')->first()->id,
            'EUR' => Currency::where('code', 'EUR')->first()->id,
            'AED' => Currency::where('code', 'AED')->first()->id,
        ];

        $languages = [
            'en' => Language::where('code', 'en')->first()->id,
            'fr' => Language::where('code', 'fr')->first()->id,
            'ar' => Language::where('code', 'ar')->first()->id,
        ];

        $countries = [
            [
                'code' => 'CA',
                'name' => [
                    'en' => 'Canada',
                    'fr' => 'Canada',
                    'ar' => 'كندا'
                ],
                'currency_id' => $currencies['CAD'],
                'default_language_id' => $languages['en'],
                'is_active' => true,
                'languages' => ['en', 'fr']
            ],
            [
                'code' => 'DZ',
                'name' => [
                    'en' => 'Algeria',
                    'fr' => 'Algérie',
                    'ar' => 'الجزائر'
                ],
                'currency_id' => $currencies['DZD'],
                'default_language_id' => $languages['ar'],
                'is_active' => true,
                'languages' => ['ar', 'fr']
            ],
            [
                'code' => 'FR',
                'name' => [
                    'en' => 'France',
                    'fr' => 'France',
                    'ar' => 'فرنسا'
                ],
                'currency_id' => $currencies['EUR'],
                'default_language_id' => $languages['fr'],
                'is_active' => true,
                'languages' => ['fr', 'en']
            ],
            [
                'code' => 'AE',
                'name' => [
                    'en' => 'United Arab Emirates',
                    'fr' => 'Émirats arabes unis',
                    'ar' => 'دولة الإمارات العربية المتحدة'
                ],
                'currency_id' => $currencies['AED'],
                'default_language_id' => $languages['ar'],
                'is_active' => true,
                'languages' => ['ar', 'en']
            ]
        ];

        foreach ($countries as $countryData) {
            $countryLanguages = $countryData['languages'];
            unset($countryData['languages']);
            
            $country = Country::create($countryData);
            
            // Attach languages to country
            foreach ($countryLanguages as $langCode) {
                $country->languages()->attach($languages[$langCode]);
            }
        }
    }
}
