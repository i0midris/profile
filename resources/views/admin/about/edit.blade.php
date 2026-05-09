@extends('admin.layouts.app')

@section('title', __('admin.about_section'))
@section('subtitle', __('admin.edit_about_content'))

@section('content')
    <div class="max-w-5xl">
        {{-- Delete Image Form (MUST be outside main form) --}}
        @if($about->image)
            <form id="delete-image-form" method="POST" action="{{ route('admin.about.delete-image') }}" class="hidden">
                @csrf
            </form>
        @endif

        <form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="admin-card p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title_en" class="form-label">{{ __('admin.title') }} ({{ __('admin.lang_en') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="title_en" name="title_en"
                            value="{{ old('title_en', $about->title_en) }}" required class="form-input">
                        @error('title_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="title_ar" class="form-label">{{ __('admin.title') }} ({{ __('admin.lang_ar') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="title_ar" name="title_ar"
                            value="{{ old('title_ar', $about->title_ar) }}" required class="form-input">
                        @error('title_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="content_en" class="form-label">{{ __('admin.content') }} ({{ __('admin.lang_en') }}) <span class="text-red-400">*</span></label>
                        <textarea id="content_en" name="content_en" rows="5" required
                            class="form-input">{{ old('content_en', $about->content_en) }}</textarea>
                        @error('content_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="content_ar" class="form-label">{{ __('admin.content') }} ({{ __('admin.lang_ar') }}) <span class="text-red-400">*</span></label>
                        <textarea id="content_ar" name="content_ar" rows="5" required
                            class="form-input">{{ old('content_ar', $about->content_ar) }}</textarea>
                        @error('content_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="image" class="form-label">{{ __('admin.image') }}</label>
                    @if($about->image)
                        <div class="mb-3 flex items-center gap-3">
                            <img src="{{ $about->image_url }}" alt="{{ __('admin.about_image_alt') }}" class="w-48 h-48 object-cover rounded-lg">
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
                    <input type="file" id="image" name="image" accept="image/*" class="form-input">
                </div>
            </div>

            <div class="admin-card p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">{{ __('admin.mission_vision') }}</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label for="mission_title_en" class="form-label">{{ __('admin.mission_title') }} ({{ __('admin.lang_en') }})</label>
                            <input type="text" id="mission_title_en" name="mission_title_en"
                                value="{{ old('mission_title_en', $about->mission_title_en) }}" class="form-input">
                            @error('mission_title_en')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="mission_content_en" class="form-label">{{ __('admin.mission_content') }} ({{ __('admin.lang_en') }})</label>
                            <textarea id="mission_content_en" name="mission_content_en" rows="3"
                                class="form-input">{{ old('mission_content_en', $about->mission_content_en) }}</textarea>
                            @error('mission_content_en')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="vision_title_en" class="form-label">{{ __('admin.vision_title') }} ({{ __('admin.lang_en') }})</label>
                            <input type="text" id="vision_title_en" name="vision_title_en"
                                value="{{ old('vision_title_en', $about->vision_title_en) }}" class="form-input">
                            @error('vision_title_en')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="vision_content_en" class="form-label">{{ __('admin.vision_content') }} ({{ __('admin.lang_en') }})</label>
                            <textarea id="vision_content_en" name="vision_content_en" rows="3"
                                class="form-input">{{ old('vision_content_en', $about->vision_content_en) }}</textarea>
                            @error('vision_content_en')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label for="mission_title_ar" class="form-label">{{ __('admin.mission_title') }} ({{ __('admin.lang_ar') }})</label>
                            <input type="text" id="mission_title_ar" name="mission_title_ar"
                                value="{{ old('mission_title_ar', $about->mission_title_ar) }}" class="form-input">
                            @error('mission_title_ar')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="mission_content_ar" class="form-label">{{ __('admin.mission_content') }} ({{ __('admin.lang_ar') }})</label>
                            <textarea id="mission_content_ar" name="mission_content_ar" rows="3"
                                class="form-input">{{ old('mission_content_ar', $about->mission_content_ar) }}</textarea>
                            @error('mission_content_ar')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="vision_title_ar" class="form-label">{{ __('admin.vision_title') }} ({{ __('admin.lang_ar') }})</label>
                            <input type="text" id="vision_title_ar" name="vision_title_ar"
                                value="{{ old('vision_title_ar', $about->vision_title_ar) }}" class="form-input">
                            @error('vision_title_ar')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="vision_content_ar" class="form-label">{{ __('admin.vision_content') }} ({{ __('admin.lang_ar') }})</label>
                            <textarea id="vision_content_ar" name="vision_content_ar" rows="3"
                                class="form-input">{{ old('vision_content_ar', $about->vision_content_ar) }}</textarea>
                            @error('vision_content_ar')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-card p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">{{ __('admin.statistics') }}</h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <label for="years_experience" class="form-label">{{ __('admin.years_experience') }}</label>
                        <input type="number" id="years_experience" name="years_experience"
                            value="{{ old('years_experience', $about->years_experience) }}" class="form-input" min="0">
                    </div>
                    <div>
                        <label for="projects_completed" class="form-label">{{ __('admin.projects_completed') }}</label>
                        <input type="number" id="projects_completed" name="projects_completed"
                            value="{{ old('projects_completed', $about->projects_completed) }}" class="form-input" min="0">
                    </div>
                    <div>
                        <label for="happy_clients" class="form-label">{{ __('admin.happy_clients') }}</label>
                        <input type="number" id="happy_clients" name="happy_clients"
                            value="{{ old('happy_clients', $about->happy_clients) }}" class="form-input" min="0">
                    </div>
                    <div>
                        <label for="team_members" class="form-label">{{ __('admin.team_members') }}</label>
                        <input type="number" id="team_members" name="team_members"
                            value="{{ old('team_members', $about->team_members) }}" class="form-input" min="0">
                    </div>
                </div>
            </div>

            <div class="admin-card p-6">
                <h3 class="text-lg font-semibold text-white mb-4">{{ __('admin.page_visibility') }}</h3>
                <input type="hidden" name="show_about_page" value="0">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="show_about_page" value="1"
                        {{ old('show_about_page', $isPageVisible) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary-500 focus:ring-primary-500/20">
                    <span class="text-white/80 text-sm">{{ __('admin.show_page_on_website') }}</span>
                </label>
                @error('show_about_page')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="px-6 py-3 rounded-lg btn-primary text-white font-medium">{{ __('admin.save_about_section') }}</button>
        </form>
    </div>
@endsection
