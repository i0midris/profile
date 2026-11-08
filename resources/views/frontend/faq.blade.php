@extends('frontend.layouts.app')

@section('title', __('frontend.faq_title') . ' | ' . ($settings->company_name ?? __('frontend.company_fallback')))
@section('meta_description', __('frontend.faq_page_description'))

@section('content')
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
        $usedCategories = collect(\App\Models\Faq::CATEGORIES)->filter(fn ($c) => $faqs->contains('category', $c))->values();
        $whatsappUrl = \App\Support\PhoneNumber::whatsappUrl($settings->whatsapp ?: $settings->phone);
    @endphp
    <script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>

    {{-- Header + search --}}
    <section class="pt-20 pb-10 relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute top-1/4 {{ app()->isLocale('ar') ? '-right-20' : '-left-20' }} w-96 h-96 bg-primary-500/15 rounded-full blur-3xl animate-pulse-slow"></div>
        </div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('frontend.faq_title') }}</h1>
            <p class="text-white/70 mb-8">{{ __('frontend.faq_page_intro') }}</p>
        </div>
    </section>

    @php
        $faqIndex = $faqs->map(fn ($f) => ['id' => (string) $f->id, 'cat' => $f->category, 'text' => mb_strtolower($f->question . ' ' . $f->answer)])->values();
    @endphp
    <section class="pb-24" x-data="{
            cat: 'all', q: '', open: (location.hash || '').replace('#q-', ''), items: @js($faqIndex),
            shows(id) { const f = this.items.find(i => i.id === String(id)); return (this.cat === 'all' || this.cat === f.cat) && (this.q.trim() === '' || f.text.includes(this.q.trim().toLowerCase())); },
            get visibleCount() { return this.items.filter(i => this.shows(i.id)).length; },
            num(id) { return this.items.filter(i => this.shows(i.id)).findIndex(i => i.id === String(id)) + 1; }
        }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- search + category tabs --}}
            <div class="max-w-3xl mx-auto mb-10">
                <label for="faq-search" class="sr-only">{{ __('frontend.faq_search') }}</label>
                <div class="relative mb-5">
                    <svg aria-hidden="true" class="w-5 h-5 absolute top-1/2 -translate-y-1/2 {{ app()->isLocale('ar') ? 'right-4' : 'left-4' }} text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M20 20l-3.5-3.5"/></svg>
                    <input id="faq-search" type="search" x-model="q" placeholder="{{ __('frontend.faq_search') }}"
                        class="w-full {{ app()->isLocale('ar') ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-4 rounded-2xl bg-white/5 border border-white/10 text-white placeholder-white/40 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                </div>
                @if($usedCategories->count() > 1)
                    <div class="flex flex-wrap justify-center gap-2" role="tablist" aria-label="{{ __('frontend.faq_title') }}">
                        @foreach($usedCategories->prepend('all') as $c)
                            <button type="button" role="tab" @click="cat = '{{ $c }}'" :aria-selected="(cat === '{{ $c }}').toString()"
                                :class="cat === '{{ $c }}' ? 'gradient-primary text-white' : 'glass text-white/75 hover:text-white'"
                                class="px-5 py-2 rounded-full text-sm font-medium transition-all">{{ __('frontend.faq_cat_' . $c) }}</button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="grid lg:grid-cols-3 gap-8 items-start">
                {{-- questions --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($faqs as $faq)
                        <article id="q-{{ $faq->id }}" class="faq-card glass rounded-2xl overflow-hidden"
                            x-show="shows({{ $faq->id }})"
                            :class="open == '{{ $faq->id }}' ? 'is-open' : ''">
                            <h2>
                                <button type="button" class="w-full flex items-center justify-between gap-4 px-6 py-5 text-start"
                                    @click="open = (open == '{{ $faq->id }}') ? '' : '{{ $faq->id }}'" :aria-expanded="(open == '{{ $faq->id }}').toString()"
                                    aria-controls="a-{{ $faq->id }}">
                                    <span class="flex items-center gap-3">
                                        <span class="faq-q-icon faq-q-num" aria-hidden="true" x-text="num({{ $faq->id }})">{{ $loop->iteration }}</span>
                                        <span class="text-white font-semibold text-base md:text-lg">{{ $faq->question }}</span>
                                    </span>
                                    <svg aria-hidden="true" class="w-5 h-5 flex-shrink-0 text-primary-400 transition-transform" :class="open == '{{ $faq->id }}' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </h2>
                            <div id="a-{{ $faq->id }}" x-show="open == '{{ $faq->id }}'" x-transition.opacity.duration.200ms x-cloak>
                                @php $hasVisual = $faq->image_url || $faq->illustration_key; @endphp
                                <div class="px-6 pb-6 grid gap-6 {{ $hasVisual ? 'md:grid-cols-5 items-center' : '' }}">
                                    <p class="text-white/80 leading-loose whitespace-pre-line {{ $hasVisual ? 'md:col-span-3' : '' }}">{{ $faq->answer }}</p>
                                    @if($faq->image_url)
                                        <img loading="lazy" decoding="async" src="{{ $faq->image_url }}" alt="{{ $faq->question }}"
                                            class="md:col-span-2 w-full rounded-xl border border-white/10 shadow-lg">
                                    @elseif($faq->illustration_key)
                                        <div class="md:col-span-2">
                                            @include('frontend.partials.faq_illustration', ['key' => $faq->illustration_key, 'uid' => $faq->id])
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                    <p class="text-center text-white/60 py-10" x-show="visibleCount === 0" x-cloak>
                        {{ __('frontend.faq_no_results') }}
                    </p>
                </div>

                {{-- side card --}}
                <aside class="glass rounded-2xl p-6 lg:sticky lg:top-28">
                    <div class="w-12 h-12 rounded-xl gradient-primary flex items-center justify-center mb-4">
                        <svg aria-hidden="true" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m-9 6l2.5-3H18a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v14z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-white mb-2">{{ __('frontend.faq_still_title') }}</h2>
                    <p class="text-white/70 text-sm leading-relaxed mb-5">{{ __('frontend.faq_still_text') }}</p>
                    <div class="space-y-3">
                        @if($whatsappUrl)
                            <a href="{{ $whatsappUrl }}?text={{ rawurlencode(__('frontend.whatsapp_general_message')) }}" target="_blank" rel="noopener noreferrer"
                                class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-white font-semibold" style="background:#25D366">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 8.01 0C3.65 0 .095 3.55.094 7.91c0 1.394.365 2.753 1.058 3.95L0 16l4.203-1.102a7.938 7.938 0 0 0 3.806.98h.003c4.36 0 7.916-3.55 7.917-7.91a7.85 7.85 0 0 0-2.328-5.642Z"/></svg>
                                {{ __('frontend.ask_on_whatsapp') }}
                            </a>
                        @endif
                        <a href="{{ route('demo', ['locale' => app()->getLocale()]) }}" class="block text-center w-full py-3 rounded-xl gradient-primary text-white font-semibold">{{ __('frontend.request_demo') }}</a>
                        <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" class="block text-center w-full py-3 rounded-xl glass text-white/85 hover:text-white font-medium">{{ __('frontend.contact') }}</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
