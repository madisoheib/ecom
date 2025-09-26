<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EcommerceSeeder::class,
            CurrencySeeder::class,
            LanguageSeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            ThemeSettingsSeeder::class,
            DefaultThemesSeeder::class,
            ProductSeeder::class,
            SliderSeeder::class,
        ]);
    }
}
