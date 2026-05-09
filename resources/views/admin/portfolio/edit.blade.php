@extends('admin.layouts.app')

@section('title', isset($portfolio) ? __('admin.edit_portfolio') : __('admin.add_portfolio_item'))
@section('subtitle', isset($portfolio) ? __('admin.update_portfolio_details') : __('admin.add_new_portfolio_item'))

@section('content')
    <div class="max-w-5xl">
        {{-- Delete Image Form (MUST be outside main form) --}}
        @if(isset($portfolio) && $portfolio->image_url)
            <form id="delete-image-form" method="POST" action="{{ route('admin.portfolio.delete-image', $portfolio) }}"
                class="hidden">
                @csrf
            </form>
        @endif

        <form method="POST"
            action="{{ isset($portfolio) ? route('admin.portfolio.update', $portfolio) : route('admin.portfolio.store') }}"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($portfolio)) @method('PUT') @endif

            <div class="admin-card p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title_en" class="form-label">{{ __('admin.title') }} ({{ __('admin.lang_en') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $portfolio->title_en ?? '') }}" required
                            class="form-input">
                        @error('title_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="title_ar" class="form-label">{{ __('admin.title') }} ({{ __('admin.lang_ar') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $portfolio->title_ar ?? '') }}" required
                            class="form-input">
                        @error('title_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="description_en" class="form-label">{{ __('admin.description') }} ({{ __('admin.lang_en') }})</label>
                        <textarea id="description_en" name="description_en" rows="3"
                            class="form-input">{{ old('description_en', $portfolio->description_en ?? '') }}</textarea>
                        @error('description_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description_ar" class="form-label">{{ __('admin.description') }} ({{ __('admin.lang_ar') }})</label>
                        <textarea id="description_ar" name="description_ar" rows="3"
                            class="form-input">{{ old('description_ar', $portfolio->description_ar ?? '') }}</textarea>
                        @error('description_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="client_name_en" class="form-label">{{ __('admin.client_name') }} ({{ __('admin.lang_en') }})</label>
                        <input type="text" id="client_name_en" name="client_name_en"
                            value="{{ old('client_name_en', $portfolio->client_name_en ?? '') }}" class="form-input">
                        @error('client_name_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="client_name_ar" class="form-label">{{ __('admin.client_name') }} ({{ __('admin.lang_ar') }})</label>
                        <input type="text" id="client_name_ar" name="client_name_ar"
                            value="{{ old('client_name_ar', $portfolio->client_name_ar ?? '') }}" class="form-input">
                        @error('client_name_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="category_en" class="form-label">{{ __('admin.category') }} ({{ __('admin.lang_en') }})</label>
                        <input type="text" id="category_en" name="category_en"
                            value="{{ old('category_en', $portfolio->category_en ?? '') }}" class="form-input" list="categories_en"
                            placeholder="{{ __('admin.placeholder_category') }}">
                        @error('category_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <datalist id="categories_en">
                            @foreach($categories ?? [] as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label for="category_ar" class="form-label">{{ __('admin.category') }} ({{ __('admin.lang_ar') }})</label>
                        <input type="text" id="category_ar" name="category_ar"
                            value="{{ old('category_ar', $portfolio->category_ar ?? '') }}" class="form-input" list="categories_ar">
                        @error('category_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <datalist id="categories_ar">
                            @foreach($categories ?? [] as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <div>
                    <label for="image" class="form-label">{{ __('admin.image') }} {{ isset($portfolio) ? '' : '*' }}</label>
                    @if(isset($portfolio) && $portfolio->image_url)
                        <div class="mb-3 flex items-center gap-3">
                            <img src="{{ $portfolio->image_url }}" alt="{{ __('admin.portfolio_image_alt') }}"
                                class="w-48 h-32 object-cover rounded-lg">
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
                    <input type="file" id="image" name="image" accept="image/*" class="form-input" {{ isset($portfolio) ? '' : 'required' }}>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="project_url" class="form-label">{{ __('admin.project_url') }}</label>
                        <input type="url" id="project_url" name="project_url"
                            value="{{ old('project_url', $portfolio->project_url ?? '') }}" class="form-input"
                            placeholder="{{ __('admin.placeholder_url') }}">
                    </div>
                    <div>
                        <label for="completed_at" class="form-label">{{ __('admin.completed_date') }}</label>
                        <input type="date" id="completed_at" name="completed_at"
                            value="{{ old('completed_at', isset($portfolio) && $portfolio->completed_at ? $portfolio->completed_at->format('Y-m-d') : '') }}"
                            class="form-input">
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div>
                        <label for="sort_order" class="form-label">{{ __('admin.sort_order') }}</label>
                        <input type="number" id="sort_order" name="sort_order"
                            value="{{ old('sort_order', $portfolio->sort_order ?? 0) }}" class="form-input w-24">
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer pt-6">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $portfolio->is_featured ?? false) ? 'checked' : '' }} class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary-500">
                        <span class="text-white/80 text-sm">{{ __('admin.featured') }}</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer pt-6">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $portfolio->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary-500">
                        <span class="text-white/80 text-sm">{{ __('admin.active') }}</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit"
                    class="px-6 py-3 rounded-lg btn-primary text-white font-medium">{{ isset($portfolio) ? __('admin.update_portfolio') : __('admin.add_portfolio') }}</button>
                <a href="{{ route('admin.portfolio.index') }}"
                    class="px-6 py-3 rounded-lg bg-white/5 text-white/70 hover:bg-white/10 hover:text-white transition-colors font-medium">{{ __('admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
