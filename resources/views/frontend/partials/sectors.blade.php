{{--
    "Solutions for your sector" — animated workplace scenes with the systems that fit each sector.
    Product chips link to the product page when that product is published. "Request a demo for your
    sector" opens the demo form already filled with the sector and its main system (step 6.3).
--}}
@php
    $sectorList = [
        ['scene' => 'restaurant',   'products' => ['pos-system', 'erp-accounting']],
        ['scene' => 'pharmacy',     'products' => ['pos-system', 'erp-accounting']],
        ['scene' => 'supermarket',  'products' => ['pos-system', 'e-commerce']],
        ['scene' => 'warehouse',    'products' => ['erp-accounting', 'sales-rep-app']],
        ['scene' => 'school',       'products' => ['hr-payroll', 'erp-accounting']],
        ['scene' => 'distribution', 'products' => ['sales-rep-app', 'erp-accounting']],
    ];
    $publishedProducts = ($visiblePages['products'] ?? false)
        ? \App\Models\Product::published()->get(['id', 'slug', 'name_ar', 'name_en'])->keyBy('slug')
        : collect();
@endphp
<section class="py-24 relative overflow-hidden" id="sectors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-14" data-animate="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('frontend.sectors_title') }}</h2>
            <p class="text-white/60 max-w-2xl mx-auto">{{ __('frontend.sectors_intro') }}</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7">
            @foreach($sectorList as $i => $sector)
                @php
                    $key = $sector['scene'];
                    $firstProduct = collect($sector['products'])->map(fn ($slug) => $publishedProducts->get($slug))->filter()->first();
                    $demoUrl = route('demo', ['locale' => app()->getLocale(), 'sector' => $key, 'product' => $sector['products'][0]]);
                    $productUrl = fn ($product) => route('products.show', ['locale' => app()->getLocale(), 'product' => $product, 'sector' => $key]);
                @endphp
                <article class="sector-card glass global-card rounded-2xl overflow-hidden flex flex-col" data-animate="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
                    <div class="sector-scene-wrap">
                        @include('frontend.partials.sector_scenes', ['scene' => $key])
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="global-card-title text-xl font-semibold text-white mb-2">{{ __('frontend.sector_' . $key . '_title') }}</h3>
                        <p class="global-card-copy text-white/65 text-sm leading-relaxed mb-4">{{ __('frontend.sector_' . $key . '_text') }}</p>
                        <div class="flex flex-wrap gap-2 mb-5">
                            @foreach($sector['products'] as $slug)
                                @if($product = $publishedProducts->get($slug))
                                    <a href="{{ $productUrl($product) }}"
                                        class="px-3 py-1 rounded-full text-xs bg-white/5 border border-white/10 text-white/80 hover:text-white hover:border-primary-400 transition-colors">{{ $product->localized('name') }}</a>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs bg-white/5 border border-white/10 text-white/80">{{ __('frontend.system_' . str_replace('-', '_', $slug)) }}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-auto flex flex-wrap items-center justify-between gap-3">
                            <a href="{{ $demoUrl }}" class="px-4 py-2.5 rounded-xl gradient-primary text-white text-sm font-semibold hover:shadow-lg hover:shadow-primary-500/30 transition-all">{{ __('frontend.sector_cta_demo') }}</a>
                            @if($firstProduct)
                                <a href="{{ $productUrl($firstProduct) }}" class="inline-flex items-center text-primary-400 hover:text-primary-300 font-medium text-sm">
                                    {{ __('frontend.sector_cta_product') }}
                                    <svg aria-hidden="true" class="w-4 h-4 ms-1.5 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
