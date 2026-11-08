<?php

namespace App\Models;

use App\Models\Concerns\FlushesPageCache;
use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Post extends Model
{
    use FlushesPageCache;
    use HasLocalizedAttributes;

    protected $fillable = ['slug', 'title_ar', 'title_en', 'excerpt_ar', 'excerpt_en', 'body_ar', 'body_en', 'cover_image', 'published_at', 'is_active'];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    protected static function booted(): void
    {
        // Publishing without a date means "publish now"; a future date schedules the post.
        static::saving(function (Post $post) {
            if ($post->is_active && !$post->published_at) {
                $post->published_at = now();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('is_active', true)->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return $this->is_active && $this->published_at && $this->published_at->lte(now());
    }

    public function getTitleAttribute(): ?string
    {
        return $this->localized('title');
    }

    public function getExcerptAttribute(): string
    {
        return $this->localized('excerpt') ?: Str::limit(trim(strip_tags(Str::markdown((string) $this->localized('body')))), 180);
    }

    /**
     * Body written in Markdown in the dashboard. Raw HTML is stripped and unsafe
     * links (javascript:, data:) are refused, so the output is safe to print.
     */
    public function getBodyHtmlAttribute(): HtmlString
    {
        return new HtmlString(Str::markdown((string) $this->localized('body'), [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 20,
        ]));
    }

    public function getReadingMinutesAttribute(): int
    {
        $words = count(preg_split('/\s+/u', trim(strip_tags((string) $this->localized('body'))), -1, PREG_SPLIT_NO_EMPTY));

        return max(1, (int) ceil($words / 200));
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }
}
