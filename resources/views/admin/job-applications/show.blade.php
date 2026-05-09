@extends('admin.layouts.app')

@section('title', __('admin.job_application_details'))
@section('subtitle', __('admin.from_name', ['name' => $application->name]))

@section('content')
@php
    $statusBadgeClasses = match ($application->status) {
        'new' => 'bg-sky-500/20 text-sky-300 border border-sky-400/40',
        'reviewed' => 'bg-indigo-500/20 text-indigo-300 border border-indigo-400/40',
        'shortlisted' => 'bg-amber-500/20 text-amber-300 border border-amber-400/40',
        'rejected' => 'bg-red-500/20 text-red-300 border border-red-400/40',
        'hired' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/40',
        default => 'bg-white/10 text-white/80 border border-white/15',
    };
@endphp
<div class="max-w-4xl">
    <div class="admin-card p-6 mb-6">
        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-white/50 text-sm mb-1">{{ __('admin.name') }}</p>
                <p class="text-white font-medium">{{ $application->name }}</p>
            </div>
            <div>
                <p class="text-white/50 text-sm mb-1">{{ __('admin.email') }}</p>
                <a href="mailto:{{ $application->email }}" class="text-white hover:text-primary-400">{{ $application->email }}</a>
            </div>
            <div>
                <p class="text-white/50 text-sm mb-1">{{ __('admin.phone') }}</p>
                <p class="text-white/80">{{ $application->phone ?: '—' }}</p>
            </div>
            <div>
                <p class="text-white/50 text-sm mb-1">{{ __('admin.job') }}</p>
                <p class="text-white/80">{{ $application->jobOpening?->title ?? ($application->desired_position ?: __('admin.general_application')) }}</p>
            </div>
            <div>
                <p class="text-white/50 text-sm mb-1">{{ __('admin.desired_position') }}</p>
                <p class="text-white/80">{{ $application->desired_position ?: '—' }}</p>
            </div>
            <div>
                <p class="text-white/50 text-sm mb-1">{{ __('admin.date') }}</p>
                <p class="text-white/80">{{ $application->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div>
                <p class="text-white/50 text-sm mb-1">{{ __('admin.status') }}</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadgeClasses }}">
                    {{ __('admin.status_' . $application->status) }}
                </span>
            </div>
        </div>

        <div class="mb-6">
            <p class="text-white/50 text-sm mb-2">{{ __('admin.cover_letter') }}</p>
            <p class="text-white/80 leading-relaxed whitespace-pre-line">{{ $application->cover_letter }}</p>
        </div>

        @if($application->cv_url)
            <div>
                <a href="{{ $application->cv_url }}" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg glass text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 7H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V20a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('admin.download_cv') }}
                </a>
            </div>
        @endif
    </div>

    <div class="admin-card p-6 mb-6">
        <h2 class="text-white font-semibold mb-4">{{ __('admin.update_status') }}</h2>
        <form method="POST" action="{{ route('admin.job-applications.status', $application) }}" class="flex flex-wrap gap-3 items-end">
            @csrf
            @method('PATCH')
            <div class="min-w-52">
                <label for="status" class="form-label">{{ __('admin.status') }}</label>
                <select id="status" name="status" class="form-input">
                    @foreach(['new', 'reviewed', 'shortlisted', 'rejected', 'hired'] as $status)
                        <option value="{{ $status }}" {{ $application->status === $status ? 'selected' : '' }}>{{ __('admin.status_' . $status) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-3 rounded-lg btn-primary text-white font-medium">{{ __('admin.update') }}</button>
        </form>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.job-applications.index') }}" class="px-6 py-3 rounded-lg bg-white/5 text-white/70 hover:bg-white/10 hover:text-white transition-colors font-medium">
            {{ __('admin.back_to_job_applications') }}
        </a>
        <form method="POST" action="{{ route('admin.job-applications.destroy', $application) }}" onsubmit="return confirm('{{ __('admin.are_you_sure') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-3 rounded-lg bg-red-500/20 text-red-300 hover:bg-red-500/30 transition-colors font-medium">
                {{ __('admin.delete') }}
            </button>
        </form>
    </div>
</div>
@endsection
