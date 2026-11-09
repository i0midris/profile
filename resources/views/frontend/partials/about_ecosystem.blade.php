{{--
    "Albaseet ecosystem" — the About-section illustration (step 6.4).
    The company logo sits at the centre, its systems orbit around it, and three live cards
    (ERP, cloud, security) send data to the centre along glowing beams.
    Everything is drawn in code and scales with its container: the stage is 520 x 600 units
    where 1em = 10 units (see .eco-stage in app.css). Purely decorative, so hidden from screen readers.
--}}
@php
    $ecoLogo = $settings->logo_url ?? null;
    $ecoSystems = [
        // [icon path (24px grid), gradient]
        ['M3 4h2l2.5 11h10l2-7H6.3M9.5 20.5a1 1 0 100-2 1 1 0 000 2zM17 20.5a1 1 0 100-2 1 1 0 000 2z', 'red'],              // POS
        ['M6.5 3h11a1.5 1.5 0 011.5 1.5v15a1.5 1.5 0 01-1.5 1.5h-11A1.5 1.5 0 015 19.5v-15A1.5 1.5 0 016.5 3zM8 7h8M8 11h2m2 0h2M8 15h2m2 0h2M8 18h2m2 0h2', 'blue'], // accounting
        ['M16 20v-1a4 4 0 00-4-4H7a4 4 0 00-4 4v1M9.5 11a3.5 3.5 0 100-7 3.5 3.5 0 000 7zM21 20v-1a4 4 0 00-3-3.8M15.5 4.2a3.5 3.5 0 010 6.6', 'navy'], // HR
        ['M6 7h12l1 13H5L6 7zM9 7a3 3 0 016 0', 'red'],                                                                     // e-commerce
        ['M3 6h11v9H3zM14 9h4l3 3v3h-7M7 18.5a1.6 1.6 0 100-3.2 1.6 1.6 0 000 3.2zM17 18.5a1.6 1.6 0 100-3.2 1.6 1.6 0 000 3.2z', 'blue'], // sales reps
        ['M21 8l-9-5-9 5 9 5 9-5zM3 8v8l9 5 9-5V8M12 13v8', 'navy'],                                                         // inventory
    ];
