<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCountry extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'country_code',
        'price',
        'currency',
        'stock_quantity',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Get the product that owns this country configuration
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the country information
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }

    /**
     * Get formatted price with currency
     */
    public function getFormattedPriceAttribute(): string
    {
        if (!$this->price || !$this->currency) {
            return 'N/A';
        }

        $currencies = [
            'USD' => '$',
            'CAD' => 'C$',
            'EUR' => '€',
            'GBP' => '£',
            'AED' => 'د.إ',
            'KWD' => 'د.ك',
            'OMR' => 'ر.ع.',
            'DZD' => 'د.ج',
        ];

        $symbol = $currencies[$this->currency] ?? $this->currency;

        return $symbol . number_format($this->price, 2);
    }

    /**
     * Check if product is in stock for this country
     */
    public function inStock(): bool
    {
        return $this->is_available && $this->stock_quantity > 0;
    }
}