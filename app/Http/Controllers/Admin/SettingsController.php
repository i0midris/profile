<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesLocalizedInput;
use App\Http\Controllers\Controller;
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg|max:1024',
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
            $validated['logo'] = $request->file('logo')->store('settings', 'public');
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $validated['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        $settings->fill($validated);
        $settings->save();

        return back()->with('success', __('admin.flash.settings_updated'));
    }

    public function deleteImage(string $field)
    {
        $settings = CompanySetting::first();

        if ($settings && in_array($field, ['logo', 'favicon']) && $settings->$field) {
            Storage::disk('public')->delete($settings->$field);
            $settings->$field = null;
            $settings->save();
            $key = $field === 'logo'
                ? 'admin.flash.logo_deleted'
                : 'admin.flash.favicon_deleted';
            return back()->with('success', __($key));
        }

        return back()->with('error', __('admin.flash.image_not_found'));
    }
}
