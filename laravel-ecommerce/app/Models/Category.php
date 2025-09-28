<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements HasMedia
{
    use HasFactory, HasSlug, HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_path',
        'parent_id',
        'sort_order',
        'is_active',
        'slug_translations',
    ];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
        'slug_translations' => 'array',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'model');
    }

    public function schemaMarkup(): MorphOne
    {
        return $this->morphOne(SchemaMarkup::class, 'model');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('category_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('category_icon')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']);
    }

    /**
     * Get localized slug for current locale
     */
    public function getLocalizedSlug($locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        if ($this->slug_translations && isset($this->slug_translations[$locale])) {
            return $this->slug_translations[$locale];
        }

        // Fallback to default slug
        return $this->slug;
    }

    /**
     * Set translated slug for a specific locale
     */
    public function setSlugTranslation(string $locale, string $slug): void
    {
        $translations = $this->slug_translations ?? [];
        $translations[$locale] = $slug;
        $this->slug_translations = $translations;
    }

    /**
     * Generate slug from name for specific locale
     */
    public function generateSlugForLocale(string $locale): string
    {
        $name = $this->getTranslation('name', $locale);

        if ($locale === 'ar') {
            // For Arabic, create transliteration or keep English
            $slug = $this->transliterateArabic($name) ?: str($this->getTranslation('name', 'en'))->slug();
        } else {
            $slug = str($name)->slug();
        }

        return $this->makeLocaleSlugUnique($slug, $locale);
    }

    /**
     * Simple Arabic transliteration
     */
    private function transliterateArabic(string $text): string
    {
        $arabicToLatin = [
            'ا' => 'a', 'ب' => 'b', 'ت' => 't', 'ث' => 'th', 'ج' => 'j',
            'ح' => 'h', 'خ' => 'kh', 'د' => 'd', 'ذ' => 'dh', 'ر' => 'r',
            'ز' => 'z', 'س' => 's', 'ش' => 'sh', 'ص' => 's', 'ض' => 'd',
            'ط' => 't', 'ظ' => 'z', 'ع' => 'a', 'غ' => 'gh', 'ف' => 'f',
            'ق' => 'q', 'ك' => 'k', 'ل' => 'l', 'م' => 'm', 'ن' => 'n',
            'ه' => 'h', 'و' => 'w', 'ي' => 'y', 'ى' => 'a'
        ];

        $transliterated = strtr($text, $arabicToLatin);
        return str($transliterated)->slug();
    }

    /**
     * Make slug unique for the given locale
     */
    private function makeLocaleSlugUnique(string $slug, string $locale): string
    {
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExistsForLocale($slug, $locale)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists for locale
     */
    private function slugExistsForLocale(string $slug, string $locale): bool
    {
        return static::where('id', '!=', $this->id ?? 0)
            ->where(function($query) use ($slug, $locale) {
                $query->where('slug', $slug)
                      ->orWhere('slug_translations->' . $locale, $slug);
            })
            ->exists();
    }
}