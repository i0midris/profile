<?php

namespace App\Admin;

use App\Models\Product;

/**
 * Field definitions for the content types managed by the generic dashboard module
 * (App\Http\Controllers\Admin\ContentController + resources/views/admin/content/*).
 *
 * Field types: text, textarea, markdown, list, image, gallery, number, checkbox, slug, url, datetime.
 * "localized" fields are stored in <name>_ar and <name>_en columns; at least one language is required
 * when "required" is set (Arabic is the site's default language).
 */
class ContentRegistry
{
    public static function all(): array
    {
        return [
            'products' => [
                'model' => Product::class,
                'label' => 'admin.content.products',
                'singular' => 'admin.content.product',
                'help' => 'admin.content.products_help',
                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                'title' => 'name',
                'subtitle' => 'tagline',
                'thumbnail' => 'cover_image',
                'order_by' => ['sort_order', 'asc'],
                'public_route' => 'products.show',
                'fields' => [
                    ['name' => 'name', 'type' => 'text', 'localized' => true, 'required' => true, 'label' => 'admin.fields.name'],
                    ['name' => 'slug', 'type' => 'slug', 'source' => 'name_en', 'label' => 'admin.fields.slug', 'help' => 'admin.fields.slug_help'],
                    ['name' => 'tagline', 'type' => 'text', 'localized' => true, 'label' => 'admin.fields.tagline'],
                    ['name' => 'description', 'type' => 'textarea', 'localized' => true, 'required' => true, 'label' => 'admin.description'],
                    ['name' => 'features', 'type' => 'list', 'localized' => true, 'label' => 'admin.fields.features'],
                    ['name' => 'sectors', 'type' => 'list', 'localized' => true, 'label' => 'admin.fields.sectors', 'help' => 'admin.fields.sectors_help'],
                    ['name' => 'cover_image', 'type' => 'image', 'max_width' => 1600, 'label' => 'admin.fields.cover_image'],
                    ['name' => 'screenshots', 'type' => 'gallery', 'max_width' => 1600, 'label' => 'admin.fields.screenshots', 'help' => 'admin.fields.screenshots_help'],
                    ['name' => 'sort_order', 'type' => 'number', 'label' => 'admin.sort_order'],
                    ['name' => 'is_active', 'type' => 'checkbox', 'default' => true, 'label' => 'admin.fields.published'],
                ],
            ],
        ];
    }

    public static function get(string $key): array
    {
        $resource = static::all()[$key] ?? abort(404);
        $resource['key'] = $key;

        return $resource;
    }

    public static function keys(): array
    {
        return array_keys(static::all());
    }
}
