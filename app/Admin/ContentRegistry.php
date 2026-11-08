<?php

namespace App\Admin;

use App\Models\ClientLogo;
use App\Models\DemoRequest;
use App\Models\Product;
use App\Support\PhoneNumber;

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
            'client-logos' => [
                'model' => ClientLogo::class,
                'label' => 'admin.content.client_logos',
                'singular' => 'admin.content.client_logo',
                'help' => 'admin.content.client_logos_help',
                'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                'title' => 'name',
                'thumbnail' => 'logo',
                'order_by' => ['sort_order', 'asc'],
                'fields' => [
                    ['name' => 'name', 'type' => 'text', 'required' => true, 'label' => 'admin.fields.client_name'],
                    ['name' => 'logo', 'type' => 'image', 'required' => true, 'max_width' => 400, 'label' => 'admin.fields.logo', 'help' => 'admin.fields.logo_help'],
                    ['name' => 'website_url', 'type' => 'url', 'label' => 'admin.fields.website_url'],
                    ['name' => 'sort_order', 'type' => 'number', 'label' => 'admin.sort_order'],
                    ['name' => 'is_active', 'type' => 'checkbox', 'default' => true, 'label' => 'admin.fields.published'],
                ],
            ],
            'demo-requests' => [
                'model' => DemoRequest::class,
                'readonly' => true,
                'readonly_view' => 'admin.content.demo_request_details',
                'label' => 'admin.content.demo_requests',
                'singular' => 'admin.content.demo_request',
                'help' => 'admin.content.demo_requests_help',
                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                'badge' => fn () => rescue(fn () => DemoRequest::pending()->count(), 0, false),
                'title' => 'name',
                'subtitle' => 'company',
                'order_by' => ['created_at', 'desc'],
                'columns' => [
                    ['label' => 'admin.phone', 'ltr' => true, 'value' => fn ($r) => PhoneNumber::display($r->phone)],
                    ['label' => 'admin.content.product', 'value' => fn ($r) => $r->product],
                    ['label' => 'admin.content.received', 'value' => fn ($r) => $r->created_at?->format('Y-m-d H:i')],
                    ['label' => 'admin.status', 'value' => fn ($r) => $r->is_handled ? __('admin.content.handled') : __('admin.content.pending')],
                ],
                'row_actions' => [
                    ['label' => 'admin.reply_whatsapp', 'url' => fn ($r) => PhoneNumber::whatsappUrl($r->phone)],
                ],
                'fields' => [
                    ['name' => 'is_handled', 'type' => 'checkbox', 'label' => 'admin.content.mark_handled'],
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
