<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = [
        'title',
        'title_en',
        'title_ar',
        'description',
        'description_en',
        'description_ar',
        'client_name',
        'client_name_en',
        'client_name_ar',
        'category',
        'category_en',
        'category_ar',
        'image',
        'gallery',
        'project_url',
        'completed_at',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'gallery' => 'array',
        'completed_at' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get active portfolios
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get featured portfolios
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

    public function getClientNameAttribute($value)
    {
        return $this->resolveLocalizedAttribute('client_name', $value);
    }

    public function getCategoryAttribute($value)
    {
        return $this->resolveLocalizedAttribute('category', $value);
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Get gallery URLs
     */
    public function getGalleryUrlsAttribute()
    {
        if (!$this->gallery)
            return [];
        return array_map(fn($image) => asset('storage/' . $image), $this->gallery);
    }

    /**
     * Get unique categories
     */
    public static function getCategories()
    {
        return self::active()
            ->get()
            ->map(fn(self $portfolio) => $portfolio->category)
            ->filter()
            ->unique()
            ->values();
    }
}
