<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Translatable\HasTranslations;

class SeoMeta extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'seo_meta';

    protected $fillable = [
        'model_type',
        'model_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'custom_meta',
        'noindex',
        'nofollow',
    ];

    public $translatable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'twitter_title',
        'twitter_description',
    ];

    protected $casts = [
        'custom_meta' => 'array',
        'noindex' => 'boolean',
        'nofollow' => 'boolean',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function getRobotsAttribute(): string
    {
        $robots = [];
        
        if ($this->noindex) {
            $robots[] = 'noindex';
        } else {
            $robots[] = 'index';
        }
        
        if ($this->nofollow) {
            $robots[] = 'nofollow';
        } else {
            $robots[] = 'follow';
        }
        
        return implode(', ', $robots);
    }
}