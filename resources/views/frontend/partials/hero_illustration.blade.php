{{--
    Hero Illustration — ERP · Cloud · Cyber Security
    Central hub = Cloud Platform icon
    All text goes through __() for i18n
--}}
<div style="position:relative;min-height:460px;isolation:isolate;">

    {{-- ── Ambient glows (transparent, no box) ─────────────── --}}
    <div style="position:absolute;top:-25px;left:4%;width:250px;height:250px;background:radial-gradient(circle,rgba(26,46,109,.28),transparent 68%);border-radius:50%;filter:blur(60px);pointer-events:none;animation:hiw-pulse 8s ease-in-out infinite;"></div>
    <div style="position:absolute;bottom:-15px;right:3%;width:220px;height:220px;background:radial-gradient(circle,rgba(228,25,31,.15),transparent 68%);border-radius:50%;filter:blur(55px);pointer-events:none;animation:hiw-pulse 8s ease-in-out infinite;animation-delay:4s;"></div>

    {{-- ── SVG connectors + animated data flow ─────────────── --}}
    <svg style="position:absolute;inset:0;width:100%;height:100%;overflow:visible;pointer-events:none;z-index:1;" viewBox="0 0 460 460" preserveAspectRatio="none">
        <defs>
            <filter id="hiw-gl" x="-120%" y="-120%" width="340%" height="340%"><feGaussianBlur stdDeviation="4" result="b"/><feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
        </defs>
        {{-- ERP → hub --}}
        <path d="M160,130 Q195,180 228,225" fill="none" stroke="rgba(228,25,31,.4)" stroke-width="1.6" stroke-dasharray="7 4"><animate attributeName="stroke-dashoffset" from="0" to="-22" dur="2s" repeatCount="indefinite"/></path>
        {{-- Cloud → hub --}}
        <path d="M300,130 Q265,180 232,225" fill="none" stroke="rgba(59,111,212,.5)" stroke-width="1.6" stroke-dasharray="7 4"><animate attributeName="stroke-dashoffset" from="0" to="-22" dur="2.5s" repeatCount="indefinite"/></path>
        {{-- Cyber → hub --}}
        <path d="M230,388 Q230,330 230,265" fill="none" stroke="rgba(228,25,31,.35)" stroke-width="1.6" stroke-dasharray="7 4"><animate attributeName="stroke-dashoffset" from="0" to="-22" dur="3s" repeatCount="indefinite"/></path>
        {{-- Glowing endpoint dots --}}
        <circle cx="160" cy="130" r="4" fill="#E4191F" opacity=".9" filter="url(#hiw-gl)"/>
        <circle cx="300" cy="130" r="4" fill="#3b6fd4" opacity=".9" filter="url(#hiw-gl)"/>
        <circle cx="230" cy="388" r="4" fill="#E4191F" opacity=".9" filter="url(#hiw-gl)"/>
        {{-- Animated packets --}}
        <circle r="5" fill="#E4191F" filter="url(#hiw-gl)"><animateMotion dur="2.4s" repeatCount="indefinite" path="M160,130 Q195,180 228,225"/><animate attributeName="opacity" values="0;1;0" dur="2.4s" repeatCount="indefinite"/></circle>
        <circle r="5" fill="#60a5fa" filter="url(#hiw-gl)"><animateMotion dur="2.8s" repeatCount="indefinite" path="M300,130 Q265,180 232,225"/><animate attributeName="opacity" values="0;1;0" dur="2.8s" repeatCount="indefinite"/></circle>
        <circle r="5" fill="rgba(255,255,255,.85)"><animateMotion dur="3.2s" repeatCount="indefinite" path="M230,388 Q230,330 230,265"/><animate attributeName="opacity" values="0;.9;0" dur="3.2s" repeatCount="indefinite"/></circle>
    </svg>

    {{-- ── Central Cloud Hub ───────────────────────────────── --}}
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-52%);z-index:5;text-align:center;">
        {{-- Outer orbit --}}
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:156px;height:156px;border-radius:50%;border:1px solid rgba(59,111,212,.2);animation:hiw-spin 28s linear infinite reverse;pointer-events:none;">
            <div style="position:absolute;top:-5px;left:50%;transform:translateX(-50%);width:10px;height:10px;border-radius:50%;background:#3b6fd4;box-shadow:0 0 14px rgba(59,111,212,.8);"></div>
        </div>
        {{-- Inner dashed ring --}}
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:116px;height:116px;border-radius:50%;border:1px dashed rgba(255,255,255,.1);animation:hiw-spin 15s linear infinite;pointer-events:none;"></div>
        {{-- Cloud disk — the actual cloud icon --}}
        <div style="width:88px;height:88px;border-radius:50%;background:linear-gradient(145deg,#1A2E6D 0%,#2a4fc8 100%);border:3px solid rgba(59,111,212,.7);display:flex;align-items:center;justify-content:center;box-shadow:0 0 0 10px rgba(26,46,109,.12),0 0 40px rgba(26,46,109,.6),0 0 60px rgba(59,111,212,.15);margin:0 auto;">
            {{-- Cloud SVG icon --}}
            <svg width="42" height="42" viewBox="0 0 48 48" fill="none">
                <path d="M36 22h-1.5C33.5 14.5 27.3 9 20 9c-8.3 0-15 6.7-15 15 0 3 .9 5.8 2.4 8.1C8.5 34 10.9 35 14 35h22c4.4 0 8-3.6 8-8s-3.6-8-8-8z" fill="rgba(255,255,255,.15)" stroke="white" stroke-width="2"/>
                <circle cx="18" cy="28" r="2" fill="rgba(255,255,255,.6)"/>
                <circle cx="26" cy="28" r="2" fill="rgba(255,255,255,.6)"/>
                <circle cx="34" cy="28" r="2" fill="rgba(255,255,255,.6)"/>
                <line x1="18" y1="24" x2="18" y2="26" stroke="rgba(255,255,255,.4)" stroke-width="1.5" stroke-linecap="round"/>
                <line x1="26" y1="22" x2="26" y2="26" stroke="rgba(255,255,255,.4)" stroke-width="1.5" stroke-linecap="round"/>
                <line x1="34" y1="24" x2="34" y2="26" stroke="rgba(255,255,255,.4)" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </div>
        {{-- Label pill --}}
        <div style="margin-top:10px;background:rgba(26,46,109,.55);border:1px solid rgba(59,111,212,.25);border-radius:20px;padding:4px 14px;display:inline-block;backdrop-filter:blur(10px);">
            <p style="font-size:9px;color:rgba(255,255,255,.65);margin:0;white-space:nowrap;letter-spacing:.7px;text-transform:uppercase;">{{ __('frontend.hero_hub_label') }}</p>
        </div>
    </div>

    {{-- ── ERP Card — top left ──────────────────────────────── --}}
    <div style="position:absolute;top:8px;left:0;width:178px;z-index:4;animation:hiw-float 7s ease-in-out infinite;">
        <div style="background:rgba(8,18,52,.92);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:16px;backdrop-filter:blur(20px);box-shadow:0 12px 40px rgba(0,0,0,.5),inset 0 1px 0 rgba(255,255,255,.05);">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                <div style="width:38px;height:38px;border-radius:10px;flex-shrink:0;background:linear-gradient(135deg,#E4191F,#7a0a0d);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 18px rgba(228,25,31,.45);">
                    <svg width="19" height="19" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="6" height="5" rx="1"/><rect x="9" y="3" width="6" height="5" rx="1"/><rect x="16" y="3" width="6" height="5" rx="1"/><rect x="2" y="10" width="6" height="5" rx="1"/><rect x="9" y="10" width="6" height="5" rx="1"/><rect x="16" y="10" width="6" height="5" rx="1"/><rect x="2" y="17" width="6" height="4" rx="1"/><rect x="9" y="17" width="6" height="4" rx="1"/></svg>
                </div>
                <div>
                    <p style="font-size:9px;color:rgba(255,255,255,.4);margin:0;text-transform:uppercase;letter-spacing:.5px;">{{ __('frontend.hero_erp_label') }}</p>
                    <p style="font-size:14px;color:white;font-weight:700;margin:2px 0 0;line-height:1.2;">{{ __('frontend.hero_erp_title') }}</p>
                </div>
            </div>
            @foreach([
                [__('frontend.hero_erp_module_hr'),  85, '#E4191F'],
                [__('frontend.hero_erp_module_acc'), 92, '#60a5fa'],
                [__('frontend.hero_erp_module_inv'), 78, 'rgba(255,255,255,.38)'],
            ] as $mp)
            <div style="margin-bottom:8px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:3px;">
                    <span style="font-size:9px;color:rgba(255,255,255,.38);">{{ $mp[0] }}</span>
                    <span style="font-size:9px;color:{{ $mp[2] }};font-weight:700;">{{ $mp[1] }}%</span>
                </div>
                <div style="height:4px;background:rgba(255,255,255,.06);border-radius:3px;overflow:hidden;">
                    <div style="height:100%;width:{{ $mp[1] }}%;background:{{ $mp[2] }};border-radius:3px;"></div>
                </div>
            </div>
            @endforeach
            <div style="margin-top:10px;display:flex;justify-content:space-between;align-items:center;padding-top:9px;border-top:1px solid rgba(255,255,255,.06);">
                <span style="font-size:9px;color:rgba(255,255,255,.3);">{{ __('frontend.hero_erp_modules_active') }}</span>
                <span style="font-size:11px;color:#E4191F;font-weight:800;">12 / 14</span>
            </div>
        </div>
    </div>

    {{-- ── Cloud Card — top right ───────────────────────────── --}}
    <div style="position:absolute;top:8px;right:0;width:178px;z-index:4;animation:hiw-float 7s ease-in-out infinite;animation-delay:2.3s;">
        <div style="background:rgba(8,18,52,.92);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:16px;backdrop-filter:blur(20px);box-shadow:0 12px 40px rgba(0,0,0,.5),inset 0 1px 0 rgba(255,255,255,.05);">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                <div style="width:38px;height:38px;border-radius:10px;flex-shrink:0;background:linear-gradient(135deg,#1A2E6D,#2d52be);border:1px solid rgba(59,111,212,.3);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 18px rgba(26,46,109,.55);">
                    <svg width="19" height="19" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></svg>
                </div>
                <div>
                    <p style="font-size:9px;color:rgba(255,255,255,.4);margin:0;text-transform:uppercase;letter-spacing:.5px;">{{ __('frontend.hero_cloud_label') }}</p>
                    <p style="font-size:14px;color:white;font-weight:700;margin:2px 0 0;line-height:1.2;">{{ __('frontend.hero_cloud_title') }}</p>
                </div>
            </div>
            {{-- Server status rows --}}
            @foreach([
                [__('frontend.hero_cloud_servers'),   '24', '#4ade80'],
                [__('frontend.hero_cloud_storage'),   '2.4 TB', '#60a5fa'],
                [__('frontend.hero_cloud_bandwidth'), '10 Gbps', 'rgba(255,255,255,.5)'],
            ] as $cs)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:5px 0;{{ !$loop->last ? 'border-bottom:1px solid rgba(255,255,255,.05);' : '' }}">
                <span style="font-size:9px;color:rgba(255,255,255,.38);">{{ $cs[0] }}</span>
                <span style="font-size:10px;color:{{ $cs[2] }};font-weight:700;">{{ $cs[1] }}</span>
            </div>
            @endforeach
            {{-- Status footer --}}
            <div style="margin-top:10px;display:flex;justify-content:space-between;align-items:center;padding-top:9px;border-top:1px solid rgba(255,255,255,.06);">
                <span style="font-size:9px;color:rgba(255,255,255,.3);">{{ __('frontend.hero_cloud_status') }}</span>
                <span style="display:flex;align-items:center;gap:4px;font-size:9px;color:#4ade80;font-weight:800;">
                    <span style="width:6px;height:6px;border-radius:50%;background:#4ade80;display:inline-block;animation:hiw-blink 1.5s ease-in-out infinite;flex-shrink:0;"></span>
                    {{ __('frontend.hero_cloud_operational') }}
                </span>
            </div>
        </div>
    </div>

    {{-- ── Cyber Security Card — bottom centre ─────────────── --}}
    <div style="position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:232px;z-index:4;animation:hiw-float-cx 7s ease-in-out infinite;animation-delay:4.5s;">
        <div style="background:rgba(8,18,52,.93);border:1px solid rgba(228,25,31,.18);border-radius:20px;padding:16px;backdrop-filter:blur(20px);box-shadow:0 12px 40px rgba(0,0,0,.5),inset 0 1px 0 rgba(255,255,255,.05);">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:13px;">
                <div style="width:38px;height:38px;border-radius:10px;flex-shrink:0;background:linear-gradient(135deg,#E4191F,#4a0508);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 18px rgba(228,25,31,.45);">
                    <svg width="19" height="19" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div style="flex:1;">
                    <p style="font-size:9px;color:rgba(255,255,255,.4);margin:0;text-transform:uppercase;letter-spacing:.5px;">{{ __('frontend.hero_cyber_label') }}</p>
                    <p style="font-size:14px;color:white;font-weight:700;margin:2px 0 0;line-height:1.2;">{{ __('frontend.hero_cyber_title') }}</p>
                </div>
                <div style="background:rgba(74,222,128,.12);border:1px solid rgba(74,222,128,.35);border-radius:7px;padding:3px 8px;flex-shrink:0;">
                    <span style="font-size:8px;color:#4ade80;font-weight:800;letter-spacing:.5px;display:flex;align-items:center;gap:3px;">
                        <span style="width:5px;height:5px;border-radius:50%;background:#4ade80;display:inline-block;animation:hiw-blink 1.2s ease-in-out infinite;"></span>
                        {{ __('frontend.hero_cyber_status') }}
                    </span>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;padding-top:12px;border-top:1px solid rgba(255,255,255,.06);text-align:center;">
                <div style="padding:0 6px;">
                    <p style="font-size:17px;font-weight:800;color:#4ade80;margin:0;line-height:1;">1,248</p>
                    <p style="font-size:8px;color:rgba(255,255,255,.3);margin:4px 0 0;line-height:1.3;">{{ __('frontend.hero_cyber_threats') }}</p>
                </div>
                <div style="padding:0 6px;border-left:1px solid rgba(255,255,255,.06);border-right:1px solid rgba(255,255,255,.06);">
                    <p style="font-size:17px;font-weight:800;color:#facc15;margin:0;line-height:1;">3</p>
                    <p style="font-size:8px;color:rgba(255,255,255,.3);margin:4px 0 0;line-height:1.3;">{{ __('frontend.hero_cyber_vulns') }}</p>
                </div>
                <div style="padding:0 6px;">
                    <p style="font-size:17px;font-weight:800;color:rgba(255,255,255,.65);margin:0;line-height:1;">847</p>
                    <p style="font-size:8px;color:rgba(255,255,255,.3);margin:4px 0 0;line-height:1.3;">{{ __('frontend.hero_cyber_rules') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Side badges ─────────────────────────────────────── --}}
    <div style="position:absolute;top:43%;left:-2px;transform:translateY(-50%);z-index:4;animation:hiw-float 6s ease-in-out infinite;animation-delay:1s;">
        <div style="background:rgba(8,18,52,.9);border:1px solid rgba(228,25,31,.3);border-radius:14px;padding:9px 15px;backdrop-filter:blur(14px);text-align:center;box-shadow:0 6px 24px rgba(0,0,0,.4);">
            <p style="font-size:21px;font-weight:900;color:white;margin:0;line-height:1;">500+</p>
            <p style="font-size:8px;color:rgba(255,255,255,.42);margin:3px 0 0;text-transform:uppercase;letter-spacing:.6px;">{{ __('frontend.hero_stat_clients') }}</p>
        </div>
    </div>
    <div style="position:absolute;top:43%;right:-2px;transform:translateY(-50%);z-index:4;animation:hiw-float 6s ease-in-out infinite;animation-delay:3.5s;">
        <div style="background:rgba(8,18,52,.9);border:1px solid rgba(59,111,212,.3);border-radius:14px;padding:9px 15px;backdrop-filter:blur(14px);text-align:center;box-shadow:0 6px 24px rgba(0,0,0,.4);">
            <p style="font-size:21px;font-weight:900;color:white;margin:0;line-height:1;">99.9%</p>
            <p style="font-size:8px;color:rgba(255,255,255,.42);margin:3px 0 0;text-transform:uppercase;letter-spacing:.6px;">{{ __('frontend.hero_stat_uptime') }}</p>
        </div>
    </div>

    {{-- ── Keyframes ───────────────────────────────────────── --}}
    <style>
        @keyframes hiw-pulse    { 0%,100%{opacity:.3;transform:scale(1)} 50%{opacity:.7;transform:scale(1.1)} }
        @keyframes hiw-spin     { from{transform:translate(-50%,-50%) rotate(0deg)} to{transform:translate(-50%,-50%) rotate(360deg)} }
        @keyframes hiw-float    { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        @keyframes hiw-float-cx { 0%,100%{transform:translateX(-50%) translateY(0)} 50%{transform:translateX(-50%) translateY(-10px)} }
        @keyframes hiw-blink    { 0%,100%{opacity:1} 50%{opacity:.15} }
    </style>
</div>
