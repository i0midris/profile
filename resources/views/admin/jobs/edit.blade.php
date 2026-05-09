@extends('admin.layouts.app')

@section('title', __('admin.edit_job'))
@section('subtitle', __('admin.update_job_details'))

@section('content')
@php
    $requirementsEn = json_decode($job->getRawOriginal('requirements_en') ?: '[]', true) ?: [];
    $requirementsAr = json_decode($job->getRawOriginal('requirements_ar') ?: '[]', true) ?: [];
@endphp

<form method="POST" action="{{ route('admin.jobs.update', $job) }}" class="admin-card p-6 space-y-6">
    @csrf
    @method('PUT')

    <div class="grid md:grid-cols-3 gap-6">
        <div>
            <label class="form-label">{{ __('admin.title') }} ({{ __('admin.lang_en') }})</label>
            <input type="text" name="title_en" class="form-input" value="{{ old('title_en', $job->getRawOriginal('title_en')) }}" required>
        </div>
        <div>
            <label class="form-label">{{ __('admin.title') }} ({{ __('admin.lang_ar') }})</label>
            <input type="text" name="title_ar" class="form-input" value="{{ old('title_ar', $job->getRawOriginal('title_ar')) }}" required>
        </div>
        <div>
            <label class="form-label">{{ __('admin.sort_order') }}</label>
            <input type="number" name="sort_order" class="form-input" value="{{ old('sort_order', $job->sort_order) }}">
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label class="form-label">{{ __('admin.department') }} ({{ __('admin.lang_en') }})</label>
            <input type="text" name="department_en" class="form-input" value="{{ old('department_en', $job->getRawOriginal('department_en')) }}">
        </div>
        <div>
            <label class="form-label">{{ __('admin.department') }} ({{ __('admin.lang_ar') }})</label>
            <input type="text" name="department_ar" class="form-input" value="{{ old('department_ar', $job->getRawOriginal('department_ar')) }}">
        </div>

        <div>
            <label class="form-label">{{ __('admin.location') }} ({{ __('admin.lang_en') }})</label>
            <input type="text" name="location_en" class="form-input" value="{{ old('location_en', $job->getRawOriginal('location_en')) }}">
        </div>
        <div>
            <label class="form-label">{{ __('admin.location') }} ({{ __('admin.lang_ar') }})</label>
            <input type="text" name="location_ar" class="form-input" value="{{ old('location_ar', $job->getRawOriginal('location_ar')) }}">
        </div>

        <div>
            <label class="form-label">{{ __('admin.job_type') }} ({{ __('admin.lang_en') }})</label>
            <input type="text" name="employment_type_en" class="form-input" value="{{ old('employment_type_en', $job->getRawOriginal('employment_type_en')) }}" placeholder="{{ __('admin.placeholder_job_type') }}">
        </div>
        <div>
            <label class="form-label">{{ __('admin.job_type') }} ({{ __('admin.lang_ar') }})</label>
            <input type="text" name="employment_type_ar" class="form-input" value="{{ old('employment_type_ar', $job->getRawOriginal('employment_type_ar')) }}" placeholder="{{ __('admin.placeholder_job_type') }}">
        </div>

        <div>
            <label class="form-label">{{ __('admin.experience_level') }} ({{ __('admin.lang_en') }})</label>
            <input type="text" name="experience_level_en" class="form-input" value="{{ old('experience_level_en', $job->getRawOriginal('experience_level_en')) }}" placeholder="{{ __('admin.placeholder_experience_level') }}">
        </div>
        <div>
            <label class="form-label">{{ __('admin.experience_level') }} ({{ __('admin.lang_ar') }})</label>
            <input type="text" name="experience_level_ar" class="form-input" value="{{ old('experience_level_ar', $job->getRawOriginal('experience_level_ar')) }}" placeholder="{{ __('admin.placeholder_experience_level') }}">
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label class="form-label">{{ __('admin.description') }} ({{ __('admin.lang_en') }})</label>
            <textarea name="description_en" rows="5" class="form-input" required>{{ old('description_en', $job->getRawOriginal('description_en')) }}</textarea>
        </div>
        <div>
            <label class="form-label">{{ __('admin.description') }} ({{ __('admin.lang_ar') }})</label>
            <textarea name="description_ar" rows="5" class="form-input" required>{{ old('description_ar', $job->getRawOriginal('description_ar')) }}</textarea>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label class="form-label">{{ __('admin.job_requirements') }} ({{ __('admin.lang_en') }})</label>
            <textarea name="requirements_en" rows="6" class="form-input" placeholder="{{ __('admin.one_requirement_per_line') }}">{{ old('requirements_en', implode(PHP_EOL, $requirementsEn)) }}</textarea>
        </div>
        <div>
            <label class="form-label">{{ __('admin.job_requirements') }} ({{ __('admin.lang_ar') }})</label>
            <textarea name="requirements_ar" rows="6" class="form-input" placeholder="{{ __('admin.one_requirement_per_line') }}">{{ old('requirements_ar', implode(PHP_EOL, $requirementsAr)) }}</textarea>
        </div>
    </div>

    <label class="inline-flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $job->is_active) ? 'checked' : '' }}
            class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary-500 focus:ring-primary-500/20">
        <span class="text-white/80 text-sm">{{ __('admin.active') }}</span>
    </label>

    <div class="flex items-center gap-3">
        <button type="submit" class="px-6 py-3 rounded-lg btn-primary text-white font-medium">{{ __('admin.update_job') }}</button>
        <a href="{{ route('admin.jobs.index') }}" class="px-6 py-3 rounded-lg bg-white/5 text-white/70 hover:bg-white/10 hover:text-white transition-colors font-medium">{{ __('admin.cancel') }}</a>
    </div>
</form>
@endsection
