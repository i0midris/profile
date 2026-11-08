@extends('admin.layouts.app')

@section('title', __($definition['label']))
@section('subtitle', isset($definition['help']) ? __($definition['help']) : '')

@section('content')
    @php
        $isReadonly = $definition['readonly'] ?? false;
        $alignStart = app()->isLocale('ar') ? 'text-right' : 'text-left';
        $alignEnd = app()->isLocale('ar') ? 'text-left' : 'text-right';
        $titleOf = function ($item) use ($definition) {
            $field = $definition['title'];
            return method_exists($item, 'localized') && array_key_exists($field . '_ar', $item->getAttributes())
                ? $item->localized($field)
                : $item->{$field};
        };
        $subtitleOf = function ($item) use ($definition) {
            if (empty($definition['subtitle'])) return null;
            $field = $definition['subtitle'];
            return array_key_exists($field . '_ar', $item->getAttributes()) ? $item->localized($field) : $item->{$field};
        };
    @endphp

    @unless($isReadonly)
        <div class="flex items-center justify-between mb-6">
            <div></div>
            <a href="{{ route('admin.content.create', $definition['key']) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg btn-primary text-white font-medium">
                <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('admin.content.add', ['item' => __($definition['singular'])]) }}
            </a>
        </div>
    @endunless

    <div class="admin-card p-4 mb-6 text-white/70 text-sm">
        {{ __('admin.content.visibility_note') }}
    </div>

    <div class="table-container overflow-x-auto">
        <table class="w-full">
            <thead class="table-header">
                <tr>
                    <th class="px-6 py-4 {{ $alignStart }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __($definition['singular']) }}</th>
                    @foreach($definition['columns'] ?? [] as $column)
                        <th class="px-6 py-4 {{ $alignStart }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __($column['label']) }}</th>
                    @endforeach
                    @if(collect($definition['fields'])->contains('name', 'is_active'))
                        <th class="px-6 py-4 {{ $alignStart }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __('admin.status') }}</th>
                    @endif
                    <th class="px-6 py-4 {{ $alignEnd }} text-xs font-medium text-white/50 uppercase tracking-wider">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($items as $item)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if(!empty($definition['thumbnail']))
                                    @if($item->{$definition['thumbnail']})
                                        <img src="{{ asset('storage/' . $item->{$definition['thumbnail']}) }}" alt=""
                                            class="w-12 h-12 rounded-lg object-cover bg-white/5">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-primary-500/20 flex-shrink-0"></div>
                                    @endif
                                @endif
                                <div class="min-w-0">
                                    <p class="text-white font-medium">{{ $titleOf($item) }}</p>
                                    @if($subtitle = $subtitleOf($item))
                                        <p class="text-white/50 text-sm line-clamp-1">{{ Str::limit($subtitle, 70) }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        @foreach($definition['columns'] ?? [] as $column)
                            <td class="px-6 py-4 text-white/70 text-sm" @if(!empty($column['ltr'])) dir="ltr" @endif>
                                {{ value($column['value'], $item) ?: '-' }}
                            </td>
                        @endforeach
                        @if(collect($definition['fields'])->contains('name', 'is_active'))
                            <td class="px-6 py-4">
                                @if($item->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-400">{{ __('admin.content.published') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400">{{ __('admin.content.draft') }}</span>
                                @endif
                            </td>
                        @endif
                        <td class="px-6 py-4 {{ $alignEnd }}">
                            <div class="flex items-center justify-end gap-2">
                                @if(!empty($definition['public_route']) && ($item->is_active ?? true))
                                    <a href="{{ route($definition['public_route'], ['locale' => app()->getLocale(), $item]) }}" target="_blank"
                                        class="p-2 rounded-lg hover:bg-white/10 text-white/70 hover:text-white transition-colors" aria-label="{{ __('admin.view_site') }}">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                @endif
                                @foreach($definition['row_actions'] ?? [] as $action)
                                    @if($url = value($action['url'], $item))
                                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                            class="px-3 py-1.5 rounded-lg glass text-white/80 hover:text-white text-sm">{{ __($action['label']) }}</a>
                                    @endif
                                @endforeach
                                <a href="{{ route('admin.content.edit', [$definition['key'], $item->getKey()]) }}"
                                    class="p-2 rounded-lg hover:bg-white/10 text-white/70 hover:text-white transition-colors" aria-label="{{ $isReadonly ? __('admin.view') : __('admin.edit') }}">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.content.destroy', [$definition['key'], $item->getKey()]) }}" class="inline"
                                    onsubmit="return confirm('{{ __('admin.content.delete_confirm') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" aria-label="{{ __('admin.delete') }}"
                                        class="p-2 rounded-lg hover:bg-red-500/10 text-white/70 hover:text-red-400 transition-colors">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-white/50">
                            {{ __('admin.content.empty') }}
                            @unless($isReadonly)
                                <a href="{{ route('admin.content.create', $definition['key']) }}" class="text-primary-400 hover:underline">
                                    {{ __('admin.content.add', ['item' => __($definition['singular'])]) }}</a>
                            @endunless
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="mt-6">{{ $items->links() }}</div>
    @endif
@endsection
