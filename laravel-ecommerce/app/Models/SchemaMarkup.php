<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SchemaMarkup extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'schema_type',
        'schema_data',
        'is_active',
    ];

    protected $casts = [
        'schema_data' => 'array',
        'is_active' => 'boolean',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getJsonLdAttribute(): string
    {
        return json_encode($this->schema_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}