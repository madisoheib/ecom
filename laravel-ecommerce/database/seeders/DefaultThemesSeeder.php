<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ThemeSettings;

class DefaultThemesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = [
            [
                'name' => 'Default Blue',
                'primary_color' => '#3b82f6',
                'secondary_color' => '#64748b',
                'accent_color' => '#10b981',
                'background_color' => '#ffffff',
                'text_color' => '#1f2937',
                'is_active' => true
            ],
            [
                'name' => 'Dark Mode',
                'primary_color' => '#6366f1',
                'secondary_color' => '#8b5cf6',
                'accent_color' => '#06b6d4',
                'background_color' => '#111827',
                'text_color' => '#f9fafb',
                'is_active' => false
            ],
            [
                'name' => 'Green Nature',
                'primary_color' => '#059669',
                'secondary_color' => '#065f46',
                'accent_color' => '#fbbf24',
                'background_color' => '#f0fdf4',
                'text_color' => '#064e3b',
                'is_active' => false
            ],
            [
                'name' => 'Purple Luxury',
                'primary_color' => '#7c3aed',
                'secondary_color' => '#a855f7',
                'accent_color' => '#f59e0b',
                'background_color' => '#faf5ff',
                'text_color' => '#581c87',
                'is_active' => false
            ],
            [
                'name' => 'Orange Energy',
                'primary_color' => '#ea580c',
                'secondary_color' => '#dc2626',
                'accent_color' => '#0891b2',
                'background_color' => '#fff7ed',
                'text_color' => '#9a3412',
                'is_active' => false
            ]
        ];

        foreach ($themes as $theme) {
            ThemeSettings::updateOrCreate(
                ['primary_color' => $theme['primary_color']],
                $theme
            );
        }
    }
}
