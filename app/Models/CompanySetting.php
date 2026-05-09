<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasLocalizedAttributes;

    public const PAGE_VISIBILITY_FIELDS = [
        'about' => 'show_about_page',
        'services' => 'show_services_page',
        'team' => 'show_team_page',
        'jobs' => 'show_jobs_page',
        'portfolio' => 'show_portfolio_page',
        'testimonials' => 'show_testimonials_page',
        'contact' => 'show_contact_page',
    ];

    protected $fillable = [
        'company_name',
        'company_name_en',
        'company_name_ar',
        'tagline',
        'tagline_en',
        'tagline_ar',
        'description',
        'description_en',
        'description_ar',
        'logo',
        'favicon',
        'email',
        'phone',
        'address',
        'address_en',
        'address_ar',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'whatsapp',
        'show_about_page',
        'show_services_page',
        'show_team_page',
        'show_jobs_page',
        'show_portfolio_page',
        'show_testimonials_page',
        'show_contact_page',
    ];

    protected $casts = [
        'show_about_page' => 'boolean',
        'show_services_page' => 'boolean',
        'show_team_page' => 'boolean',
        'show_jobs_page' => 'boolean',
        'show_portfolio_page' => 'boolean',
        'show_testimonials_page' => 'boolean',
        'show_contact_page' => 'boolean',
    ];

    /**
     * Get the singleton company settings
     */
    public static function getSettings()
    {
        $defaults = [
            'company_name' => 'Company Name',
            'company_name_en' => 'Company Name',
            'company_name_ar' => 'Company Name',
            'tagline' => 'Your tagline here',
            'tagline_en' => 'Your tagline here',
            'tagline_ar' => 'Your tagline here',
            'show_about_page' => true,
            'show_services_page' => true,
            'show_team_page' => true,
            'show_jobs_page' => true,
            'show_portfolio_page' => true,
            'show_testimonials_page' => true,
            'show_contact_page' => true,
        ];

        try {
            return self::first() ?? new self($defaults);
        } catch (\Throwable) {
            return new self($defaults);
        }
    }

    public static function setPageVisibility(string $page, bool $isVisible): void
    {
        $field = self::PAGE_VISIBILITY_FIELDS[$page] ?? null;

        if ($field === null) {
            return;
        }

        $settings = self::first() ?? new self();
        $settings->setAttribute($field, $isVisible);
        $settings->save();
    }

    public function isPageVisible(string $page): bool
    {
        $field = self::PAGE_VISIBILITY_FIELDS[$page] ?? null;

        if ($field === null) {
            return true;
        }

        $value = $this->getAttribute($field);

        return $value === null ? true : (bool) $value;
    }

    public function getVisiblePages(): array
    {
        $visibility = [];

        foreach (array_keys(self::PAGE_VISIBILITY_FIELDS) as $page) {
            $visibility[$page] = $this->isPageVisible($page);
        }

        return $visibility;
    }

    public function getCompanyNameAttribute($value)
    {
        return $this->resolveLocalizedAttribute('company_name', $value);
    }

    public function getTaglineAttribute($value)
    {
        return $this->resolveLocalizedAttribute('tagline', $value);
    }

    public function getDescriptionAttribute($value)
    {
        return $this->resolveLocalizedAttribute('description', $value);
    }

    public function getAddressAttribute($value)
    {
        return $this->resolveLocalizedAttribute('address', $value);
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    /**
     * Get favicon URL
     */
    public function getFaviconUrlAttribute()
    {
        return $this->favicon ? asset('storage/' . $this->favicon) : null;
    }
}
