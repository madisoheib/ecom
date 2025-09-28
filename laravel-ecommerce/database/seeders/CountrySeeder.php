<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get references to currencies, languages, and regions

        $currencies = [
            'CAD' => Currency::where('code', 'CAD')->first()->id,
            'DZD' => Currency::where('code', 'DZD')->first()->id,
            'EUR' => Currency::where('code', 'EUR')->first()->id,
            'AED' => Currency::where('code', 'AED')->first()->id,
            'KWD' => Currency::where('code', 'KWD')->first()->id,
            'OMR' => Currency::where('code', 'OMR')->first()->id,
        ];

        $languages = [
            'en' => Language::where('code', 'en')->first()->id,
            'fr' => Language::where('code', 'fr')->first()->id,
            'ar' => Language::where('code', 'ar')->first()->id,
        ];

        $regions = [
            'NA' => DB::table('regions')->where('code', 'NA')->first()->id,
            'EU' => DB::table('regions')->where('code', 'EU')->first()->id,
            'ME' => DB::table('regions')->where('code', 'ME')->first()->id,
            'AF' => DB::table('regions')->where('code', 'AF')->first()->id,
        ];

        $countries = [
            [
                'code' => 'CA',
                'name' => [
                    'en' => 'Canada',
                    'fr' => 'Canada',
                    'ar' => 'كندا'
                ],
                'region_id' => $regions['NA'],
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
                'region_id' => $regions['AF'],
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
                'region_id' => $regions['EU'],
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
                'region_id' => $regions['ME'],
                'currency_id' => $currencies['AED'],
                'default_language_id' => $languages['ar'],
                'is_active' => true,
                'languages' => ['ar', 'en']
            ],
            [
                'code' => 'KW',
                'name' => [
                    'en' => 'Kuwait',
                    'fr' => 'Koweït',
                    'ar' => 'الكويت'
                ],
                'region_id' => $regions['ME'],
                'currency_id' => $currencies['KWD'],
                'default_language_id' => $languages['ar'],
                'is_active' => true,
                'languages' => ['ar', 'en']
            ],
            [
                'code' => 'OM',
                'name' => [
                    'en' => 'Oman',
                    'fr' => 'Oman',
                    'ar' => 'عُمان'
                ],
                'region_id' => $regions['ME'],
                'currency_id' => $currencies['OMR'],
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
