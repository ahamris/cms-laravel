<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Frontend\DocController as ApiDocController;
use App\Http\Controllers\Api\Frontend\FeatureController as ApiFeatureController;
use App\Http\Controllers\Api\Frontend\HomepageController;
use App\Http\Controllers\Api\Frontend\LegalController as ApiLegalController;
use App\Http\Controllers\Api\Frontend\LiveSessionController as ApiLiveSessionController;
use App\Http\Controllers\Api\Frontend\ModuleController as ApiModuleController;
use App\Http\Controllers\Api\Frontend\PageController as ApiPageController;
use App\Http\Controllers\Api\Frontend\SitemapController as ApiSitemapController;
use App\Http\Controllers\Api\Frontend\SolutionController as ApiSolutionController;
use App\Http\Controllers\Api\Frontend\StaticPageController as ApiStaticPageController;
use App\Http\Controllers\Api\Frontend\VacancyController as ApiVacancyController;
use App\Http\Controllers\Api\AnalyticsTrackingController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

// Sanctum auth (no auth required for login)
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('api.logout');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum')->name('api.user');

// Frontend content API (Sanctum protected)
Route::middleware('auth:sanctum')->group(function () {
    // Homepage / settings
    Route::get('/settings', [HomepageController::class, 'settings'])->name('api.settings');

    // Pages, blog, legal, static
    Route::get('/pages', [ApiPageController::class, 'index'])->name('api.pages.index');
    Route::get('/pages/{slug}', [ApiPageController::class, 'show'])->name('api.pages.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/blog-posts', [BlogController::class, 'apiPosts'])->name('api.blog-posts');
    Route::get('/blog/{slug}', [BlogController::class, 'apiShow'])->name('api.blog.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/legal/{slug}', [ApiLegalController::class, 'show'])->name('api.legal.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/static/{slug}', [ApiStaticPageController::class, 'show'])->name('api.static.show')->where('slug', '[a-z0-9\-]+');

    // Documentation
    Route::get('/docs', [ApiDocController::class, 'index'])->name('api.docs.index');
    Route::get('/docs/search', [ApiDocController::class, 'search'])->name('api.docs.search');
    Route::get('/docs/{version}', [ApiDocController::class, 'showVersion'])->name('api.docs.version')->where('version', '[a-z0-9\.\-]+');
    Route::get('/docs/{version}/{section}/{page}', [ApiDocController::class, 'showPage'])->name('api.docs.page')->where(['version' => '[a-z0-9\.\-]+', 'section' => '[a-z0-9\-]+', 'page' => '[a-z0-9\-]+']);

    // Live sessions
    Route::get('/live-sessions', [ApiLiveSessionController::class, 'index'])->name('api.live-sessions.index');
    Route::get('/live-sessions/{slug}', [ApiLiveSessionController::class, 'show'])->name('api.live-sessions.show')->where('slug', '[a-z0-9\-]+');

    // Modules, features, solutions
    Route::get('/modules', [ApiModuleController::class, 'index'])->name('api.modules.index');
    Route::get('/modules/{slug}', [ApiModuleController::class, 'show'])->name('api.modules.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/features', [ApiFeatureController::class, 'index'])->name('api.features.index');
    Route::get('/features/{anchor}', [ApiFeatureController::class, 'show'])->name('api.features.show')->where('anchor', '[a-z0-9\-]+');
    Route::get('/solutions', [ApiSolutionController::class, 'index'])->name('api.solutions.index');
    Route::get('/solutions/{anchor}', [ApiSolutionController::class, 'show'])->name('api.solutions.show')->where('anchor', '[a-z0-9\-]+');

    // Sitemap (JSON)
    Route::get('/sitemap', [ApiSitemapController::class, 'index'])->name('api.sitemap');

    // Vacancies
    Route::get('/vacancies', [ApiVacancyController::class, 'index'])->name('api.vacancies.index');
    Route::get('/vacancies/{slug}', [ApiVacancyController::class, 'show'])->name('api.vacancies.show')->where('slug', '[a-z0-9\-]+');
});

// Analytics tracking routes (public, rate limited)
Route::prefix('analytics')->middleware('throttle:api')->group(function () {
    Route::post('track', [AnalyticsTrackingController::class, 'track'])->name('api.analytics.track');
    Route::post('batch-track', [AnalyticsTrackingController::class, 'batchTrack'])->name('api.analytics.batch-track');
    Route::post('guest-activity', [AnalyticsTrackingController::class, 'guestActivity'])->name('api.analytics.guest-activity');
    Route::post('performance', [AnalyticsTrackingController::class, 'performance'])->name('api.analytics.performance');
    Route::get('stats', [AnalyticsTrackingController::class, 'stats'])->name('api.analytics.stats');
});
