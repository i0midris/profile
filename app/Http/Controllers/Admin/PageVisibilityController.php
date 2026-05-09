<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use Illuminate\Http\Request;

class PageVisibilityController extends Controller
{
    public function update(Request $request, string $page)
    {
        abort_unless(array_key_exists($page, CompanySetting::PAGE_VISIBILITY_FIELDS), 404);

        $request->validate([
            'is_visible' => ['required', 'boolean'],
        ]);

        CompanySetting::setPageVisibility($page, $request->boolean('is_visible'));

        return back()->with('success', __('admin.flash.page_visibility_updated'));
    }
}
