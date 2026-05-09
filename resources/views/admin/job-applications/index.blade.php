@extends('admin.layouts.app')

@section('title', __('admin.job_applications'))
@section('subtitle', __('admin.manage_job_applications'))

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 flex-wrap">
        @php
            $statuses = [
                '' => __('admin.all'),
                'new' => __('admin.status_new'),
                'reviewed' => __('admin.status_reviewed'),
                'shortlisted' => __('admin.status_shortlisted'),
                'rejected' => __('admin.status_rejected'),
                'hired' => __('admin.status_hired'),
            ];
        @endphp

        @foreach($statuses as $status => $label)
            <a href="{{ route('admin.job-applications.index', $status !== '' ? ['status' => $status] : []) }}"
                class="px-4 py-2 rounded-lg {{ request('status', '') === $status ? 'gradient-primary text-white' : 'glass text-white/70 hover:text-white' }} transition-colors">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>

<div class="table-container overflow-hidden">
    <table class="w-full">
        <thead class="table-header">
            <tr>
                <th class="px-6 py-4 {{ app()->isLocale('ar') ? 'text-right' : 'text-left' }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __('admin.name') }}</th>
                <th class="px-6 py-4 {{ app()->isLocale('ar') ? 'text-right' : 'text-left' }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __('admin.job') }}</th>
                <th class="px-6 py-4 {{ app()->isLocale('ar') ? 'text-right' : 'text-left' }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __('admin.status') }}</th>
                <th class="px-6 py-4 {{ app()->isLocale('ar') ? 'text-right' : 'text-left' }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __('admin.date') }}</th>
                <th class="px-6 py-4 {{ app()->isLocale('ar') ? 'text-left' : 'text-right' }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __('admin.actions') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($applications as $application)
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
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-white font-medium">{{ $application->name }}</p>
                        <p class="text-white/50 text-sm">{{ $application->email }}</p>
                    </td>
                    <td class="px-6 py-4 text-white/70">
                        {{ $application->jobOpening?->title ?? ($application->desired_position ?: __('admin.general_application')) }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadgeClasses }}">
                            {{ __('admin.status_' . $application->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-white/50 text-sm">{{ $application->created_at->format('M d, Y h:i A') }}</td>
                    <td class="px-6 py-4 {{ app()->isLocale('ar') ? 'text-left' : 'text-right' }}">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.job-applications.show', $application) }}"
                                class="p-2 rounded-lg hover:bg-white/10 text-white/70 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.job-applications.destroy', $application) }}" class="inline" onsubmit="return confirm('{{ __('admin.are_you_sure') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-white/70 hover:text-red-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-white/50">{{ __('admin.no_job_applications_found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($applications->hasPages())
    <div class="mt-6">{{ $applications->links() }}</div>
@endif
@endsection
