<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesLocalizedInput;
use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    use HandlesLocalizedInput;

    public function index()
    {
        $members = TeamMember::ordered()->paginate(10);
        $isPageVisible = CompanySetting::getSettings()->isPageVisible('team');

        return view('admin.team.index', compact('members', 'isPageVisible'));
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|required_without_all:name_en,name_ar|string|max:255',
            'name_en' => 'nullable|required_without_all:name,name_ar|string|max:255',
            'name_ar' => 'nullable|required_without_all:name,name_en|string|max:255',
            'position' => 'nullable|required_without_all:position_en,position_ar|string|max:255',
            'position_en' => 'nullable|required_without_all:position,position_ar|string|max:255',
            'position_ar' => 'nullable|required_without_all:position,position_en|string|max:255',
            'bio' => 'nullable|string',
            'bio_en' => 'nullable|string',
            'bio_ar' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated = $this->hydrateLocalizedFields($validated, [
            'name',
            'position',
            'bio',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('team', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        TeamMember::create($validated);

        return redirect()->route('admin.team.index')->with('success', __('admin.flash.team_member_created'));
    }

    public function edit(TeamMember $team)
    {
        return view('admin.team.edit', ['member' => $team]);
    }

    public function update(Request $request, TeamMember $team)
    {
        $validated = $request->validate([
            'name' => 'nullable|required_without_all:name_en,name_ar|string|max:255',
            'name_en' => 'nullable|required_without_all:name,name_ar|string|max:255',
            'name_ar' => 'nullable|required_without_all:name,name_en|string|max:255',
            'position' => 'nullable|required_without_all:position_en,position_ar|string|max:255',
            'position_en' => 'nullable|required_without_all:position,position_ar|string|max:255',
            'position_ar' => 'nullable|required_without_all:position,position_en|string|max:255',
            'bio' => 'nullable|string',
            'bio_en' => 'nullable|string',
            'bio_ar' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated = $this->hydrateLocalizedFields($validated, [
            'name',
            'position',
            'bio',
        ]);

        if ($request->hasFile('photo')) {
            if ($team->photo) {
                Storage::disk('public')->delete($team->photo);
            }
            $validated['photo'] = $request->file('photo')->store('team', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $team->update($validated);

        return redirect()->route('admin.team.index')->with('success', __('admin.flash.team_member_updated'));
    }

    public function destroy(TeamMember $team)
    {
        if ($team->photo) {
            Storage::disk('public')->delete($team->photo);
        }
        $team->delete();

        return redirect()->route('admin.team.index')->with('success', __('admin.flash.team_member_deleted'));
    }

    public function deleteImage(TeamMember $team)
    {
        if ($team->photo) {
            Storage::disk('public')->delete($team->photo);
            $team->photo = null;
            $team->save();
            return back()->with('success', __('admin.flash.photo_deleted'));
        }

        return back()->with('error', __('admin.flash.photo_not_found'));
    }
}
