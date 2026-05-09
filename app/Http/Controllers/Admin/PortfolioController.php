<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesLocalizedInput;
use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    use HandlesLocalizedInput;

    public function index()
    {
        $portfolios = Portfolio::ordered()->paginate(10);
        $isPageVisible = CompanySetting::getSettings()->isPageVisible('portfolio');

        return view('admin.portfolio.index', compact('portfolios', 'isPageVisible'));
    }

    public function create()
    {
        $categories = Portfolio::getCategories();
        return view('admin.portfolio.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|required_without_all:title_en,title_ar|string|max:255',
            'title_en' => 'nullable|required_without_all:title,title_ar|string|max:255',
            'title_ar' => 'nullable|required_without_all:title,title_en|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'client_name' => 'nullable|string|max:255',
            'client_name_en' => 'nullable|string|max:255',
            'client_name_ar' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'category_en' => 'nullable|string|max:100',
            'category_ar' => 'nullable|string|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'project_url' => 'nullable|url|max:255',
            'completed_at' => 'nullable|date',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated = $this->hydrateLocalizedFields($validated, [
            'title',
            'description',
            'client_name',
            'category',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('portfolio', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        Portfolio::create($validated);

        return redirect()->route('admin.portfolio.index')->with('success', __('admin.flash.portfolio_created'));
    }

    public function edit(Portfolio $portfolio)
    {
        $categories = Portfolio::getCategories();
        return view('admin.portfolio.edit', compact('portfolio', 'categories'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title' => 'nullable|required_without_all:title_en,title_ar|string|max:255',
            'title_en' => 'nullable|required_without_all:title,title_ar|string|max:255',
            'title_ar' => 'nullable|required_without_all:title,title_en|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'client_name' => 'nullable|string|max:255',
            'client_name_en' => 'nullable|string|max:255',
            'client_name_ar' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'category_en' => 'nullable|string|max:100',
            'category_ar' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'project_url' => 'nullable|url|max:255',
            'completed_at' => 'nullable|date',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated = $this->hydrateLocalizedFields($validated, [
            'title',
            'description',
            'client_name',
            'category',
        ]);

        if ($request->hasFile('image')) {
            if ($portfolio->image) {
                Storage::disk('public')->delete($portfolio->image);
            }
            $validated['image'] = $request->file('image')->store('portfolio', 'public');
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $portfolio->update($validated);

        return redirect()->route('admin.portfolio.index')->with('success', __('admin.flash.portfolio_updated'));
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->image) {
            Storage::disk('public')->delete($portfolio->image);
        }
        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')->with('success', __('admin.flash.portfolio_deleted'));
    }

    public function deleteImage(Portfolio $portfolio)
    {
        if ($portfolio->image) {
            Storage::disk('public')->delete($portfolio->image);
            $portfolio->image = null;
            $portfolio->save();
            return back()->with('success', __('admin.flash.image_deleted'));
        }

        return back()->with('error', __('admin.flash.image_not_found'));
    }
}
