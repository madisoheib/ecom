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

class Product extends Model implements HasMedia
{
    use HasFactory, HasSlug, HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'barcode',
        'description',
        'short_description',
        'specifications',
        'price',
        'compare_price',
        'cost_price',
        'registration_discount',
        'stock_quantity',
        'track_quantity',
        'quantity',
        'allow_backorder',
        'brand_id',
        'is_active',
        'is_featured',
        'is_digital',
        'weight',
        'width',
        'height',
        'length',
        'views_count',
        'sales_count',
        'default_country',
        // Manual slug translations (keeping this as it has custom logic)
        'slug_translations',
        // SEO fields
        'meta_title',
        'meta_description',
        'meta_keywords',
        'focus_keyword',
        'schema_markup',
        'canonical_url',
        'index_follow',
        'content_score',
        'seo_updated_at',
    ];

    public $translatable = ['name', 'description', 'short_description', 'specifications'];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'length' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_digital' => 'boolean',
        'track_quantity' => 'boolean',
        'allow_backorder' => 'boolean',
        'index_follow' => 'boolean',
        // Manual slug translations (keeping this as it has custom logic)
        'slug_translations' => 'array',
        // SEO fields casts
        'meta_title' => 'array',
        'meta_description' => 'array',
        'meta_keywords' => 'array',
        'seo_updated_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage(): HasMany
    {
        return $this->images()->where('is_primary', true);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
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
        $this->addMediaCollection('product_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    // Multilingual content helpers
    public function getTranslatedName($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->getTranslation('name', $locale) ?: $this->name;
    }

    public function getTranslatedDescription($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->getTranslation('description', $locale) ?: $this->description;
    }

    public function getTranslatedSpecifications($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->getTranslation('specifications', $locale) ?: $this->specifications;
    }

    public function getTranslatedShortDescription($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->getTranslation('short_description', $locale) ?: $this->short_description;
    }

    // SEO helpers
    public function getMetaTitle($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $metaTitles = $this->meta_title;

        if (is_array($metaTitles) && isset($metaTitles[$locale])) {
            return $metaTitles[$locale];
        }

        // Fallback to translated product name
        return $this->getTranslatedName($locale) . ' - ' . config('app.name');
    }

    public function getMetaDescription($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $metaDescriptions = $this->meta_description;

        if (is_array($metaDescriptions) && isset($metaDescriptions[$locale])) {
            return $metaDescriptions[$locale];
        }

        // Fallback to truncated description
        $description = $this->getTranslatedShortDescription($locale) ?: $this->getTranslatedDescription($locale);
        return $description ? substr(strip_tags($description), 0, 160) . '...' : '';
    }

    public function getMetaKeywords($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $metaKeywords = $this->meta_keywords;

        if (is_array($metaKeywords) && isset($metaKeywords[$locale])) {
            return $metaKeywords[$locale];
        }

        // Fallback to focus keyword and category names
        $keywords = [];
        if ($this->focus_keyword) {
            $keywords[] = $this->focus_keyword;
        }

        foreach ($this->categories as $category) {
            $keywords[] = $category->name;
        }

        if ($this->brand) {
            $keywords[] = $this->brand->name;
        }

        return implode(', ', array_unique($keywords));
    }

    public function getCanonicalUrl()
    {
        if ($this->canonical_url) {
            return $this->canonical_url;
        }

        $category = $this->categories->first();
        return localized_route('products.show', [
            'categorySlug' => $category ? $category->getLocalizedSlug() : 'produits',
            'productSlug' => $this->getLocalizedSlug()
        ]);
    }

    public function generateSchemaMarkup($userCountry = null)
    {
        $category = $this->categories->first();
        $brand = $this->brand;

        // Get country-specific pricing if user country is provided
        if ($userCountry) {
            $price = $this->getPriceForCountry($userCountry);
            $currency = $this->getCurrencyForCountry($userCountry);
            $stock = $this->getStockForCountry($userCountry);
        } else {
            $price = $this->price;
            $currency = get_user_currency();
            $stock = $this->stock_quantity;
        }

        return [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $this->getTranslatedName(),
            'description' => $this->getTranslatedDescription(),
            'image' => [
                // Add product images here
                asset('images/placeholder-product.jpg')
            ],
            'brand' => $brand ? [
                '@type' => 'Brand',
                'name' => $brand->name
            ] : null,
            'category' => $category ? $category->name : null,
            'sku' => $this->sku,
            'offers' => [
                '@type' => 'Offer',
                'url' => $this->getCanonicalUrl(),
                'priceCurrency' => $currency,
                'price' => $price,
                'priceValidUntil' => now()->addYear()->format('Y-m-d'),
                'availability' => $stock > 0
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => config('app.name')
                ]
            ]
        ];
    }

    public function updateSeoScore()
    {
        $score = 0;

        // Check if focus keyword is set
        if ($this->focus_keyword) {
            $score += 10;

            // Check if focus keyword is in title
            $title = $this->getMetaTitle();
            if (stripos($title, $this->focus_keyword) !== false) {
                $score += 15;
            }

            // Check if focus keyword is in description
            $description = $this->getMetaDescription();
            if (stripos($description, $this->focus_keyword) !== false) {
                $score += 10;
            }

            // Check if focus keyword is in content
            $content = $this->getTranslatedDescription();
            if (stripos($content, $this->focus_keyword) !== false) {
                $score += 10;
            }
        }

        // Check meta title length (optimal: 30-60 characters)
        $titleLength = strlen($this->getMetaTitle());
        if ($titleLength >= 30 && $titleLength <= 60) {
            $score += 15;
        } elseif ($titleLength > 0) {
            $score += 5;
        }

        // Check meta description length (optimal: 120-160 characters)
        $descLength = strlen($this->getMetaDescription());
        if ($descLength >= 120 && $descLength <= 160) {
            $score += 15;
        } elseif ($descLength > 0) {
            $score += 5;
        }

        // Check if product has description
        if ($this->getTranslatedDescription()) {
            $score += 10;
        }

        // Check if product has images (placeholder for now)
        $score += 5;

        // Check if canonical URL is set
        if ($this->canonical_url || $this->slug) {
            $score += 5;
        }

        // Update the score
        $this->update([
            'content_score' => min($score, 100), // Cap at 100
            'seo_updated_at' => now()
        ]);

        return $this->content_score;
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
        $name = $this->getTranslatedName($locale);

        if ($locale === 'ar') {
            // For Arabic, create transliteration or keep English
            $slug = $this->transliterateArabic($name) ?: str($this->getTranslatedName('en'))->slug();
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

    /**
     * Get product countries configurations
     */
    public function productCountries(): HasMany
    {
        return $this->hasMany(ProductCountry::class);
    }

    /**
     * Get available countries for this product
     */
    public function availableCountries()
    {
        return $this->productCountries()->where('is_available', true);
    }

    /**
     * Get price for specific country
     */
    public function getPriceForCountry(string $countryCode): ?float
    {
        $productCountry = $this->productCountries()
            ->where('country_code', $countryCode)
            ->where('is_available', true)
            ->first();

        return $productCountry ? $productCountry->price : $this->price;
    }

    /**
     * Get currency for specific country
     */
    public function getCurrencyForCountry(string $countryCode): string
    {
        $productCountry = $this->productCountries()
            ->where('country_code', $countryCode)
            ->where('is_available', true)
            ->first();

        if ($productCountry && $productCountry->currency) {
            return $productCountry->currency;
        }

        // Fallback to country's default currency
        $countryCurrencies = [
            'CA' => 'CAD',
            'US' => 'USD',
            'FR' => 'EUR',
            'AE' => 'AED',
            'KW' => 'KWD',
            'OM' => 'OMR',
            'DZ' => 'DZD',
        ];

        return $countryCurrencies[$countryCode] ?? 'USD';
    }

    /**
     * Check if product is available in specific country
     */
    public function isAvailableInCountry(string $countryCode): bool
    {
        $productCountry = $this->productCountries()
            ->where('country_code', $countryCode)
            ->first();

        if ($productCountry) {
            return $productCountry->is_available && $productCountry->stock_quantity > 0;
        }

        // If no specific country config, check if it's the default country
        return $this->default_country === $countryCode && $this->stock_quantity > 0;
    }

    /**
     * Get stock quantity for specific country
     */
    public function getStockForCountry(string $countryCode): int
    {
        $productCountry = $this->productCountries()
            ->where('country_code', $countryCode)
            ->where('is_available', true)
            ->first();

        return $productCountry ? $productCountry->stock_quantity : $this->stock_quantity;
    }
}