<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slider extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image_path',
        'button_text',
        'button_url',
        'background_color',
        'text_color',
        'sort_order',
        'is_active',
    ];

    protected $translatable = [
        'title',
        'subtitle',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('slider_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(200)
            ->sharpen(10);

        $this->addMediaConversion('large')
            ->width(1920)
            ->height(800)
            ->quality(85);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    public function getImageUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('slider_image');
        return $media ? $media->getUrl('large') : null;
    }

    public function getThumbUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('slider_image');
        return $media ? $media->getUrl('thumb') : null;
    }
}
