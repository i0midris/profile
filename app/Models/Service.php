<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = [
        'title',
        'title_en',
        'title_ar',
        'description',
        'description_en',
        'description_ar',
        'icon',
        'image',
        'features',
        'features_en',
        'features_ar',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'features_en' => 'array',
        'features_ar' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get active services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get featured services
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function getTitleAttribute($value)
    {
        return $this->resolveLocalizedAttribute('title', $value);
    }

    public function getDescriptionAttribute($value)
    {
        return $this->resolveLocalizedAttribute('description', $value);
    }

    public function getFeaturesAttribute($value)
    {
        return $this->resolveLocalizedJsonArray('features', $value);
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
