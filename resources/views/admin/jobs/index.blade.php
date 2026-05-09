@extends('admin.layouts.app')

@section('title', __('admin.jobs'))
@section('subtitle', __('admin.manage_job_openings'))

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg btn-primary text-white font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        {{ __('admin.add_job') }}
    </a>
</div>

<div class="admin-card p-4 mb-6">
    <form method="POST" action="{{ route('admin.page-visibility.update', 'jobs') }}"
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        @csrf
        @method('PUT')
        <input type="hidden" name="is_visible" value="0">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_visible" value="1" {{ $isPageVisible ? 'checked' : '' }}
                class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary-500 focus:ring-primary-500/20">
            <span class="text-white/80 text-sm">{{ __('admin.show_page_on_website') }}</span>
        </label>
        <button type="submit"
            class="px-4 py-2 rounded-lg btn-primary text-white text-sm font-medium self-start sm:self-auto">{{ __('admin.save_page_visibility') }}</button>
    </form>
</div>

<div class="table-container overflow-hidden">
    <table class="w-full">
        <thead class="table-header">
            <tr>
                <th class="px-6 py-4 {{ app()->isLocale('ar') ? 'text-right' : 'text-left' }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __('admin.title') }}</th>
                <th class="px-6 py-4 {{ app()->isLocale('ar') ? 'text-right' : 'text-left' }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __('admin.department') }}</th>
                <th class="px-6 py-4 {{ app()->isLocale('ar') ? 'text-right' : 'text-left' }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __('admin.job_type') }}</th>
                <th class="px-6 py-4 {{ app()->isLocale('ar') ? 'text-right' : 'text-left' }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __('admin.sort_order') }}</th>
                <th class="px-6 py-4 {{ app()->isLocale('ar') ? 'text-right' : 'text-left' }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __('admin.status') }}</th>
                <th class="px-6 py-4 {{ app()->isLocale('ar') ? 'text-left' : 'text-right' }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __('admin.actions') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($jobs as $job)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-white font-medium">{{ $job->title }}</p>
                        @if($job->location)
                            <p class="text-white/40 text-xs mt-1">{{ $job->location }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-white/70">{{ $job->department ?: '—' }}</td>
                    <td class="px-6 py-4 text-white/70">{{ $job->employment_type ?: '—' }}</td>
                    <td class="px-6 py-4 text-white/70">{{ $job->sort_order }}</td>
                    <td class="px-6 py-4">
                        @if($job->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-400">{{ __('admin.active') }}</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400">{{ __('admin.inactive') }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 {{ app()->isLocale('ar') ? 'text-left' : 'text-right' }}">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.jobs.edit', $job) }}" class="p-2 rounded-lg hover:bg-white/10 text-white/70 hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" class="inline" onsubmit="return confirm('{{ __('admin.are_you_sure') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-white/70 hover:text-red-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-white/50">{{ __('admin.no_jobs_found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($jobs->hasPages())
    <div class="mt-6">{{ $jobs->links() }}</div>
@endif
@endsection
