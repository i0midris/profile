<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesLocalizedInput;
use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use App\Models\JobOpening;
use Illuminate\Http\Request;

class JobOpeningController extends Controller
{
    use HandlesLocalizedInput;

    public function index()
    {
        $jobs = JobOpening::ordered()->paginate(10);
        $isPageVisible = CompanySetting::getSettings()->isPageVisible('jobs');

        return view('admin.jobs.index', compact('jobs', 'isPageVisible'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|required_without_all:title_en,title_ar|string|max:255',
            'title_en' => 'nullable|required_without_all:title,title_ar|string|max:255',
            'title_ar' => 'nullable|required_without_all:title,title_en|string|max:255',
            'department' => 'nullable|string|max:255',
            'department_en' => 'nullable|string|max:255',
            'department_ar' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'location_ar' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:255',
            'employment_type_en' => 'nullable|string|max:255',
            'employment_type_ar' => 'nullable|string|max:255',
            'experience_level' => 'nullable|string|max:255',
            'experience_level_en' => 'nullable|string|max:255',
            'experience_level_ar' => 'nullable|string|max:255',
            'description' => 'nullable|required_without_all:description_en,description_ar|string',
            'description_en' => 'nullable|required_without_all:description,description_ar|string',
            'description_ar' => 'nullable|required_without_all:description,description_en|string',
            'requirements' => 'nullable|string',
            'requirements_en' => 'nullable|string',
            'requirements_ar' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated = $this->hydrateLocalizedFields($validated, [
            'title',
            'department',
            'location',
            'employment_type',
            'experience_level',
            'description',
            'requirements',
        ]);

        $validated['requirements'] = $this->normalizeLineList($validated['requirements'] ?? null);
        $validated['requirements_en'] = $this->normalizeLineList($validated['requirements_en'] ?? null);
        $validated['requirements_ar'] = $this->normalizeLineList($validated['requirements_ar'] ?? null);
        $validated['is_active'] = $request->boolean('is_active', true);

        JobOpening::create($validated);

        return redirect()->route('admin.jobs.index')->with('success', __('admin.flash.job_created'));
    }

    public function edit(JobOpening $job)
    {
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, JobOpening $job)
    {
        $validated = $request->validate([
            'title' => 'nullable|required_without_all:title_en,title_ar|string|max:255',
            'title_en' => 'nullable|required_without_all:title,title_ar|string|max:255',
            'title_ar' => 'nullable|required_without_all:title,title_en|string|max:255',
            'department' => 'nullable|string|max:255',
            'department_en' => 'nullable|string|max:255',
            'department_ar' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'location_ar' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:255',
            'employment_type_en' => 'nullable|string|max:255',
            'employment_type_ar' => 'nullable|string|max:255',
            'experience_level' => 'nullable|string|max:255',
            'experience_level_en' => 'nullable|string|max:255',
            'experience_level_ar' => 'nullable|string|max:255',
            'description' => 'nullable|required_without_all:description_en,description_ar|string',
            'description_en' => 'nullable|required_without_all:description,description_ar|string',
            'description_ar' => 'nullable|required_without_all:description,description_en|string',
            'requirements' => 'nullable|string',
            'requirements_en' => 'nullable|string',
            'requirements_ar' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated = $this->hydrateLocalizedFields($validated, [
            'title',
            'department',
            'location',
            'employment_type',
            'experience_level',
            'description',
            'requirements',
        ]);

        $validated['requirements'] = $this->normalizeLineList($validated['requirements'] ?? null);
        $validated['requirements_en'] = $this->normalizeLineList($validated['requirements_en'] ?? null);
        $validated['requirements_ar'] = $this->normalizeLineList($validated['requirements_ar'] ?? null);
        $validated['is_active'] = $request->boolean('is_active');

        $job->update($validated);

        return redirect()->route('admin.jobs.index')->with('success', __('admin.flash.job_updated'));
    }

    public function destroy(JobOpening $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')->with('success', __('admin.flash.job_deleted'));
    }

    private function normalizeLineList(?string $value): array
    {
        if ($value === null || trim($value) === '') {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $value) ?: [];

        return array_values(array_filter(array_map(
            static fn (string $line) => trim($line),
            $lines
        )));
    }
}

