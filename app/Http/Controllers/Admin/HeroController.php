<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesLocalizedInput;
use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroController extends Controller
{
    use HandlesLocalizedInput;

    public function edit()
    {
        $hero = HeroSection::active()->first() ?? new HeroSection();
        return view('admin.hero.edit', compact('hero'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|required_without_all:title_en,title_ar|string|max:255',
            'title_en' => 'nullable|required_without_all:title,title_ar|string|max:255',
            'title_ar' => 'nullable|required_without_all:title,title_en|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',
            'subtitle_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_text_en' => 'nullable|string|max:100',
            'button_text_ar' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'button_text_secondary' => 'nullable|string|max:100',
            'button_text_secondary_en' => 'nullable|string|max:100',
            'button_text_secondary_ar' => 'nullable|string|max:100',
            'button_link_secondary' => 'nullable|string|max:255',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'foreground_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $hero = HeroSection::first() ?? new HeroSection();

        $validated = $this->hydrateLocalizedFields($validated, [
            'title',
            'subtitle',
            'description',
            'button_text',
            'button_text_secondary',
        ]);

        if ($request->hasFile('background_image')) {
            if ($hero->background_image) {
                Storage::disk('public')->delete($hero->background_image);
            }
            $validated['background_image'] = $request->file('background_image')->store('hero', 'public');
        }

        if ($request->hasFile('foreground_image')) {
            if ($hero->foreground_image) {
                Storage::disk('public')->delete($hero->foreground_image);
            }
            $validated['foreground_image'] = $request->file('foreground_image')->store('hero', 'public');
        }

        $validated['is_active'] = true;
        $hero->fill($validated);
        $hero->save();

        return back()->with('success', __('admin.flash.hero_updated'));
    }

    public function deleteImage(string $field)
    {
        $hero = HeroSection::first();

        if ($hero && in_array($field, ['background_image', 'foreground_image']) && $hero->$field) {
            Storage::disk('public')->delete($hero->$field);
            $hero->$field = null;
            $hero->save();
            return back()->with('success', __('admin.flash.image_deleted'));
        }

        return back()->with('error', __('admin.flash.image_not_found'));
    }
}
