{{-- Case study (plan step 3.2): when any of these is filled, the item gets its own page on the website. --}}
<div class="rounded-xl border border-white/10 p-5 space-y-5">
    <div>
        <h3 class="text-white font-semibold">{{ __('admin.case_study.title') }}</h3>
        <p class="text-white/50 text-sm mt-1">{{ __('admin.case_study.help') }}</p>
    </div>
    @foreach(['challenge', 'solution', 'results'] as $field)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach(['ar' => __('admin.lang_ar'), 'en' => __('admin.lang_en')] as $lang => $langLabel)
                <div>
                    <label for="{{ $field }}_{{ $lang }}" class="form-label">{{ __('admin.case_study.' . $field) }} ({{ $langLabel }})</label>
                    <textarea id="{{ $field }}_{{ $lang }}" name="{{ $field }}_{{ $lang }}" rows="3" class="form-input"
                        dir="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">{{ old($field . '_' . $lang, $portfolio?->{$field . '_' . $lang}) }}</textarea>
                    @error($field . '_' . $lang)<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                </div>
            @endforeach
        </div>
    @endforeach
</div>
