<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get country IDs
        $algeria = Country::where('code', 'DZ')->first();
        $canada = Country::where('code', 'CA')->first();
        $france = Country::where('code', 'FR')->first();
        $uae = Country::where('code', 'AE')->first();

        $cities = [
            // Villes d'Algérie
            [
                'name' => [
                    'en' => 'Algiers',
                    'fr' => 'Alger',
                    'ar' => 'الجزائر'
                ],
                'country_id' => $algeria?->id,
                'is_active' => true
            ],
            [
                'name' => [
                    'en' => 'Oran',
                    'fr' => 'Oran',
                    'ar' => 'وهران'
                ],
                'country_id' => $algeria?->id,
                'is_active' => true
            ],
            [
                'name' => [
                    'en' => 'Constantine',
                    'fr' => 'Constantine',
                    'ar' => 'قسنطينة'
                ],
                'country_id' => $algeria?->id,
                'is_active' => true
            ],
            
            // Villes du Canada
            [
                'name' => [
                    'en' => 'Toronto',
                    'fr' => 'Toronto',
                    'ar' => 'تورونتو'
                ],
                'country_id' => $canada?->id,
                'is_active' => true
            ],
            [
                'name' => [
                    'en' => 'Montreal',
                    'fr' => 'Montréal',
                    'ar' => 'مونتريال'
                ],
                'country_id' => $canada?->id,
                'is_active' => true
            ],
            [
                'name' => [
                    'en' => 'Vancouver',
                    'fr' => 'Vancouver',
                    'ar' => 'فانكوفر'
                ],
                'country_id' => $canada?->id,
                'is_active' => true
            ],
            
            // Villes de France
            [
                'name' => [
                    'en' => 'Paris',
                    'fr' => 'Paris',
                    'ar' => 'باريس'
                ],
                'country_id' => $france?->id,
                'is_active' => true
            ],
            [
                'name' => [
                    'en' => 'Lyon',
                    'fr' => 'Lyon',
                    'ar' => 'ليون'
                ],
                'country_id' => $france?->id,
                'is_active' => true
            ],
            [
                'name' => [
                    'en' => 'Marseille',
                    'fr' => 'Marseille',
                    'ar' => 'مرسيليا'
                ],
                'country_id' => $france?->id,
                'is_active' => true
            ],
            
            // Villes des Émirats
            [
                'name' => [
                    'en' => 'Dubai',
                    'fr' => 'Dubaï',
                    'ar' => 'دبي'
                ],
                'country_id' => $uae?->id,
                'is_active' => true
            ],
            [
                'name' => [
                    'en' => 'Abu Dhabi',
                    'fr' => 'Abou Dhabi',
                    'ar' => 'أبو ظبي'
                ],
                'country_id' => $uae?->id,
                'is_active' => true
            ],
            [
                'name' => [
                    'en' => 'Sharjah',
                    'fr' => 'Charjah',
                    'ar' => 'الشارقة'
                ],
                'country_id' => $uae?->id,
                'is_active' => true
            ]
        ];

        foreach ($cities as $cityData) {
            if ($cityData['country_id']) {
                City::create($cityData);
            }
        }
    }
}
