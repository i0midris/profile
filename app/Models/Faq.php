<?php

namespace App\Models;

use App\Models\Concerns\FlushesPageCache;
use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use FlushesPageCache;
    use HasLocalizedAttributes;

    protected $fillable = ['question_ar', 'question_en', 'answer_ar', 'answer_en', 'show_on_home', 'sort_order', 'is_active'];

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
}
