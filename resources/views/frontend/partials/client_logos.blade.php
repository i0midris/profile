{{-- "Trusted by" strip — only rendered when client logos are published from the dashboard (plan step 3.2). --}}
@if(isset($clientLogos) && $clientLogos->count())
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-center text-2xl md:text-3xl font-bold text-white mb-10" data-animate="fade-up">{{ __('frontend.trusted_by') }}</h2>
        <ul class="flex flex-wrap items-center justify-center gap-4 sm:gap-6">
            @foreach($clientLogos as $client)
                <li>
                    @if($client->website_url)
                        <a href="{{ $client->website_url }}" target="_blank" rel="noopener noreferrer" class="client-logo-tile" title="{{ $client->name }}">
                    @else
                        <span class="client-logo-tile" title="{{ $client->name }}">
                    @endif
                        <img loading="lazy" decoding="async" src="{{ $client->logo_url }}" alt="{{ $client->name }}" class="max-h-12 max-w-[120px] object-contain">
                    @if($client->website_url)</a>@else</span>@endif
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif
