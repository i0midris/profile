<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesLocalizedInput;
use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    use HandlesLocalizedInput;

    public function edit()
    {
        $about = AboutSection::active()->first() ?? new AboutSection();
        $isPageVisible = CompanySetting::getSettings()->isPageVisible('about');

        return view('admin.about.edit', compact('about', 'isPageVisible'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|required_without_all:title_en,title_ar|string|max:255',
            'title_en' => 'nullable|required_without_all:title,title_ar|string|max:255',
            'title_ar' => 'nullable|required_without_all:title,title_en|string|max:255',
            'content' => 'nullable|required_without_all:content_en,content_ar|string',
            'content_en' => 'nullable|required_without_all:content,content_ar|string',
            'content_ar' => 'nullable|required_without_all:content,content_en|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'mission_title' => 'nullable|string|max:255',
            'mission_title_en' => 'nullable|string|max:255',
            'mission_title_ar' => 'nullable|string|max:255',
            'mission_content' => 'nullable|string',
            'mission_content_en' => 'nullable|string',
            'mission_content_ar' => 'nullable|string',
            'vision_title' => 'nullable|string|max:255',
            'vision_title_en' => 'nullable|string|max:255',
            'vision_title_ar' => 'nullable|string|max:255',
            'vision_content' => 'nullable|string',
            'vision_content_en' => 'nullable|string',
            'vision_content_ar' => 'nullable|string',
            'years_experience' => 'nullable|integer|min:0',
            'projects_completed' => 'nullable|integer|min:0',
            'happy_clients' => 'nullable|integer|min:0',
            'team_members' => 'nullable|integer|min:0',
            'show_about_page' => 'required|boolean',
        ]);

        $about = AboutSection::first() ?? new AboutSection();

        $validated = $this->hydrateLocalizedFields($validated, [
            'title',
            'content',
            'mission_title',
            'mission_content',
            'vision_title',
            'vision_content',
        ]);

        if ($request->hasFile('image')) {
            if ($about->image) {
                Storage::disk('public')->delete($about->image);
            }
            $validated['image'] = $request->file('image')->store('about', 'public');
        }

        $showAboutPage = (bool) $validated['show_about_page'];
        unset($validated['show_about_page']);

        $validated['is_active'] = true;
        $about->fill($validated);
        $about->save();
        CompanySetting::setPageVisibility('about', $showAboutPage);

        return back()->with('success', __('admin.flash.about_updated'));
    }

    public function deleteImage()
    {
        $about = AboutSection::first();

        if ($about && $about->image) {
            Storage::disk('public')->delete($about->image);
            $about->image = null;
            $about->save();
            return back()->with('success', __('admin.flash.image_deleted'));
        }

        return back()->with('error', __('admin.flash.image_not_found'));
    }
}
