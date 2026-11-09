@extends('frontend.layouts.app')

@section('title', __('frontend.request_demo') . ' | ' . ($settings->company_name ?? __('frontend.company_fallback')))
@section('meta_description', __('frontend.demo_intro'))

@section('content')
    @php
        $inputClass = 'w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-white/40 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all';
        $whatsappUrl = \App\Support\PhoneNumber::whatsappUrl($settings->whatsapp ?: $settings->phone);
    @endphp

    <section class="pt-20 pb-10 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-1/4 {{ app()->isLocale('ar') ? 'right-0' : 'left-0' }} w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('frontend.request_demo') }}</h1>
            <p class="text-white/70 max-w-2xl mx-auto">{{ __('frontend.demo_intro') }}</p>
        </div>
    </section>

    <section class="pb-24">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass rounded-2xl p-6 sm:p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-green-500/20 border border-green-500/30 text-green-300" role="status">{{ session('success') }}</div>
                @endif
                @if($errors->has('demo'))
                    <div class="mb-6 p-4 rounded-lg bg-red-500/20 border border-red-500/30 text-red-300" role="alert">{{ $errors->first('demo') }}</div>
                @endif

                @if(!session('success') && ($selectedProduct || $selectedSector))
                    <div class="demo-prefilled mb-6 p-4 rounded-xl flex items-start gap-3" role="status">
                        <span class="demo-prefilled-icon" aria-hidden="true"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                        <div>
                            <p class="font-semibold text-white">{{ __('frontend.demo_prefilled_title') }}</p>
                            <p class="text-white/70 text-sm leading-relaxed">{{ __('frontend.demo_prefilled_text') }}</p>
                        </div>
                    </div>
                @endif
                @include('frontend.partials.remembered_notice')

                <form method="POST" action="{{ route('demo.send', ['locale' => app()->getLocale()]) }}" class="space-y-6" data-lead-form="demo_request" data-remember-form>
                    @csrf
                    <input type="hidden" name="form_started_at" value="{{ now()->timestamp }}">
                    <div class="hidden" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.your_name') }} <span class="text-red-400">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" data-remember="name" class="{{ $inputClass }}">
                            @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="company" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.company_name_field') }}</label>
                            <input type="text" id="company" name="company" value="{{ old('company') }}" autocomplete="organization" data-remember="company" class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.phone_number') }} <span class="text-red-400">*</span></label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required dir="ltr" inputmode="tel" autocomplete="tel" data-remember="phone"
                                placeholder="{{ __('frontend.placeholder_phone') }}" class="{{ $inputClass }} {{ app()->isLocale('ar') ? 'text-right' : '' }}">
                            @error('phone')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.email_address') }}</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" data-remember="email" class="{{ $inputClass }}">
                            @error('email')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="product" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.demo_product') }}</label>
                        @if($products->count())
                            <select id="product" name="product" class="{{ $inputClass }}">
                                <option value="">{{ __('frontend.demo_product_any') }}</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->name }}" @selected(old('product', $selectedProduct) === $product->name)>{{ $product->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" id="product" name="product" value="{{ old('product', $selectedProduct) }}"
                                placeholder="{{ __('frontend.demo_product_placeholder') }}" class="{{ $inputClass }}">
                        @endif
                    </div>
                    <div>
                        <label for="sector" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.demo_sector') }}</label>
                        <select id="sector" name="sector" class="{{ $inputClass }}">
                            <option value="">{{ __('frontend.demo_sector_any') }}</option>
                            @foreach(\App\Models\DemoRequest::SECTORS as $sectorKey)
                                <option value="{{ $sectorKey }}" @selected(old('sector', $selectedSector) === $sectorKey)>{{ \App\Models\DemoRequest::sectorLabel($sectorKey) }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>

                    <fieldset>
                        <legend class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.demo_preferred_contact') }}</legend>
                        <div class="flex flex-wrap gap-3">
                            @foreach(\App\Models\DemoRequest::CONTACT_METHODS as $method)
                                <label class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-white/80 cursor-pointer">
                                    <input type="radio" name="preferred_contact" value="{{ $method }}" @checked(old('preferred_contact', 'whatsapp') === $method)>
                                    {{ __('frontend.contact_method_' . $method) }}
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <div>
                        <label for="message" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.demo_message') }}</label>
                        <textarea id="message" name="message" rows="4" class="{{ $inputClass }} resize-none"
                            placeholder="{{ __('frontend.demo_message_placeholder') }}">{{ old('message', session('success') ? null : $prefillMessage) }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-4 rounded-xl gradient-primary text-white font-semibold hover:shadow-lg hover:shadow-primary-500/30 transition-all">
                        {{ __('frontend.demo_submit') }}
                    </button>
                    @if($whatsappUrl)
                        <p class="text-center text-white/60 text-sm">
                            {{ __('frontend.demo_or_whatsapp') }}
                            <a href="{{ $whatsappUrl }}?text={{ rawurlencode(__('frontend.whatsapp_general_message')) }}" target="_blank" rel="noopener noreferrer"
                                data-track="whatsapp_click" class="text-primary-400 hover:text-primary-300 font-medium">{{ __('frontend.whatsapp') }}</a>
                        </p>
                    @endif
                </form>
            </div>
        </div>
    </section>
@endsection
