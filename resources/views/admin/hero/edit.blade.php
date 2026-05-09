@extends('admin.layouts.app')

@section('title', __('admin.hero_section'))
@section('subtitle', __('admin.edit_hero_content'))

@section('content')
    <div class="max-w-5xl">
        {{-- Delete Image Form (MUST be outside main form) --}}
        @if($hero->foreground_image)
            <form id="delete-image-form" method="POST" action="{{ route('admin.hero.delete-image', 'foreground_image') }}"
                class="hidden">
                @csrf
            </form>
        @endif

        <form method="POST" action="{{ route('admin.hero.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="admin-card p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title_en" class="form-label">{{ __('admin.title') }} ({{ __('admin.lang_en') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="title_en" name="title_en"
                            value="{{ old('title_en', $hero->title_en) }}" required class="form-input"
                            placeholder="{{ __('admin.hero_title_placeholder') }}">
                        @error('title_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="title_ar" class="form-label">{{ __('admin.title') }} ({{ __('admin.lang_ar') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="title_ar" name="title_ar"
                            value="{{ old('title_ar', $hero->title_ar) }}" required class="form-input">
                        @error('title_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="subtitle_en" class="form-label">{{ __('admin.subtitle') }} ({{ __('admin.lang_en') }})</label>
                        <input type="text" id="subtitle_en" name="subtitle_en"
                            value="{{ old('subtitle_en', $hero->subtitle_en) }}" class="form-input"
                            placeholder="{{ __('admin.hero_subtitle_placeholder') }}">
                        @error('subtitle_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="subtitle_ar" class="form-label">{{ __('admin.subtitle') }} ({{ __('admin.lang_ar') }})</label>
                        <input type="text" id="subtitle_ar" name="subtitle_ar"
                            value="{{ old('subtitle_ar', $hero->subtitle_ar) }}" class="form-input">
                        @error('subtitle_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="description_en" class="form-label">{{ __('admin.description') }} ({{ __('admin.lang_en') }})</label>
                        <textarea id="description_en" name="description_en" rows="3" class="form-input"
                            placeholder="{{ __('admin.hero_description_placeholder') }}">{{ old('description_en', $hero->description_en) }}</textarea>
                        @error('description_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description_ar" class="form-label">{{ __('admin.description') }} ({{ __('admin.lang_ar') }})</label>
                        <textarea id="description_ar" name="description_ar" rows="3"
                            class="form-input">{{ old('description_ar', $hero->description_ar) }}</textarea>
                        @error('description_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="button_text_en" class="form-label">{{ __('admin.primary_button_text') }} ({{ __('admin.lang_en') }})</label>
                        <input type="text" id="button_text_en" name="button_text_en"
                            value="{{ old('button_text_en', $hero->button_text_en) }}" class="form-input"
                            placeholder="{{ __('admin.primary_button_text_placeholder') }}">
                        @error('button_text_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="button_text_ar" class="form-label">{{ __('admin.primary_button_text') }} ({{ __('admin.lang_ar') }})</label>
                        <input type="text" id="button_text_ar" name="button_text_ar"
                            value="{{ old('button_text_ar', $hero->button_text_ar) }}" class="form-input">
                        @error('button_text_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="button_link" class="form-label">{{ __('admin.primary_button_link') }}</label>
                    <input type="text" id="button_link" name="button_link"
                        value="{{ old('button_link', $hero->button_link) }}" class="form-input"
                        placeholder="{{ __('admin.primary_button_link_placeholder') }}">
                    @error('button_link')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="button_text_secondary_en" class="form-label">{{ __('admin.secondary_button_text') }} ({{ __('admin.lang_en') }})</label>
                        <input type="text" id="button_text_secondary_en" name="button_text_secondary_en"
                            value="{{ old('button_text_secondary_en', $hero->button_text_secondary_en) }}" class="form-input"
                            placeholder="{{ __('admin.secondary_button_text_placeholder') }}">
                        @error('button_text_secondary_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="button_text_secondary_ar" class="form-label">{{ __('admin.secondary_button_text') }} ({{ __('admin.lang_ar') }})</label>
                        <input type="text" id="button_text_secondary_ar" name="button_text_secondary_ar"
                            value="{{ old('button_text_secondary_ar', $hero->button_text_secondary_ar) }}" class="form-input">
                        @error('button_text_secondary_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="button_link_secondary" class="form-label">{{ __('admin.secondary_button_link') }}</label>
                    <input type="text" id="button_link_secondary" name="button_link_secondary"
                        value="{{ old('button_link_secondary', $hero->button_link_secondary) }}" class="form-input"
                        placeholder="{{ __('admin.secondary_button_link_placeholder') }}">
                    @error('button_link_secondary')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="foreground_image" class="form-label">{{ __('admin.hero_image') }}</label>
                    @if($hero->foreground_image)
                        <div class="mb-3 flex items-center gap-3">
                            <img src="{{ $hero->foreground_image_url }}" alt="{{ __('admin.hero_image_alt') }}" class="h-32 object-contain rounded-lg">
                            <button type="button"
                                onclick="if(confirm('{{ __('admin.delete_this_image') }}')) document.getElementById('delete-image-form').submit();"
                                class="p-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                title="{{ __('admin.delete_image') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    @endif
                    <input type="file" id="foreground_image" name="foreground_image" accept="image/*" class="form-input">
                </div>
            </div>

            <button type="submit" class="px-6 py-3 rounded-lg btn-primary text-white font-medium">{{ __('admin.save_hero_section') }}</button>
        </form>
    </div>
@endsection
