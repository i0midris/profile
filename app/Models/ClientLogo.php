<?php

namespace App\Models;

use App\Models\Concerns\FlushesPageCache;
use Illuminate\Database\Eloquent\Model;

class ClientLogo extends Model
{
    use FlushesPageCache;

    protected $fillable = ['name', 'logo', 'website_url', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
}
