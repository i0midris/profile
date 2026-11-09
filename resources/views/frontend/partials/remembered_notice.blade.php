{{--
    Shown by app.js only when it filled the form with details the visitor typed in an earlier
    request on this device (saved in the browser, never sent anywhere else). The link clears them.
--}}
<div class="remembered-notice mb-6 px-4 py-3 rounded-xl text-sm flex flex-wrap items-center justify-between gap-2" data-remembered-notice hidden>
    <span class="text-white/80">{{ __('frontend.remembered_text') }}</span>
    <button type="button" class="text-primary-400 hover:text-primary-300 font-medium underline underline-offset-4" data-remembered-clear>{{ __('frontend.remembered_clear') }}</button>
</div>
