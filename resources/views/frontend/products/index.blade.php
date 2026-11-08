@extends('frontend.layouts.app')

@section('title', __('frontend.products') . ' | ' . ($settings->company_name ?? __('frontend.company_fallback')))
@section('meta_description', __('frontend.seo_products_description'))

@section('content')
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-1/4 {{ app()->isLocale('ar') ? 'left-0' : 'right-0' }} w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('frontend.our_products') }}</h1>
            <p class="text-white/60 max-w-2xl mx-auto">{{ __('frontend.products_intro') }}</p>
        </div>
    </section>

    <section class="pt-4 pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $index => $product)
                    @include('frontend.partials.product_card', ['product' => $product, 'index' => $index])
                @endforeach
            </div>
        </div>
    </section>
    @include('frontend.partials.system_finder')
    @include('frontend.partials.sectors')
@endsection
