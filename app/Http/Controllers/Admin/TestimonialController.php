<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesLocalizedInput;
use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    use HandlesLocalizedInput;

    public function index()
    {
        $testimonials = Testimonial::ordered()->paginate(10);
        $isPageVisible = CompanySetting::getSettings()->isPageVisible('testimonials');

        return view('admin.testimonials.index', compact('testimonials', 'isPageVisible'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'nullable|required_without_all:client_name_en,client_name_ar|string|max:255',
            'client_name_en' => 'nullable|required_without_all:client_name,client_name_ar|string|max:255',
            'client_name_ar' => 'nullable|required_without_all:client_name,client_name_en|string|max:255',
            'client_position' => 'nullable|string|max:255',
            'client_position_en' => 'nullable|string|max:255',
            'client_position_ar' => 'nullable|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'client_company_en' => 'nullable|string|max:255',
            'client_company_ar' => 'nullable|string|max:255',
            'client_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content' => 'nullable|required_without_all:content_en,content_ar|string',
            'content_en' => 'nullable|required_without_all:content,content_ar|string',
            'content_ar' => 'nullable|required_without_all:content,content_en|string',
            'rating' => 'required|integer|min:1|max:5',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated = $this->hydrateLocalizedFields($validated, [
            'client_name',
            'client_position',
            'client_company',
            'content',
        ]);

        if ($request->hasFile('client_photo')) {
            $validated['client_photo'] = $request->file('client_photo')->store('testimonials', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')->with('success', __('admin.flash.testimonial_created'));
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'client_name' => 'nullable|required_without_all:client_name_en,client_name_ar|string|max:255',
            'client_name_en' => 'nullable|required_without_all:client_name,client_name_ar|string|max:255',
            'client_name_ar' => 'nullable|required_without_all:client_name,client_name_en|string|max:255',
            'client_position' => 'nullable|string|max:255',
            'client_position_en' => 'nullable|string|max:255',
            'client_position_ar' => 'nullable|string|max:255',
            'client_company' => 'nullable|string|max:255',
            'client_company_en' => 'nullable|string|max:255',
            'client_company_ar' => 'nullable|string|max:255',
            'client_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content' => 'nullable|required_without_all:content_en,content_ar|string',
            'content_en' => 'nullable|required_without_all:content,content_ar|string',
            'content_ar' => 'nullable|required_without_all:content,content_en|string',
            'rating' => 'required|integer|min:1|max:5',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated = $this->hydrateLocalizedFields($validated, [
            'client_name',
            'client_position',
            'client_company',
            'content',
        ]);

        if ($request->hasFile('client_photo')) {
            if ($testimonial->client_photo) {
                Storage::disk('public')->delete($testimonial->client_photo);
            }
            $validated['client_photo'] = $request->file('client_photo')->store('testimonials', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')->with('success', __('admin.flash.testimonial_updated'));
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->client_photo) {
            Storage::disk('public')->delete($testimonial->client_photo);
        }
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', __('admin.flash.testimonial_deleted'));
    }

    public function deleteImage(Testimonial $testimonial)
    {
        if ($testimonial->client_photo) {
            Storage::disk('public')->delete($testimonial->client_photo);
            $testimonial->client_photo = null;
            $testimonial->save();
            return back()->with('success', __('admin.flash.photo_deleted'));
        }

        return back()->with('error', __('admin.flash.photo_not_found'));
    }
}
