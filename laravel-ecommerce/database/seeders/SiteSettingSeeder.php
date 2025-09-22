<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'site_title' => 'Modern E-Commerce Store',
            'site_description' => 'Your one-stop shop for quality products',
            'meta_description' => 'Discover amazing products at unbeatable prices with fast shipping',
            'meta_keywords' => 'ecommerce, online shop, quality products, fast shipping',
            'default_currency' => 'USD',
            'primary_color' => '#000000',
            'secondary_color' => '#FFC845',
            'accent_color' => '#FFD700',
            'company_email' => 'info@example.com',
            'company_phone' => '+1 234 567 8900',
            'company_address' => '123 Commerce St, Business City, BC 12345',
            'facebook_url' => 'https://facebook.com/yourstore',
            'twitter_url' => 'https://twitter.com/yourstore',
            'instagram_url' => 'https://instagram.com/yourstore',
            'linkedin_url' => 'https://linkedin.com/company/yourstore',
            'youtube_url' => 'https://youtube.com/@yourstore',
        ];

        // Create or update the single site settings row
        $siteSetting = SiteSetting::first();

        if ($siteSetting) {
            $siteSetting->update($settings);
            $this->command->info('Site settings have been updated successfully!');
        } else {
            SiteSetting::create($settings);
            $this->command->info('Site settings have been created successfully!');
        }
    }
}