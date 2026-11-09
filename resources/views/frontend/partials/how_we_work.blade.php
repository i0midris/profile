{{-- "How we work" — four steps with animated icons, in the same visual language as the hero illustration. --}}
@php
    $steps = [
        ['key' => 'consult', 'color' => '#3b6fd4', 'icon' => '<path d="M21 12c0 4.4-4 8-9 8a9.9 9.9 0 01-4.3-1L3 20l1.4-3.7A7.6 7.6 0 013 12c0-4.4 4-8 9-8s9 3.6 9 8z"/><circle cx="8.5" cy="12" r="1" fill="currentColor"/><circle cx="12" cy="12" r="1" fill="currentColor"/><circle cx="15.5" cy="12" r="1" fill="currentColor"/>'],
        ['key' => 'demo', 'color' => '#E4191F', 'icon' => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/><path d="M10.5 8.2v3.6L13.6 10z" fill="currentColor"/>'],
        ['key' => 'setup', 'color' => '#22a06b', 'icon' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.8-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1a1.7 1.7 0 00-1.1-1.5 1.7 1.7 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.8 1.7 1.7 0 00-1.5-1H3a2 2 0 110-4h.1a1.7 1.7 0 001.5-1.1 1.7 1.7 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.8.3H9a1.7 1.7 0 001-1.5V3a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.8V9a1.7 1.7 0 001.5 1H21a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z"/>', 'spin' => true],
        ['key' => 'support', 'color' => '#d98a16', 'icon' => '<path d="M3 14v-2a9 9 0 0118 0v2"/><rect x="2.5" y="13" width="4" height="7" rx="1.5"/><rect x="17.5" y="13" width="4" height="7" rx="1.5"/><path d="M19.5 20a3 3 0 01-3 2H13"/>'],
    ];
@endphp
<section class="py-24 relative overflow-hidden how-we-work">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute top-1/3 {{ app()->isLocale('ar') ? 'left-10' : 'right-10' }} w-72 h-72 bg-primary-500/10 rounded-full blur-3xl animate-pulse-slow"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16" data-animate="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('frontend.how_we_work_title') }}</h2>
            <p class="text-white/60 max-w-2xl mx-auto">{{ __('frontend.how_we_work_intro') }}</p>
        </div>

        <ol class="hww-steps grid sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
            {{-- animated connector line behind the cards (desktop). The dashes travel from x1 to x2,
                 so the line starts at step 1: on the right in Arabic, on the left in English. --}}
            @php $hwwRtl = app()->isLocale('ar'); @endphp
            <svg class="hww-line hidden lg:block" viewBox="0 0 1000 20" preserveAspectRatio="none" aria-hidden="true">
                <line x1="{{ $hwwRtl ? 960 : 40 }}" y1="10" x2="{{ $hwwRtl ? 40 : 960 }}" y2="10" stroke="rgba(228,25,31,.45)" stroke-width="2" stroke-dasharray="8 7">
                    <animate attributeName="stroke-dashoffset" from="0" to="-30" dur="1.6s" repeatCount="indefinite"/>
                </line>
            </svg>
            @foreach($steps as $i => $step)
                <li class="hww-card glass global-card rounded-2xl p-7 text-center relative" data-animate="fade-up" data-aos-delay="{{ $i * 120 }}">
                    <span class="hww-num" style="--c: {{ $step['color'] }}">{{ $i + 1 }}</span>
                    <div class="hww-icon" style="--c: {{ $step['color'] }}; --d: {{ $i * 0.6 }}s">
                        <span class="hww-ring" aria-hidden="true"></span>
                        <svg aria-hidden="true" class="w-9 h-9 {{ !empty($step['spin']) ? 'hww-spin' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $step['icon'] !!}</svg>
                    </div>
                    <h3 class="global-card-title text-lg font-semibold text-white mb-2">{{ __('frontend.how_step_' . $step['key'] . '_title') }}</h3>
                    <p class="global-card-copy text-white/65 text-sm leading-relaxed">{{ __('frontend.how_step_' . $step['key'] . '_text') }}</p>
                </li>
            @endforeach
        </ol>

        <div class="text-center mt-12" data-animate="fade-up">
            <a href="{{ route('demo', ['locale' => app()->getLocale()]) }}"
                class="inline-flex items-center px-8 py-4 rounded-xl gradient-primary text-white font-semibold hover:shadow-lg hover:shadow-primary-500/30 transform hover:-translate-y-1 transition-all duration-300">
                {{ __('frontend.request_demo') }}
                <svg aria-hidden="true" class="w-5 h-5 ms-2 rtl-flip" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
