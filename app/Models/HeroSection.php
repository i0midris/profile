<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = [
        'title',
        'title_en',
        'title_ar',
        'subtitle',
        'subtitle_en',
        'subtitle_ar',
        'description',
        'description_en',
        'description_ar',
        'button_text',
        'button_text_en',
        'button_text_ar',
        'button_link',
        'button_text_secondary',
        'button_text_secondary_en',
        'button_text_secondary_ar',
        'button_link_secondary',
        'background_image',
        'foreground_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active hero section
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getTitleAttribute($value)
    {
        return $this->resolveLocalizedAttribute('title', $value);
    }

    public function getSubtitleAttribute($value)
    {
        return $this->resolveLocalizedAttribute('subtitle', $value);
    }

    public function getDescriptionAttribute($value)
    {
        return $this->resolveLocalizedAttribute('description', $value);
    }

    public function getButtonTextAttribute($value)
    {
        return $this->resolveLocalizedAttribute('button_text', $value);
    }

    public function getButtonTextSecondaryAttribute($value)
    {
        return $this->resolveLocalizedAttribute('button_text_secondary', $value);
    }

    /**
     * Get background image URL
     */
    public function getBackgroundImageUrlAttribute()
    {
        return $this->background_image ? asset('storage/' . $this->background_image) : null;
    }

    /**
     * Get foreground image URL
     */
    public function getForegroundImageUrlAttribute()
    {
        return $this->foreground_image ? asset('storage/' . $this->foreground_image) : null;
    }
}
