@extends('frontend.layouts.app')

@section('title', $settings->company_name ?? __('frontend.home'))
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
            <div data-aos="fade-right">
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
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
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
            
            <div data-aos="fade-left" class="hidden lg:block">
                <div class="relative">
                    @if($hero->foreground_image_url ?? false)
                        <img src="{{ $hero->foreground_image_url }}" alt="{{ __('frontend.hero_image_alt') }}" class="relative z-10 animate-float">
                    @else
                        @include('frontend.partials.hero_illustration')
                    @endif


                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview -->
@if($services->count() > 0)
<section class="py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('frontend.our_services') }}</h2>
            <p class="text-white/60 max-w-2xl mx-auto">{{ __('frontend.services_description') }}</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $index => $service)
                <div class="glass global-card rounded-2xl p-8 hover-lift" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="global-card-icon w-14 h-14 rounded-xl gradient-primary flex items-center justify-center mb-6">
                        @include('frontend.partials.icon', ['icon' => $service->icon ?? 'code'])
                    </div>
                    <h3 class="global-card-title text-xl font-semibold text-white mb-3">{{ $service->title }}</h3>
                    <p class="global-card-copy text-white/60 mb-4">{{ Str::limit($service->description, 120) }}</p>
                    @if(($visiblePages['services'] ?? true))
                        <a href="{{ route('services', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center text-primary-400 hover:text-primary-300 font-medium">
                            {{ __('frontend.learn_more') }}
                            <svg class="w-4 h-4 ms-2 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

