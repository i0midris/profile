<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;

class JobOpening extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = [
        'title',
        'title_en',
        'title_ar',
        'department',
        'department_en',
        'department_ar',
        'location',
        'location_en',
        'location_ar',
        'employment_type',
        'employment_type_en',
        'employment_type_ar',
        'experience_level',
        'experience_level_en',
        'experience_level_ar',
        'description',
        'description_en',
        'description_ar',
        'requirements',
        'requirements_en',
        'requirements_ar',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'requirements' => 'array',
        'requirements_en' => 'array',
        'requirements_ar' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('created_at');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function getTitleAttribute($value)
    {
        return $this->resolveLocalizedAttribute('title', $value);
    }

    public function getDepartmentAttribute($value)
    {
        return $this->resolveLocalizedAttribute('department', $value);
    }

    public function getLocationAttribute($value)
    {
        return $this->resolveLocalizedAttribute('location', $value);
    }

    public function getEmploymentTypeAttribute($value)
    {
        return $this->resolveLocalizedAttribute('employment_type', $value);
    }

    public function getExperienceLevelAttribute($value)
    {
        return $this->resolveLocalizedAttribute('experience_level', $value);
    }

    public function getDescriptionAttribute($value)
    {
        return $this->resolveLocalizedAttribute('description', $value);
    }

    public function getRequirementsAttribute($value)
    {
        return $this->resolveLocalizedJsonArray('requirements', $value);
    }
}
