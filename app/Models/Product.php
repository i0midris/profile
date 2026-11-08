<?php

namespace App\Models;

use App\Models\Concerns\FlushesPageCache;
use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use FlushesPageCache;
    use HasLocalizedAttributes;

    protected $fillable = [
        'slug', 'name_ar', 'name_en', 'tagline_ar', 'tagline_en', 'description_ar', 'description_en',
        'features_ar', 'features_en', 'sectors_ar', 'sectors_en', 'cover_image', 'screenshots',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'features_ar' => 'array',
        'features_en' => 'array',
        'sectors_ar' => 'array',
        'sectors_en' => 'array',
        'screenshots' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function getNameAttribute(): ?string
    {
        return $this->localized('name');
    }

    public function getTaglineAttribute(): ?string
    {
        return $this->localized('tagline');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->localized('description');
    }

    public function getFeaturesAttribute(): array
    {
        return $this->localizedList('features');
    }

    public function getSectorsAttribute(): array
    {
        return $this->localizedList('sectors');
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }

    public function getScreenshotUrlsAttribute(): array
    {
        return array_map(fn ($path) => asset('storage/' . $path), $this->screenshots ?? []);
    }
}
