<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = [
        'client_name',
        'client_name_en',
        'client_name_ar',
        'client_position',
        'client_position_en',
        'client_position_ar',
        'client_company',
        'client_company_en',
        'client_company_ar',
        'client_photo',
        'content',
        'content_en',
        'content_ar',
        'rating',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get active testimonials
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get featured testimonials
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

    public function getClientNameAttribute($value)
    {
        return $this->resolveLocalizedAttribute('client_name', $value);
    }

    public function getClientPositionAttribute($value)
    {
        return $this->resolveLocalizedAttribute('client_position', $value);
    }

    public function getClientCompanyAttribute($value)
    {
        return $this->resolveLocalizedAttribute('client_company', $value);
    }

    public function getContentAttribute($value)
    {
        return $this->resolveLocalizedAttribute('content', $value);
    }

    /**
     * Get client photo URL
     */
    public function getClientPhotoUrlAttribute()
    {
        return $this->client_photo ? asset('storage/' . $this->client_photo) : null;
    }

    /**
     * Get star rating as array for display
     */
    public function getStarsAttribute()
    {
        return array_fill(0, $this->rating, true);
    }
}
