@extends('frontend.layouts.app')

@section('title', $portfolio->title . ' | ' . ($settings->company_name ?? __('frontend.company_fallback')))
@section('meta_description', Str::limit($portfolio->localized('challenge') ?: $portfolio->description, 160))
@if($portfolio->image_url)
    @section('og_image', $portfolio->image_url)
@endif

@section('content')
    <section class="pt-20 pb-12 relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <nav class="text-sm text-white/60 mb-6" aria-label="breadcrumb">
                <a href="{{ route('portfolio', ['locale' => app()->getLocale()]) }}" class="hover:text-white">{{ __('frontend.portfolio') }}</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-white/80">{{ $portfolio->title }}</span>
            </nav>
            <span class="inline-block px-3 py-1 rounded-full text-sm bg-primary-500/15 text-primary-400 mb-4">{{ __('frontend.case_study') }}</span>
            <h1 class="text-[2rem] leading-snug sm:text-4xl md:text-5xl font-bold text-white mb-4 break-words">{{ $portfolio->title }}</h1>
            <div class="flex flex-wrap gap-x-6 gap-y-2 text-white/70 mb-8">
                @if($portfolio->client_name)<span>{{ __('frontend.client') }}: <strong class="text-white">{{ $portfolio->client_name }}</strong></span>@endif
                @if($portfolio->category)<span>{{ $portfolio->category }}</span>@endif
                @if($portfolio->completed_at)<span>{{ \Illuminate\Support\Carbon::parse($portfolio->completed_at)->translatedFormat('F Y') }}</span>@endif
            </div>
            @if($portfolio->image_url)
                <img fetchpriority="high" src="{{ $portfolio->image_url }}" alt="{{ $portfolio->title }}" class="rounded-3xl shadow-2xl w-full mb-10">
            @endif
            @if($portfolio->description)
                <p class="text-lg text-white/80 leading-relaxed whitespace-pre-line">{{ $portfolio->description }}</p>
            @endif
        </div>
    </section>

    <section class="pb-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-3 gap-6">
            @foreach(['challenge' => 'frontend.the_challenge', 'solution' => 'frontend.our_solution', 'results' => 'frontend.the_results'] as $field => $label)
                @if($text = $portfolio->localized($field))
                    <div class="glass rounded-2xl p-6">
                        <h2 class="text-xl font-bold text-white mb-3">{{ __($label) }}</h2>
                        <p class="text-white/75 leading-relaxed whitespace-pre-line">{{ $text }}</p>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 flex flex-wrap gap-4">
            @if($portfolio->project_url)
                <a href="{{ $portfolio->project_url }}" target="_blank" rel="noopener noreferrer" class="px-6 py-3 rounded-xl glass text-white font-semibold hover:bg-white/20">{{ __('frontend.view_project') }}</a>
            @endif
            <a href="{{ Route::has('demo') ? route('demo', ['locale' => app()->getLocale()]) : route('contact', ['locale' => app()->getLocale()]) }}"
                class="px-6 py-3 rounded-xl gradient-primary text-white font-semibold">{{ __('frontend.request_demo') }}</a>
        </div>
    </section>
@endsection
