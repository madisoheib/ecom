<?php

namespace App\Helpers;

use App\Models\SiteSetting;

class SettingsHelper
{
    public static function getSiteTitle(): string
    {
        return SiteSetting::getSetting('site_title', 'My Website');
    }

    public static function getSiteLogo(): ?string
    {
        return SiteSetting::getSetting('site_logo');
    }

    public static function getMetaDescription(): ?string
    {
        return SiteSetting::getSetting('meta_description');
    }

    public static function getMetaKeywords(): ?string
    {
        return SiteSetting::getSetting('meta_keywords');
    }

    public static function getDefaultCurrency(): string
    {
        return SiteSetting::getSetting('default_currency', 'USD');
    }

    public static function getPrimaryColor(): string
    {
        return SiteSetting::getSetting('primary_color', '#3b82f6');
    }

    public static function getSecondaryColor(): string
    {
        return SiteSetting::getSetting('secondary_color', '#64748b');
    }

    public static function getAccentColor(): string
    {
        return SiteSetting::getSetting('accent_color', '#10b981');
    }

    public static function getAllColors(): array
    {
        return [
            'primary' => static::getPrimaryColor(),
            'secondary' => static::getSecondaryColor(),
            'accent' => static::getAccentColor(),
        ];
    }

    public static function getMetaTags(): array
    {
        return [
            'title' => static::getSiteTitle(),
            'description' => static::getMetaDescription(),
            'keywords' => static::getMetaKeywords(),
        ];
    }
}