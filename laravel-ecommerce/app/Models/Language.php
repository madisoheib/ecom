<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Language extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'code',
        'name',
        'native_name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public $translatable = ['name'];

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'country_languages');
    }

    public function defaultForCountries(): HasMany
    {
        return $this->hasMany(Country::class, 'default_language_id');
    }
}