<!-- About Preview -->
@if($about)
<section class="py-24 relative">
    <div class="absolute inset-0">
        <div class="absolute top-1/2 {{ app()->isLocale('ar') ? 'right-1/4' : 'left-1/4' }} w-64 h-64 bg-primary-500/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                @if($about->image_url)
                    <img src="{{ $about->image_url }}" alt="{{ __('frontend.about_image_alt') }}" class="rounded-3xl shadow-2xl">
                @else
                    {{-- ── About Us illustration (no image uploaded) ── --}}
                    <div style="position:relative;width:100%;aspect-ratio:1/1;max-width:480px;margin:0 auto;isolation:isolate;">

                        {{-- Ambient glows --}}
                        <div style="position:absolute;top:10%;left:10%;width:200px;height:200px;background:radial-gradient(circle,rgba(26,46,109,.35),transparent 70%);border-radius:50%;filter:blur(50px);pointer-events:none;animation:ab-pulse 7s ease-in-out infinite;"></div>
                        <div style="position:absolute;bottom:10%;right:10%;width:180px;height:180px;background:radial-gradient(circle,rgba(228,25,31,.18),transparent 70%);border-radius:50%;filter:blur(45px);pointer-events:none;animation:ab-pulse 7s ease-in-out infinite;animation-delay:3.5s;"></div>

                        {{-- ── Central brand circle ── --}}
                        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:5;text-align:center;">
                            <div style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,#1A2E6D,#2a4fc8);border:3px solid rgba(228,25,31,.7);display:flex;align-items:center;justify-content:center;box-shadow:0 0 0 12px rgba(26,46,109,.12),0 0 50px rgba(26,46,109,.55),0 0 80px rgba(228,25,31,.12);margin:0 auto;">
                                {{-- Company / tech logo mark --}}
                                <svg width="50" height="50" viewBox="0 0 50 50" fill="none">
                                    <rect x="8" y="8" width="14" height="10" rx="2" fill="rgba(255,255,255,.15)" stroke="white" stroke-width="1.5"/>
                                    <rect x="28" y="8" width="14" height="10" rx="2" fill="rgba(255,255,255,.15)" stroke="white" stroke-width="1.5"/>
                                    <rect x="8" y="23" width="14" height="10" rx="2" fill="rgba(255,255,255,.15)" stroke="white" stroke-width="1.5"/>
                                    <rect x="28" y="23" width="14" height="10" rx="2" fill="#E4191F" stroke="#E4191F" stroke-width="1.5"/>
                                    <rect x="16" y="37" width="18" height="5" rx="1.5" fill="rgba(255,255,255,.3)"/>
                                    <line x1="25" y1="8" x2="25" y2="5" stroke="rgba(228,25,31,.6)" stroke-width="1.5" stroke-linecap="round"/>
                                    <circle cx="25" cy="4" r="2" fill="#E4191F"/>
                                </svg>
                            </div>
                            {{-- Outer orbit --}}
                            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:148px;height:148px;border-radius:50%;border:1px dashed rgba(228,25,31,.2);animation:ab-spin 22s linear infinite;pointer-events:none;">
                                <div style="position:absolute;top:-5px;left:50%;transform:translateX(-50%);width:10px;height:10px;border-radius:50%;background:#E4191F;box-shadow:0 0 12px #E4191F;"></div>
                            </div>
                        </div>

                        {{-- ── Service pillar: ERP (top-left) ── --}}
                        <div style="position:absolute;top:8%;left:3%;width:160px;z-index:4;animation:ab-float 7s ease-in-out infinite;">
                            <div style="background:rgba(8,18,52,.92);border:1px solid rgba(255,255,255,.1);border-radius:18px;padding:14px;backdrop-filter:blur(18px);box-shadow:0 8px 30px rgba(0,0,0,.45);">
                                <div style="display:flex;align-items:center;gap:9px;margin-bottom:10px;">
                                    <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#E4191F,#7a0a0d);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 3px 14px rgba(228,25,31,.4);">
                                        <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="6" height="5" rx="1"/><rect x="9" y="3" width="6" height="5" rx="1"/><rect x="16" y="3" width="6" height="5" rx="1"/><rect x="2" y="10" width="6" height="5" rx="1"/><rect x="9" y="10" width="6" height="5" rx="1"/><rect x="16" y="10" width="6" height="5" rx="1"/><rect x="2" y="17" width="6" height="4" rx="1"/><rect x="9" y="17" width="6" height="4" rx="1"/></svg>
                                    </div>
                                    <div>
                                        <p style="font-size:8.5px;color:rgba(255,255,255,.38);margin:0;text-transform:uppercase;letter-spacing:.5px;">{{ __('frontend.hero_erp_label') }}</p>
                                        <p style="font-size:12px;color:white;font-weight:700;margin:1px 0 0;">{{ __('frontend.hero_erp_title') }}</p>
                                    </div>
                                </div>
                                <div style="display:flex;align-items:flex-end;gap:3px;height:30px;">
                                    @foreach([40,62,35,78,50,88,65] as $bi => $bh)
                                    <div style="flex:1;height:{{$bh}}%;border-radius:2px 2px 0 0;background:{{ $bi===5 ? '#E4191F' : 'rgba(255,255,255,.18)' }};"></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- ── Service pillar: Cloud (top-right) ── --}}
                        <div style="position:absolute;top:8%;right:3%;width:160px;z-index:4;animation:ab-float 7s ease-in-out infinite;animation-delay:2.2s;">
                            <div style="background:rgba(8,18,52,.92);border:1px solid rgba(255,255,255,.1);border-radius:18px;padding:14px;backdrop-filter:blur(18px);box-shadow:0 8px 30px rgba(0,0,0,.45);">
                                <div style="display:flex;align-items:center;gap:9px;margin-bottom:10px;">
                                    <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#1A2E6D,#2d52be);border:1px solid rgba(59,111,212,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></svg>
                                    </div>
                                    <div>
                                        <p style="font-size:8.5px;color:rgba(255,255,255,.38);margin:0;text-transform:uppercase;letter-spacing:.5px;">{{ __('frontend.hero_cloud_label') }}</p>
                                        <p style="font-size:12px;color:white;font-weight:700;margin:1px 0 0;">{{ __('frontend.hero_cloud_title') }}</p>
                                    </div>
                                </div>
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <div>
                                        <p style="font-size:20px;color:white;font-weight:800;margin:0;line-height:1;">99.9%</p>
                                        <p style="font-size:8px;color:rgba(255,255,255,.35);margin:2px 0 0;">{{ __('frontend.hero_stat_uptime') }}</p>
                                    </div>
                                    <span style="display:flex;align-items:center;gap:3px;font-size:8.5px;color:#4ade80;font-weight:700;">
                                        <span style="display:flex;align-items:center;gap:3px;font-size:8.5px;color:#4ade80;font-weight:700;">
                                            <span style="width:6px;height:6px;border-radius:50%;background:#4ade80;display:inline-block;animation:ab-blink 1.5s ease-in-out infinite;"></span>
                                            {{ __('frontend.hero_cloud_operational') }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- ── Service pillar: Cyber (bottom-centre) ── --}}
                        <div style="position:absolute;bottom:6%;left:50%;transform:translateX(-50%);width:200px;z-index:4;animation:ab-float-cx 7s ease-in-out infinite;animation-delay:4.5s;">
                            <div style="background:rgba(8,18,52,.92);border:1px solid rgba(228,25,31,.18);border-radius:18px;padding:14px;backdrop-filter:blur(18px);box-shadow:0 8px 30px rgba(0,0,0,.45);">
                                <div style="display:flex;align-items:center;gap:9px;margin-bottom:10px;">
                                    <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#E4191F,#4a0508);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 3px 14px rgba(228,25,31,.4);">
                                        <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <div style="flex:1;">
                                        <p style="font-size:8.5px;color:rgba(255,255,255,.38);margin:0;text-transform:uppercase;letter-spacing:.5px;">{{ __('frontend.hero_cyber_label') }}</p>
                                        <p style="font-size:12px;color:white;font-weight:700;margin:1px 0 0;">{{ __('frontend.hero_cyber_title') }}</p>
                                    </div>
                                    <div style="background:rgba(74,222,128,.12);border:1px solid rgba(74,222,128,.3);border-radius:6px;padding:2px 7px;">
                                        <span style="font-size:8px;color:#4ade80;font-weight:800;">{{ __('frontend.hero_cyber_status') }}</span>
                                    </div>
                                </div>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                                    <div style="text-align:center;padding:6px;background:rgba(255,255,255,.04);border-radius:8px;">
                                        <p style="font-size:15px;font-weight:800;color:#4ade80;margin:0;">1,248</p>
                                        <p style="font-size:7.5px;color:rgba(255,255,255,.3);margin:2px 0 0;">{{ __('frontend.hero_cyber_threats') }}</p>
                                    </div>
                                    <div style="text-align:center;padding:6px;background:rgba(255,255,255,.04);border-radius:8px;">
                                        <p style="font-size:15px;font-weight:800;color:#facc15;margin:0;">847</p>
                                        <p style="font-size:7.5px;color:rgba(255,255,255,.3);margin:2px 0 0;">{{ __('frontend.hero_cyber_rules') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── Connector SVG ── --}}
                        <svg style="position:absolute;inset:0;width:100%;height:100%;overflow:visible;pointer-events:none;z-index:2;" viewBox="0 0 480 480" preserveAspectRatio="none">
                            <line x1="160" y1="130" x2="240" y2="240" stroke="rgba(228,25,31,.3)" stroke-width="1.2" stroke-dasharray="5 4"><animate attributeName="stroke-dashoffset" from="0" to="-18" dur="2s" repeatCount="indefinite"/></line>
                            <line x1="320" y1="130" x2="240" y2="240" stroke="rgba(59,111,212,.35)" stroke-width="1.2" stroke-dasharray="5 4"><animate attributeName="stroke-dashoffset" from="0" to="-18" dur="2.5s" repeatCount="indefinite"/></line>
                            <line x1="240" y1="390" x2="240" y2="270" stroke="rgba(228,25,31,.28)" stroke-width="1.2" stroke-dasharray="5 4"><animate attributeName="stroke-dashoffset" from="0" to="-18" dur="3s" repeatCount="indefinite"/></line>
                        </svg>

                        {{-- ── Keyframes ── --}}
                        <style>
                            @keyframes ab-pulse    { 0%,100%{opacity:.3;transform:scale(1)} 50%{opacity:.65;transform:scale(1.08)} }
                            @keyframes ab-spin     { from{transform:translate(-50%,-50%) rotate(0deg)} to{transform:translate(-50%,-50%) rotate(360deg)} }
                            @keyframes ab-float    { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
                            @keyframes ab-float-cx { 0%,100%{transform:translateX(-50%) translateY(0)} 50%{transform:translateX(-50%) translateY(-8px)} }
                            @keyframes ab-blink    { 0%,100%{opacity:1} 50%{opacity:.15} }
                        </style>
                    </div>
                @endif
            </div>
            
            <div data-aos="fade-left">
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
                        <svg class="w-5 h-5 ms-2 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('frontend.featured_work') }}</h2>
            <p class="text-white/60 max-w-2xl mx-auto">{{ __('frontend.featured_work_description') }}</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($portfolios as $index => $portfolio)
                <div class="group relative overflow-hidden rounded-2xl" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    @if($portfolio->image_url)
                        <img src="{{ $portfolio->image_url }}" alt="{{ $portfolio->title }}" class="w-full h-72 object-cover transition-transform duration-500 group-hover:scale-110">
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
                    <svg class="w-5 h-5 ms-2 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('frontend.what_clients_say') }}</h2>
            <p class="text-white/60 max-w-2xl mx-auto">{{ __('frontend.clients_say_description') }}</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($testimonials as $index => $testimonial)
                <div class="glass rounded-2xl p-8" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < $testimonial->rating; $i++)
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-white/80 mb-6 leading-relaxed">"{{ $testimonial->content }}"</p>
                    <div class="flex items-center gap-4">
                        @if($testimonial->client_photo_url)
                            <img src="{{ $testimonial->client_photo_url }}" alt="{{ $testimonial->client_name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center">
                                <span class="text-white font-semibold">{{ substr($testimonial->client_name, 0, 1) }}</span>
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

<!-- CTA Section -->
<section class="py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass rounded-3xl p-12 md:p-16 text-center relative overflow-hidden" data-aos="fade-up">
            <div class="absolute inset-0 gradient-primary opacity-10"></div>
            <div class="relative">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('frontend.ready_start_project') }}</h2>
                <p class="text-white/70 max-w-2xl mx-auto mb-8">{{ __('frontend.ready_start_project_description') }}</p>
                @if(($visiblePages['contact'] ?? true))
                    <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-8 py-4 rounded-xl gradient-primary text-white font-semibold hover:shadow-lg hover:shadow-primary-500/30 transform hover:-translate-y-1 transition-all duration-300">
                        {{ __('frontend.get_in_touch') }}
                        <svg class="w-5 h-5 ms-2 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
