<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ThemeSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'text_color',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getActiveTheme(): ?self
    {
        return static::where('is_active', true)->first();
    }

    public function activate(): void
    {
        static::query()->update(['is_active' => false]);
        $this->update(['is_active' => true]);
        $this->clearThemeCache();
    }

    public function clearThemeCache(): void
    {
        Cache::forget('active_theme');
    }

    protected static function booted(): void
    {
        static::saved(function ($theme) {
            $theme->clearThemeCache();
        });

        static::deleted(function ($theme) {
            $theme->clearThemeCache();
        });
    }
}
