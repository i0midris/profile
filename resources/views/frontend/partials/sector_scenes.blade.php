{{--
    Animated sector scenes (inline SVG, 320x200). Each shows a workplace with an
    Albaseet Soft screen running. Usage: @include('frontend.partials.sector_scenes', ['scene' => 'restaurant'])
    Colours are fixed (navy/red brand) so the scenes look the same in light and dark themes.
--}}
@php $uid = 'sc' . $scene; @endphp
<svg viewBox="0 0 320 200" class="sector-scene" role="img" aria-hidden="true" direction="ltr" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="{{ $uid }}-bg" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#1d3a85"/><stop offset="1" stop-color="#0e1a44"/>
        </linearGradient>
        <linearGradient id="{{ $uid }}-scr" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#f6f8fd"/><stop offset="1" stop-color="#e3e9f7"/>
        </linearGradient>
    </defs>
    <rect width="320" height="200" fill="url(#{{ $uid }}-bg)"/>
    <circle cx="270" cy="30" r="60" fill="#3b6fd4" opacity=".18"/>
    <circle cx="40" cy="190" r="70" fill="#e4191f" opacity=".12"/>

@switch($scene)
    @case('restaurant')
        {{-- hanging lamps --}}
        <g class="sc-sway" style="transform-origin:70px 0">
            <line x1="70" y1="0" x2="70" y2="26" stroke="#8fa3d6" stroke-width="1.5"/>
            <path d="M58 26h24l-5 12H63z" fill="#f5b942"/><ellipse cx="70" cy="40" rx="16" ry="5" fill="#ffd77a" opacity=".35" class="sc-glow"/>
        </g>
        <g class="sc-sway" style="transform-origin:250px 0;animation-delay:1.2s">
            <line x1="250" y1="0" x2="250" y2="22" stroke="#8fa3d6" stroke-width="1.5"/>
            <path d="M238 22h24l-5 12h-14z" fill="#f5b942"/>
        </g>
        {{-- menu board --}}
        <rect x="190" y="40" width="96" height="46" rx="6" fill="#132558" stroke="#2b4da6"/>
        <rect x="200" y="50" width="40" height="4" rx="2" fill="#e4191f"/>
        <rect x="200" y="60" width="70" height="3" rx="1.5" fill="#6f86c7"/><rect x="200" y="68" width="58" height="3" rx="1.5" fill="#6f86c7"/><rect x="200" y="76" width="64" height="3" rx="1.5" fill="#6f86c7"/>
        {{-- counter --}}
        <rect x="0" y="150" width="320" height="50" fill="#7a4a2a"/><rect x="0" y="146" width="320" height="8" rx="2" fill="#9c6239"/>
        {{-- POS terminal --}}
        <rect x="70" y="138" width="10" height="10" fill="#26345c"/><rect x="58" y="146" width="34" height="5" rx="2" fill="#26345c"/>
        <rect x="34" y="82" width="82" height="58" rx="6" fill="#1b2650"/>
        <rect x="39" y="87" width="72" height="48" rx="3" fill="url(#{{ $uid }}-scr)"/>
        <rect x="42" y="90" width="66" height="7" rx="2" fill="#1a2e6d"/><circle cx="104" cy="93.5" r="2" fill="#e4191f"/>
        <g class="sc-bars">
            <rect x="46" y="112" width="7" height="18" rx="1.5" fill="#bccaeb"/><rect x="56" y="106" width="7" height="24" rx="1.5" fill="#bccaeb"/>
            <rect x="66" y="116" width="7" height="14" rx="1.5" fill="#bccaeb"/><rect x="76" y="101" width="7" height="29" rx="1.5" fill="#e4191f"/>
            <rect x="86" y="110" width="7" height="20" rx="1.5" fill="#bccaeb"/><rect x="96" y="104" width="7" height="26" rx="1.5" fill="#bccaeb"/>
        </g>
        {{-- receipt printer --}}
        <rect x="124" y="128" width="30" height="20" rx="3" fill="#26345c"/>
        <rect x="129" y="112" width="20" height="18" rx="1" fill="#fff" class="sc-receipt"/>
        <rect x="132" y="116" width="14" height="1.6" fill="#c4cbe0"/><rect x="132" y="120" width="10" height="1.6" fill="#c4cbe0"/><rect x="132" y="124" width="12" height="1.6" fill="#c4cbe0"/>
        {{-- coffee cups with steam --}}
        <path d="M222 128h22l-3 18h-16z" fill="#fff"/><path d="M244 132c6 0 6 8 0 8" stroke="#fff" stroke-width="2.5" fill="none"/>
        <path class="sc-steam" d="M229 122c-3-4 3-6 0-10" stroke="#cfd8f0" stroke-width="2" fill="none" stroke-linecap="round"/>
        <path class="sc-steam" style="animation-delay:.8s" d="M237 122c-3-4 3-6 0-10" stroke="#cfd8f0" stroke-width="2" fill="none" stroke-linecap="round"/>
        <path d="M262 132h18l-2 14h-14z" fill="#e4191f"/>
        @break

    @case('pharmacy')
        {{-- green cross sign --}}
        <g class="sc-pulse" style="transform-origin:272px 38px">
            <rect x="254" y="20" width="36" height="36" rx="8" fill="#1f9d63"/>
            <path d="M268 28h8v6h6v8h-6v6h-8v-6h-6v-8h6z" fill="#fff"/>
        </g>
        {{-- shelves --}}
        @foreach([60, 98] as $sy)
            <rect x="20" y="{{ $sy + 26 }}" width="200" height="5" rx="2" fill="#2b4da6"/>
            @foreach([[26,'#e4191f'],[48,'#3b6fd4'],[70,'#f5b942'],[92,'#1f9d63'],[114,'#ffffff'],[136,'#3b6fd4'],[158,'#e4191f'],[180,'#f5b942'],[200,'#1f9d63']] as $i => $box)
                <rect x="{{ $box[0] }}" y="{{ $sy + 6 + ($i % 3) * 3 }}" width="16" height="{{ 20 - ($i % 3) * 3 }}" rx="2" fill="{{ $box[1] }}" opacity=".9"/>
            @endforeach
        @endforeach
        {{-- counter + screen with expiry alert --}}
        <rect x="0" y="160" width="320" height="40" fill="#e9eef8"/><rect x="0" y="156" width="320" height="6" fill="#cfd8ec"/>
        <rect x="200" y="96" width="96" height="62" rx="6" fill="#1b2650"/>
        <rect x="205" y="101" width="86" height="52" rx="3" fill="url(#{{ $uid }}-scr)"/>
        <rect x="208" y="104" width="80" height="7" rx="2" fill="#1a2e6d"/>
        @foreach([0,1,2,3] as $r)
            <rect x="212" y="{{ 116 + $r * 9 }}" width="44" height="3" rx="1.5" fill="#9aa9cf"/>
            <rect x="262" y="{{ 115 + $r * 9 }}" width="20" height="5" rx="2.5" fill="{{ $r === 1 ? '#fde3e4' : '#e2f4ea' }}"/>
        @endforeach
        <g class="sc-blink"><circle cx="286" cy="124" r="5" fill="#e4191f"/><rect x="285" y="121" width="2" height="4" fill="#fff"/><rect x="285" y="126" width="2" height="1.5" fill="#fff"/></g>
        <rect x="244" y="158" width="8" height="4" fill="#1b2650"/>
        @break

    @case('supermarket')
        {{-- shelves in the background --}}
        @foreach([[16,'#e4191f'],[56,'#f5b942'],[96,'#1f9d63'],[136,'#3b6fd4'],[176,'#e4191f'],[216,'#1f9d63'],[256,'#f5b942']] as $i => $s)
            <rect x="{{ $s[0] }}" y="34" width="34" height="50" rx="3" fill="#132558"/>
            <rect x="{{ $s[0] + 4 }}" y="40" width="26" height="12" rx="2" fill="{{ $s[1] }}" opacity=".85"/>
            <rect x="{{ $s[0] + 4 }}" y="58" width="26" height="12" rx="2" fill="{{ $s[1] }}" opacity=".55"/>
        @endforeach
        {{-- checkout belt with moving items --}}
        <rect x="0" y="150" width="320" height="50" fill="#26345c"/>
        <rect x="20" y="136" width="200" height="16" rx="8" fill="#0d1636"/>
        <g class="sc-belt">
            <rect x="30" y="122" width="18" height="16" rx="3" fill="#f5b942"/><circle cx="76" cy="129" r="9" fill="#e4191f"/>
            <rect x="96" y="118" width="14" height="20" rx="3" fill="#1f9d63"/><rect x="128" y="124" width="22" height="14" rx="3" fill="#ffffff"/>
        </g>
        {{-- scanner with moving beam --}}
        <rect x="176" y="122" width="40" height="16" rx="3" fill="#1b2650"/>
        <rect x="182" y="126" width="28" height="3" rx="1.5" fill="#e4191f" class="sc-scan"/>
        {{-- POS screen with total --}}
        <rect x="232" y="88" width="72" height="54" rx="6" fill="#1b2650"/>
        <rect x="237" y="93" width="62" height="44" rx="3" fill="url(#{{ $uid }}-scr)"/>
        <rect x="240" y="96" width="56" height="6" rx="2" fill="#1a2e6d"/>
        <rect x="242" y="107" width="34" height="3" rx="1.5" fill="#9aa9cf"/><rect x="242" y="113" width="28" height="3" rx="1.5" fill="#9aa9cf"/><rect x="242" y="119" width="30" height="3" rx="1.5" fill="#9aa9cf"/>
        <rect x="242" y="126" width="52" height="8" rx="2" fill="#e4191f" class="sc-blink"/>
        <rect x="262" y="142" width="12" height="8" fill="#1b2650"/>
        @break

    @case('warehouse')
        {{-- racks --}}
        @foreach([24, 104] as $rx)
            <rect x="{{ $rx }}" y="30" width="4" height="124" fill="#2b4da6"/><rect x="{{ $rx + 64 }}" y="30" width="4" height="124" fill="#2b4da6"/>
            @foreach([62, 102, 142] as $ry)
                <rect x="{{ $rx }}" y="{{ $ry }}" width="68" height="4" fill="#2b4da6"/>
                <rect x="{{ $rx + 8 }}" y="{{ $ry - 22 }}" width="22" height="22" rx="2" fill="#c98a46"/><rect x="{{ $rx + 36 }}" y="{{ $ry - 16 }}" width="24" height="16" rx="2" fill="#b07436"/>
                <rect x="{{ $rx + 12 }}" y="{{ $ry - 14 }}" width="14" height="4" rx="1" fill="#f3e2c9"/>
            @endforeach
        @endforeach
        <rect x="0" y="154" width="320" height="46" fill="#26345c"/>
        {{-- moving box on a trolley --}}
        <g class="sc-trolley">
            <rect x="0" y="128" width="26" height="22" rx="2" fill="#c98a46"/><rect x="0" y="150" width="30" height="4" fill="#8fa3d6"/>
            <circle cx="6" cy="157" r="3" fill="#0d1636"/><circle cx="24" cy="157" r="3" fill="#0d1636"/>
        </g>
        {{-- handheld tablet with stock chart --}}
        <g class="sc-float">
            <rect x="196" y="56" width="104" height="74" rx="8" fill="#1b2650"/>
            <rect x="202" y="62" width="92" height="62" rx="4" fill="url(#{{ $uid }}-scr)"/>
            <rect x="206" y="66" width="84" height="7" rx="2" fill="#1a2e6d"/>
            <polyline class="sc-line" points="210,112 224,100 238,106 252,90 266,96 284,80" fill="none" stroke="#e4191f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            <rect x="210" y="116" width="76" height="3" rx="1.5" fill="#cbd5ee"/>
        </g>
        @break

    @case('school')
        {{-- board --}}
        <rect x="24" y="28" width="132" height="70" rx="5" fill="#16523c" stroke="#7a4a2a" stroke-width="4"/>
        <path d="M40 50h40M40 62h70M40 74h52" stroke="#d8efe3" stroke-width="3" stroke-linecap="round" opacity=".85"/>
        {{-- clock --}}
        <circle cx="210" cy="40" r="16" fill="#fff"/><circle cx="210" cy="40" r="16" fill="none" stroke="#2b4da6" stroke-width="3"/>
        <line x1="210" y1="40" x2="210" y2="30" stroke="#1a2e6d" stroke-width="2.5" stroke-linecap="round" class="sc-hand" style="transform-origin:210px 40px"/>
        <line x1="210" y1="40" x2="218" y2="40" stroke="#e4191f" stroke-width="2" stroke-linecap="round"/>
        {{-- bookshelf --}}
        <rect x="248" y="58" width="56" height="92" rx="3" fill="#132558"/>
        @foreach([[252,'#e4191f'],[260,'#3b6fd4'],[268,'#f5b942'],[276,'#1f9d63'],[284,'#ffffff'],[292,'#e4191f']] as $b)
            <rect x="{{ $b[0] }}" y="64" width="6" height="26" rx="1" fill="{{ $b[1] }}" opacity=".9"/><rect x="{{ $b[0] }}" y="100" width="6" height="22" rx="1" fill="{{ $b[1] }}" opacity=".6"/>
        @endforeach
        {{-- desk + monitor with student/HR system --}}
        <rect x="0" y="160" width="320" height="40" fill="#7a4a2a"/><rect x="40" y="152" width="190" height="10" rx="3" fill="#9c6239"/>
        <rect x="76" y="94" width="104" height="58" rx="6" fill="#1b2650"/>
        <rect x="81" y="99" width="94" height="44" rx="3" fill="url(#{{ $uid }}-scr)"/>
        <rect x="84" y="102" width="88" height="7" rx="2" fill="#1a2e6d"/>
        @foreach([0,1,2] as $r)
            <circle cx="92" cy="{{ 117 + $r * 9 }}" r="3" fill="#bccaeb"/><rect x="99" y="{{ 115 + $r * 9 }}" width="44" height="3" rx="1.5" fill="#9aa9cf"/>
            <rect x="150" y="{{ 114 + $r * 9 }}" width="18" height="5" rx="2.5" fill="{{ $r === 2 ? '#fde3e4' : '#e2f4ea' }}" class="{{ $r === 0 ? 'sc-blink' : '' }}"/>
        @endforeach
        <rect x="122" y="146" width="12" height="7" fill="#1b2650"/>
        @break

    @case('distribution')
        {{-- road --}}
        <rect x="0" y="150" width="320" height="50" fill="#26345c"/>
        <path d="M0 175h320" stroke="#f5b942" stroke-width="3" stroke-dasharray="18 14" class="sc-road"/>
        {{-- buildings --}}
        <rect x="18" y="70" width="40" height="80" fill="#132558"/><rect x="62" y="92" width="34" height="58" fill="#17306c"/><rect x="100" y="60" width="30" height="90" fill="#132558"/>
        @foreach([[24,78],[40,78],[24,96],[40,96],[24,114],[40,114],[68,100],[82,100],[106,70],[118,70],[106,90],[118,90]] as $w)
            <rect x="{{ $w[0] }}" y="{{ $w[1] }}" width="8" height="8" rx="1" fill="#f5d27a" opacity=".55"/>
        @endforeach
        {{-- moving truck --}}
        <g class="sc-truck">
            <rect x="140" y="118" width="70" height="38" rx="4" fill="#ffffff"/>
            <rect x="148" y="128" width="40" height="6" rx="2" fill="#1a2e6d"/><rect x="148" y="138" width="26" height="4" rx="2" fill="#e4191f"/>
            <path d="M210 128h18l12 14v14h-30z" fill="#e4191f"/><rect x="214" y="132" width="12" height="9" rx="1.5" fill="#bcd2ff"/>
            <circle cx="160" cy="158" r="7" fill="#0d1636"/><circle cx="160" cy="158" r="3" fill="#8fa3d6"/>
            <circle cx="226" cy="158" r="7" fill="#0d1636"/><circle cx="226" cy="158" r="3" fill="#8fa3d6"/>
        </g>
        {{-- phone with sales-rep app + map pin --}}
        <g class="sc-float">
            <rect x="254" y="28" width="52" height="92" rx="9" fill="#1b2650"/>
            <rect x="258" y="36" width="44" height="76" rx="4" fill="url(#{{ $uid }}-scr)"/>
            <rect x="260" y="38" width="40" height="6" rx="2" fill="#1a2e6d"/>
            <path d="M262 52h36v34h-36z" fill="#dfe7f7"/><path d="M262 70l12-8 10 6 14-10" stroke="#9aa9cf" stroke-width="2" fill="none"/>
            <g class="sc-pin"><path d="M280 54c-5 0-8 3.6-8 8 0 6 8 13 8 13s8-7 8-13c0-4.4-3-8-8-8z" fill="#e4191f"/><circle cx="280" cy="62" r="2.8" fill="#fff"/></g>
            <rect x="262" y="92" width="36" height="6" rx="3" fill="#1f9d63"/><rect x="262" y="102" width="26" height="4" rx="2" fill="#9aa9cf"/>
        </g>
        @break
@endswitch

    {{-- Albaseet Soft badge --}}
    <g transform="translate(10 10)">
        <rect width="74" height="18" rx="9" fill="#ffffff" opacity=".92"/>
        <rect x="5" y="4" width="10" height="10" rx="3" fill="#e4191f"/>
        <text x="19" y="12.6" direction="ltr" style="unicode-bidi:isolate" font-size="8.5" font-weight="700" fill="#1a2e6d" font-family="Segoe UI, Tahoma, sans-serif">Albaseet Soft</text>
    </g>
</svg>
