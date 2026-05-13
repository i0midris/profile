<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use App\Models\CompanySetting;
use App\Models\ContactMessage;
use App\Models\HeroSection;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Get common data for all pages
     */
    private function getCommonData()
    {
        $settings = CompanySetting::getSettings();

        return [
            'settings' => $settings,
            'visiblePages' => $settings->getVisiblePages(),
        ];
    }

    public function home()
    {
        $commonData = $this->getCommonData();
        $visiblePages = $commonData['visiblePages'];

        $hero = HeroSection::active()->first();
        if ($hero) {
            $hero->setAttribute('button_link_localized', $this->localizeInternalUrl($hero->button_link));
            $hero->setAttribute('button_link_secondary_localized', $this->localizeInternalUrl($hero->button_link_secondary));
        }

        $data = array_merge($commonData, [
            'hero' => $hero,
            'services' => $visiblePages['services']
                ? Service::active()->featured()->ordered()->take(6)->get()
                : collect(),
            'about' => $visiblePages['about']
                ? AboutSection::active()->first()
                : null,
            'testimonials' => $visiblePages['testimonials']
                ? Testimonial::active()->featured()->ordered()->take(3)->get()
                : collect(),
            'portfolios' => $visiblePages['portfolio']
                ? Portfolio::active()->featured()->ordered()->take(6)->get()
                : collect(),
        ]);

        return view('frontend.home', $data);
    }

    public function about()
    {
        $this->ensurePageIsVisible('about');

        $data = array_merge($this->getCommonData(), [
            'about' => AboutSection::active()->first(),
            'team' => TeamMember::active()->ordered()->take(4)->get(),
        ]);

        return view('frontend.about', $data);
    }

    public function services()
    {
        $this->ensurePageIsVisible('services');

        $data = array_merge($this->getCommonData(), [
            'services' => Service::active()->ordered()->get(),
        ]);

        return view('frontend.services', $data);
    }

    public function team()
    {
        $this->ensurePageIsVisible('team');

        $data = array_merge($this->getCommonData(), [
            'members' => TeamMember::active()->ordered()->get(),
        ]);

        return view('frontend.team', $data);
    }

    public function portfolio()
    {
        $this->ensurePageIsVisible('portfolio');

        $data = array_merge($this->getCommonData(), [
            'portfolios' => Portfolio::active()->ordered()->get(),
            'categories' => Portfolio::getCategories(),
        ]);

        return view('frontend.portfolio', $data);
    }

    public function testimonials()
    {
        $this->ensurePageIsVisible('testimonials');

        $data = array_merge($this->getCommonData(), [
            'testimonials' => Testimonial::active()->ordered()->get(),
        ]);

        return view('frontend.testimonials', $data);
    }

    public function jobs(Request $request)
    {
        $this->ensurePageIsVisible('jobs');

        $jobs = JobOpening::active()->ordered()->get();
        $selectedJobId = (int) $request->query('job', 0);

        if (!$jobs->contains('id', $selectedJobId)) {
            $selectedJobId = $jobs->first()->id ?? null;
        }

        $data = array_merge($this->getCommonData(), [
            'jobs' => $jobs,
            'selectedJobId' => $selectedJobId,
        ]);

        return view('frontend.jobs', $data);
    }

    public function contact()
    {
        $this->ensurePageIsVisible('contact');

        return view('frontend.contact', $this->getCommonData());
    }

    public function sendMessage(Request $request)
    {
        $this->ensurePageIsVisible('contact');

        // Honeypot: bots usually fill hidden fields.
        if ($request->filled('company')) {
            return back()->with('success', __('frontend.contact_form_success'));
        }

        // Timing trap: reject submissions sent too quickly.
        $formStartedAt = (int) $request->input('form_started_at', 0);
        if ($formStartedAt <= 0 || now()->timestamp - $formStartedAt < 3) {
            return back()
                ->withErrors(['contact' => __('frontend.contact_submit_too_fast')])
                ->withInput();
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => [
                'required',
                'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $normalizedMessage = preg_replace('/\s+/u', '', (string) $value) ?? '';

                    if (mb_strlen($normalizedMessage, 'UTF-8') < 10) {
                        $fail(__('frontend.contact_message_min'));
                    }
                },
            ],
            'company' => 'nullable|string|max:0',
            'form_started_at' => 'required|integer',
        ], [
            'message.required' => __('frontend.contact_message_required'),
        ]);

        ContactMessage::create(collect($validated)->except(['company', 'form_started_at'])->all());

        return back()->with('success', __('frontend.contact_form_success'));
    }

    public function applyForJob(Request $request)
    {
        $this->ensurePageIsVisible('jobs');
        $jobOpeningId = $request->input('job_opening_id');
        $desiredPosition = trim((string) $request->input('desired_position', ''));

        $request->merge([
            'job_opening_id' => $jobOpeningId === '' ? null : $jobOpeningId,
            'desired_position' => $desiredPosition === '' ? null : $desiredPosition,
        ]);

        $validated = $request->validate([
            'job_opening_id' => 'nullable|exists:job_openings,id',
            'desired_position' => 'nullable|required_without:job_opening_id|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'cover_letter' => 'required|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $job = null;
        if (!empty($validated['job_opening_id'])) {
            $job = JobOpening::active()->findOrFail((int) $validated['job_opening_id']);
        }

        $cvPath = $request->file('cv')->store('job-applications/cv', 'public');

        JobApplication::create([
            'job_opening_id' => $job?->id,
            'desired_position' => $job ? null : ($validated['desired_position'] ?? null),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'cover_letter' => $validated['cover_letter'],
            'cv_path' => $cvPath,
            'status' => 'new',
        ]);

        return back()->with('success', __('frontend.job_application_success'));
    }

    /**
     * Prefix internal links with the current locale while preserving query/fragment.
     */
    private function localizeInternalUrl(?string $url): ?string
    {
        if ($url === null) {
            return null;
        }

        $url = trim($url);
        if ($url === '') {
            return '';
        }

        if (Str::startsWith($url, ['#', 'mailto:', 'tel:', 'http://', 'https://', '//'])) {
            return $url;
        }

        $parts = parse_url($url);
        if ($parts === false) {
            return $url;
        }

        $path = $parts['path'] ?? '';
        if ($path === '') {
            return $url;
        }

        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        if (!preg_match('#^/(en|ar)(/|$)#', $path)) {
            $path = '/' . app()->getLocale() . $path;
        }

        $query = isset($parts['query']) ? ('?' . $parts['query']) : '';
        $fragment = isset($parts['fragment']) ? ('#' . $parts['fragment']) : '';

        return $path . $query . $fragment;
    }

    private function ensurePageIsVisible(string $page): void
    {
        if (!CompanySetting::getSettings()->isPageVisible($page)) {
            abort(404);
        }
    }
}
