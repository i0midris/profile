{{-- FAQ accordion + FAQPage structured data (plan step 3.5). Rendered only when questions are published. --}}
@if(isset($faqs) && $faqs->count())
    @php
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
            ])->values()->all(),
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    <section class="py-24" id="faq">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-animate="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('frontend.faq_title') }}</h2>
                <p class="text-white/60 max-w-2xl mx-auto">{{ __('frontend.faq_intro') }}</p>
            </div>
            <div class="space-y-3">
                @foreach($faqs as $faq)
                    <details class="faq-item glass rounded-2xl group" @if($loop->first) open @endif>
                        <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-6 py-5 text-white font-semibold">
                            <span>{{ $faq->question }}</span>
                            <svg aria-hidden="true" class="w-5 h-5 flex-shrink-0 text-primary-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </summary>
                        <div class="px-6 pb-6 -mt-1 text-white/75 leading-relaxed whitespace-pre-line">{{ $faq->answer }}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
@endif
