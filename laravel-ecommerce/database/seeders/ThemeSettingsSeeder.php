<?php

namespace Database\Seeders;

use App\Models\ThemeSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ThemeSettings::create([
            'primary_color' => '#3B82F6',
            'secondary_color' => '#64748B',
            'accent_color' => '#10B981',
            'background_color' => '#FFFFFF',
            'text_color' => '#1F2937',
            'is_active' => true,
        ]);

        ThemeSettings::create([
            'primary_color' => '#DC2626',
            'secondary_color' => '#78716C',
            'accent_color' => '#F59E0B',
            'background_color' => '#FAFAFA',
            'text_color' => '#0F172A',
            'is_active' => false,
        ]);

        ThemeSettings::create([
            'primary_color' => '#7C3AED',
            'secondary_color' => '#6B7280',
            'accent_color' => '#EC4899',
            'background_color' => '#F9FAFB',
            'text_color' => '#111827',
            'is_active' => false,
        ]);
    }
}
