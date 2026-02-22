<?php

use App\Http\Controllers\Api\Frontend\AcademyController as ApiAcademyController;
use App\Http\Controllers\Api\Frontend\BlogController as ApiBlogController;
use App\Http\Controllers\Api\Frontend\ChangelogController as ApiChangelogController;
use App\Http\Controllers\Api\Frontend\CommentController as ApiCommentController;
use App\Http\Controllers\Api\Frontend\ContactController as ApiContactController;
use App\Http\Controllers\Api\Frontend\DocController as ApiDocController;
use App\Http\Controllers\Api\Frontend\FeatureController as ApiFeatureController;
use App\Http\Controllers\Api\Frontend\HomepageController;
use App\Http\Controllers\Api\Frontend\LegalController as ApiLegalController;
use App\Http\Controllers\Api\Frontend\LiveSessionController as ApiLiveSessionController;
use App\Http\Controllers\Api\Frontend\MediaController as ApiMediaController;
use App\Http\Controllers\Api\Frontend\MenuController as ApiMenuController;
use App\Http\Controllers\Api\Frontend\ModuleController as ApiModuleController;
use App\Http\Controllers\Api\Frontend\PageController as ApiPageController;
use App\Http\Controllers\Api\Frontend\PricingController as ApiPricingController;
use App\Http\Controllers\Api\Frontend\SitemapController as ApiSitemapController;
use App\Http\Controllers\Api\Frontend\SolutionController as ApiSolutionController;
use App\Http\Controllers\Api\Frontend\StaticPageController as ApiStaticPageController;
use App\Http\Controllers\Api\Frontend\TrialController as ApiTrialController;
use App\Http\Controllers\Api\Frontend\VacancyController as ApiVacancyController;
use App\Http\Controllers\Api\AnalyticsTrackingController;
use App\Http\Controllers\Api\Frontend\SearchController as ApiSearchController;
use Illuminate\Support\Facades\Route;

