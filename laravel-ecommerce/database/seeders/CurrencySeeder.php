<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'code' => 'CAD',
                'name' => [
                    'en' => 'Canadian Dollar',
                    'fr' => 'Dollar canadien',
                    'ar' => 'دولار كندي'
                ],
                'symbol' => 'CA$',
                'is_active' => true
            ],
            [
                'code' => 'DZD',
                'name' => [
                    'en' => 'Algerian Dinar',
                    'fr' => 'Dinar algérien',
                    'ar' => 'دينار جزائري'
                ],
                'symbol' => 'د.ج',
                'is_active' => true
            ],
            [
                'code' => 'EUR',
                'name' => [
                    'en' => 'Euro',
                    'fr' => 'Euro',
                    'ar' => 'يورو'
                ],
                'symbol' => '€',
                'is_active' => true
            ],
            [
                'code' => 'AED',
                'name' => [
                    'en' => 'UAE Dirham',
                    'fr' => 'Dirham des Émirats arabes unis',
                    'ar' => 'درهم إماراتي'
                ],
                'symbol' => 'د.إ',
                'is_active' => true
            ],
            [
                'code' => 'USD',
                'name' => [
                    'en' => 'US Dollar',
                    'fr' => 'Dollar américain',
                    'ar' => 'دولار أمريكي'
                ],
                'symbol' => '$',
                'is_active' => true
            ],
            [
                'code' => 'KWD',
                'name' => [
                    'en' => 'Kuwaiti Dinar',
                    'fr' => 'Dinar koweïtien',
                    'ar' => 'دينار كويتي'
                ],
                'symbol' => 'د.ك',
                'is_active' => true
            ],
            [
                'code' => 'OMR',
                'name' => [
                    'en' => 'Omani Rial',
                    'fr' => 'Rial omanais',
                    'ar' => 'ريال عماني'
                ],
                'symbol' => 'ر.ع.',
                'is_active' => true
            ]
        ];

        foreach ($currencies as $currencyData) {
            Currency::create($currencyData);
        }
    }
}
