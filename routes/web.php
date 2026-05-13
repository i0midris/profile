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
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => '{locale}', 'where' => ['locale' => 'en|ar'], 'middleware' => 'setlocale'], function() {
    Route::controller(PageController::class)->group(function () {
        Route::get('/', 'home')->name('home');
        Route::get('/about', 'about')->name('about');
        Route::get('/services', 'services')->name('services');
        Route::get('/team', 'team')->name('team');
        Route::get('/jobs', 'jobs')->name('jobs');
        Route::get('/portfolio', 'portfolio')->name('portfolio');
        Route::get('/testimonials', 'testimonials')->name('testimonials');
        Route::get('/contact', 'contact')->name('contact');
        Route::post('/contact', 'sendMessage')->middleware('throttle:contact-form')->name('contact.send');
        Route::post('/jobs/apply', 'applyForJob')->name('jobs.apply');
    });
});

Route::get('/sitemap.xml', function () {
    $settings = CompanySetting::getSettings();

    // Per-page config: [routeName, priority, changefreq]
    $pageConfig = [
        'home'         => ['1.0', 'daily'],
        'about'        => ['0.8', 'monthly'],
        'services'     => ['0.9', 'weekly'],
        'portfolio'    => ['0.8', 'weekly'],
        'team'         => ['0.6', 'monthly'],
        'jobs'         => ['0.7', 'weekly'],
        'testimonials' => ['0.5', 'monthly'],
        'contact'      => ['0.7', 'monthly'],
    ];

    // Filter by page visibility
    $visiblePages = array_filter(
        array_keys($pageConfig),
        fn ($r) => $r === 'home' || $settings->isPageVisible($r)
    );

    $locales    = ['en', 'ar'];
    $defaultMod = now()->toAtomString();
    $urls       = [];

    foreach ($visiblePages as $routeName) {
        [$priority, $changefreq] = $pageConfig[$routeName];

        // Build alternates map: locale => absolute URL
        $alternates = [];
        foreach ($locales as $loc) {
            $alternates[$loc] = route($routeName, ['locale' => $loc]);
        }

        foreach ($locales as $locale) {
            $urls[] = [
                'loc'        => $alternates[$locale],
                'lastmod'    => $defaultMod,
                'priority'   => $priority,
                'changefreq' => $changefreq,
                'alternates' => $alternates,   // all locale variants for hreflang
            ];
        }
    }

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('sitemap');

// Redirect / to /ar/
Route::get('/', function () {
    return redirect('/ar');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin Auth (Guest)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
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
