<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroController;
use App\Http\Controllers\Admin\JobApplicationController;
use App\Http\Controllers\Admin\JobOpeningController;
use App\Http\Controllers\Admin\PageVisibilityController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Middleware\CachePublicPage;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\AboutSection;
use App\Models\CompanySetting;
use App\Models\HeroSection;
use App\Models\JobOpening;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => '{locale}', 'where' => ['locale' => 'en|ar'], 'middleware' => 'setlocale'], function() {
    // Read-only pages: identical for every visitor, so no session/cookies are
    // created and the rendered HTML is cached (see CachePublicPage).
    Route::controller(PageController::class)
        ->middleware(CachePublicPage::class)
        ->withoutMiddleware([StartSession::class, ShareErrorsFromSession::class, ValidateCsrfToken::class, AddQueuedCookiesToResponse::class])
        ->group(function () {
            Route::get('/', 'home')->name('home');
            Route::get('/about', 'about')->name('about');
            Route::get('/services', 'services')->name('services');
            Route::get('/team', 'team')->name('team');
            Route::get('/portfolio', 'portfolio')->name('portfolio');
            Route::get('/testimonials', 'testimonials')->name('testimonials');
        });

    // Pages with forms keep the session (CSRF token, validation errors, flash messages).
    Route::controller(PageController::class)->group(function () {
        Route::get('/jobs', 'jobs')->name('jobs');
        Route::get('/contact', 'contact')->name('contact');
        Route::post('/contact', 'sendMessage')->middleware('throttle:contact-form')->name('contact.send');
        Route::post('/jobs/apply', 'applyForJob')->name('jobs.apply');
    });
});

Route::get('/sitemap.xml', function () {
    $settings = CompanySetting::getSettings();

    // Per-page config: [priority, changefreq, models whose content appears on the page]
    $pageConfig = [
        'home'         => ['1.0', 'weekly',  [CompanySetting::class, HeroSection::class, AboutSection::class, Service::class]],
        'about'        => ['0.8', 'monthly', [AboutSection::class, TeamMember::class]],
        'services'     => ['0.9', 'weekly',  [Service::class]],
        'portfolio'    => ['0.8', 'weekly',  [Portfolio::class]],
        'team'         => ['0.6', 'monthly', [TeamMember::class]],
        'jobs'         => ['0.7', 'weekly',  [JobOpening::class]],
        'testimonials' => ['0.5', 'monthly', [Testimonial::class]],
        'contact'      => ['0.7', 'monthly', [CompanySetting::class]],
    ];

    // Real last-modified date: the latest updated_at among the page's content.
    $lastModified = function (array $models): string {
        $dates = collect($models)
            ->map(fn (string $model) => rescue(fn () => $model::max('updated_at'), null, false))
            ->filter();

        return ($dates->isEmpty() ? now() : Carbon::parse($dates->max()))->toAtomString();
    };

    // Filter by page visibility
    $visiblePages = array_filter(
        array_keys($pageConfig),
        fn ($r) => $r === 'home' || $settings->isPageVisible($r)
    );

    $locales = ['en', 'ar'];
    $urls    = [];

    foreach ($visiblePages as $routeName) {
        [$priority, $changefreq, $models] = $pageConfig[$routeName];
        $lastmod = $lastModified($models);

        // Build alternates map: locale => absolute URL
        $alternates = [];
        foreach ($locales as $loc) {
            $alternates[$loc] = route($routeName, ['locale' => $loc]);
        }
        $neutralPath = preg_replace('#^/ar(?=/|$)#', '', parse_url($alternates['ar'], PHP_URL_PATH));

        foreach ($locales as $locale) {
            $urls[] = [
                'loc'        => $alternates[$locale],
                'lastmod'    => $lastmod,
                'priority'   => $priority,
                'changefreq' => $changefreq,
                'alternates' => $alternates,   // all locale variants for hreflang
                'x_default'  => url($neutralPath ?: '/'),
            ];
        }
    }

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('sitemap');

// Language-neutral URLs (/, /about, /services, ...) send visitors to their browser's
// language (Arabic by default). These URLs are the hreflang "x-default" targets.
$redirectToPreferredLocale = function (Request $request, string $page = '') {
    $locale = $request->getPreferredLanguage(['ar', 'en']) ?? 'ar';

    return redirect('/' . $locale . ($page !== '' ? '/' . $page : ''))
        ->header('Vary', 'Accept-Language');
};

Route::get('/', fn (Request $request) => $redirectToPreferredLocale($request));
Route::get('/{page}', fn (Request $request, string $page) => $redirectToPreferredLocale($request, $page))
    ->whereIn('page', ['about', 'services', 'team', 'jobs', 'portfolio', 'testimonials', 'contact']);

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin Auth (Guest)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:admin-login');
});

// Admin Panel (Authenticated)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Settings
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/delete-image/{field}', [SettingsController::class, 'deleteImage'])->name('settings.delete-image');

    // Hero Section
    Route::get('/hero', [HeroController::class, 'edit'])->name('hero.edit');
    Route::put('/hero', [HeroController::class, 'update'])->name('hero.update');
    Route::post('/hero/delete-image/{field}', [HeroController::class, 'deleteImage'])->name('hero.delete-image');

    // About Section
    Route::get('/about', [AboutController::class, 'edit'])->name('about.edit');
    Route::put('/about', [AboutController::class, 'update'])->name('about.update');
    Route::post('/about/delete-image', [AboutController::class, 'deleteImage'])->name('about.delete-image');

    // Page visibility
    Route::put('/page-visibility/{page}', [PageVisibilityController::class, 'update'])->name('page-visibility.update');

    // Services CRUD - delete-image must come before resource
    Route::post('/services/{service}/delete-image', [ServiceController::class, 'deleteImage'])->name('services.delete-image');
    Route::resource('services', ServiceController::class)->except(['show']);

    // Team CRUD - delete-image must come before resource
    Route::post('/team/{team}/delete-image', [TeamMemberController::class, 'deleteImage'])->name('team.delete-image');
    Route::resource('team', TeamMemberController::class)->except(['show']);

    // Jobs CRUD
    Route::resource('jobs', JobOpeningController::class)->except(['show']);

    // Job Applications
    Route::get('/job-applications', [JobApplicationController::class, 'index'])->name('job-applications.index');
    Route::get('/job-applications/{application}', [JobApplicationController::class, 'show'])->name('job-applications.show');
    Route::patch('/job-applications/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('job-applications.status');
    Route::delete('/job-applications/{application}', [JobApplicationController::class, 'destroy'])->name('job-applications.destroy');

    // Portfolio CRUD - delete-image must come before resource
    Route::post('/portfolio/{portfolio}/delete-image', [PortfolioController::class, 'deleteImage'])->name('portfolio.delete-image');
    Route::resource('portfolio', PortfolioController::class)->except(['show']);

    // Testimonials CRUD - delete-image must come before resource
    Route::post('/testimonials/{testimonial}/delete-image', [TestimonialController::class, 'deleteImage'])->name('testimonials.delete-image');
    Route::resource('testimonials', TestimonialController::class)->except(['show']);

    // Contact Messages
    Route::get('/messages', [ContactMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{message}/replied', [ContactMessageController::class, 'markAsReplied'])->name('messages.replied');
    Route::delete('/messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');
});
