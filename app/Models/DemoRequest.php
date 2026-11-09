<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoRequest extends Model
{
    public const CONTACT_METHODS = ['whatsapp', 'call', 'email'];

    /** Types of business offered on the demo form; the same keys as the "Solutions for your sector" cards. */
    public const SECTORS = ['restaurant', 'pharmacy', 'supermarket', 'warehouse', 'school', 'distribution', 'other'];

    protected $fillable = ['name', 'company', 'phone', 'email', 'product', 'sector', 'preferred_contact', 'message', 'is_handled', 'locale'];

    protected $casts = [
        'is_handled' => 'boolean',
    ];

    public static function sectorLabel(?string $sector): ?string
    {
        if (!$sector || !in_array($sector, self::SECTORS, true)) {
            return null;
        }

        return $sector === 'other' ? __('frontend.sector_other_title') : __('frontend.sector_' . $sector . '_title');
    }

    public function scopePending($query)
    {
        return $query->where('is_handled', false);
    }
}
