<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
      data-theme-label-system="{{ __('frontend.system_mode') }}"
      data-theme-label-light="{{ __('frontend.light_mode') }}"
      data-theme-label-dark="{{ __('frontend.dark_mode') }}">

<head>
    @php
        $route = request()->route();
        $routeName = $route?->getName();
        $currentLocale = app()->getLocale();
        $frontendRouteNames = ['home', 'about', 'services', 'team', 'jobs', 'portfolio', 'testimonials', 'contact'];

        $canonicalUrl = url()->current();
        $alternateEnUrl = null;
        $alternateArUrl = null;

        if ($routeName && in_array($routeName, $frontendRouteNames, true)) {
            $params = $route->parameters();
            $alternateEnUrl = route($routeName, array_merge($params, ['locale' => 'en']));
            $alternateArUrl = route($routeName, array_merge($params, ['locale' => 'ar']));
            $canonicalUrl = $currentLocale === 'ar' ? $alternateArUrl : $alternateEnUrl;
        }

        $defaultTitle = $settings->company_name ?? config('app.name');
        $pageTitle = trim($__env->yieldContent('title', $defaultTitle));

        $defaultDescription = $settings->description ?? __('frontend.meta_description_fallback');
        $pageDescription = trim($__env->yieldContent('meta_description', $defaultDescription));
        $visiblePages = $visiblePages ?? $settings->getVisiblePages();
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="ahrefs-site-verification" content="8612ba2161fb0cb28e8b4941a5842b4ebb4e8dc31ede567922fc151640f311c7">
    <title>{{ $pageTitle }}</title>

    <link rel="canonical" href="{{ $canonicalUrl }}">
    @if($alternateEnUrl && $alternateArUrl)
        <link rel="alternate" hreflang="en" href="{{ $alternateEnUrl }}">
        <link rel="alternate" hreflang="ar" href="{{ $alternateArUrl }}">
        <link rel="alternate" hreflang="x-default" href="{{ $alternateArUrl }}">
    @endif

    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:locale" content="{{ $currentLocale === 'ar' ? 'ar_SA' : 'en_US' }}">
    <meta property="og:locale:alternate" content="{{ $currentLocale === 'ar' ? 'en_US' : 'ar_SA' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">

    @if($settings->favicon_url ?? false)
        <link rel="icon" href="{{ $settings->favicon_url }}" type="image/x-icon">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col overflow-x-hidden">
    <!-- Navigation -->
    <nav class="glass-dark fixed top-0 left-0 right-0 z-50" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo -->
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="flex items-center gap-2 sm:gap-3 min-w-0 flex-shrink">
                    @if($settings->logo_url ?? false)
                        <img src="{{ $settings->logo_url }}" alt="{{ $settings->company_name }}"
                            class="h-8 sm:h-10 flex-shrink-0">
                    @else
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl gradient-primary flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    @endif
                    <span
                        class="text-white font-bold text-lg sm:text-xl truncate">{{ $settings->company_name ?? __('frontend.company_fallback') }}</span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home', ['locale' => app()->getLocale()]) }}"
                        class="text-white/80 hover:text-white transition-colors {{ request()->routeIs('home') ? 'text-white font-medium' : '' }}">{{ __('frontend.home') }}</a>
                    @if($visiblePages['about'] ?? true)
                        <a href="{{ route('about', ['locale' => app()->getLocale()]) }}"
                            class="text-white/80 hover:text-white transition-colors {{ request()->routeIs('about') ? 'text-white font-medium' : '' }}">{{ __('frontend.about') }}</a>
                    @endif
                    @if($visiblePages['services'] ?? true)
                        <a href="{{ route('services', ['locale' => app()->getLocale()]) }}"
                            class="text-white/80 hover:text-white transition-colors {{ request()->routeIs('services') ? 'text-white font-medium' : '' }}">{{ __('frontend.services') }}</a>
                    @endif
                    @if($visiblePages['team'] ?? true)
                        <a href="{{ route('team', ['locale' => app()->getLocale()]) }}"
                            class="text-white/80 hover:text-white transition-colors {{ request()->routeIs('team') ? 'text-white font-medium' : '' }}">{{ __('frontend.team') }}</a>
                    @endif
                    @if($visiblePages['jobs'] ?? true)
                        <a href="{{ route('jobs', ['locale' => app()->getLocale()]) }}"
                            class="text-white/80 hover:text-white transition-colors {{ request()->routeIs('jobs') ? 'text-white font-medium' : '' }}">{{ __('frontend.jobs') }}</a>
                    @endif
                    @if($visiblePages['portfolio'] ?? true)
                        <a href="{{ route('portfolio', ['locale' => app()->getLocale()]) }}"
                            class="text-white/80 hover:text-white transition-colors {{ request()->routeIs('portfolio') ? 'text-white font-medium' : '' }}">{{ __('frontend.portfolio') }}</a>
                    @endif
                    @if($visiblePages['testimonials'] ?? true)
                        <a href="{{ route('testimonials', ['locale' => app()->getLocale()]) }}"
                            class="text-white/80 hover:text-white transition-colors {{ request()->routeIs('testimonials') ? 'text-white font-medium' : '' }}">{{ __('frontend.testimonials') }}</a>
                    @endif
                    @if($visiblePages['contact'] ?? true)
                        <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}"
                            class="px-5 py-2.5 rounded-xl gradient-primary text-white font-medium hover:shadow-lg hover:shadow-primary-500/30 transition-all">{{ __('frontend.contact') }}</a>
                    @endif
                </div>

                <!-- Language Switcher + Theme Selector -->
                <div class="hidden md:flex items-center gap-2.5 flex-shrink-0">
                    <div class="nav-control-shell" role="group" aria-label="Language switcher">
                        <button type="button" data-locale-switch="en"
                                class="nav-pill-button {{ app()->getLocale() === 'en' ? 'is-active' : '' }}">
                            {{ __('frontend.language_en') }}
                        </button>
                        <button type="button" data-locale-switch="ar"
                                class="nav-pill-button {{ app()->getLocale() === 'ar' ? 'is-active' : '' }}">
                            {{ __('frontend.language_ar') }}
                        </button>
                    </div>
                    <div class="nav-control-shell nav-theme-dropdown"
                         x-data="{ menuOpen: false }"
                         @click.outside="menuOpen = false"
                         @keydown.escape.window="menuOpen = false">
                        <button type="button"
                                class="nav-theme-trigger"
                                @click="menuOpen = !menuOpen"
                                :aria-expanded="menuOpen.toString()"
                                aria-haspopup="menu">
                            <span class="nav-theme-icon" aria-hidden="true">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M21 12.79A9 9 0 1111.21 3c0 0 0 0 0 0A7 7 0 0021 12.79z" />
                                </svg>
                            </span>
                            <span class="nav-theme-current" data-theme-current>{{ __('frontend.system_mode') }}</span>
                            <svg class="nav-theme-chevron" :class="{ 'rotate-180': menuOpen }" viewBox="0 0 12 12" fill="none"
                                 aria-hidden="true">
                                <path d="M2.25 4.5L6 8.25L9.75 4.5" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <div x-cloak
                             x-show="menuOpen"
                             x-transition.origin.top.right
                             class="nav-theme-menu"
                             role="menu">
                            <button type="button" class="nav-theme-option" data-theme-option="system" role="menuitemradio"
                                    aria-checked="false" @click="menuOpen = false">
                                {{ __('frontend.system_mode') }}
                            </button>
                            <button type="button" class="nav-theme-option" data-theme-option="light" role="menuitemradio"
                                    aria-checked="false" @click="menuOpen = false">
                                {{ __('frontend.light_mode') }}
                            </button>
                            <button type="button" class="nav-theme-option" data-theme-option="dark" role="menuitemradio"
                                    aria-checked="false" @click="menuOpen = false">
                                {{ __('frontend.dark_mode') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Language Switcher + Theme Dropdown -->
                <div class="md:hidden me-2 flex items-center gap-1">
                    <div class="nav-control-shell p-0.5 rounded-lg" role="group" aria-label="Language switcher">
                        <button type="button" data-locale-switch="en"
                                class="nav-pill-button text-xs px-2 py-1 {{ app()->getLocale() === 'en' ? 'is-active' : '' }}">
                            {{ __('frontend.language_en') }}
                        </button>
                        <button type="button" data-locale-switch="ar"
                                class="nav-pill-button text-xs px-2 py-1 {{ app()->getLocale() === 'ar' ? 'is-active' : '' }}">
                            {{ __('frontend.language_ar') }}
                        </button>
                    </div>
                    <div class="nav-control-shell nav-theme-dropdown nav-theme-dropdown-compact"
                         x-data="{ menuOpen: false }"
                         @click.outside="menuOpen = false"
                         @keydown.escape.window="menuOpen = false">
                        <button type="button"
                                class="nav-theme-trigger"
                                @click="menuOpen = !menuOpen"
                                :aria-expanded="menuOpen.toString()"
                                aria-haspopup="menu">
                            <span class="nav-theme-icon" aria-hidden="true">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M21 12.79A9 9 0 1111.21 3c0 0 0 0 0 0A7 7 0 0021 12.79z" />
                                </svg>
                            </span>
                            <span class="nav-theme-current" data-theme-current>{{ __('frontend.system_mode') }}</span>
                            <svg class="nav-theme-chevron" :class="{ 'rotate-180': menuOpen }" viewBox="0 0 12 12" fill="none"
                                 aria-hidden="true">
                                <path d="M2.25 4.5L6 8.25L9.75 4.5" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <div x-cloak
                             x-show="menuOpen"
                             x-transition.origin.top.right
                             class="nav-theme-menu"
                             role="menu">
                            <button type="button" class="nav-theme-option" data-theme-option="system" role="menuitemradio"
                                    aria-checked="false" @click="menuOpen = false">
                                {{ __('frontend.system_mode') }}
                            </button>
                            <button type="button" class="nav-theme-option" data-theme-option="light" role="menuitemradio"
                                    aria-checked="false" @click="menuOpen = false">
                                {{ __('frontend.light_mode') }}
                            </button>
                            <button type="button" class="nav-theme-option" data-theme-option="dark" role="menuitemradio"
                                    aria-checked="false" @click="menuOpen = false">
                                {{ __('frontend.dark_mode') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="md:hidden p-2 text-white flex-shrink-0">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <div x-show="open" x-transition class="md:hidden pb-4">
                <div class="flex flex-col gap-2">
                    <a href="{{ route('home', ['locale' => app()->getLocale()]) }}"
                        class="px-4 py-2 rounded-lg text-white/80 hover:text-white hover:bg-white/5 transition-colors">{{ __('frontend.home') }}</a>
                    @if($visiblePages['about'] ?? true)
                        <a href="{{ route('about', ['locale' => app()->getLocale()]) }}"
                            class="px-4 py-2 rounded-lg text-white/80 hover:text-white hover:bg-white/5 transition-colors">{{ __('frontend.about') }}</a>
                    @endif
                    @if($visiblePages['services'] ?? true)
                        <a href="{{ route('services', ['locale' => app()->getLocale()]) }}"
                            class="px-4 py-2 rounded-lg text-white/80 hover:text-white hover:bg-white/5 transition-colors">{{ __('frontend.services') }}</a>
                    @endif
                    @if($visiblePages['team'] ?? true)
                        <a href="{{ route('team', ['locale' => app()->getLocale()]) }}"
                            class="px-4 py-2 rounded-lg text-white/80 hover:text-white hover:bg-white/5 transition-colors">{{ __('frontend.team') }}</a>
                    @endif
                    @if($visiblePages['jobs'] ?? true)
                        <a href="{{ route('jobs', ['locale' => app()->getLocale()]) }}"
                            class="px-4 py-2 rounded-lg text-white/80 hover:text-white hover:bg-white/5 transition-colors">{{ __('frontend.jobs') }}</a>
                    @endif
                    @if($visiblePages['portfolio'] ?? true)
                        <a href="{{ route('portfolio', ['locale' => app()->getLocale()]) }}"
                            class="px-4 py-2 rounded-lg text-white/80 hover:text-white hover:bg-white/5 transition-colors">{{ __('frontend.portfolio') }}</a>
                    @endif
                    @if($visiblePages['testimonials'] ?? true)
                        <a href="{{ route('testimonials', ['locale' => app()->getLocale()]) }}"
                            class="px-4 py-2 rounded-lg text-white/80 hover:text-white hover:bg-white/5 transition-colors">{{ __('frontend.testimonials') }}</a>
                    @endif
                    @if($visiblePages['contact'] ?? true)
                        <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}"
                            class="px-4 py-2 rounded-lg gradient-primary text-white text-center mt-2">{{ __('frontend.contact') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 pt-16 sm:pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer mt-auto">
        <div class="site-footer-glow" aria-hidden="true"></div>
        <div class="site-footer-inner max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-6 sm:pt-16 sm:pb-7">
            <div class="site-footer-grid grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-6">
                <!-- Company Info -->
                <div class="site-footer-block site-footer-brand md:col-span-2">
                    @php
                        $footerWhatsappNumber = $settings->whatsapp ?: $settings->phone;
                        $footerWhatsappDigits = $footerWhatsappNumber ? preg_replace('/\D+/', '', $footerWhatsappNumber) : null;
                        $footerWhatsappUrl = $footerWhatsappDigits ? 'https://wa.me/' . $footerWhatsappDigits : null;
                    @endphp

                    <div class="flex items-center gap-3 mb-4">
                        @if($settings->logo_url ?? false)
                            <img src="{{ $settings->logo_url }}" alt="{{ $settings->company_name }}" class="h-8">
                        @endif
                        <span class="site-footer-brand-name">{{ $settings->company_name ?? __('frontend.company_fallback') }}</span>
                    </div>
                    <p class="site-footer-description">{{ $settings->description ?? '' }}</p>

                    <!-- Social Links -->
                    <div class="site-footer-social flex items-center gap-3.5">
                        @if($footerWhatsappUrl)
                            <a href="{{ $footerWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="site-footer-social-link" aria-label="{{ __('frontend.whatsapp') }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                    <path
                                        d="M13.601 2.326A7.854 7.854 0 0 0 8.01 0C3.65 0 .095 3.55.094 7.91c0 1.394.365 2.753 1.058 3.95L0 16l4.203-1.102a7.938 7.938 0 0 0 3.806.98h.003c4.36 0 7.916-3.55 7.917-7.91a7.85 7.85 0 0 0-2.328-5.642ZM8.013 14.6h-.003a6.65 6.65 0 0 1-3.394-.93l-.243-.145-2.493.654.666-2.433-.158-.25a6.61 6.61 0 0 1-1.013-3.542c.002-3.655 2.98-6.63 6.64-6.63a6.6 6.6 0 0 1 4.715 1.958 6.6 6.6 0 0 1 1.944 4.72c-.001 3.656-2.98 6.63-6.64 6.63Z" />
                                    <path
                                        d="M11.67 9.756c-.2-.1-1.173-.578-1.354-.644-.18-.067-.312-.1-.444.1-.132.2-.51.644-.625.778-.115.133-.23.15-.428.05-.2-.1-.842-.31-1.603-.987-.592-.527-.992-1.178-1.108-1.378-.115-.2-.012-.307.087-.406.09-.09.2-.233.297-.35.1-.116.133-.2.2-.333.066-.133.033-.25-.017-.35-.05-.1-.444-1.066-.608-1.46-.16-.383-.323-.33-.444-.336l-.378-.006c-.133 0-.35.05-.534.25s-.7.683-.7 1.666.716 1.934.816 2.067c.1.133 1.41 2.15 3.416 3.015.477.206.85.329 1.14.42.479.152.915.13 1.26.079.384-.058 1.173-.478 1.338-.939.166-.462.166-.856.116-.939-.05-.083-.182-.133-.38-.233Z" />
                                </svg>
                            </a>
                        @endif
                        @if($settings->facebook ?? false)
                            <a href="{{ $settings->facebook }}" target="_blank" class="site-footer-social-link" aria-label="Facebook">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                        @endif
                        @if($settings->twitter ?? false)
                            <a href="{{ $settings->twitter }}" target="_blank" class="site-footer-social-link" aria-label="X">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                </svg>
                            </a>
                        @endif
                        @if($settings->instagram ?? false)
                            <a href="{{ $settings->instagram }}" target="_blank" class="site-footer-social-link" aria-label="Instagram">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" />
                                </svg>
                            </a>
                        @endif
                        @if($settings->linkedin ?? false)
                            <a href="{{ $settings->linkedin }}" target="_blank" class="site-footer-social-link" aria-label="LinkedIn">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="site-footer-block">
                    <h3 class="site-footer-heading">{{ __('frontend.quick_links') }}</h3>
                    <ul class="site-footer-links space-y-2.5">
                        <li><a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="site-footer-link">{{ __('frontend.home') }}</a></li>
                        @if($visiblePages['about'] ?? true)
                            <li><a href="{{ route('about', ['locale' => app()->getLocale()]) }}" class="site-footer-link">{{ __('frontend.about') }}</a></li>
                        @endif
                        @if($visiblePages['services'] ?? true)
                            <li><a href="{{ route('services', ['locale' => app()->getLocale()]) }}" class="site-footer-link">{{ __('frontend.services') }}</a></li>
                        @endif
                        @if($visiblePages['jobs'] ?? true)
                            <li><a href="{{ route('jobs', ['locale' => app()->getLocale()]) }}" class="site-footer-link">{{ __('frontend.jobs') }}</a></li>
                        @endif
                        @if($visiblePages['portfolio'] ?? true)
                            <li><a href="{{ route('portfolio', ['locale' => app()->getLocale()]) }}" class="site-footer-link">{{ __('frontend.portfolio') }}</a></li>
                        @endif
                        @if($visiblePages['testimonials'] ?? true)
                            <li><a href="{{ route('testimonials', ['locale' => app()->getLocale()]) }}" class="site-footer-link">{{ __('frontend.testimonials') }}</a></li>
                        @endif
                        @if($visiblePages['contact'] ?? true)
                            <li><a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="site-footer-link">{{ __('frontend.contact') }}</a></li>
                        @endif
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="site-footer-block">
                    <h3 class="site-footer-heading">{{ __('frontend.contact') }}</h3>
                    <ul class="site-footer-contact space-y-3">
                        @if($settings->email ?? false)
                            <li class="site-footer-contact-item">
                                <span class="site-footer-contact-icon" aria-hidden="true">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <a href="mailto:{{ $settings->email }}" class="site-footer-contact-link">{{ $settings->email }}</a>
                            </li>
                        @endif
                        @if($settings->phone ?? false)
                            <li class="site-footer-contact-item">
                                <span class="site-footer-contact-icon" aria-hidden="true">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </span>
                                <span class="site-footer-muted">{{ $settings->phone }}</span>
                            </li>
                        @endif
                        @if($settings->address ?? false)
                            <li class="site-footer-contact-item">
                                <span class="site-footer-contact-icon mt-0.5" aria-hidden="true">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </span>
                                <span class="site-footer-muted">{{ $settings->address }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="site-footer-bottom mt-10 pt-6 text-center text-sm">
                © {{ date('Y') }} {{ $settings->company_name ?? __('frontend.company_fallback') }}. {{ __('frontend.all_rights_reserved') }}
            </div>
        </div>
    </footer>
</body>

</html>
