<?php

namespace App\Traits;

use App\Models\SeoMeta;
use App\Models\SchemaMarkup;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'model');
    }

    public function schemaMarkup(): MorphOne
    {
        return $this->morphOne(SchemaMarkup::class, 'model');
    }

    public function createDefaultSeoMeta(): void
    {
        if (!$this->seoMeta()->exists()) {
            $this->seoMeta()->create([
                'meta_title' => $this->getDefaultMetaTitle(),
                'meta_description' => $this->getDefaultMetaDescription(),
            ]);
        }
    }

    public function getDefaultMetaTitle(): array
    {
        return [
            'fr' => $this->name ?? '',
            'en' => $this->name ?? '',
            'ar' => $this->name ?? '',
        ];
    }

    public function getDefaultMetaDescription(): array
    {
        return [
            'fr' => $this->description ?? '',
            'en' => $this->description ?? '',
            'ar' => $this->description ?? '',
        ];
    }

    public function getMetaTitleAttribute(): ?string
    {
        return $this->seoMeta?->meta_title;
    }

    public function getMetaDescriptionAttribute(): ?string
    {
        return $this->seoMeta?->meta_description;
    }

    public function generateSchemaMarkup(): array
    {
        return [];
    }
}