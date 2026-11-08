{{--
    Animated answer illustrations for the FAQ page, drawn in the same visual language as the
    home-page hero panel (dark glass cards, brand red/blue, soft glows, calm motion).
    Usage: @include('frontend.partials.faq_illustration', ['key' => 'offline'])
    Keys: offline, branches, hardware, training, setup, customize, demo, security, support
--}}
@php
    $id = 'fi' . $key . ($uid ?? '');
    $T = fn ($k) => e(__('frontend.ill_' . $k));
    $card = 'fill="#081234" fill-opacity=".94" stroke="#ffffff" stroke-opacity=".1"';
@endphp
<svg viewBox="0 0 360 240" class="faq-illustration" role="img" aria-hidden="true" direction="ltr" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="{{ $id }}-bg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#1a2e6d"/><stop offset="1" stop-color="#0a1230"/></linearGradient>
        <radialGradient id="{{ $id }}-gb" cx=".5" cy=".5" r=".5"><stop offset="0" stop-color="#3b6fd4" stop-opacity=".45"/><stop offset="1" stop-color="#3b6fd4" stop-opacity="0"/></radialGradient>
        <radialGradient id="{{ $id }}-gr" cx=".5" cy=".5" r=".5"><stop offset="0" stop-color="#E4191F" stop-opacity=".35"/><stop offset="1" stop-color="#E4191F" stop-opacity="0"/></radialGradient>
        <linearGradient id="{{ $id }}-red" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#E4191F"/><stop offset="1" stop-color="#7a0a0d"/></linearGradient>
        <linearGradient id="{{ $id }}-blue" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#2d52be"/><stop offset="1" stop-color="#1A2E6D"/></linearGradient>
    </defs>
    <rect width="360" height="240" rx="18" fill="url(#{{ $id }}-bg)"/>
    <circle cx="70" cy="50" r="90" fill="url(#{{ $id }}-gb)" class="fi-breathe"/>
    <circle cx="300" cy="200" r="90" fill="url(#{{ $id }}-gr)" class="fi-breathe" style="animation-delay:2s"/>

