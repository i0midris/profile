<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesLocalizedInput;
use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    use HandlesLocalizedInput;

    public function index()
    {
        $services = Service::ordered()->paginate(10);
        $isPageVisible = CompanySetting::getSettings()->isPageVisible('services');

        return view('admin.services.index', compact('services', 'isPageVisible'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|required_without_all:title_en,title_ar|string|max:255',
            'title_en' => 'nullable|required_without_all:title,title_ar|string|max:255',
            'title_ar' => 'nullable|required_without_all:title,title_en|string|max:255',
            'description' => 'nullable|required_without_all:description_en,description_ar|string',
            'description_en' => 'nullable|required_without_all:description,description_ar|string',
            'description_ar' => 'nullable|required_without_all:description,description_en|string',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'features_en' => 'nullable|array',
            'features_en.*' => 'string|max:255',
            'features_ar' => 'nullable|array',
            'features_ar.*' => 'string|max:255',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated = $this->hydrateLocalizedFields($validated, [
            'title',
            'description',
            'features',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', __('admin.flash.service_created'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'nullable|required_without_all:title_en,title_ar|string|max:255',
            'title_en' => 'nullable|required_without_all:title,title_ar|string|max:255',
            'title_ar' => 'nullable|required_without_all:title,title_en|string|max:255',
            'description' => 'nullable|required_without_all:description_en,description_ar|string',
            'description_en' => 'nullable|required_without_all:description,description_ar|string',
            'description_ar' => 'nullable|required_without_all:description,description_en|string',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'features_en' => 'nullable|array',
            'features_en.*' => 'string|max:255',
            'features_ar' => 'nullable|array',
            'features_ar.*' => 'string|max:255',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated = $this->hydrateLocalizedFields($validated, [
            'title',
            'description',
            'features',
        ]);

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', __('admin.flash.service_updated'));
    }

    public function destroy(Service $service)
    {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', __('admin.flash.service_deleted'));
    }

    public function deleteImage(Service $service)
    {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
            $service->image = null;
            $service->save();
            return back()->with('success', __('admin.flash.image_deleted'));
        }

        return back()->with('error', __('admin.flash.image_not_found'));
    }
}
