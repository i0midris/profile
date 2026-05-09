@extends('admin.layouts.app')

@section('title', __('admin.add_job'))
@section('subtitle', __('admin.create_new_job'))

@section('content')
<form method="POST" action="{{ route('admin.jobs.store') }}" class="admin-card p-6 space-y-6">
    @csrf

    <div class="grid md:grid-cols-3 gap-6">
        <div>
            <label class="form-label">{{ __('admin.title') }} ({{ __('admin.lang_en') }})</label>
            <input type="text" name="title_en" class="form-input" value="{{ old('title_en') }}" required>
        </div>
        <div>
            <label class="form-label">{{ __('admin.title') }} ({{ __('admin.lang_ar') }})</label>
            <input type="text" name="title_ar" class="form-input" value="{{ old('title_ar') }}" required>
        </div>
        <div>
            <label class="form-label">{{ __('admin.sort_order') }}</label>
            <input type="number" name="sort_order" class="form-input" value="{{ old('sort_order', 0) }}">
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label class="form-label">{{ __('admin.department') }} ({{ __('admin.lang_en') }})</label>
            <input type="text" name="department_en" class="form-input" value="{{ old('department_en') }}">
        </div>
        <div>
            <label class="form-label">{{ __('admin.department') }} ({{ __('admin.lang_ar') }})</label>
            <input type="text" name="department_ar" class="form-input" value="{{ old('department_ar') }}">
        </div>

        <div>
            <label class="form-label">{{ __('admin.location') }} ({{ __('admin.lang_en') }})</label>
            <input type="text" name="location_en" class="form-input" value="{{ old('location_en') }}">
        </div>
        <div>
            <label class="form-label">{{ __('admin.location') }} ({{ __('admin.lang_ar') }})</label>
            <input type="text" name="location_ar" class="form-input" value="{{ old('location_ar') }}">
        </div>

        <div>
            <label class="form-label">{{ __('admin.job_type') }} ({{ __('admin.lang_en') }})</label>
            <input type="text" name="employment_type_en" class="form-input" value="{{ old('employment_type_en') }}" placeholder="{{ __('admin.placeholder_job_type') }}">
        </div>
        <div>
            <label class="form-label">{{ __('admin.job_type') }} ({{ __('admin.lang_ar') }})</label>
            <input type="text" name="employment_type_ar" class="form-input" value="{{ old('employment_type_ar') }}" placeholder="{{ __('admin.placeholder_job_type') }}">
        </div>

        <div>
            <label class="form-label">{{ __('admin.experience_level') }} ({{ __('admin.lang_en') }})</label>
            <input type="text" name="experience_level_en" class="form-input" value="{{ old('experience_level_en') }}" placeholder="{{ __('admin.placeholder_experience_level') }}">
        </div>
        <div>
            <label class="form-label">{{ __('admin.experience_level') }} ({{ __('admin.lang_ar') }})</label>
            <input type="text" name="experience_level_ar" class="form-input" value="{{ old('experience_level_ar') }}" placeholder="{{ __('admin.placeholder_experience_level') }}">
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label class="form-label">{{ __('admin.description') }} ({{ __('admin.lang_en') }})</label>
            <textarea name="description_en" rows="5" class="form-input" required>{{ old('description_en') }}</textarea>
        </div>
        <div>
            <label class="form-label">{{ __('admin.description') }} ({{ __('admin.lang_ar') }})</label>
            <textarea name="description_ar" rows="5" class="form-input" required>{{ old('description_ar') }}</textarea>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label class="form-label">{{ __('admin.job_requirements') }} ({{ __('admin.lang_en') }})</label>
            <textarea name="requirements_en" rows="6" class="form-input" placeholder="{{ __('admin.one_requirement_per_line') }}">{{ old('requirements_en') }}</textarea>
        </div>
        <div>
            <label class="form-label">{{ __('admin.job_requirements') }} ({{ __('admin.lang_ar') }})</label>
            <textarea name="requirements_ar" rows="6" class="form-input" placeholder="{{ __('admin.one_requirement_per_line') }}">{{ old('requirements_ar') }}</textarea>
        </div>
    </div>

    <label class="inline-flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
            class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary-500 focus:ring-primary-500/20">
        <span class="text-white/80 text-sm">{{ __('admin.active') }}</span>
    </label>

    <div class="flex items-center gap-3">
        <button type="submit" class="px-6 py-3 rounded-lg btn-primary text-white font-medium">{{ __('admin.add_job') }}</button>
        <a href="{{ route('admin.jobs.index') }}" class="px-6 py-3 rounded-lg bg-white/5 text-white/70 hover:bg-white/10 hover:text-white transition-colors font-medium">{{ __('admin.cancel') }}</a>
    </div>
</form>
@endsection
