{{--
    "Find the right system" — a 3-step wizard (sector, main need, branches) that recommends
    a system and hands the visitor to the demo form or WhatsApp with their answers filled in.
--}}
@php
    $finderProducts = \App\Models\Product::published()->get(['id', 'slug', 'name_ar', 'name_en', 'tagline_ar', 'tagline_en'])->keyBy('slug');
    $systems = collect(['pos-system', 'erp-accounting', 'hr-payroll', 'e-commerce', 'sales-rep-app'])->mapWithKeys(fn ($slug) => [$slug => [
        'name' => $finderProducts->get($slug)?->localized('name') ?: __('frontend.system_' . str_replace('-', '_', $slug)),
        'text' => $finderProducts->get($slug)?->localized('tagline') ?: __('frontend.finder_sys_' . str_replace('-', '_', $slug)),
        'url' => $finderProducts->has($slug) ? route('products.show', ['locale' => app()->getLocale(), 'product' => $finderProducts->get($slug)]) : null,
        'demo' => route('demo', ['locale' => app()->getLocale(), 'product' => $slug]),
    ]])->all();
    $finderIcons = [
        'restaurant' => 'M7 3v8a2 2 0 002 2v8M5 3v5M9 3v5M17 3c-1.7 0-3 2.2-3 5s1.3 4 3 4v9',
        'pharmacy' => 'M10 3h4v7h7v4h-7v7h-4v-7H3v-4h7z',
        'retail' => 'M3 4h2l2.4 11.2a2 2 0 002 1.6h7.7a2 2 0 001.9-1.5L21 8H6.2M10 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z',
        'distribution' => 'M3 7h11v9H3zM14 10h4l3 3v3h-7M7.5 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm10 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z',
        'organisation' => 'M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6M9 11h.01M15 11h.01',
        'other' => 'M4 8h16v11H4zM9 8V5h6v3M4 13h16',
        'sales' => 'M6 3h12v18l-3-2-3 2-3-2-3 2zM9 8h6M9 12h6M9 16h3',
        'accounts' => 'M4 20V10M10 20V4M16 20v-7M22 20H2',
        'staff' => 'M16 11a3 3 0 100-6 3 3 0 000 6zM8 11a3 3 0 100-6 3 3 0 000 6zM2 20a6 6 0 0112 0M14 20a6 6 0 018 0',
        'online' => 'M12 21a9 9 0 100-18 9 9 0 000 18zM3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18',
        'field' => 'M8 2h8a2 2 0 012 2v16a2 2 0 01-2 2H8a2 2 0 01-2-2V4a2 2 0 012-2zM11 18h2',
    ];
    $finderWhatsapp = \App\Support\PhoneNumber::whatsappUrl($settings->whatsapp ?: $settings->phone);
    $sectorsOpts = ['restaurant' => 1, 'pharmacy' => 1, 'retail' => 1, 'distribution' => 1, 'organisation' => 1, 'other' => 1];
    $needsOpts = ['sales' => 'pos-system', 'accounts' => 'erp-accounting', 'staff' => 'hr-payroll', 'online' => 'e-commerce', 'field' => 'sales-rep-app'];
    $sizeOpts = ['one', 'few', 'many'];
    $labels = collect($sectorsOpts)->keys()->mapWithKeys(fn ($k) => ['s_' . $k => __('frontend.finder_sector_' . $k)])
        ->merge(collect($needsOpts)->keys()->mapWithKeys(fn ($k) => ['n_' . $k => __('frontend.finder_need_' . $k)]))
        ->merge(collect($sizeOpts)->mapWithKeys(fn ($k) => ['z_' . $k => __('frontend.finder_size_' . $k)]));
