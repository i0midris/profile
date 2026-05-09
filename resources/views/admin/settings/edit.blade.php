@extends('admin.layouts.app')

@section('title', __('admin.company_settings'))
@section('subtitle', __('admin.configure_company_information'))

@section('content')
    <div class="max-w-5xl">
        {{-- Delete Image Forms (MUST be outside main form) --}}
        @if($settings->logo)
            <form id="delete-logo-form" method="POST" action="{{ route('admin.settings.delete-image', 'logo') }}"
                class="hidden">
                @csrf
            </form>
        @endif
        @if($settings->favicon)
            <form id="delete-favicon-form" method="POST" action="{{ route('admin.settings.delete-image', 'favicon') }}"
                class="hidden">
                @csrf
            </form>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Info -->
            <div class="admin-card p-6">
                <h3 class="text-lg font-semibold text-white mb-4">{{ __('admin.basic_information') }}</h3>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company_name_en" class="form-label">{{ __('admin.company_name') }} ({{ __('admin.lang_en') }}) <span
                                    class="text-red-400">*</span></label>
                            <input type="text" id="company_name_en" name="company_name_en"
                                value="{{ old('company_name_en', $settings->company_name_en) }}" required class="form-input">
                            @error('company_name_en')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="company_name_ar" class="form-label">{{ __('admin.company_name') }} ({{ __('admin.lang_ar') }}) <span
                                    class="text-red-400">*</span></label>
                            <input type="text" id="company_name_ar" name="company_name_ar"
                                value="{{ old('company_name_ar', $settings->company_name_ar) }}" required class="form-input">
                            @error('company_name_ar')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tagline_en" class="form-label">{{ __('admin.tagline') }} ({{ __('admin.lang_en') }})</label>
                            <input type="text" id="tagline_en" name="tagline_en"
                                value="{{ old('tagline_en', $settings->tagline_en) }}"
                                class="form-input" placeholder="{{ __('admin.placeholder_company_tagline') }}">
                            @error('tagline_en')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="tagline_ar" class="form-label">{{ __('admin.tagline') }} ({{ __('admin.lang_ar') }})</label>
                            <input type="text" id="tagline_ar" name="tagline_ar"
                                value="{{ old('tagline_ar', $settings->tagline_ar) }}"
                                class="form-input">
                            @error('tagline_ar')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="description_en" class="form-label">{{ __('admin.description') }} ({{ __('admin.lang_en') }})</label>
                            <textarea id="description_en" name="description_en" rows="3"
                                class="form-input">{{ old('description_en', $settings->description_en) }}</textarea>
                            @error('description_en')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="description_ar" class="form-label">{{ __('admin.description') }} ({{ __('admin.lang_ar') }})</label>
                            <textarea id="description_ar" name="description_ar" rows="3"
                                class="form-input">{{ old('description_ar', $settings->description_ar) }}</textarea>
                            @error('description_ar')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="logo" class="form-label">{{ __('admin.logo') }}</label>
                            @if($settings->logo)
                                <div class="mb-3 flex items-center gap-3">
                                    <img src="{{ $settings->logo_url }}" alt="{{ __('admin.logo') }}" class="h-16 object-contain">
                                    <button type="button"
                                        onclick="if(confirm('{{ __('admin.delete_logo_confirm') }}')) document.getElementById('delete-logo-form').submit();"
                                        class="p-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                        title="{{ __('admin.delete_logo') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                            <input type="file" id="logo" name="logo" accept="image/*" class="form-input">
                        </div>
                        <div>
                            <label for="favicon" class="form-label">{{ __('admin.favicon') }}</label>
                            @if($settings->favicon)
                                <div class="mb-3 flex items-center gap-3">
                                    <img src="{{ $settings->favicon_url }}" alt="{{ __('admin.favicon') }}" class="h-8 object-contain">
                                    <button type="button"
                                        onclick="if(confirm('{{ __('admin.delete_favicon_confirm') }}')) document.getElementById('delete-favicon-form').submit();"
                                        class="p-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                        title="{{ __('admin.delete_favicon') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                            <input type="file" id="favicon" name="favicon" accept="image/*,.ico" class="form-input">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="admin-card p-6">
                <h3 class="text-lg font-semibold text-white mb-4">{{ __('admin.contact_information') }}</h3>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="form-label">{{ __('admin.email') }}</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $settings->email) }}"
                                class="form-input" placeholder="{{ __('admin.placeholder_contact_email') }}">
                        </div>
                        <div>
                            <label for="phone" class="form-label">{{ __('admin.phone') }}</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $settings->phone) }}"
                                class="form-input" placeholder="{{ __('admin.placeholder_phone') }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="address_en" class="form-label">{{ __('admin.address') }} ({{ __('admin.lang_en') }})</label>
                            <textarea id="address_en" name="address_en" rows="2"
                                class="form-input">{{ old('address_en', $settings->address_en) }}</textarea>
                            @error('address_en')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="address_ar" class="form-label">{{ __('admin.address') }} ({{ __('admin.lang_ar') }})</label>
                            <textarea id="address_ar" name="address_ar" rows="2"
                                class="form-input">{{ old('address_ar', $settings->address_ar) }}</textarea>
                            @error('address_ar')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Links -->
            <div class="admin-card p-6">
                <h3 class="text-lg font-semibold text-white mb-4">{{ __('admin.social_media_links') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="facebook" class="form-label">{{ __('admin.facebook') }}</label>
                        <input type="url" id="facebook" name="facebook" value="{{ old('facebook', $settings->facebook) }}"
                            class="form-input" placeholder="{{ __('admin.placeholder_facebook') }}">
                    </div>
                    <div>
                        <label for="twitter" class="form-label">{{ __('admin.twitter_x') }}</label>
                        <input type="url" id="twitter" name="twitter" value="{{ old('twitter', $settings->twitter) }}"
                            class="form-input" placeholder="{{ __('admin.placeholder_twitter') }}">
                    </div>
                    <div>
                        <label for="instagram" class="form-label">{{ __('admin.instagram') }}</label>
                        <input type="url" id="instagram" name="instagram"
                            value="{{ old('instagram', $settings->instagram) }}" class="form-input"
                            placeholder="{{ __('admin.placeholder_instagram') }}">
                    </div>
                    <div>
                        <label for="linkedin" class="form-label">{{ __('admin.linkedin') }}</label>
                        <input type="url" id="linkedin" name="linkedin" value="{{ old('linkedin', $settings->linkedin) }}"
                            class="form-input" placeholder="{{ __('admin.placeholder_linkedin') }}">
                    </div>
                    <div>
                        <label for="youtube" class="form-label">{{ __('admin.youtube') }}</label>
                        <input type="url" id="youtube" name="youtube" value="{{ old('youtube', $settings->youtube) }}"
                            class="form-input" placeholder="{{ __('admin.placeholder_youtube') }}">
                    </div>
                    <div>
                        <label for="whatsapp" class="form-label">{{ __('admin.whatsapp') }}</label>
                        <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $settings->whatsapp) }}"
                            class="form-input" placeholder="{{ __('admin.placeholder_whatsapp') }}">
                    </div>
                </div>
            </div>

            <button type="submit"
                class="px-6 py-3 rounded-lg btn-primary text-white font-medium">{{ __('admin.save_settings') }}</button>
        </form>
    </div>
@endsection
