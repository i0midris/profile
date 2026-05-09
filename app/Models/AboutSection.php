<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = [
        'title',
        'title_en',
        'title_ar',
        'content',
        'content_en',
        'content_ar',
        'image',
        'mission_title',
        'mission_title_en',
        'mission_title_ar',
        'mission_content',
        'mission_content_en',
        'mission_content_ar',
        'vision_title',
        'vision_title_en',
        'vision_title_ar',
        'vision_content',
        'vision_content_en',
        'vision_content_ar',
        'years_experience',
        'projects_completed',
        'happy_clients',
        'team_members',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'years_experience' => 'integer',
        'projects_completed' => 'integer',
        'happy_clients' => 'integer',
        'team_members' => 'integer',
    ];

    /**
     * Get active about section
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getTitleAttribute($value)
    {
        return $this->resolveLocalizedAttribute('title', $value);
    }

    public function getContentAttribute($value)
    {
        return $this->resolveLocalizedAttribute('content', $value);
    }

    public function getMissionTitleAttribute($value)
    {
        return $this->resolveLocalizedAttribute('mission_title', $value);
    }

    public function getMissionContentAttribute($value)
    {
        return $this->resolveLocalizedAttribute('mission_content', $value);
    }

    public function getVisionTitleAttribute($value)
    {
        return $this->resolveLocalizedAttribute('vision_title', $value);
    }

    public function getVisionContentAttribute($value)
    {
        return $this->resolveLocalizedAttribute('vision_content', $value);
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
