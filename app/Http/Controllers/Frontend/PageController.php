<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageNotification;
use App\Models\AboutSection;
use App\Models\ClientLogo;
use App\Models\CompanySetting;
use App\Models\ContactMessage;
use App\Models\DemoRequest;
use App\Models\Faq;
use App\Models\HeroSection;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            'products' => $visiblePages['products']
                ? Product::published()->ordered()->take(6)->get()
                : collect(),
            'clientLogos' => ClientLogo::published()->ordered()->get(),
            'faqs' => Faq::published()->where('show_on_home', true)->ordered()->take(8)->get(),
        ]);

        return view('frontend.home', $data);
    }

    public function about()
    {
        $this->ensurePageIsVisible('about');

        $data = array_merge($this->getCommonData(), [
            'about' => AboutSection::active()->first(),
            'team' => TeamMember::active()->ordered()->take(4)->get(),
            'clientLogos' => ClientLogo::published()->ordered()->get(),
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

    public function products()
    {
        $this->ensurePageIsVisible('products');

        return view('frontend.products.index', array_merge($this->getCommonData(), [
            'products' => Product::published()->ordered()->get(),
        ]));
    }

    public function product(string $locale, Product $product)
    {
        abort_unless($product->is_active, 404);

        return view('frontend.products.show', array_merge($this->getCommonData(), [
            'product' => $product,
            'related' => Product::published()->ordered()->whereKeyNot($product->getKey())->take(3)->get(),
        ]));
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

    public function portfolioItem(string $locale, Portfolio $portfolio)
    {
        $this->ensurePageIsVisible('portfolio');
        abort_unless($portfolio->is_active && $portfolio->isCaseStudy(), 404);

        return view('frontend.portfolio_show', array_merge($this->getCommonData(), [
            'portfolio' => $portfolio,
        ]));
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

        return view('frontend.contact', array_merge($this->getCommonData(), [
            'faqs' => Faq::published()->ordered()->get(),
        ]));
    }

    public function demo(Request $request)
    {
        $products = Product::published()->ordered()->get();
        $selected = $products->firstWhere('slug', (string) $request->query('product'))?->name;

        return view('frontend.demo', array_merge($this->getCommonData(), [
            'products' => $products,
            'selectedProduct' => $selected,
        ]));
    }

    public function sendDemoRequest(Request $request)
    {
        // Same spam protection as the contact form: honeypot field + minimum fill time.
        if ($request->filled('website')) {
            return back()->with('success', __('frontend.demo_success'));
        }
        $formStartedAt = (int) $request->input('form_started_at', 0);
        if ($formStartedAt <= 0 || now()->timestamp - $formStartedAt < 3) {
            return back()->withErrors(['demo' => __('frontend.contact_submit_too_fast')])->withInput();
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => ['required', 'string', 'max:50', 'regex:/^[0-9+\-\s()]{6,}$/'],
            'email' => 'nullable|email|max:255',
            'product' => 'nullable|string|max:255',
            'preferred_contact' => 'required|in:' . implode(',', DemoRequest::CONTACT_METHODS),
            'message' => 'nullable|string|max:2000',
        ], [
            'phone.regex' => __('frontend.contact_phone_invalid'),
        ]);

        $demoRequest = DemoRequest::create($validated + ['locale' => app()->getLocale()]);

        $recipient = CompanySetting::getSettings()->email;
        if (!empty($recipient) && filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            try {
                config(['mail.mailers.smtp.timeout' => 4]);
                Mail::mailer('smtp')->raw(
                    collect([
                        'Name' => $demoRequest->name,
                        'Company' => $demoRequest->company,
                        'Phone' => $demoRequest->phone,
                        'Email' => $demoRequest->email,
                        'Product' => $demoRequest->product,
                        'Preferred contact' => $demoRequest->preferred_contact,
                        'Message' => $demoRequest->message,
                    ])->filter()->map(fn ($v, $k) => "{$k}: {$v}")->implode("\n"),
                    fn ($mail) => $mail->to($recipient)->subject(__('frontend.demo_email_subject', ['name' => $demoRequest->name]))
                );
            } catch (\Throwable $exception) {
                Log::error('Failed to send demo request notification email.', ['demo_request_id' => $demoRequest->id, 'error' => $exception->getMessage()]);
            }
        }

        return back()->with('success', __('frontend.demo_success'))->with('lead', 'demo_request');
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
            // Either a phone number or an email is enough to get back to the visitor.
            'email' => 'nullable|required_without:phone|email|max:255',
            'phone' => ['nullable', 'required_without:email', 'string', 'max:50', 'regex:/^[0-9+\-\s()]{6,}$/'],
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
            'email.required_without' => __('frontend.contact_phone_or_email_required'),
            'phone.required_without' => __('frontend.contact_phone_or_email_required'),
            'phone.regex' => __('frontend.contact_phone_invalid'),
        ]);

        $contactMessage = ContactMessage::create(collect($validated)->except(['company', 'form_started_at'])->all());

        $recipientEmail = CompanySetting::getSettings()->email;
        if (!empty($recipientEmail) && filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            try {
                // Keep contact submissions responsive when SMTP is slow/unreachable.
                config(['mail.mailers.smtp.timeout' => 4]);
                Mail::mailer('smtp')->to($recipientEmail)->send(new ContactMessageNotification($contactMessage));
            } catch (\Throwable $exception) {
                Log::error('Failed to send contact message notification email.', [
                    'contact_message_id' => $contactMessage->id,
                    'recipient' => $recipientEmail,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return back()->with('success', __('frontend.contact_form_success'))->with('lead', 'contact_form');
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
