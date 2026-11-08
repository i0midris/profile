@php
    // Error pages run outside the normal routes: pick the language from the URL and load the shared data.
    $errorLocale = in_array(request()->segment(1), ['ar', 'en'], true) ? request()->segment(1) : 'ar';
    app()->setLocale($errorLocale);
    $settings = \App\Models\CompanySetting::getSettings();
    $visiblePages = $settings->getVisiblePages();
@endphp
@extends('frontend.layouts.app')

@section('title', __('frontend.not_found_title') . ' | ' . ($settings->company_name ?? __('frontend.company_fallback')))

@section('content')
    <section class="min-h-[70vh] flex items-center relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute top-1/4 {{ app()->isLocale('ar') ? '-right-20' : '-left-20' }} w-96 h-96 bg-primary-500/15 rounded-full blur-3xl animate-pulse-slow"></div>
            <div class="absolute bottom-10 {{ app()->isLocale('ar') ? '-left-20' : '-right-20' }} w-80 h-80 bg-accent-500/15 rounded-full blur-3xl animate-pulse-slow" style="animation-delay:1.5s"></div>
        </div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative py-20">
            <p class="text-[7rem] sm:text-[9rem] font-extrabold leading-none gradient-text animate-float" aria-hidden="true">404</p>
            <h1 class="text-3xl md:text-4xl font-bold text-white mt-4 mb-4">{{ __('frontend.not_found_title') }}</h1>
            <p class="text-white/70 mb-10">{{ __('frontend.not_found_text') }}</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="px-8 py-4 rounded-xl gradient-primary text-white font-semibold">{{ __('frontend.back_home') }}</a>
                @if($visiblePages['services'] ?? true)
                    <a href="{{ route('services', ['locale' => app()->getLocale()]) }}" class="px-8 py-4 rounded-xl glass text-white font-semibold hover:bg-white/20">{{ __('frontend.services') }}</a>
                @endif
                <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="px-8 py-4 rounded-xl glass text-white font-semibold hover:bg-white/20">{{ __('frontend.contact') }}</a>
            </div>
        </div>
    </section>
@endsection
