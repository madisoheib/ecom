<?php

namespace App\Helpers;

use App\Models\WebsiteSetting;

class SettingsHelper
{
    public static function getSiteTitle(): string
    {
        return WebsiteSetting::get('site_title', 'My Website');
    }

    public static function getSiteLogo(): ?string
    {
        return WebsiteSetting::get('site_logo');
    }

    public static function getMetaDescription(): ?string
    {
        return WebsiteSetting::get('meta_description');
    }

    public static function getMetaKeywords(): ?string
    {
        return WebsiteSetting::get('meta_keywords');
    }

    public static function getDefaultCurrency(): string
    {
        return WebsiteSetting::get('default_currency', 'USD');
    }

    public static function getPrimaryColor(): string
    {
        return WebsiteSetting::get('primary_color', '#3b82f6');
    }

    public static function getSecondaryColor(): string
    {
        return WebsiteSetting::get('secondary_color', '#64748b');
    }

    public static function getAccentColor(): string
    {
        return WebsiteSetting::get('accent_color', '#10b981');
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