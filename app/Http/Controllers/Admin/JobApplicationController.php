<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = JobApplication::with('jobOpening')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $applications = $query->paginate(15)->withQueryString();

        return view('admin.job-applications.index', compact('applications'));
    }

    public function show(JobApplication $application)
    {
        if ($application->status === 'new') {
            $application->update(['status' => 'reviewed']);
            $application->refresh();
        }

        $application->load('jobOpening');

        return view('admin.job-applications.show', compact('application'));
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,reviewed,shortlisted,rejected,hired',
        ]);

        $application->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', __('admin.flash.job_application_status_updated'));
    }

    public function destroy(JobApplication $application)
    {
        if ($application->cv_path) {
            Storage::disk('public')->delete($application->cv_path);
        }

        $application->delete();

        return redirect()->route('admin.job-applications.index')->with('success', __('admin.flash.job_application_deleted'));
    }
}

