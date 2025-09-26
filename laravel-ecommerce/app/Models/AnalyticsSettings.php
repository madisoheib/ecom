<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_analytics_id',
        'google_tag_manager_id',
        'facebook_pixel_id',
        'is_active',
        'track_ecommerce',
        'anonymize_ip'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'track_ecommerce' => 'boolean',
        'anonymize_ip' => 'boolean',
    ];

    public static function getActive(): ?self
    {
        return static::where('is_active', true)->first();
    }

    public function activate(): void
    {
        static::query()->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }
}
