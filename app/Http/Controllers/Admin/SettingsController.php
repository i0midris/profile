<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesLocalizedInput;
use App\Http\Controllers\Controller;
use App\Rules\SafeSvg;
use App\Support\ImageUploader;
use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    use HandlesLocalizedInput;

    public function edit()
    {
        $settings = CompanySetting::getSettings();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        // Accept what people usually paste: the whole Search Console <meta> tag or a GA snippet.
        if (preg_match('/content=["\']([^"\']+)["\']/', (string) $request->input('google_site_verification'), $m)) {
            $request->merge(['google_site_verification' => $m[1]]);
        }
        if (preg_match('/\bG-[A-Z0-9]{4,20}\b/i', (string) $request->input('ga_measurement_id'), $m)) {
            $request->merge(['ga_measurement_id' => strtoupper($m[0])]);
        }

        $validated = $request->validate([
            'company_name' => 'nullable|required_without_all:company_name_en,company_name_ar|string|max:255',
            'company_name_en' => 'nullable|required_without_all:company_name,company_name_ar|string|max:255',
            'company_name_ar' => 'nullable|required_without_all:company_name,company_name_en|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'tagline_en' => 'nullable|string|max:255',
            'tagline_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'address_en' => 'nullable|string|max:500',
            'address_ar' => 'nullable|string|max:500',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|string|max:50',
            'working_hours_ar' => 'nullable|string|max:1000',
            'google_site_verification' => ['nullable', 'string', 'max:200', 'regex:/^[A-Za-z0-9_\-]+$/'],
            'ga_measurement_id' => ['nullable', 'string', 'regex:/^G-[A-Z0-9]{4,20}$/'],
            'working_hours_en' => 'nullable|string|max:1000',
            // Only Google Maps embed links (Share > Embed a map > copy the src URL)
            'map_embed_url' => ['nullable', 'url', 'max:1000', 'regex:#^https://(www\.)?google\.com/maps/embed#'],
            'logo' => ['nullable', 'image:allow_svg', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048', new SafeSvg()],
            'favicon' => 'nullable|image|mimes:ico,png,jpg|max:1024',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $settings = CompanySetting::first() ?? new CompanySetting();

        $validated = $this->hydrateLocalizedFields($validated, [
            'company_name',
            'tagline',
            'description',
            'address',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = ImageUploader::store($request->file('logo'), 'settings', 600);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $validated['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        // Handle social share image upload
        if ($request->hasFile('og_image')) {
            if ($settings->og_image) {
                Storage::disk('public')->delete($settings->og_image);
            }
            $validated['og_image'] = $request->file('og_image')->store('settings', 'public');
        }

        $settings->fill($validated);
        $settings->save();

        return back()->with('success', __('admin.flash.settings_updated'));
    }

    public function deleteImage(string $field)
    {
        $settings = CompanySetting::first();

        if ($settings && in_array($field, ['logo', 'favicon', 'og_image']) && $settings->$field) {
            Storage::disk('public')->delete($settings->$field);
            $settings->$field = null;
            $settings->save();
            $key = match ($field) {
                'logo' => 'admin.flash.logo_deleted',
                'favicon' => 'admin.flash.favicon_deleted',
                default => 'admin.flash.og_image_deleted',
            };
            return back()->with('success', __($key));
        }

        return back()->with('error', __('admin.flash.image_not_found'));
    }
}