@endphp
<div class="eco" aria-hidden="true" data-eco>
    <div class="eco-stage">
        {{-- Backdrop: glow, dotted field, slow rings --}}
        <div class="eco-glow eco-glow-a"></div>
        <div class="eco-glow eco-glow-b"></div>
        <div class="eco-dots"></div>
        <svg class="eco-rings" viewBox="0 0 520 600">
            <defs>
                <linearGradient id="eco-ring-grad" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0" stop-color="#E4191F" stop-opacity=".55"/>
                    <stop offset=".5" stop-color="#3b6fd4" stop-opacity=".15"/>
                    <stop offset="1" stop-color="#3b6fd4" stop-opacity=".55"/>
                </linearGradient>
            </defs>
            <g class="eco-spin-slow"><circle cx="260" cy="300" r="168" fill="none" stroke="url(#eco-ring-grad)" stroke-width="1.2" stroke-dasharray="2 7"/>
                <circle cx="260" cy="132" r="3.5" fill="#E4191F" class="eco-glow-dot"/><circle cx="428" cy="300" r="2.5" fill="#60a5fa"/><circle cx="141" cy="419" r="2.5" fill="#60a5fa"/></g>
            <circle cx="260" cy="300" r="102" fill="none" class="eco-ring-orbit" stroke-width="1"/>
        </svg>

        {{-- Data beams from each card into the centre, with travelling light packets --}}
        <svg class="eco-beams eco-depth-1" viewBox="0 0 520 600">
            <defs>
                <linearGradient id="eco-beam-red" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#E4191F" stop-opacity=".1"/><stop offset="1" stop-color="#E4191F" stop-opacity=".7"/></linearGradient>
                <linearGradient id="eco-beam-blue" x1="1" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#60a5fa" stop-opacity=".1"/><stop offset="1" stop-color="#60a5fa" stop-opacity=".75"/></linearGradient>
                <linearGradient id="eco-beam-up" x1="0" y1="1" x2="0" y2="0"><stop offset="0" stop-color="#E4191F" stop-opacity=".1"/><stop offset="1" stop-color="#E4191F" stop-opacity=".7"/></linearGradient>
                <filter id="eco-blur" x="-200%" y="-200%" width="500%" height="500%"><feGaussianBlur stdDeviation="3.2" result="b"/><feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
            </defs>
            <path id="eco-p1" d="M150 168 C165 208 200 245 226 260" fill="none" stroke="url(#eco-beam-red)" stroke-width="2" stroke-linecap="round"/>
            <path id="eco-p2" d="M380 186 C366 224 326 250 298 265" fill="none" stroke="url(#eco-beam-blue)" stroke-width="2" stroke-linecap="round"/>
            <path id="eco-p3" d="M260 442 C260 412 260 382 260 352" fill="none" stroke="url(#eco-beam-up)" stroke-width="2" stroke-linecap="round"/>
            <circle r="4" fill="#ff5a5f" filter="url(#eco-blur)"><animateMotion dur="2.2s" repeatCount="indefinite"><mpath href="#eco-p1"/></animateMotion></circle>
            <circle r="3" fill="#fff" opacity=".8" filter="url(#eco-blur)"><animateMotion dur="2.2s" begin="1.1s" repeatCount="indefinite"><mpath href="#eco-p1"/></animateMotion></circle>
            <circle r="4" fill="#60a5fa" filter="url(#eco-blur)"><animateMotion dur="2.6s" repeatCount="indefinite"><mpath href="#eco-p2"/></animateMotion></circle>
            <circle r="3" fill="#fff" opacity=".8" filter="url(#eco-blur)"><animateMotion dur="2.6s" begin="1.3s" repeatCount="indefinite"><mpath href="#eco-p2"/></animateMotion></circle>
            <circle r="4" fill="#ff5a5f" filter="url(#eco-blur)"><animateMotion dur="1.9s" repeatCount="indefinite"><mpath href="#eco-p3"/></animateMotion></circle>
            <circle cx="150" cy="168" r="4" fill="#E4191F" filter="url(#eco-blur)"/>
            <circle cx="380" cy="186" r="4" fill="#60a5fa" filter="url(#eco-blur)"/>
            <circle cx="260" cy="442" r="4" fill="#E4191F" filter="url(#eco-blur)"/>
        </svg>

        {{-- The company's systems orbiting the centre --}}
        <div class="eco-orbit">
            @foreach($ecoSystems as $i => [$path, $tone])
                <div class="eco-sat" style="--a: {{ $i * 60 }}deg">
                    <div class="eco-sat-chip eco-tone-{{ $tone }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $path }}"/></svg>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Centre: the company --}}
        <div class="eco-hub eco-depth-2">
            <span class="eco-wave"></span><span class="eco-wave eco-wave-2"></span>
            <div class="eco-hub-ring"></div>
            <div class="eco-hub-core">
                @if($ecoLogo)
                    <span class="eco-hub-logo"><img src="{{ $ecoLogo }}" alt="" loading="lazy" decoding="async"></span>
                @else
                    <svg class="eco-hub-mark" viewBox="0 0 50 50" fill="none">
                        <rect x="8" y="8" width="14" height="10" rx="2" fill="rgba(255,255,255,.15)" stroke="#fff" stroke-width="1.5"/>
                        <rect x="28" y="8" width="14" height="10" rx="2" fill="rgba(255,255,255,.15)" stroke="#fff" stroke-width="1.5"/>
                        <rect x="8" y="23" width="14" height="10" rx="2" fill="rgba(255,255,255,.15)" stroke="#fff" stroke-width="1.5"/>
                        <rect x="28" y="23" width="14" height="10" rx="2" fill="#E4191F"/>
                        <rect x="16" y="37" width="18" height="5" rx="1.5" fill="rgba(255,255,255,.3)"/>
                    </svg>
                @endif
            </div>
        </div>

        {{-- Card 1: ERP — live chart --}}
        <div class="eco-card eco-card-erp eco-depth-3">
            <div class="eco-card-head">
                <span class="eco-icon eco-tone-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></svg></span>
                <span><span class="eco-label">{{ __('frontend.eco_erp_label') }}</span><span class="eco-title">{{ __('frontend.eco_erp_title') }}</span></span>
            </div>
            <div class="eco-chart">
                @foreach([[.45, .7], [.6, .4], [.35, .65], [.75, .5], [.5, .85], [.9, .6], [.62, .95]] as $b => [$from, $to])
                    <span class="eco-bar {{ $b === 5 ? 'is-hot' : '' }}" style="--from: {{ $from }}; --to: {{ $to }}; --d: {{ $b * -0.35 }}s"></span>
                @endforeach
                <svg class="eco-spark" viewBox="0 0 140 44" preserveAspectRatio="none"><polyline points="2,34 22,26 42,30 62,18 82,22 102,10 138,6" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" pathLength="100"/></svg>
            </div>
            <div class="eco-tags">
                <span class="eco-tag">{{ __('frontend.hero_erp_module_acc') }}</span>
                <span class="eco-tag">{{ __('frontend.hero_erp_module_inv') }}</span>
            </div>
        </div>

        {{-- Card 2: Cloud — devices kept in sync --}}
        <div class="eco-card eco-card-cloud eco-depth-3">
            <div class="eco-card-head">
                <span class="eco-icon eco-tone-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 109 20h9a5 5 0 000-10z"/></svg></span>
                <span><span class="eco-label">{{ __('frontend.eco_cloud_label') }}</span><span class="eco-title">{{ __('frontend.eco_cloud_title') }}</span></span>
            </div>
            <svg class="eco-sync" viewBox="0 0 180 54">
                <path id="eco-s1" d="M34 27 H74" stroke="rgba(96,165,250,.45)" stroke-width="1.5" stroke-dasharray="3 4"/>
                <path id="eco-s2" d="M146 27 H106" stroke="rgba(96,165,250,.45)" stroke-width="1.5" stroke-dasharray="3 4"/>
                {{-- desktop --}}
                <g class="eco-sync-dev"><rect x="4" y="14" width="30" height="20" rx="3" fill="none" stroke="currentColor" stroke-width="2"/><path d="M12 40h14M19 34v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></g>
                {{-- cloud hub --}}
                <g><circle cx="90" cy="27" r="17" fill="url(#eco-sync-grad)"/><path d="M97 27.5h-.8a6 6 0 10-5.7 7.5H97a3.8 3.8 0 000-7.5z" fill="#fff" transform="translate(-4 -3.5)"/></g>
                {{-- phone --}}
                <g class="eco-sync-dev"><rect x="151" y="10" width="20" height="34" rx="4" fill="none" stroke="currentColor" stroke-width="2"/><path d="M158 38h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></g>
                <defs><linearGradient id="eco-sync-grad" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#2d52be"/><stop offset="1" stop-color="#1A2E6D"/></linearGradient></defs>
                <circle r="3.2" fill="#60a5fa"><animateMotion dur="1.6s" repeatCount="indefinite"><mpath href="#eco-s1"/></animateMotion></circle>
                <circle r="3.2" fill="#4ade80"><animateMotion dur="1.6s" begin=".8s" repeatCount="indefinite"><mpath href="#eco-s2"/></animateMotion></circle>
            </svg>
            <div class="eco-status"><span class="eco-live"></span>{{ __('frontend.eco_cloud_status') }}</div>
        </div>

        {{-- Card 3: Security — scanning shield and checks --}}
        <div class="eco-card eco-card-sec eco-depth-3">
            <div class="eco-card-head">
                <span class="eco-icon eco-tone-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></span>
                <span class="eco-grow"><span class="eco-label">{{ __('frontend.eco_sec_label') }}</span><span class="eco-title">{{ __('frontend.eco_sec_title') }}</span></span>
                <span class="eco-badge"><span class="eco-live"></span>{{ __('frontend.eco_sec_status') }}</span>
            </div>
            <div class="eco-sec-body">
                <div class="eco-shield">
                    <svg viewBox="0 0 48 56"><defs><linearGradient id="eco-shield-grad" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#E4191F"/><stop offset="1" stop-color="#7a0a0d"/></linearGradient><clipPath id="eco-shield-clip"><path d="M24 54s20-9 20-25V10L24 2 4 10v19c0 16 20 25 20 25z"/></clipPath></defs>
                        <path d="M24 54s20-9 20-25V10L24 2 4 10v19c0 16 20 25 20 25z" fill="url(#eco-shield-grad)"/>
                        <g clip-path="url(#eco-shield-clip)"><rect class="eco-scan" x="0" y="-6" width="48" height="6" fill="rgba(255,255,255,.55)"/></g>
                        <path d="M15 28l6 6 12-13" fill="none" stroke="#fff" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round" class="eco-shield-tick"/>
                    </svg>
                </div>
                <ul class="eco-checks">
                    @foreach([__('frontend.hero_cyber_encryption'), __('frontend.hero_cyber_permissions'), __('frontend.hero_cyber_backup')] as $c => $check)
                        <li style="--d: {{ $c * 0.6 }}s"><span class="eco-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"/></svg></span>{{ $check }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Drifting particles --}}
        @foreach([[72, 300, 0], [452, 330, 1.4], [330, 60, 2.6], [120, 450, 3.4], [420, 460, .8]] as [$px, $py, $pd])
            <span class="eco-particle" style="left: {{ $px / 5.2 }}%; top: {{ $py / 6 }}%; --d: {{ $pd }}s"></span>
        @endforeach
    </div>
</div>
