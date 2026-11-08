@extends('frontend.layouts.app')

@section('title', __('frontend.blog') . ' | ' . ($settings->company_name ?? __('frontend.company_fallback')))
@section('meta_description', __('frontend.blog_intro'))

@section('content')
    <section class="py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('frontend.blog') }}</h1>
            <p class="text-white/60 max-w-2xl mx-auto">{{ __('frontend.blog_intro') }}</p>
        </div>
    </section>

    <section class="pt-4 pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $index => $post)
                    @include('frontend.partials.post_card', ['post' => $post, 'index' => $index])
                @endforeach
            </div>
            @if($posts->hasPages())
                <div class="mt-12">{{ $posts->links() }}</div>
            @endif
        </div>
    </section>
@endsection
