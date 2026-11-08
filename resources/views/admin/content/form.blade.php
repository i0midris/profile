@extends('admin.layouts.app')

@section('title', $item ? __('admin.content.edit', ['item' => __($definition['singular'])]) : __('admin.content.add', ['item' => __($definition['singular'])]))
@section('subtitle', __($definition['label']))

@section('content')
    @php
        $isReadonly = $definition['readonly'] ?? false;
        $languages = ['ar' => __('admin.lang_ar'), 'en' => __('admin.lang_en')];
        $val = fn (string $key, $default = null) => old($key, $item?->getAttributes() ? ($item->getAttributes()[$key] ?? $default) : $default);
        $listVal = function (string $key) use ($item) {
            $value = old($key, $item ? $item->{$key} : []);
            $value = is_string($value) ? (json_decode($value, true) ?: []) : ($value ?? []);
            return count($value) ? array_values($value) : [''];
        };
    @endphp

    <div class="max-w-5xl">
        @if($isReadonly && !empty($definition['readonly_view']))
            @include($definition['readonly_view'], ['item' => $item])
        @endif

        <form method="POST" enctype="multipart/form-data" class="space-y-6"
            action="{{ $item ? route('admin.content.update', [$definition['key'], $item->getKey()]) : route('admin.content.store', $definition['key']) }}">
            @csrf
            @if($item) @method('PUT') @endif

            <div class="admin-card p-6 space-y-6">
                @foreach($definition['fields'] as $field)
                    @php
                        $name = $field['name'];
                        $label = __($field['label']);
                        $required = !empty($field['required']);
                        $help = isset($field['help']) ? __($field['help']) : null;
                    @endphp

                    @if(in_array($field['type'], ['text', 'textarea', 'markdown']) && !empty($field['localized']))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($languages as $lang => $langLabel)
                                <div>
                                    <label for="{{ $name }}_{{ $lang }}" class="form-label">{{ $label }} ({{ $langLabel }})
                                        @if($required && $lang === 'ar')<span class="text-red-400">*</span>@endif</label>
                                    @if($field['type'] === 'text')
                                        <input type="text" id="{{ $name }}_{{ $lang }}" name="{{ $name }}_{{ $lang }}" value="{{ $val($name . '_' . $lang) }}"
                                            class="form-input" dir="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
                                    @else
                                        <textarea id="{{ $name }}_{{ $lang }}" name="{{ $name }}_{{ $lang }}" rows="{{ $field['type'] === 'markdown' ? 16 : 5 }}"
                                            class="form-input {{ $field['type'] === 'markdown' ? 'font-mono text-sm' : '' }}" dir="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">{{ $val($name . '_' . $lang) }}</textarea>
                                    @endif
                                    @error($name . '_' . $lang)<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                                </div>
                            @endforeach
                        </div>
                        @if($help)<p class="text-white/50 text-sm -mt-4">{{ $help }}</p>@endif

                    @elseif(in_array($field['type'], ['text', 'textarea', 'markdown', 'url', 'datetime', 'slug']))
                        <div>
                            <label for="{{ $name }}" class="form-label">{{ $label }} @if($required)<span class="text-red-400">*</span>@endif</label>
                            @if($field['type'] === 'textarea' || $field['type'] === 'markdown')
                                <textarea id="{{ $name }}" name="{{ $name }}" rows="6" class="form-input">{{ $val($name) }}</textarea>
                            @else
                                @php
                                    $inputType = ['url' => 'url', 'datetime' => 'datetime-local'][$field['type']] ?? 'text';
                                    $current = $val($name);
                                    if ($field['type'] === 'datetime' && $current) { $current = \Illuminate\Support\Carbon::parse($current)->format('Y-m-d\TH:i'); }
                                @endphp
                                <input type="{{ $inputType }}" id="{{ $name }}" name="{{ $name }}" value="{{ $current }}" class="form-input"
                                    @if(in_array($field['type'], ['url', 'slug', 'datetime'])) dir="ltr" @endif>
                            @endif
                            @if($help)<p class="text-white/50 text-sm mt-1">{{ $help }}</p>@endif
                            @error($name)<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>

                    @elseif($field['type'] === 'list')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach(!empty($field['localized']) ? $languages : ['' => ''] as $lang => $langLabel)
                                @php $key = $lang ? "{$name}_{$lang}" : $name; @endphp
                                <div>
                                    <label class="form-label">{{ $label }} @if($langLabel)({{ $langLabel }})@endif</label>
                                    <div x-data="{ items: @js($listVal($key)) }" class="space-y-2">
                                        <template x-for="(entry, index) in items" :key="index">
                                            <div class="flex gap-2">
                                                <input type="text" :name="'{{ $key }}[' + index + ']'" x-model="items[index]" class="form-input flex-1"
                                                    dir="{{ $lang === 'en' ? 'ltr' : 'rtl' }}">
                                                <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1" aria-label="{{ __('admin.delete') }}"
                                                    class="px-3 py-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors">&times;</button>
                                            </div>
                                        </template>
                                        <button type="button" @click="items.push('')" class="text-primary-400 hover:text-primary-300 text-sm">+ {{ __('admin.content.add_item') }}</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($help)<p class="text-white/50 text-sm -mt-4">{{ $help }}</p>@endif

                    @elseif($field['type'] === 'image')
                        <div>
                            <label for="{{ $name }}" class="form-label">{{ $label }} @if($required)<span class="text-red-400">*</span>@endif</label>
                            @if($item && $item->{$name})
                                <div class="mb-3 flex items-center gap-4">
                                    <img src="{{ asset('storage/' . $item->{$name}) }}" alt="" class="h-20 rounded-lg object-contain bg-white/5">
                                    @unless($required)
                                        <label class="flex items-center gap-2 text-sm text-red-300 cursor-pointer">
                                            <input type="checkbox" name="remove_{{ $name }}" value="1"> {{ __('admin.content.remove_image') }}
                                        </label>
                                    @endunless
                                </div>
                            @endif
                            <input type="file" id="{{ $name }}" name="{{ $name }}" accept="image/*" class="form-input">
                            @if($help)<p class="text-white/50 text-sm mt-1">{{ $help }}</p>@endif
                            @error($name)<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                        </div>

                    @elseif($field['type'] === 'gallery')
                        <div>
                            <label for="{{ $name }}" class="form-label">{{ $label }}</label>
                            @if($item && !empty($item->{$name}))
                                <div class="mb-3 grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @foreach($item->{$name} as $path)
                                        <label class="block relative rounded-lg overflow-hidden bg-white/5 cursor-pointer">
                                            <img src="{{ asset('storage/' . $path) }}" alt="" class="w-full h-24 object-cover">
                                            <span class="absolute bottom-0 inset-x-0 bg-black/60 text-xs text-white px-2 py-1 flex items-center gap-1">
                                                <input type="checkbox" name="keep_{{ $name }}[]" value="{{ $path }}" checked> {{ __('admin.content.keep') }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" id="{{ $name }}" name="{{ $name }}[]" accept="image/*" multiple class="form-input">
                            @if($help)<p class="text-white/50 text-sm mt-1">{{ $help }}</p>@endif
                            @if($errors->has($name) || $errors->has($name . '.*'))<p class="mt-1 text-sm text-red-400">{{ $errors->first($name) ?: $errors->first($name . '.*') }}</p>@endif
                        </div>

                    @elseif($field['type'] === 'number')
                        <div class="max-w-xs">
                            <label for="{{ $name }}" class="form-label">{{ $label }}</label>
                            <input type="number" id="{{ $name }}" name="{{ $name }}" value="{{ $val($name, 0) }}" min="0" class="form-input">
                        </div>

                    @elseif($field['type'] === 'checkbox')
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="{{ $name }}" value="0">
                            <input type="checkbox" name="{{ $name }}" value="1" {{ $val($name, $field['default'] ?? false) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary-500 focus:ring-primary-500/20">
                            <span class="text-white/80 text-sm">{{ $label }}</span>
                        </label>
                        @if($help)<p class="text-white/50 text-sm -mt-4">{{ $help }}</p>@endif
                    @endif
                @endforeach
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-3 rounded-lg btn-primary text-white font-medium">{{ __('admin.content.save') }}</button>
                <a href="{{ route('admin.content.index', $definition['key']) }}"
                    class="px-6 py-3 rounded-lg bg-white/5 text-white/70 hover:bg-white/10 hover:text-white transition-colors font-medium">{{ __('admin.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
