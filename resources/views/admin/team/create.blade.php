@extends('admin.layouts.app')

@section('title', isset($member) ? __('admin.edit_team_member') : __('admin.add_team_member'))
@section('subtitle', isset($member) ? __('admin.update_member_details') : __('admin.add_new_team_member'))

@section('content')
    <div class="max-w-5xl">
        <form method="POST" action="{{ isset($member) ? route('admin.team.update', $member) : route('admin.team.store') }}"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($member)) @method('PUT') @endif

            <div class="admin-card p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name_en" class="form-label">{{ __('admin.name') }} ({{ __('admin.lang_en') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="name_en" name="name_en" value="{{ old('name_en', $member->name_en ?? '') }}" required
                            class="form-input">
                        @error('name_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="name_ar" class="form-label">{{ __('admin.name') }} ({{ __('admin.lang_ar') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar', $member->name_ar ?? '') }}" required
                            class="form-input">
                        @error('name_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="position_en" class="form-label">{{ __('admin.position') }} ({{ __('admin.lang_en') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="position_en" name="position_en"
                            value="{{ old('position_en', $member->position_en ?? '') }}" required class="form-input"
                            placeholder="{{ __('admin.placeholder_position') }}">
                        @error('position_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="position_ar" class="form-label">{{ __('admin.position') }} ({{ __('admin.lang_ar') }}) <span class="text-red-400">*</span></label>
                        <input type="text" id="position_ar" name="position_ar"
                            value="{{ old('position_ar', $member->position_ar ?? '') }}" required class="form-input">
                        @error('position_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="bio_en" class="form-label">{{ __('admin.bio') }} ({{ __('admin.lang_en') }})</label>
                        <textarea id="bio_en" name="bio_en" rows="3"
                            class="form-input">{{ old('bio_en', $member->bio_en ?? '') }}</textarea>
                        @error('bio_en')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="bio_ar" class="form-label">{{ __('admin.bio') }} ({{ __('admin.lang_ar') }})</label>
                        <textarea id="bio_ar" name="bio_ar" rows="3"
                            class="form-input">{{ old('bio_ar', $member->bio_ar ?? '') }}</textarea>
                        @error('bio_ar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="photo" class="form-label">{{ __('admin.photo') }}</label>
                    @if(isset($member) && $member->photo)
                        <div class="mb-3">
                            <img src="{{ $member->photo_url }}" alt="{{ $member->name }}"
                                class="w-24 h-24 rounded-full object-cover">
                        </div>
                    @endif
                    <input type="file" id="photo" name="photo" accept="image/*" class="form-input">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="form-label">{{ __('admin.email') }}</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $member->email ?? '') }}"
                            class="form-input">
                    </div>
                    <div>
                        <label for="phone" class="form-label">{{ __('admin.phone') }}</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $member->phone ?? '') }}"
                            class="form-input">
                    </div>
                </div>
            </div>

            <div class="admin-card p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">{{ __('admin.social_links') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="facebook" class="form-label">{{ __('admin.facebook') }}</label>
                        <input type="url" id="facebook" name="facebook"
                            value="{{ old('facebook', $member->facebook ?? '') }}" class="form-input">
                    </div>
                    <div>
                        <label for="twitter" class="form-label">{{ __('admin.twitter') }}</label>
                        <input type="url" id="twitter" name="twitter" value="{{ old('twitter', $member->twitter ?? '') }}"
                            class="form-input">
                    </div>
                    <div>
                        <label for="instagram" class="form-label">{{ __('admin.instagram') }}</label>
                        <input type="url" id="instagram" name="instagram"
                            value="{{ old('instagram', $member->instagram ?? '') }}" class="form-input">
                    </div>
                    <div>
                        <label for="linkedin" class="form-label">{{ __('admin.linkedin') }}</label>
                        <input type="url" id="linkedin" name="linkedin"
                            value="{{ old('linkedin', $member->linkedin ?? '') }}" class="form-input">
                    </div>
                </div>
            </div>

            <div class="admin-card p-6">
                <div class="flex items-center gap-6">
                    <div>
                        <label for="sort_order" class="form-label">{{ __('admin.sort_order') }}</label>
                        <input type="number" id="sort_order" name="sort_order"
                            value="{{ old('sort_order', $member->sort_order ?? 0) }}" class="form-input w-24">
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer pt-6">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $member->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary-500">
                        <span class="text-white/80 text-sm">{{ __('admin.active') }}</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit"
                    class="px-6 py-3 rounded-lg btn-primary text-white font-medium">{{ isset($member) ? __('admin.update_member') : __('admin.add_member') }}</button>
                <a href="{{ route('admin.team.index') }}"
                    class="px-6 py-3 rounded-lg bg-white/5 text-white/70 hover:bg-white/10 hover:text-white transition-colors font-medium">{{ __('admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
