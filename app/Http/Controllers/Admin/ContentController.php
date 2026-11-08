<?php

namespace App\Http\Controllers\Admin;

use App\Admin\ContentRegistry;
use App\Http\Controllers\Controller;
use App\Rules\SafeSvg;
use App\Support\ImageUploader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Generic list / create / edit / delete screens for the content types defined in
 * App\Admin\ContentRegistry (products, client logos, FAQ, blog posts...).
 */
class ContentController extends Controller
{
    public function index(string $resource)
    {
        $definition = ContentRegistry::get($resource);
        [$column, $direction] = $definition['order_by'] ?? ['id', 'desc'];

        $items = $definition['model']::query()->orderBy($column, $direction)->orderByDesc('id')->paginate(15);

        return view('admin.content.index', compact('definition', 'items'));
    }

    public function create(string $resource)
    {
        abort_if(ContentRegistry::get($resource)['readonly'] ?? false, 404);

        return view('admin.content.form', [
            'definition' => ContentRegistry::get($resource),
            'item' => null,
        ]);
    }

    public function store(Request $request, string $resource)
    {
        $definition = ContentRegistry::get($resource);
        abort_if($definition['readonly'] ?? false, 404);

        $model = new $definition['model']();
        $model->fill($this->payload($request, $definition, $model))->save();

        return redirect()->route('admin.content.index', $resource)->with('success', __('admin.cms.saved'));
    }

    public function edit(string $resource, int $id)
    {
        $definition = ContentRegistry::get($resource);

        return view('admin.content.form', [
            'definition' => $definition,
            'item' => $definition['model']::findOrFail($id),
        ]);
    }

    public function update(Request $request, string $resource, int $id)
    {
        $definition = ContentRegistry::get($resource);
        $model = $definition['model']::findOrFail($id);

        $model->fill($this->payload($request, $definition, $model))->save();

        return redirect()->route('admin.content.index', $resource)->with('success', __('admin.cms.saved'));
    }

    public function destroy(string $resource, int $id)
    {
        $definition = ContentRegistry::get($resource);
        $model = $definition['model']::findOrFail($id);

        foreach ($definition['fields'] as $field) {
            if ($field['type'] === 'image' && $model->{$field['name']}) {
                Storage::disk('public')->delete($model->{$field['name']});
            }
            if ($field['type'] === 'gallery') {
                Storage::disk('public')->delete($model->{$field['name']} ?? []);
            }
        }

        $model->delete();

        return redirect()->route('admin.content.index', $resource)->with('success', __('admin.cms.deleted'));
    }

    /**
     * Validate the request against the field definitions and build the attributes to save.
     */
    private function payload(Request $request, array $definition, Model $model): array
    {
        $rules = [];
        foreach ($definition['fields'] as $field) {
            $name = $field['name'];
            $required = !empty($field['required']);

            switch ($field['type']) {
                case 'text':
                case 'textarea':
                case 'markdown':
                    $base = $field['type'] === 'text' ? ['string', 'max:255'] : ['string', 'max:100000'];
                    if (!empty($field['localized'])) {
                        $rules["{$name}_ar"] = array_merge(['nullable', $required ? "required_without:{$name}_en" : 'nullable'], $base);
                        $rules["{$name}_en"] = array_merge(['nullable', $required ? "required_without:{$name}_ar" : 'nullable'], $base);
                    } else {
                        $rules[$name] = array_merge([$required ? 'required' : 'nullable'], $base);
                    }
                    break;
                case 'list':
                    foreach (!empty($field['localized']) ? ["{$name}_ar", "{$name}_en"] : [$name] as $key) {
                        $rules[$key] = ['nullable', 'array'];
                        $rules["{$key}.*"] = ['nullable', 'string', 'max:255'];
                    }
                    break;
                case 'image':
                    $rules[$name] = [($required && !$model->{$name}) ? 'required' : 'nullable', 'image:allow_svg', 'mimes:jpeg,png,jpg,gif,webp,svg', 'max:4096', new SafeSvg()];
                    $rules["remove_{$name}"] = ['nullable', 'boolean'];
                    break;
                case 'gallery':
                    $rules[$name] = ['nullable', 'array', 'max:20'];
                    $rules["{$name}.*"] = ['image', 'mimes:jpeg,png,jpg,webp', 'max:4096'];
                    $rules["keep_{$name}"] = ['nullable', 'array'];
                    $rules["keep_{$name}.*"] = ['string'];
                    break;
                case 'number':
                    $rules[$name] = ['nullable', 'integer', 'min:0', 'max:1000000'];
                    break;
                case 'checkbox':
                    $rules[$name] = ['nullable', 'boolean'];
                    break;
                case 'select':
                    $rules[$name] = [!empty($field['required']) ? 'required' : 'nullable', Rule::in($field['options'])];
                    break;
                case 'url':
                    $rules[$name] = [$required ? 'required' : 'nullable', 'url', 'max:500'];
                    break;
                case 'datetime':
                    $rules[$name] = [$required ? 'required' : 'nullable', 'date'];
                    break;
                case 'slug':
                    $rules[$name] = ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                        Rule::unique($model->getTable(), $name)->ignore($model->getKey())];
                    break;
            }
        }

