@extends('admin.layouts.app')

@section('title', __('admin.add_service'))
@section('subtitle', __('admin.create_new_service'))

@section('content')
    <div class="max-w-5xl">
        <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="admin-card p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title_en" class="form-label">{{ __('admin.service_title') }} ({{ __('admin.lang_en') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" required class="form-input"
                            placeholder="{{ __('admin.placeholder_service_title') }}">
                        @error('title_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="title_ar" class="form-label">{{ __('admin.service_title') }} ({{ __('admin.lang_ar') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar') }}" required class="form-input">
                        @error('title_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="description_en" class="form-label">{{ __('admin.description') }} ({{ __('admin.lang_en') }}) <span class="text-red-400">*</span></label>
                        <textarea id="description_en" name="description_en" rows="4" required class="form-input"
                            placeholder="{{ __('admin.placeholder_service_description') }}">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description_ar" class="form-label">{{ __('admin.description') }} ({{ __('admin.lang_ar') }}) <span class="text-red-400">*</span></label>
                        <textarea id="description_ar" name="description_ar" rows="4" required
                            class="form-input">{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="icon" class="form-label">{{ __('admin.icon_name') }}</label>
                        <input type="text" id="icon" name="icon" value="{{ old('icon') }}" class="form-input"
                            placeholder="{{ __('admin.placeholder_icon_name') }}">
                        <p class="text-white/40 text-sm mt-1">{{ __('admin.icon_name_help') }}</p>
                    </div>

                    <div>
                        <label for="sort_order" class="form-label">{{ __('admin.sort_order') }}</label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}"
                            class="form-input" placeholder="0">
                    </div>
                </div>

                <div>
                    <label for="image" class="form-label">{{ __('admin.image') }}</label>
                    <input type="file" id="image" name="image" accept="image/*" class="form-input">
                </div>

                @php
                    $featuresEn = old('features_en', ['']);
                    $featuresAr = old('features_ar', ['']);
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">{{ __('admin.features') }} ({{ __('admin.lang_en') }})</label>
                        <div x-data="{ features: @js($featuresEn) }" class="space-y-2">
                            <template x-for="(feature, index) in features" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" :name="'features_en[' + index + ']'" x-model="features[index]"
                                        class="form-input flex-1" placeholder="{{ __('admin.placeholder_feature_item') }}">
                                    <button type="button" @click="features.splice(index, 1)"
                                        class="px-3 py-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                        x-show="features.length > 1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="features.push('')"
                                class="text-primary-400 hover:text-primary-300 text-sm">{{ __('admin.add_another_feature') }}</button>
                        </div>
                        @if($errors->has('features_en') || $errors->has('features_en.*'))
                            <p class="mt-1 text-sm text-red-400">{{ $errors->first('features_en') ?: $errors->first('features_en.*') }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="form-label">{{ __('admin.features') }} ({{ __('admin.lang_ar') }})</label>
                        <div x-data="{ features: @js($featuresAr) }" class="space-y-2">
                            <template x-for="(feature, index) in features" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" :name="'features_ar[' + index + ']'" x-model="features[index]"
                                        class="form-input flex-1">
                                    <button type="button" @click="features.splice(index, 1)"
                                        class="px-3 py-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                        x-show="features.length > 1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="features.push('')"
                                class="text-primary-400 hover:text-primary-300 text-sm">{{ __('admin.add_another_feature') }}</button>
                        </div>
                        @if($errors->has('features_ar') || $errors->has('features_ar.*'))
                            <p class="mt-1 text-sm text-red-400">{{ $errors->first('features_ar') ?: $errors->first('features_ar.*') }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary-500 focus:ring-primary-500/20">
                        <span class="text-white/80 text-sm">{{ __('admin.featured_service') }}</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary-500 focus:ring-primary-500/20">
                        <span class="text-white/80 text-sm">{{ __('admin.active') }}</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit"
                    class="px-6 py-3 rounded-lg btn-primary text-white font-medium">{{ __('admin.create_service') }}</button>
                <a href="{{ route('admin.services.index') }}"
                    class="px-6 py-3 rounded-lg bg-white/5 text-white/70 hover:bg-white/10 hover:text-white transition-colors font-medium">{{ __('admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
