{{-- Product card used on the products page and the home page. --}}
<a href="{{ route('products.show', ['locale' => app()->getLocale(), $product]) }}"
    class="glass global-card rounded-2xl overflow-hidden h-full flex flex-col hover-lift group" data-animate="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
    @if($product->cover_image_url)
        <img loading="lazy" decoding="async" src="{{ $product->cover_image_url }}" alt="{{ $product->name }}"
            class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105">
    @else
        <div class="w-full h-48 bg-gradient-to-br from-primary-500/30 to-accent-500/30 flex items-center justify-center">
            <span class="text-white/80 text-2xl font-bold px-6 text-center">{{ $product->name }}</span>
        </div>
    @endif
    <div class="p-6 flex-1 flex flex-col">
        <h3 class="global-card-title text-xl font-semibold text-white mb-2">{{ $product->name }}</h3>
        @if($product->tagline)
            <p class="global-card-copy text-white/60 mb-4 leading-relaxed">{{ $product->tagline }}</p>
        @endif
        @if(count($product->sectors))
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach(array_slice($product->sectors, 0, 3) as $sector)
                    <span class="px-2.5 py-1 rounded-full text-xs bg-white/5 border border-white/10 text-white/70">{{ $sector }}</span>
                @endforeach
            </div>
        @endif
        <span class="mt-auto inline-flex items-center text-primary-400 font-medium">
            {{ __('frontend.view_product') }}
            <svg aria-hidden="true" class="w-4 h-4 ms-2 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </span>
    </div>
</a>