@endphp
<section class="py-24 relative overflow-hidden" id="system-finder">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute top-10 {{ app()->isLocale('ar') ? 'right-1/4' : 'left-1/4' }} w-80 h-80 bg-primary-500/15 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute bottom-0 {{ app()->isLocale('ar') ? 'left-10' : 'right-10' }} w-72 h-72 bg-accent-500/10 rounded-full blur-3xl animate-pulse-slow" style="animation-delay:2s"></div>
    </div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative"
        x-data="{
            step: 1, sector: null, need: null, size: null,
            systems: @js($systems), needs: @js($needsOpts), labels: @js($labels),
            pick(field, value) { this[field] = value; setTimeout(() => this.step++, 220); },
            reset() { this.step = 1; this.sector = this.need = this.size = null; },
            get primary() { return this.needs[this.need] || 'erp-accounting'; },
            get extras() {
                const out = [];
                const add = s => { if (s !== this.primary && !out.includes(s)) out.push(s); };
                if (['restaurant', 'pharmacy', 'retail'].includes(this.sector)) add('pos-system');
                if (this.sector === 'distribution') add('sales-rep-app');
                if (this.sector === 'organisation') add('hr-payroll');
                if (this.size !== 'one') add('erp-accounting');
                return out.slice(0, 2);
            },
            get message() {
                return @js(__('frontend.finder_whatsapp_intro')) + '\n- ' + this.labels['s_' + this.sector] + '\n- ' + this.labels['n_' + this.need]
                    + '\n- ' + this.labels['z_' + this.size] + '\n' + @js(__('frontend.finder_whatsapp_system')) + ' ' + this.systems[this.primary].name;
            }
        }">
        <div class="text-center mb-10" data-animate="fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary-500/10 text-primary-400 text-sm font-medium mb-4">
                <span class="w-2 h-2 rounded-full bg-primary-400 animate-pulse"></span>{{ __('frontend.finder_badge') }}
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">{{ __('frontend.finder_title') }}</h2>
            <p class="text-white/65 max-w-2xl mx-auto">{{ __('frontend.finder_intro') }}</p>
        </div>

        <div class="finder-card glass rounded-3xl p-6 sm:p-10" data-animate="fade-up">
            {{-- progress --}}
            <div class="flex items-center gap-3 mb-8" aria-hidden="true">
                <template x-for="i in 3" :key="i">
                    <div class="flex-1 h-2 rounded-full bg-white/10 overflow-hidden">
                        <div class="h-full gradient-primary transition-all duration-500" :style="'width:' + (step > i ? 100 : (step === i ? 50 : 0)) + '%'"></div>
                    </div>
                </template>
            </div>

            {{-- step 1: sector --}}
            <div x-show="step === 1" x-transition:enter="finder-in" x-transition:enter-start="finder-from" x-transition:enter-end="finder-to">
                <p class="text-white/50 text-sm mb-1">{{ __('frontend.finder_step', ['n' => 1]) }}</p>
                <h3 class="text-xl md:text-2xl font-bold text-white mb-6">{{ __('frontend.finder_q_sector') }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach(array_keys($sectorsOpts) as $key)
                        <button type="button" @click="pick('sector', '{{ $key }}')" class="finder-option" :class="sector === '{{ $key }}' && 'is-picked'">
                            <span class="finder-emoji" aria-hidden="true"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $finderIcons[$key] }}"/></svg></span>{{ __('frontend.finder_sector_' . $key) }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- step 2: need --}}
            <div x-show="step === 2" x-cloak x-transition:enter="finder-in" x-transition:enter-start="finder-from" x-transition:enter-end="finder-to">
                <p class="text-white/50 text-sm mb-1">{{ __('frontend.finder_step', ['n' => 2]) }}</p>
                <h3 class="text-xl md:text-2xl font-bold text-white mb-6">{{ __('frontend.finder_q_need') }}</h3>
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach(array_keys($needsOpts) as $key)
                        <button type="button" @click="pick('need', '{{ $key }}')" class="finder-option" :class="need === '{{ $key }}' && 'is-picked'">
                            <span class="finder-emoji" aria-hidden="true"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $finderIcons[$key] }}"/></svg></span>{{ __('frontend.finder_need_' . $key) }}
                        </button>
                    @endforeach
                </div>
                <button type="button" @click="step = 1" class="mt-6 text-white/60 hover:text-white text-sm">{{ __('frontend.finder_back') }}</button>
            </div>

            {{-- step 3: size --}}
            <div x-show="step === 3" x-cloak x-transition:enter="finder-in" x-transition:enter-start="finder-from" x-transition:enter-end="finder-to">
                <p class="text-white/50 text-sm mb-1">{{ __('frontend.finder_step', ['n' => 3]) }}</p>
                <h3 class="text-xl md:text-2xl font-bold text-white mb-6">{{ __('frontend.finder_q_size') }}</h3>
                <div class="grid sm:grid-cols-3 gap-3">
                    @foreach(['one' => '1', 'few' => '2–5', 'many' => '+5'] as $key => $badge)
                        <button type="button" @click="pick('size', '{{ $key }}')" class="finder-option" :class="size === '{{ $key }}' && 'is-picked'">
                            <span class="finder-emoji finder-num" aria-hidden="true">{{ $badge }}</span>{{ __('frontend.finder_size_' . $key) }}
                        </button>
                    @endforeach
                </div>
                <button type="button" @click="step = 2" class="mt-6 text-white/60 hover:text-white text-sm">{{ __('frontend.finder_back') }}</button>
            </div>

            {{-- result --}}
            <div x-show="step === 4" x-cloak x-transition:enter="finder-in" x-transition:enter-start="finder-from" x-transition:enter-end="finder-to">
                <div class="text-center mb-8">
                    <div class="finder-check mx-auto mb-4" aria-hidden="true">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-white/60 text-sm mb-1">{{ __('frontend.finder_result_intro') }}</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-white" x-text="systems[primary].name"></h3>
                    <p class="text-white/70 mt-2 max-w-xl mx-auto" x-text="systems[primary].text"></p>
                </div>

                <template x-if="extras.length">
                    <div class="mb-8">
                        <p class="text-center text-white/60 text-sm mb-3">{{ __('frontend.finder_also') }}</p>
                        <div class="flex flex-wrap justify-center gap-2">
                            <template x-for="slug in extras" :key="slug">
                                <a :href="systems[slug].url || systems[slug].demo" class="px-4 py-2 rounded-full text-sm bg-white/5 border border-white/10 text-white/85 hover:border-primary-400 hover:text-white transition-colors" x-text="systems[slug].name"></a>
                            </template>
                        </div>
                    </div>
                </template>

                <div class="flex flex-wrap justify-center gap-3">
                    <a :href="systems[primary].demo" class="px-7 py-3.5 rounded-xl gradient-primary text-white font-semibold hover:shadow-lg hover:shadow-primary-500/30 transition-all">{{ __('frontend.finder_cta_demo') }}</a>
                    @if($finderWhatsapp)
                        <a :href="@js($finderWhatsapp) + '?text=' + encodeURIComponent(message)" target="_blank" rel="noopener noreferrer" data-track="system_finder"
                            class="px-7 py-3.5 rounded-xl text-white font-semibold inline-flex items-center gap-2" style="background:#25D366">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 8.01 0C3.65 0 .095 3.55.094 7.91c0 1.394.365 2.753 1.058 3.95L0 16l4.203-1.102a7.938 7.938 0 0 0 3.806.98h.003c4.36 0 7.916-3.55 7.917-7.91a7.85 7.85 0 0 0-2.328-5.642Z"/></svg>
                            {{ __('frontend.finder_cta_whatsapp') }}
                        </a>
                    @endif
                    <template x-if="systems[primary].url">
                        <a :href="systems[primary].url" class="px-7 py-3.5 rounded-xl glass text-white font-semibold hover:bg-white/20">{{ __('frontend.view_product') }}</a>
                    </template>
                </div>
                <div class="text-center mt-6">
                    <button type="button" @click="reset()" class="text-white/60 hover:text-white text-sm">{{ __('frontend.finder_restart') }}</button>
                </div>
            </div>
        </div>
    </div>
</section>
