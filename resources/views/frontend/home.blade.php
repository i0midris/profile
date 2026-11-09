@extends('frontend.layouts.app')

@section('title', ($settings->company_name ?? __('frontend.company_fallback')) . ' | ' . __('frontend.seo_home_title'))
@section('meta_description', $hero?->description ?? __('frontend.services_description'))

@section('content')
<!-- Hero Section -->
<section class="min-h-screen flex items-center relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 {{ app()->isLocale('ar') ? '-right-20' : '-left-20' }} w-96 h-96 bg-primary-500/20 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute bottom-1/4 {{ app()->isLocale('ar') ? '-left-20' : '-right-20' }} w-96 h-96 bg-accent-500/20 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 1.5s;"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Above the fold: no entrance animation, so the heading is visible and in place immediately (fixes mobile clipping, faster LCP). --}}
            <div>
                @if($hero->subtitle ?? false)
                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-primary-500/10 text-primary-400 text-sm font-medium mb-6">
                        <span class="w-2 h-2 bg-primary-400 rounded-full me-2 animate-pulse"></span>
                        {{ $hero->subtitle }}
                    </span>
                @endif
                
                @php
                    $heroTitle = $hero->title ?? __('frontend.build_digital_presence');
                    $heroTitleEscaped = e($heroTitle);
                    $heroTitleHighlighted = preg_replace('/([^\s]+)\s*$/u', '<span class="gradient-text">$1</span>', $heroTitleEscaped);
                @endphp
                <h1 class="text-[2rem] leading-snug sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white md:leading-tight mb-6 break-words">
                    {!! $heroTitleHighlighted !!}
                </h1>
                
                @if($hero->description ?? false)
                    <p class="text-lg text-white/70 mb-8 max-w-xl">{{ $hero->description }}</p>
                @endif
                
                <div class="flex flex-wrap gap-4">
                    @if($hero->button_text ?? false)
                        <a href="{{ $hero->button_link_localized ?? '#' }}" class="px-8 py-4 rounded-xl gradient-primary text-white font-semibold hover:shadow-lg hover:shadow-primary-500/30 transform hover:-translate-y-1 transition-all duration-300">
                            {{ $hero->button_text }}
                        </a>
                    @endif
                    @if($hero->button_text_secondary ?? false)
                        <a href="{{ $hero->button_link_secondary_localized ?? '#' }}" class="px-8 py-4 rounded-xl glass text-white font-semibold hover:bg-white/20 transition-all duration-300">
                            {{ $hero->button_text_secondary }}
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="hidden lg:block">
                <div class="relative">
                    @if($hero->foreground_image_url ?? false)
                        <img fetchpriority="high" src="{{ $hero->foreground_image_url }}" alt="{{ __('frontend.hero_image_alt') }}" class="relative z-10 animate-float">
                    @else
                        @include('frontend.partials.hero_illustration')
                    @endif


                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.partials.systems_marquee')

<!-- Services Preview -->
@if($services->count() > 0)
<section class="py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-animate="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('frontend.our_services') }}</h2>
            <p class="text-white/60 max-w-2xl mx-auto">{{ __('frontend.services_description') }}</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $index => $service)
                <div class="glass global-card rounded-2xl p-8 hover-lift" data-animate="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="global-card-icon w-14 h-14 rounded-xl gradient-primary flex items-center justify-center mb-6">
                        @include('frontend.partials.icon', ['icon' => $service->icon ?? 'code'])
                    </div>
                    <h3 class="global-card-title text-xl font-semibold text-white mb-3">{{ $service->title }}</h3>
                    <p class="global-card-copy text-white/60 mb-4">{{ Str::limit($service->description, 120) }}</p>
                    @if(($visiblePages['services'] ?? true))
                        <a href="{{ route('services', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center text-primary-400 hover:text-primary-300 font-medium">
                            {{ __('frontend.learn_more') }}
                            <svg aria-hidden="true" class="w-4 h-4 ms-2 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('frontend.partials.how_we_work')

@include('frontend.partials.sectors')

<!-- Products Preview (appears once products are published from the dashboard) -->
@if($products->count() > 0)
<section class="py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-animate="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('frontend.our_products') }}</h2>
            <p class="text-white/60 max-w-2xl mx-auto">{{ __('frontend.products_intro') }}</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($products as $index => $product)
                @include('frontend.partials.product_card', ['product' => $product, 'index' => $index])
            @endforeach
        </div>
    </div>
</section>
@endif

@include('frontend.partials.client_logos')

