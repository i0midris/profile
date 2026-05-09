<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = [
        'name',
        'name_en',
        'name_ar',
        'position',
        'position_en',
        'position_ar',
        'bio',
        'bio_en',
        'bio_ar',
        'photo',
        'email',
        'phone',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get active team members
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function getNameAttribute($value)
    {
        return $this->resolveLocalizedAttribute('name', $value);
    }

    public function getPositionAttribute($value)
    {
        return $this->resolveLocalizedAttribute('position', $value);
    }

    public function getBioAttribute($value)
    {
        return $this->resolveLocalizedAttribute('bio', $value);
    }

    /**
     * Get photo URL
     */
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    /**
     * Check if member has any social links
     */
    public function getHasSocialLinksAttribute()
    {
        return $this->facebook || $this->twitter || $this->instagram || $this->linkedin;
    }
}
