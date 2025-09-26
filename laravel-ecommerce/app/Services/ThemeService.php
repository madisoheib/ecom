<?php

namespace App\Services;

use App\Models\ThemeSettings;
use Illuminate\Support\Facades\Cache;

class ThemeService
{
    public function getActiveTheme(): ?ThemeSettings
    {
        try {
            return Cache::remember('active_theme', 3600, function () {
                return ThemeSettings::getActiveTheme();
            });
        } catch (\Exception $e) {
            // Return null if table doesn't exist yet or other database errors
            return null;
        }
    }

    public function getCssVariables(): string
    {
        $theme = $this->getActiveTheme();
        
        if (!$theme) {
            return $this->getDefaultCssVariables();
        }

        return ":root {
            --color-primary: {$theme->primary_color};
            --color-secondary: {$theme->secondary_color};
            --color-accent: {$theme->accent_color};
            --color-background: {$theme->background_color};
            --color-text: {$theme->text_color};
            
            --color-primary-rgb: " . $this->hexToRgb($theme->primary_color) . ";
            --color-secondary-rgb: " . $this->hexToRgb($theme->secondary_color) . ";
            --color-accent-rgb: " . $this->hexToRgb($theme->accent_color) . ";
            --color-background-rgb: " . $this->hexToRgb($theme->background_color) . ";
            --color-text-rgb: " . $this->hexToRgb($theme->text_color) . ";
        }";
    }

    private function getDefaultCssVariables(): string
    {
        return ":root {
            --color-primary: #3B82F6;
            --color-secondary: #64748B;
            --color-accent: #10B981;
            --color-background: #FFFFFF;
            --color-text: #1F2937;
            
            --color-primary-rgb: 59, 130, 246;
            --color-secondary-rgb: 100, 116, 139;
            --color-accent-rgb: 16, 185, 129;
            --color-background-rgb: 255, 255, 255;
            --color-text-rgb: 31, 41, 55;
        }";
    }

    private function hexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return "$r, $g, $b";
    }

    public function clearCache(): void
    {
        Cache::forget('active_theme');
    }
}