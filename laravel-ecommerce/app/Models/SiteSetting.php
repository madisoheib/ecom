<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_title',
        'site_logo',
        'meta_description',
        'meta_keywords',
        'default_currency',
        'primary_color',
        'secondary_color',
        'accent_color',
        'company_email',
        'company_phone',
        'company_address',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'site_description',
    ];

    protected static $cachedSettings = null;

    public static function current()
    {
        if (static::$cachedSettings === null) {
            static::$cachedSettings = static::first() ?? static::create([]);
        }
        return static::$cachedSettings;
    }

    public static function clearStaticCache()
    {
        static::$cachedSettings = null;
    }

    public static function getSetting($key, $default = null)
    {
        $settings = static::current();
        return $settings->$key ?? $default;
    }

    public static function setSetting($key, $value)
    {
        $settings = static::current();
        $settings->update([$key => $value]);
        return $settings;
    }
}
