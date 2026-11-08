<?php

namespace App\Models;

use App\Models\Concerns\FlushesPageCache;
use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use FlushesPageCache;
    use HasLocalizedAttributes;

    public const CATEGORIES = ['general', 'products', 'setup', 'support'];

    /** Built-in animated illustrations (resources/views/frontend/partials/faq_illustration.blade.php). */
    public const ILLUSTRATIONS = ['auto', 'offline', 'branches', 'hardware', 'training', 'setup', 'customize', 'demo', 'security', 'support', 'none'];

    /** Used when the illustration is "auto". */
    private const CATEGORY_ILLUSTRATION = ['general' => 'demo', 'products' => 'branches', 'setup' => 'training', 'support' => 'support'];

    protected $fillable = ['category', 'question_ar', 'question_en', 'answer_ar', 'answer_en', 'image', 'illustration', 'show_on_home', 'sort_order', 'is_active'];

    protected $casts = [
        'show_on_home' => 'boolean',
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

    public function getQuestionAttribute(): ?string
    {
        return $this->localized('question');
    }

    public function getAnswerAttribute(): ?string
    {
        return $this->localized('answer');
    }

    /**
     * Key of the illustration to draw next to the answer, or null when an image was
     * uploaded (the image is shown instead) or the admin chose "none".
     */
    public function getIllustrationKeyAttribute(): ?string
    {
        $key = $this->illustration ?: 'auto';
        if ($this->image || $key === 'none') {
            return null;
        }

        return $key === 'auto' ? (self::CATEGORY_ILLUSTRATION[$this->category] ?? 'demo') : $key;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