@switch($key)
    {{-- ───────────── Works offline, then syncs ───────────── --}}
    @case('offline')
        {{-- device (POS / phone) with queued invoices --}}
        <rect x="28" y="54" width="118" height="140" rx="16" {!! $card !!}/>
        <rect x="40" y="66" width="94" height="10" rx="3" fill="#1A2E6D"/>
        <g class="fi-wifi-off" transform="translate(116 62)">
            <path d="M-8 10a12 12 0 0116 0" stroke="#E4191F" stroke-width="2.2" fill="none" stroke-linecap="round"/>
            <path d="M-4 14a6 6 0 018 0" stroke="#E4191F" stroke-width="2.2" fill="none" stroke-linecap="round"/>
            <line x1="-9" y1="4" x2="9" y2="18" stroke="#E4191F" stroke-width="2.2" stroke-linecap="round"/>
        </g>
        @foreach([0,1,2] as $i)
            <g class="fi-queue" style="animation-delay: {{ $i * 0.9 }}s">
                <rect x="40" y="{{ 88 + $i * 32 }}" width="94" height="24" rx="6" fill="#ffffff" fill-opacity=".05" stroke="#ffffff" stroke-opacity=".08"/>
                <rect x="48" y="{{ 96 + $i * 32 }}" width="40" height="4" rx="2" fill="#ffffff" fill-opacity=".45"/>
                <rect x="48" y="{{ 103 + $i * 32 }}" width="26" height="3" rx="1.5" fill="#ffffff" fill-opacity=".25"/>
                <rect x="106" y="{{ 96 + $i * 32 }}" width="20" height="8" rx="4" fill="#facc15" fill-opacity=".85"/>
            </g>
        @endforeach
        <text x="87" y="212" text-anchor="middle" direction="rtl" font-size="11" fill="#ffffff" fill-opacity=".7" font-weight="600">{!! $T('offline') !!}</text>
        {{-- sync path with travelling packets --}}
        <path id="{{ $id }}-p" d="M150 124 C 190 124, 196 90, 236 90" fill="none" stroke="#3b6fd4" stroke-opacity=".5" stroke-width="2" stroke-dasharray="6 5" class="fi-dash"/>
        @foreach([0, 0.8, 1.6] as $d)
            <circle r="4.5" fill="#60a5fa"><animateMotion dur="2.4s" begin="{{ $d }}s" repeatCount="indefinite"><mpath href="#{{ $id }}-p"/></animateMotion><animate attributeName="opacity" values="0;1;1;0" dur="2.4s" begin="{{ $d }}s" repeatCount="indefinite"/></circle>
        @endforeach
        {{-- cloud server --}}
        <g class="fi-float">
            <rect x="228" y="46" width="108" height="96" rx="18" {!! $card !!}/>
            <path d="M302 92h-3c-2-11-11-19-23-19-13 0-23 10-23 23 0 1 0 2 .2 3-6 1-10 6-10 12 0 7 5 12 12 12h47c7 0 12-6 12-13s-5-13-12-13z" fill="url(#{{ $id }}-blue)" stroke="#60a5fa" stroke-opacity=".6"/>
            <circle cx="282" cy="112" r="11" fill="#4ade80" class="fi-pop"/><path d="M276.5 112l4 4 7.5-8" stroke="#081234" stroke-width="2.6" fill="none" stroke-linecap="round" stroke-linejoin="round" class="fi-pop"/>
        </g>
        <text x="282" y="166" text-anchor="middle" direction="rtl" font-size="11" fill="#4ade80" font-weight="700">{!! $T('synced') !!}</text>
        @break

    {{-- ───────────── Several branches, one system ───────────── --}}
    @case('branches')
        @php $nodes = [[60, 64], [60, 176], [300, 64], [300, 176]]; @endphp
        @foreach($nodes as $n)
            <line x1="180" y1="120" x2="{{ $n[0] }}" y2="{{ $n[1] }}" stroke="#3b6fd4" stroke-opacity=".45" stroke-width="2" stroke-dasharray="6 5" class="fi-dash"/>
        @endforeach
        @foreach($nodes as $i => $n)
            <circle r="4" fill="{{ $i % 2 ? '#E4191F' : '#60a5fa' }}">
                <animateMotion dur="2.2s" begin="{{ $i * 0.5 }}s" repeatCount="indefinite" path="M{{ $n[0] }} {{ $n[1] }} L180 120"/>
                <animate attributeName="opacity" values="0;1;0" dur="2.2s" begin="{{ $i * 0.5 }}s" repeatCount="indefinite"/>
            </circle>
        @endforeach
        {{-- branch stores --}}
        @foreach($nodes as $i => $n)
            <g class="fi-float" style="animation-delay: {{ $i * 0.7 }}s">
                <rect x="{{ $n[0] - 38 }}" y="{{ $n[1] - 30 }}" width="76" height="56" rx="12" {!! $card !!}/>
                <path d="M{{ $n[0] - 18 }} {{ $n[1] - 12 }}h36l-4 -8h-28z" fill="#E4191F" fill-opacity=".9"/>
                <rect x="{{ $n[0] - 16 }}" y="{{ $n[1] - 12 }}" width="32" height="18" rx="2" fill="#ffffff" fill-opacity=".12"/>
                <rect x="{{ $n[0] - 4 }}" y="{{ $n[1] - 4 }}" width="8" height="10" rx="1" fill="#60a5fa" fill-opacity=".8"/>
                <text x="{{ $n[0] }}" y="{{ $n[1] + 20 }}" text-anchor="middle" direction="rtl" font-size="9.5" fill="#ffffff" fill-opacity=".7">{!! $T('branch') !!} {{ $i + 1 }}</text>
            </g>
        @endforeach
        {{-- headquarters with combined report --}}
        <circle cx="180" cy="120" r="56" fill="none" stroke="#3b6fd4" stroke-opacity=".2" class="fi-spin-slow" stroke-dasharray="3 6"/>
        <rect x="136" y="80" width="88" height="80" rx="16" fill="url(#{{ $id }}-blue)" stroke="#60a5fa" stroke-opacity=".5"/>
        <text x="180" y="98" text-anchor="middle" direction="rtl" font-size="10" fill="#ffffff" font-weight="700">{!! $T('hq') !!}</text>
        @foreach([[150, 22], [162, 34], [174, 16], [186, 40], [198, 28]] as $b)
            <rect x="{{ $b[0] }}" y="{{ 148 - $b[1] }}" width="8" height="{{ $b[1] }}" rx="2" fill="{{ $b[1] === 40 ? '#E4191F' : '#ffffff' }}" fill-opacity="{{ $b[1] === 40 ? 1 : .55 }}" class="fi-bar" style="animation-delay: {{ $b[0] / 100 }}s"/>
        @endforeach
        @break

    {{-- ───────────── Barcode scanner + receipt printer ───────────── --}}
    @case('hardware')
        {{-- product with barcode --}}
        <g class="fi-float">
            <rect x="34" y="70" width="104" height="96" rx="14" {!! $card !!}/>
            <rect x="50" y="86" width="72" height="40" rx="4" fill="#ffffff"/>
            @foreach([0,3,5,9,11,14,17,19,23,26,28,31,34,37,40,43,46,49,52,55,58,61,64] as $i => $x)
                <rect x="{{ 54 + $x }}" y="90" width="{{ $i % 3 ? 1.5 : 3 }}" height="28" fill="#0a1230"/>
            @endforeach
            <rect x="50" y="102" width="72" height="2.5" fill="#E4191F" class="fi-scanline"/>
            <rect x="50" y="134" width="44" height="5" rx="2.5" fill="#ffffff" fill-opacity=".5"/><rect x="50" y="144" width="30" height="4" rx="2" fill="#ffffff" fill-opacity=".3"/>
        </g>
        {{-- scanner gun --}}
        <g transform="translate(150 54) rotate(18)">
            <path d="M0 0h58a10 10 0 0110 10v8a10 10 0 01-10 10H30l-6 34a6 6 0 01-6 5H10a5 5 0 01-5-6l5-33H8A8 8 0 010 20z" fill="url(#{{ $id }}-red)"/>
            <rect x="52" y="6" width="10" height="16" rx="3" fill="#ffb4b6" class="fi-blink"/>
        </g>
        <path d="M178 94 L132 100 L132 106 Z" fill="#E4191F" fill-opacity=".35" class="fi-beam"/>
        {{-- receipt printer --}}
        <rect x="236" y="120" width="96" height="62" rx="12" {!! $card !!}/>
        <rect x="248" y="132" width="72" height="8" rx="4" fill="#0a1230"/>
        <g class="fi-print">
            <path d="M252 136h64v56l-6-4-6 4-6-4-6 4-6-4-6 4-6-4-6 4-6-4-6 4z" fill="#ffffff"/>
            <rect x="260" y="146" width="40" height="3" rx="1.5" fill="#1A2E6D" fill-opacity=".5"/><rect x="260" y="154" width="30" height="3" rx="1.5" fill="#1A2E6D" fill-opacity=".35"/>
            <rect x="260" y="162" width="48" height="3" rx="1.5" fill="#1A2E6D" fill-opacity=".35"/><rect x="290" y="172" width="18" height="5" rx="2" fill="#E4191F"/>
        </g>
        <rect x="236" y="168" width="96" height="16" rx="6" fill="#081234"/>
        <circle cx="320" cy="176" r="3" fill="#4ade80" class="fi-blink"/>
        @break

    {{-- ───────────── Training the team ───────────── --}}
    @case('training')
        {{-- board with dashboard --}}
        <rect x="88" y="26" width="184" height="112" rx="14" {!! $card !!}/>
        <rect x="100" y="38" width="160" height="10" rx="3" fill="#1A2E6D"/><circle cx="252" cy="43" r="3" fill="#E4191F"/>
        @foreach([[110, 40], [126, 58], [142, 32], [158, 66], [174, 48], [190, 74]] as $i => $b)
            <rect x="{{ $b[0] }}" y="{{ 128 - $b[1] }}" width="10" height="{{ $b[1] }}" rx="2.5" fill="{{ $i === 5 ? '#E4191F' : '#60a5fa' }}" fill-opacity="{{ $i === 5 ? 1 : .55 }}" class="fi-bar" style="animation-delay: {{ $i * .25 }}s"/>
        @endforeach
        <rect x="212" y="60" width="48" height="6" rx="3" fill="#ffffff" fill-opacity=".45"/><rect x="212" y="72" width="38" height="5" rx="2.5" fill="#ffffff" fill-opacity=".25"/>
        <rect x="212" y="84" width="44" height="5" rx="2.5" fill="#ffffff" fill-opacity=".25"/>
        {{-- pointer --}}
        <line x1="296" y1="150" x2="248" y2="98" stroke="#facc15" stroke-width="3" stroke-linecap="round" class="fi-point"/>
        {{-- trainees with check marks --}}
        @foreach([90, 150, 210, 270] as $i => $x)
            <g>
                <circle cx="{{ $x }}" cy="178" r="15" fill="url(#{{ $id }}-blue)" stroke="#60a5fa" stroke-opacity=".5"/>
                <circle cx="{{ $x }}" cy="173" r="5" fill="#ffffff" fill-opacity=".85"/><path d="M{{ $x - 8 }} 187a8 7 0 0116 0" fill="#ffffff" fill-opacity=".85"/>
                <rect x="{{ $x - 22 }}" y="198" width="44" height="22" rx="8" {!! $card !!}/>
                <g class="fi-check" style="animation-delay: {{ $i * 0.6 }}s">
                    <circle cx="{{ $x + 12 }}" cy="166" r="7" fill="#4ade80"/><path d="M{{ $x + 8.5 }} 166l2.5 2.5 4.5-5" stroke="#081234" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
            </g>
        @endforeach
        @break

    {{-- ───────────── Setup timeline ───────────── --}}
    @case('setup')
        <rect x="24" y="40" width="312" height="160" rx="18" {!! $card !!}/>
        <text x="312" y="68" text-anchor="start" direction="rtl" font-size="12" fill="#ffffff" font-weight="700">{!! $T('setup_plan') !!}</text>
        {{-- calendar --}}
        <g transform="translate(44 52)">
            <rect width="44" height="40" rx="8" fill="url(#{{ $id }}-red)"/><rect y="12" width="44" height="28" rx="0" fill="#ffffff"/>
            <rect y="34" width="44" height="6" rx="0" fill="#ffffff"/>
            @foreach([[6,17],[17,17],[28,17],[6,27],[17,27],[28,27]] as $c)
                <rect x="{{ $c[0] }}" y="{{ $c[1] }}" width="8" height="6" rx="1.5" fill="{{ $c === [28,27] ? '#E4191F' : '#cbd5ee' }}"/>
            @endforeach
        </g>
        {{-- track --}}
        <rect x="56" y="132" width="248" height="8" rx="4" fill="#ffffff" fill-opacity=".08"/>
        <rect x="56" y="132" width="248" height="8" rx="4" fill="url(#{{ $id }}-red)" class="fi-progress"/>
        @foreach(['analysis', 'prepare', 'migrate', 'golive'] as $i => $step)
            @php $cx = 304 - $i * 82.6; @endphp
            <circle cx="{{ $cx }}" cy="136" r="14" fill="#081234" stroke="#E4191F" stroke-width="3" class="fi-node" style="animation-delay: {{ $i * 0.9 }}s"/>
            <text x="{{ $cx }}" y="140.5" text-anchor="middle" font-size="12" font-weight="800" fill="#ffffff">{{ $i + 1 }}</text>
            <text x="{{ $cx }}" y="170" text-anchor="middle" direction="rtl" font-size="9.5" fill="#ffffff" fill-opacity=".75">{!! $T('step_' . $step) !!}</text>
        @endforeach
        @break

    {{-- ───────────── Customised to your business ───────────── --}}
    @case('customize')
        {{-- dashboard with movable widgets --}}
        <rect x="24" y="30" width="200" height="180" rx="16" {!! $card !!}/>
        <rect x="36" y="42" width="176" height="10" rx="3" fill="#1A2E6D"/>
        <g class="fi-swap-a"><rect x="36" y="62" width="84" height="64" rx="10" fill="#ffffff" fill-opacity=".06" stroke="#60a5fa" stroke-opacity=".4"/>
            @foreach([[46,20],[58,34],[70,26],[82,42],[94,30]] as $b)<rect x="{{ $b[0] }}" y="{{ 118 - $b[1] }}" width="8" height="{{ $b[1] }}" rx="2" fill="#60a5fa" fill-opacity=".7"/>@endforeach
        </g>
        <g class="fi-swap-b"><rect x="128" y="62" width="84" height="64" rx="10" fill="#ffffff" fill-opacity=".06" stroke="#E4191F" stroke-opacity=".5"/>
            <circle cx="170" cy="94" r="20" fill="none" stroke="#ffffff" stroke-opacity=".15" stroke-width="8"/>
            <circle cx="170" cy="94" r="20" fill="none" stroke="#E4191F" stroke-width="8" stroke-dasharray="80 126" transform="rotate(-90 170 94)"/>
        </g>
        <rect x="36" y="136" width="176" height="62" rx="10" fill="#ffffff" fill-opacity=".05"/>
        @foreach([0,1,2] as $r)<rect x="46" y="{{ 148 + $r * 16 }}" width="{{ 120 - $r * 22 }}" height="5" rx="2.5" fill="#ffffff" fill-opacity=".35"/><rect x="180" y="{{ 147 + $r * 16 }}" width="22" height="7" rx="3.5" fill="#4ade80" fill-opacity=".7"/>@endforeach
        {{-- settings panel --}}
        <g class="fi-float">
            <rect x="236" y="44" width="100" height="152" rx="16" {!! $card !!}/>
            <g transform="translate(286 74)"><circle r="16" fill="url(#{{ $id }}-red)"/>
                <g class="fi-spin"><circle r="7" fill="none" stroke="#ffffff" stroke-width="3"/>@foreach([0,45,90,135,180,225,270,315] as $a)<rect x="-1.6" y="-11" width="3.2" height="4" rx="1" fill="#ffffff" transform="rotate({{ $a }})"/>@endforeach</g></g>
            @foreach([0,1,2] as $t)
                <rect x="250" y="{{ 104 + $t * 28 }}" width="40" height="5" rx="2.5" fill="#ffffff" fill-opacity=".45"/>
                <rect x="298" y="{{ 100 + $t * 28 }}" width="26" height="14" rx="7" fill="{{ $t === 1 ? '#ffffff' : '#4ade80' }}" fill-opacity="{{ $t === 1 ? .15 : .9 }}"/>
                <circle cx="{{ $t === 1 ? 305 : 317 }}" cy="{{ 107 + $t * 28 }}" r="5" fill="#ffffff" class="{{ $t === 1 ? 'fi-toggle' : '' }}"/>
            @endforeach
        </g>
        @break

    {{-- ───────────── Request a demo ───────────── --}}
    @case('demo')
        {{-- laptop with play --}}
        <rect x="40" y="40" width="190" height="122" rx="12" {!! $card !!}/>
        <rect x="52" y="52" width="166" height="98" rx="6" fill="#ffffff" fill-opacity=".05"/>
        <rect x="60" y="60" width="150" height="9" rx="3" fill="#1A2E6D"/>
        @foreach([[70,24],[84,40],[98,30],[112,48],[126,36]] as $b)<rect x="{{ $b[0] }}" y="{{ 140 - $b[1] }}" width="9" height="{{ $b[1] }}" rx="2" fill="#60a5fa" fill-opacity=".5"/>@endforeach
        <g class="fi-pulse" style="transform-origin:170px 106px"><circle cx="170" cy="106" r="22" fill="url(#{{ $id }}-red)"/><path d="M164 96v20l16-10z" fill="#ffffff"/></g>
        <circle cx="170" cy="106" r="22" fill="none" stroke="#E4191F" stroke-width="2" class="fi-ring" style="transform-origin:170px 106px"/>
        <path d="M24 164h222l-14 14H38z" fill="#2b4da6"/>
        {{-- calendar card with chosen date --}}
        <g class="fi-float">
            <rect x="250" y="34" width="92" height="104" rx="14" {!! $card !!}/>
            <rect x="250" y="34" width="92" height="24" rx="14" fill="url(#{{ $id }}-red)"/><rect x="250" y="48" width="92" height="10" fill="#9a1016"/>
            @foreach(range(0, 11) as $c)
                @php $cx = 262 + ($c % 4) * 20; $cy = 70 + intdiv($c, 4) * 20; @endphp
                <rect x="{{ $cx }}" y="{{ $cy }}" width="14" height="12" rx="3" fill="{{ $c === 6 ? '#E4191F' : '#ffffff' }}" fill-opacity="{{ $c === 6 ? 1 : .12 }}" class="{{ $c === 6 ? 'fi-blink' : '' }}"/>
            @endforeach
        </g>
        {{-- chat bubble --}}
        <g class="fi-pop" style="animation-delay:.6s">
            <rect x="244" y="152" width="100" height="44" rx="14" fill="#4ade80" fill-opacity=".95"/>
            <path d="M262 196l-4 12 14-12z" fill="#4ade80" fill-opacity=".95"/>
            <text x="294" y="179" text-anchor="middle" direction="rtl" font-size="11" font-weight="700" fill="#081234">{!! $T('demo_booked') !!}</text>
        </g>
        @break

    {{-- ───────────── Data security ───────────── --}}
    @case('security')
        {{-- shield --}}
        <circle cx="120" cy="118" r="70" fill="none" stroke="#E4191F" stroke-opacity=".25" stroke-dasharray="4 8" class="fi-spin-slow"/>
        <g class="fi-pulse" style="transform-origin:120px 118px">
            <path d="M120 58l52 18v34c0 34-24 56-52 66-28-10-52-32-52-66V76z" fill="url(#{{ $id }}-red)"/>
            <rect x="102" y="112" width="36" height="28" rx="6" fill="#ffffff"/>
            <path d="M108 112v-8a12 12 0 0124 0v8" stroke="#ffffff" stroke-width="5" fill="none"/>
            <circle cx="120" cy="124" r="4" fill="#E4191F"/><rect x="118.5" y="125" width="3" height="8" rx="1.5" fill="#E4191F"/>
        </g>
        {{-- permissions card --}}
        <rect x="212" y="36" width="124" height="104" rx="14" {!! $card !!}/>
        <text x="324" y="58" text-anchor="start" direction="rtl" font-size="10.5" font-weight="700" fill="#ffffff">{!! $T('permissions') !!}</text>
        @foreach([['#4ade80', 1], ['#4ade80', 1], ['#ffffff', 0]] as $i => $p)
            <circle cx="226" cy="{{ 76 + $i * 20 }}" r="6" fill="#3b6fd4" fill-opacity=".7"/>
            <rect x="238" y="{{ 73 + $i * 20 }}" width="44" height="5" rx="2.5" fill="#ffffff" fill-opacity=".45"/>
            <rect x="296" y="{{ 69 + $i * 20 }}" width="28" height="14" rx="7" fill="{{ $p[0] }}" fill-opacity="{{ $p[1] ? .9 : .15 }}"/>
            <circle cx="{{ $p[1] ? 317 : 303 }}" cy="{{ 76 + $i * 20 }}" r="5" fill="#ffffff"/>
        @endforeach
        {{-- backup card --}}
        <rect x="212" y="152" width="124" height="56" rx="14" {!! $card !!}/>
        <g transform="translate(240 180)" class="fi-spin"><path d="M-10 -2a10 10 0 0118-4" stroke="#60a5fa" stroke-width="3" fill="none" stroke-linecap="round"/><path d="M10 2a10 10 0 01-18 4" stroke="#60a5fa" stroke-width="3" fill="none" stroke-linecap="round"/><path d="M8 -12l1 7-7-1z" fill="#60a5fa"/><path d="M-8 12l-1-7 7 1z" fill="#60a5fa"/></g>
        <text x="324" y="177" text-anchor="start" direction="rtl" font-size="10.5" font-weight="700" fill="#ffffff">{!! $T('backup') !!}</text>
        <rect x="262" y="186" width="62" height="5" rx="2.5" fill="#ffffff" fill-opacity=".12"/><rect x="262" y="186" width="62" height="5" rx="2.5" fill="#4ade80" class="fi-progress"/>
        @break

    {{-- ───────────── Technical support ───────────── --}}
    @case('support')
        {{-- agent with headset --}}
        <g class="fi-float">
            <circle cx="86" cy="104" r="50" fill="url(#{{ $id }}-blue)" stroke="#60a5fa" stroke-opacity=".4"/>
            <circle cx="86" cy="94" r="16" fill="#ffffff" fill-opacity=".9"/>
            <path d="M58 136a28 22 0 0156 0" fill="#ffffff" fill-opacity=".9"/>
            <path d="M66 94a20 20 0 0140 0" stroke="#E4191F" stroke-width="4" fill="none" stroke-linecap="round"/>
            <rect x="60" y="90" width="9" height="14" rx="3" fill="#E4191F"/><rect x="103" y="90" width="9" height="14" rx="3" fill="#E4191F"/>
            <path d="M107 104c0 8-6 12-14 12" stroke="#E4191F" stroke-width="2.5" fill="none"/>
            <circle cx="122" cy="68" r="7" fill="#4ade80" class="fi-blink"/>
        </g>
        <text x="86" y="176" text-anchor="middle" direction="rtl" font-size="11" fill="#ffffff" fill-opacity=".75" font-weight="600">{!! $T('support_team') !!}</text>
        {{-- conversation --}}
        <g class="fi-msg" style="animation-delay:0s">
            <rect x="162" y="40" width="132" height="32" rx="12" fill="#ffffff" fill-opacity=".12"/>
            <rect x="174" y="50" width="88" height="5" rx="2.5" fill="#ffffff" fill-opacity=".6"/><rect x="174" y="60" width="60" height="4" rx="2" fill="#ffffff" fill-opacity=".35"/>
        </g>
        <g class="fi-msg" style="animation-delay:1s">
            <rect x="190" y="84" width="140" height="32" rx="12" fill="#25D366"/>
            <rect x="202" y="94" width="96" height="5" rx="2.5" fill="#ffffff" fill-opacity=".9"/><rect x="202" y="104" width="70" height="4" rx="2" fill="#ffffff" fill-opacity=".7"/>
        </g>
        <g class="fi-msg" style="animation-delay:2s">
            <rect x="162" y="128" width="168" height="54" rx="14" {!! $card !!}/>
            <circle cx="182" cy="155" r="10" fill="#4ade80"/><path d="M177 155l3.5 3.5 6.5-7" stroke="#081234" stroke-width="2.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            <text x="318" y="152" text-anchor="start" direction="rtl" font-size="11.5" font-weight="700" fill="#ffffff">{!! $T('resolved') !!}</text>
            <rect x="238" y="160" width="80" height="4" rx="2" fill="#ffffff" fill-opacity=".3"/>
        </g>
        @break
@endswitch
</svg>
