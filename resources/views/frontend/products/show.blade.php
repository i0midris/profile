@extends('frontend.layouts.app')

@section('title', $product->name . ' | ' . ($settings->company_name ?? __('frontend.company_fallback')))
@section('meta_description', Str::limit($product->tagline ?: $product->description, 160))
@if($product->cover_image_url)
    @section('og_image', $product->cover_image_url)
@endif

@section('content')
    @php
        $whatsappUrl = \App\Support\PhoneNumber::whatsappUrl($settings->whatsapp ?: $settings->phone);
        $whatsappLink = $whatsappUrl ? $whatsappUrl . '?text=' . rawurlencode(__('frontend.whatsapp_product_message', ['product' => $product->name])) : null;
        $demoUrl = Route::has('demo')
            ? route('demo', ['locale' => app()->getLocale(), 'product' => $product->slug])
            : route('contact', ['locale' => app()->getLocale()]);
        $productSchema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            'name' => $product->name,
            'description' => $product->description,
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Windows, Web, Android, iOS',
            'image' => $product->cover_image_url ?: ($product->screenshot_urls[0] ?? null),
            'url' => url()->current(),
            'publisher' => ['@type' => 'Organization', 'name' => $settings->company_name, 'url' => url('/')],
        ]);
    @endphp
    <script type="application/ld+json">{!! json_encode($productSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>

    {{-- Header --}}
    <section class="pt-20 pb-16 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-1/4 {{ app()->isLocale('ar') ? '-right-20' : '-left-20' }} w-96 h-96 bg-primary-500/15 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <nav class="text-sm text-white/60 mb-6" aria-label="breadcrumb">
                <a href="{{ route('products', ['locale' => app()->getLocale()]) }}" class="hover:text-white">{{ __('frontend.products') }}</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-white/80">{{ $product->name }}</span>
            </nav>
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-[2rem] leading-snug sm:text-4xl md:text-5xl font-bold text-white mb-4 break-words">{{ $product->name }}</h1>
                    @if($product->tagline)
                        <p class="text-xl text-white/80 mb-4">{{ $product->tagline }}</p>
                    @endif
                    <p class="text-white/70 leading-relaxed mb-8 whitespace-pre-line">{{ $product->description }}</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ $demoUrl }}" class="px-8 py-4 rounded-xl gradient-primary text-white font-semibold hover:shadow-lg hover:shadow-primary-500/30 transition-all">
                            {{ __('frontend.request_demo') }}
                        </a>
                        @if($whatsappLink)
                            <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer" data-track="whatsapp_click"
                                class="px-8 py-4 rounded-xl glass text-white font-semibold hover:bg-white/20 transition-all inline-flex items-center gap-2">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 8.01 0C3.65 0 .095 3.55.094 7.91c0 1.394.365 2.753 1.058 3.95L0 16l4.203-1.102a7.938 7.938 0 0 0 3.806.98h.003c4.36 0 7.916-3.55 7.917-7.91a7.85 7.85 0 0 0-2.328-5.642Z"/></svg>
                                {{ __('frontend.ask_on_whatsapp') }}
                            </a>
                        @endif
                    </div>
                </div>
                @if($product->cover_image_url)
                    <img fetchpriority="high" src="{{ $product->cover_image_url }}" alt="{{ $product->name }}" class="rounded-3xl shadow-2xl w-full">
                @endif
            </div>
        </div>
    </section>

    {{-- Features & sectors --}}
    @if(count($product->features) || count($product->sectors))
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-3 gap-8">
                @if(count($product->features))
                    <div class="lg:col-span-2 glass rounded-2xl p-8">
                        <h2 class="text-2xl font-bold text-white mb-6">{{ __('frontend.product_features') }}</h2>
                        <ul class="grid sm:grid-cols-2 gap-4">
                            @foreach($product->features as $feature)
                                <li class="flex items-start gap-3 text-white/80">
                                    <svg aria-hidden="true" class="w-5 h-5 mt-0.5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(count($product->sectors))
                    <div class="glass rounded-2xl p-8 {{ count($product->features) ? '' : 'lg:col-span-3' }}">
                        <h2 class="text-2xl font-bold text-white mb-6">{{ __('frontend.product_sectors') }}</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->sectors as $sector)
                                <span class="px-3 py-1.5 rounded-full text-sm bg-white/5 border border-white/10 text-white/80">{{ $sector }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- Screenshots with a simple lightbox --}}
    @if(count($product->screenshot_urls))
        <section class="py-16" x-data="{ shown: null }" @keydown.escape.window="shown = null">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-8">{{ __('frontend.product_screenshots') }}</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($product->screenshot_urls as $i => $url)
                        <button type="button" @click="shown = @js($url)" class="block rounded-2xl overflow-hidden glass hover-lift"
                            aria-label="{{ __('frontend.product_screenshots') }} {{ $i + 1 }}">
                            <img loading="lazy" decoding="async" src="{{ $url }}" alt="{{ $product->name }} — {{ $i + 1 }}" class="w-full h-56 object-cover object-top">
                        </button>
                    @endforeach
                </div>
            </div>
            <div x-cloak x-show="shown" x-transition.opacity @click="shown = null"
                class="fixed inset-0 z-[60] bg-black/85 flex items-center justify-center p-4" role="dialog" aria-modal="true">
                <template x-if="shown"><img :src="shown" alt="{{ $product->name }}" class="max-h-[90vh] max-w-full rounded-xl shadow-2xl"></template>
                <button type="button" class="absolute top-4 end-4 text-white text-3xl leading-none" aria-label="{{ __('frontend.close') }}">&times;</button>
            </div>
        </section>
    @endif

    {{-- Other products --}}
    @if($related->count())
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-8">{{ __('frontend.our_products') }}</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($related as $index => $other)
                        @include('frontend.partials.product_card', ['product' => $other, 'index' => $index])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
