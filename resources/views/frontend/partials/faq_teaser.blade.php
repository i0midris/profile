{{-- Short FAQ teaser: a few featured questions linking to the full FAQ page. --}}
@if(($visiblePages['faq'] ?? false) && isset($faqs) && $faqs->count())
<section class="py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="faq-teaser glass rounded-3xl p-6 sm:p-10 relative overflow-hidden" data-animate="fade-up">
            <div class="absolute -top-16 {{ app()->isLocale('ar') ? '-left-16' : '-right-16' }} w-56 h-56 rounded-full bg-primary-500/15 blur-3xl" aria-hidden="true"></div>
            <div class="relative grid md:grid-cols-5 gap-8 items-center">
                <div class="md:col-span-2">
                    <span class="faq-q-icon faq-q-icon-lg mb-4" aria-hidden="true">؟</span>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">{{ __('frontend.faq_teaser_title') }}</h2>
                    <p class="text-white/70 mb-6">{{ __('frontend.faq_teaser_text') }}</p>
                    <a href="{{ route('faq', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center px-6 py-3 rounded-xl gradient-primary text-white font-semibold">
                        {{ __('frontend.faq_all') }}
                        <svg aria-hidden="true" class="w-5 h-5 ms-2 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
                <ul class="md:col-span-3 space-y-3">
                    @foreach($faqs as $faq)
                        <li>
                            <a href="{{ route('faq', ['locale' => app()->getLocale()]) }}#q-{{ $faq->id }}"
                                class="flex items-center justify-between gap-4 px-5 py-4 rounded-2xl bg-white/5 border border-white/10 hover:border-primary-400 text-white/90 hover:text-white transition-colors">
                                <span class="font-medium">{{ $faq->question }}</span>
                                <svg aria-hidden="true" class="w-4 h-4 flex-shrink-0 text-primary-400 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
@endif
