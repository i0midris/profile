@extends('frontend.layouts.app')

@section('title', $post->title . ' | ' . ($settings->company_name ?? __('frontend.company_fallback')))
@section('meta_description', Str::limit($post->excerpt, 160))
@if($post->cover_image_url)
    @section('og_image', $post->cover_image_url)
@endif

@section('content')
    @php
        $articleSchema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => Str::limit($post->excerpt, 160),
            'image' => $post->cover_image_url,
            'datePublished' => $post->published_at->toAtomString(),
            'dateModified' => $post->updated_at->toAtomString(),
            'inLanguage' => app()->getLocale(),
            'mainEntityOfPage' => url()->current(),
            'author' => ['@type' => 'Organization', 'name' => $settings->company_name],
            'publisher' => array_filter(['@type' => 'Organization', 'name' => $settings->company_name, 'logo' => $settings->logo_url ? ['@type' => 'ImageObject', 'url' => $settings->logo_url] : null]),
        ]);
    @endphp
    <script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>

    <article class="pt-16 pb-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-sm text-white/60 mb-6" aria-label="breadcrumb">
                <a href="{{ route('blog', ['locale' => app()->getLocale()]) }}" class="hover:text-white">{{ __('frontend.blog') }}</a>
            </nav>
            <h1 class="text-[2rem] leading-snug sm:text-4xl md:text-5xl font-bold text-white mb-4 break-words">{{ $post->title }}</h1>
            <p class="text-white/60 mb-8">
                <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->locale(app()->getLocale())->translatedFormat('j F Y') }}</time>
                · {{ trans_choice('frontend.reading_minutes', $post->reading_minutes, ['count' => $post->reading_minutes]) }}
            </p>
            @if($post->cover_image_url)
                <img fetchpriority="high" src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="rounded-3xl shadow-2xl w-full mb-10">
            @endif
            <div class="prose-article">{{ $post->body_html }}</div>

            <div class="glass rounded-2xl p-6 sm:p-8 mt-12 text-center">
                <h2 class="text-2xl font-bold text-white mb-3">{{ __('frontend.blog_cta_title') }}</h2>
                <p class="text-white/70 mb-6">{{ __('frontend.blog_cta_text') }}</p>
                <a href="{{ route('demo', ['locale' => app()->getLocale()]) }}" class="inline-flex px-8 py-4 rounded-xl gradient-primary text-white font-semibold">{{ __('frontend.request_demo') }}</a>
            </div>
        </div>
    </article>

    @if($related->count())
        <section class="pb-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-8">{{ __('frontend.more_articles') }}</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($related as $index => $other)
                        @include('frontend.partials.post_card', ['post' => $other, 'index' => $index])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
