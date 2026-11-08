{{-- Infinite marquee of the company's systems, right under the hero. --}}
@php
    $marqueeItems = [
        ['pos_system', 'M6 3h12v18l-3-2-3 2-3-2-3 2zM9 8h6M9 12h6'],
        ['erp_accounting', 'M4 20V10M10 20V4M16 20v-7M22 20H2'],
        ['hr_payroll', 'M16 11a3 3 0 100-6 3 3 0 000 6zM8 11a3 3 0 100-6 3 3 0 000 6zM2 20a6 6 0 0112 0M14 20a6 6 0 018 0'],
        ['e_commerce', 'M3 4h2l2.4 11.2a2 2 0 002 1.6h7.7a2 2 0 001.9-1.5L21 8H6.2'],
        ['sales_rep_app', 'M8 2h8a2 2 0 012 2v16a2 2 0 01-2 2H8a2 2 0 01-2-2V4a2 2 0 012-2zM11 18h2'],
        ['inventory', 'M3 7l9-4 9 4-9 4-9-4zM3 7v10l9 4 9-4V7M12 11v10'],
        ['reports', 'M9 17v-6M13 17V7M17 17v-3M4 4h16v16H4z'],
        ['cybersecurity', 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'],
    ];
@endphp
<div class="systems-marquee" aria-label="{{ __('frontend.our_products') }}">
    <div class="systems-marquee-track">
        @foreach([0, 1] as $copy)
            <ul class="systems-marquee-list" @if($copy) aria-hidden="true" @endif>
                @foreach($marqueeItems as [$key, $path])
                    <li>
                        <span class="systems-marquee-icon"><svg aria-hidden="true" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $path }}"/></svg></span>
                        {{ __('frontend.system_' . $key) }}
                    </li>
                @endforeach
            </ul>
        @endforeach
    </div>
</div>