<!-- About Preview -->
@if($about)
<section class="py-24 relative">
    <div class="absolute inset-0">
        <div class="absolute top-1/2 {{ app()->isLocale('ar') ? 'right-1/4' : 'left-1/4' }} w-64 h-64 bg-primary-500/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div data-animate="fade-right">
                @if($about->image_url)
                    <img loading="lazy" decoding="async" src="{{ $about->image_url }}" alt="{{ __('frontend.about_image_alt') }}" class="rounded-3xl shadow-2xl">
                @else
                    @include('frontend.partials.about_ecosystem')
                @endif
            </div>
            
            <div data-animate="fade-left">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">{{ $about->title }}</h2>
                <p class="text-white/70 mb-8 leading-relaxed">{{ Str::limit($about->content, 300) }}</p>
                
                @if($about->years_experience || $about->projects_completed || $about->happy_clients)
                <div class="grid grid-cols-3 gap-6 mb-8">
                    @if($about->years_experience)
                    <div>
                        <p class="text-3xl font-bold gradient-text">
                            <span data-counter-target="{{ $about->years_experience }}">{{ $about->years_experience }}</span>+
                        </p>
                        <p class="text-white/60 text-sm">{{ __('frontend.years_experience') }}</p>
                    </div>
                    @endif
                    @if($about->projects_completed)
                    <div>
                        <p class="text-3xl font-bold gradient-text">
                            <span data-counter-target="{{ $about->projects_completed }}">{{ $about->projects_completed }}</span>+
                        </p>
                        <p class="text-white/60 text-sm">{{ __('frontend.projects_done') }}</p>
                    </div>
                    @endif
                    @if($about->happy_clients)
                    <div>
                        <p class="text-3xl font-bold gradient-text">
                            <span data-counter-target="{{ $about->happy_clients }}">{{ $about->happy_clients }}</span>+
                        </p>
                        <p class="text-white/60 text-sm">{{ __('frontend.happy_clients') }}</p>
                    </div>
                    @endif
                </div>
                @endif
                
                @if(($visiblePages['about'] ?? true))
                    <a href="{{ route('about', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-6 py-3 rounded-xl gradient-primary text-white font-semibold hover:shadow-lg hover:shadow-primary-500/30 transition-all">
                        {{ __('frontend.learn_more_about_us') }}
                        <svg aria-hidden="true" class="w-5 h-5 ms-2 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

<!-- Portfolio Preview -->
@if($portfolios->count() > 0)
<section class="py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-animate="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('frontend.featured_work') }}</h2>
            <p class="text-white/60 max-w-2xl mx-auto">{{ __('frontend.featured_work_description') }}</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($portfolios as $index => $portfolio)
                <div class="group relative overflow-hidden rounded-2xl" data-animate="fade-up" data-aos-delay="{{ $index * 100 }}">
                    @if($portfolio->image_url)
                        <img loading="lazy" decoding="async" src="{{ $portfolio->image_url }}" alt="{{ $portfolio->title }}" class="w-full h-72 object-cover transition-transform duration-500 group-hover:scale-110">
                    @else
                        <div class="w-full h-72 bg-gradient-to-br from-primary-500/30 to-accent-500/30"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-dark-900/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                        <div>
                            @if($portfolio->category)
                                <span class="text-primary-400 text-sm font-medium">{{ $portfolio->category }}</span>
                            @endif
                            <h3 class="text-xl font-semibold text-white">{{ $portfolio->title }}</h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if(($visiblePages['portfolio'] ?? true))
            <div class="text-center mt-12">
                <a href="{{ route('portfolio', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-8 py-4 rounded-xl glass text-white font-semibold hover:bg-white/20 transition-all">
                    {{ __('frontend.view_all_projects') }}
                    <svg aria-hidden="true" class="w-5 h-5 ms-2 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</section>
@endif

<!-- Testimonials Preview -->
@if($testimonials->count() > 0)
<section class="py-24 relative">
    <div class="absolute inset-0">
        <div class="absolute bottom-0 {{ app()->isLocale('ar') ? 'left-1/4' : 'right-1/4' }} w-64 h-64 bg-accent-500/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16" data-animate="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('frontend.what_clients_say') }}</h2>
            <p class="text-white/60 max-w-2xl mx-auto">{{ __('frontend.clients_say_description') }}</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($testimonials as $index => $testimonial)
                <div class="glass rounded-2xl p-8" data-animate="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < $testimonial->rating; $i++)
                            <svg aria-hidden="true" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-white/80 mb-6 leading-relaxed">"{{ $testimonial->content }}"</p>
                    <div class="flex items-center gap-4">
                        @if($testimonial->client_photo_url)
                            <img loading="lazy" decoding="async" src="{{ $testimonial->client_photo_url }}" alt="{{ $testimonial->client_name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center">
                                <span class="text-white font-semibold">{{ mb_substr($testimonial->client_name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <p class="text-white font-semibold">{{ $testimonial->client_name }}</p>
                            <p class="text-white/50 text-sm">{{ $testimonial->client_position }}{{ $testimonial->client_company ? ', ' . $testimonial->client_company : '' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('frontend.partials.faq_teaser')

<!-- CTA Section -->
<section class="py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass rounded-3xl p-12 md:p-16 text-center relative overflow-hidden" data-animate="fade-up">
            <div class="absolute inset-0 gradient-primary opacity-10"></div>
            <div class="relative">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('frontend.ready_start_project') }}</h2>
                <p class="text-white/70 max-w-2xl mx-auto mb-8">{{ __('frontend.ready_start_project_description') }}</p>
                @if(($visiblePages['contact'] ?? true))
                    <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-8 py-4 rounded-xl gradient-primary text-white font-semibold hover:shadow-lg hover:shadow-primary-500/30 transform hover:-translate-y-1 transition-all duration-300">
                        {{ __('frontend.get_in_touch') }}
                        <svg aria-hidden="true" class="w-5 h-5 ms-2 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
