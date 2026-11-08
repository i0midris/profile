<a href="{{ route('blog.show', ['locale' => app()->getLocale(), $post]) }}"
    class="glass global-card rounded-2xl overflow-hidden h-full flex flex-col hover-lift group" data-animate="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
    @if($post->cover_image_url)
        <img loading="lazy" decoding="async" src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
    @endif
    <div class="p-6 flex-1 flex flex-col">
        <p class="text-white/60 text-sm mb-2">
            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->locale(app()->getLocale())->translatedFormat('j F Y') }}</time>
            · {{ trans_choice('frontend.reading_minutes', $post->reading_minutes, ['count' => $post->reading_minutes]) }}
        </p>
        <h2 class="global-card-title text-xl font-semibold text-white mb-3">{{ $post->title }}</h2>
        <p class="global-card-copy text-white/60 leading-relaxed mb-4">{{ Str::limit($post->excerpt, 160) }}</p>
        <span class="mt-auto inline-flex items-center text-primary-400 font-medium">{{ __('frontend.read_more') }}
            <svg aria-hidden="true" class="w-4 h-4 ms-2 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
    </div>
</a>