        $request->validate($rules);
        $data = [];

        foreach ($definition['fields'] as $field) {
            $name = $field['name'];
            switch ($field['type']) {
                case 'text':
                case 'textarea':
                case 'markdown':
                case 'url':
                case 'datetime':
                    foreach (!empty($field['localized']) ? ["{$name}_ar", "{$name}_en"] : [$name] as $key) {
                        $value = $request->input($key);
                        $data[$key] = is_string($value) && trim($value) !== '' ? trim($value) : null;
                    }
                    break;
                case 'list':
                    foreach (!empty($field['localized']) ? ["{$name}_ar", "{$name}_en"] : [$name] as $key) {
                        $data[$key] = array_values(array_filter(array_map('trim', (array) $request->input($key, [])), fn ($v) => $v !== ''));
                    }
                    break;
                case 'number':
                    $data[$name] = (int) $request->input($name, 0);
                    break;
                case 'checkbox':
                    $data[$name] = $request->boolean($name);
                    break;
                case 'select':
                    $data[$name] = $request->input($name) ?: ($field['default'] ?? null);
                    break;
                case 'slug':
                    $source = $request->input($field['source'] ?? '') ?: $request->input(str_replace('_en', '_ar', $field['source'] ?? ''));
                    $slug = $request->input($name) ?: Str::slug((string) $source);
                    $data[$name] = $this->uniqueSlug($slug ?: Str::lower(Str::random(8)), $model, $name);
                    break;
                case 'image':
                    if ($request->boolean("remove_{$name}") && $model->{$name}) {
                        Storage::disk('public')->delete($model->{$name});
                        $data[$name] = null;
                    }
                    if ($request->hasFile($name)) {
                        if ($model->{$name}) {
                            Storage::disk('public')->delete($model->{$name});
                        }
                        $data[$name] = ImageUploader::store($request->file($name), $definition['key'], $field['max_width'] ?? 1600);
                    }
                    break;
                case 'gallery':
                    $existing = $model->{$name} ?? [];
                    $keep = array_values(array_intersect($existing, (array) $request->input("keep_{$name}", [])));
                    Storage::disk('public')->delete(array_diff($existing, $keep));
                    foreach ((array) $request->file($name, []) as $file) {
                        $keep[] = ImageUploader::store($file, $definition['key'], $field['max_width'] ?? 1600);
                    }
                    $data[$name] = $keep;
                    break;
            }
        }

        return $data;
    }

    private function uniqueSlug(string $slug, Model $model, string $column): string
    {
        $candidate = $slug;
        $i = 2;
        while ($model->newQuery()->where($column, $candidate)->whereKeyNot($model->getKey())->exists()) {
            $candidate = "{$slug}-{$i}";
            $i++;
        }

        return $candidate;
    }
}