// Frontend content API (public, no auth; only allowed origins – documented in Swagger)
Route::middleware('frontend.origins')->group(function () {
    Route::get('/pages', [ApiPageController::class, 'index'])->name('api.pages.index');
    Route::get('/pages/{slug}', [ApiPageController::class, 'show'])->name('api.pages.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/search', [ApiSearchController::class, 'index'])->name('api.search');
    Route::get('/search/suggestions', [ApiSearchController::class, 'suggestions'])->name('search.suggestions');
    Route::get('/blog-posts', [ApiBlogController::class, 'apiPosts'])->name('api.blog-posts');
    Route::get('/blog/{slug}', [ApiBlogController::class, 'apiShow'])->name('api.blog.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/legal/{slug}', [ApiLegalController::class, 'show'])->name('api.legal.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/static/{slug}', [ApiStaticPageController::class, 'show'])->name('api.static.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/settings', [HomepageController::class, 'settings'])->name('api.settings');
    Route::get('/homepage', [HomepageController::class, 'homepage'])->name('api.homepage');
    Route::get('/docs', [ApiDocController::class, 'index'])->name('api.docs.index');
    Route::get('/docs/search', [ApiDocController::class, 'search'])->name('api.docs.search');
    Route::get('/docs/{version}', [ApiDocController::class, 'showVersion'])->name('api.docs.version')->where('version', '[a-z0-9\.\-]+');
    Route::get('/docs/{version}/{section}/{page}', [ApiDocController::class, 'showPage'])->name('api.docs.page')->where(['version' => '[a-z0-9\.\-]+', 'section' => '[a-z0-9\-]+', 'page' => '[a-z0-9\-]+']);
    Route::get('/live-sessions', [ApiLiveSessionController::class, 'index'])->name('api.live-sessions.index');
    Route::get('/live-sessions/{slug}', [ApiLiveSessionController::class, 'show'])->name('api.live-sessions.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/modules', [ApiModuleController::class, 'index'])->name('api.modules.index');
    Route::get('/modules/{slug}', [ApiModuleController::class, 'show'])->name('api.modules.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/features', [ApiFeatureController::class, 'index'])->name('api.features.index');
    Route::get('/features/{anchor}', [ApiFeatureController::class, 'show'])->name('api.features.show')->where('anchor', '[a-z0-9\-]+');
    Route::get('/solutions', [ApiSolutionController::class, 'index'])->name('api.solutions.index');
    Route::get('/solutions/{anchor}', [ApiSolutionController::class, 'show'])->name('api.solutions.show')->where('anchor', '[a-z0-9\-]+');
    Route::get('/sitemap', [ApiSitemapController::class, 'index'])->name('api.sitemap');
    Route::get('/media', [ApiMediaController::class, 'index'])->name('api.media.index');
    Route::get('/vacancies', [ApiVacancyController::class, 'index'])->name('api.vacancies.index');
    Route::get('/vacancies/{slug}', [ApiVacancyController::class, 'show'])->name('api.vacancies.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/vacancies/{slug}/apply', [ApiVacancyController::class, 'apply'])->name('api.vacancies.apply')->where('slug', '[a-z0-9\-]+');
    Route::post('/vacancies/{slug}/apply', [ApiVacancyController::class, 'submit'])->name('api.vacancies.submit')->where('slug', '[a-z0-9\-]+');

    // Contact
    Route::get('/contact', [ApiContactController::class, 'index'])->name('api.contact.index');
    Route::post('/contact/verstuur', [ApiContactController::class, 'storeContact'])->name('api.contact.submit');

    // Blog (artikelen)
    Route::get('/artikelen/load-more', [ApiBlogController::class, 'loadMore'])->name('api.blog.load-more');
    Route::post('/artikelen/reactie', [ApiCommentController::class, 'store'])->name('api.comment.store');
    Route::post('/artikelen/reactie/{comment}/like', [ApiCommentController::class, 'like'])->name('api.comment.like');
    Route::post('/artikelen/reactie/{comment}/dislike', [ApiCommentController::class, 'dislike'])->name('api.comment.dislike');

    // Pricing (prijzen)
    Route::get('/prijzen', [ApiPricingController::class, 'index'])->name('api.pricing.index');
    Route::get('/prijzen/configurator', [ApiPricingController::class, 'configurator'])->name('api.pricing.configurator');
    Route::get('/prijzen/{slug}', [ApiPricingController::class, 'show'])->name('api.pricing.show')->where('slug', '[a-z0-9\-]+');

    // Changelog
    Route::get('/changelog', [ApiChangelogController::class, 'index'])->name('api.changelog.index');
    Route::get('/changelog/{slug}', [ApiChangelogController::class, 'show'])->name('api.changelog.show')->where('slug', '[a-z0-9\-]+');

    // Trial (proefversie)
    Route::get('/proefversie', [ApiTrialController::class, 'index'])->name('api.trial.index');
    Route::get('/proefversie/success', [ApiTrialController::class, 'success'])->name('api.trial.success');

    // Academy
    Route::get('/academy', [ApiAcademyController::class, 'index'])->name('api.academy.index');
    Route::get('/academy/categories', [ApiAcademyController::class, 'categories'])->name('api.academy.categories');
    Route::get('/academy/category/{slug}', [ApiAcademyController::class, 'showCategory'])->name('api.academy.category.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/academy/video/{slug}', [ApiAcademyController::class, 'showVideo'])->name('api.academy.video.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/academy/live-sessions/recordings', [ApiLiveSessionController::class, 'recordings'])->name('api.live-sessions.recordings');
    Route::post('/academy/live-sessions/{slug}/register', [ApiLiveSessionController::class, 'register'])->name('api.live-sessions.register')->where('slug', '[a-z0-9\-]+');

    // Header and footer menu structures
    Route::get('/menus', [ApiMenuController::class, 'index'])->name('api.menus.index');
    Route::get('/menus/header', [ApiMenuController::class, 'header'])->name('api.menus.header');
    Route::get('/menus/footer', [ApiMenuController::class, 'footer'])->name('api.menus.footer');
    Route::get('/menus/sticky', [ApiMenuController::class, 'sticky'])->name('api.menus.sticky');
});

// Analytics tracking routes (public, rate limited)
Route::prefix('analytics')->middleware('throttle:api')->group(function () {
    Route::post('track', [AnalyticsTrackingController::class, 'track'])->name('api.analytics.track');
    Route::post('batch-track', [AnalyticsTrackingController::class, 'batchTrack'])->name('api.analytics.batch-track');
    Route::post('guest-activity', [AnalyticsTrackingController::class, 'guestActivity'])->name('api.analytics.guest-activity');
    Route::post('performance', [AnalyticsTrackingController::class, 'performance'])->name('api.analytics.performance');
    Route::get('stats', [AnalyticsTrackingController::class, 'stats'])->name('api.analytics.stats');
});
