<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoRequest extends Model
{
    public const CONTACT_METHODS = ['whatsapp', 'call', 'email'];

    protected $fillable = ['name', 'company', 'phone', 'email', 'product', 'preferred_contact', 'message', 'is_handled', 'locale'];

    protected $casts = [
        'is_handled' => 'boolean',
    ];

    public function scopePending($query)
    {
        return $query->where('is_handled', false);
    }
}
