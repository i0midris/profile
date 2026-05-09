@extends('admin.layouts.app')

@section('title', isset($testimonial) ? __('admin.edit_testimonial') : __('admin.add_testimonial'))
@section('subtitle', isset($testimonial) ? __('admin.update_testimonial_details') : __('admin.add_new_client_testimonial'))

@section('content')
    <div class="max-w-5xl">
        {{-- Delete Image Form (MUST be outside main form) --}}
        @if(isset($testimonial) && $testimonial->client_photo_url)
            <form id="delete-image-form" method="POST" action="{{ route('admin.testimonials.delete-image', $testimonial) }}"
                class="hidden">
                @csrf
            </form>
        @endif

        <form method="POST"
            action="{{ isset($testimonial) ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($testimonial)) @method('PUT') @endif

            <div class="admin-card p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_name_en" class="form-label">{{ __('admin.client_name') }} ({{ __('admin.lang_en') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="client_name_en" name="client_name_en"
                            value="{{ old('client_name_en', $testimonial->client_name_en ?? '') }}" required class="form-input">
                        @error('client_name_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="client_name_ar" class="form-label">{{ __('admin.client_name') }} ({{ __('admin.lang_ar') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="client_name_ar" name="client_name_ar"
                            value="{{ old('client_name_ar', $testimonial->client_name_ar ?? '') }}" required class="form-input">
                        @error('client_name_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_position_en" class="form-label">{{ __('admin.position') }} ({{ __('admin.lang_en') }})</label>
                        <input type="text" id="client_position_en" name="client_position_en"
                            value="{{ old('client_position_en', $testimonial->client_position_en ?? '') }}" class="form-input"
                            placeholder="{{ __('admin.placeholder_position_short') }}">
                        @error('client_position_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="client_position_ar" class="form-label">{{ __('admin.position') }} ({{ __('admin.lang_ar') }})</label>
                        <input type="text" id="client_position_ar" name="client_position_ar"
                            value="{{ old('client_position_ar', $testimonial->client_position_ar ?? '') }}" class="form-input">
                        @error('client_position_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_company_en" class="form-label">{{ __('admin.company') }} ({{ __('admin.lang_en') }})</label>
                        <input type="text" id="client_company_en" name="client_company_en"
                            value="{{ old('client_company_en', $testimonial->client_company_en ?? '') }}" class="form-input">
                        @error('client_company_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="client_company_ar" class="form-label">{{ __('admin.company') }} ({{ __('admin.lang_ar') }})</label>
                        <input type="text" id="client_company_ar" name="client_company_ar"
                            value="{{ old('client_company_ar', $testimonial->client_company_ar ?? '') }}" class="form-input">
                        @error('client_company_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="content_en" class="form-label">{{ __('admin.testimonial_content') }} ({{ __('admin.lang_en') }}) <span class="text-red-400">*</span></label>
                        <textarea id="content_en" name="content_en" rows="4" required class="form-input"
                            placeholder="{{ __('admin.placeholder_testimonial_content') }}">{{ old('content_en', $testimonial->content_en ?? '') }}</textarea>
                        @error('content_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="content_ar" class="form-label">{{ __('admin.testimonial_content') }} ({{ __('admin.lang_ar') }}) <span class="text-red-400">*</span></label>
                        <textarea id="content_ar" name="content_ar" rows="4" required class="form-input">{{ old('content_ar', $testimonial->content_ar ?? '') }}</textarea>
                        @error('content_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_photo" class="form-label">{{ __('admin.client_photo') }}</label>
                        @if(isset($testimonial) && $testimonial->client_photo_url)
                            <div class="mb-3 flex items-center gap-3">
                                <img src="{{ $testimonial->client_photo_url }}" alt="{{ $testimonial->client_name }}"
                                    class="w-16 h-16 rounded-full object-cover">
                                <button type="button"
                                    onclick="if(confirm('{{ __('admin.delete_this_photo') }}')) document.getElementById('delete-image-form').submit();"
                                    class="p-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                    title="{{ __('admin.delete_photo') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                        <input type="file" id="client_photo" name="client_photo" accept="image/*" class="form-input">
                    </div>
                    <div>
                        <label for="rating" class="form-label">{{ __('admin.rating') }} <span class="text-red-400">*</span></label>
                        <select id="rating" name="rating" required class="form-input">
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ old('rating', $testimonial->rating ?? 5) == $i ? 'selected' : '' }}>
                                    {{ __('admin.star_rating', ['count' => $i]) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div>
                        <label for="sort_order" class="form-label">{{ __('admin.sort_order') }}</label>
                        <input type="number" id="sort_order" name="sort_order"
                            value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" class="form-input w-24">
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer pt-6">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $testimonial->is_featured ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary-500">
                        <span class="text-white/80 text-sm">{{ __('admin.featured') }}</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer pt-6">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $testimonial->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary-500">
                        <span class="text-white/80 text-sm">{{ __('admin.active') }}</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit"
                    class="px-6 py-3 rounded-lg btn-primary text-white font-medium">{{ isset($testimonial) ? __('admin.update') : __('admin.add') }}
                    {{ __('admin.testimonials') }}</button>
                <a href="{{ route('admin.testimonials.index') }}"
                    class="px-6 py-3 rounded-lg bg-white/5 text-white/70 hover:bg-white/10 hover:text-white transition-colors font-medium">{{ __('admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
