<?php

use App\Http\Controllers\Api\Frontend\BlogController as ApiBlogController;
use App\Http\Controllers\Api\V1\CategoryController as ApiV1CategoryController;
use App\Http\Controllers\Api\V1\FormController as ApiV1FormController;
use App\Http\Controllers\Api\V1\MediaController as ApiV1MediaController;
use App\Http\Controllers\Api\V1\TagController as ApiV1TagController;
use App\Http\Controllers\Api\Frontend\CourseController as ApiCourseController;
use App\Http\Controllers\Api\Frontend\ChangelogController as ApiChangelogController;
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
use App\Http\Controllers\Api\Frontend\PartnersController as ApiPartnersController;
use App\Http\Controllers\Api\Frontend\TechStackController as ApiTechStackController;
use App\Http\Controllers\Api\Frontend\PricingController as ApiPricingController;
use App\Http\Controllers\Api\Frontend\RobotsTxtController as ApiRobotsTxtController;
use App\Http\Controllers\Api\Frontend\SitemapController as ApiSitemapController;
use App\Http\Controllers\Api\Frontend\SolutionController as ApiSolutionController;
use App\Http\Controllers\Api\Frontend\StaticPageController as ApiStaticPageController;
use App\Http\Controllers\Api\Frontend\TrialController as ApiTrialController;
use App\Http\Controllers\Api\Frontend\VacancyController as ApiVacancyController;
use App\Http\Controllers\Api\AnalyticsTrackingController;
use App\Http\Controllers\Api\Frontend\SearchController as ApiSearchController;
use Illuminate\Support\Facades\Route;

// Frontend content API (public, no auth; CORS via config/cors.php)
Route::get('/pages', [ApiPageController::class, 'index'])->name('api.pages.index');
Route::get('/pages/search', [ApiPageController::class, 'search'])->middleware('throttle:search')->name('api.pages.search');
Route::get('/pages/tree', [ApiPageController::class, 'tree'])->name('api.pages.tree');
Route::get('/pages/{slug}', [ApiPageController::class, 'show'])->name('api.pages.show');
Route::get('/pages/{slug}/blocks', [ApiPageController::class, 'blocks'])->name('api.pages.blocks');
Route::get('/search', [ApiSearchController::class, 'index'])->name('api.search');
Route::get('/search/suggestions', [ApiSearchController::class, 'suggestions'])->name('search.suggestions');

// Blog (posts + comments)
Route::prefix('blog')->name('api.blog.')->group(function () {
    Route::get('/', [ApiBlogController::class, 'index'])->name('index');
    Route::get('/search', [ApiBlogController::class, 'search'])->middleware('throttle:search')->name('search');
    Route::get('/{slug}', [ApiBlogController::class, 'apiShow'])->name('show')->where('slug', '[a-zA-Z0-9\-_]+');
    Route::post('/{slug}/comments', [ApiBlogController::class, 'storeComment'])->middleware('throttle:forms')->name('comments.store')->where('slug', '[a-zA-Z0-9\-_]+');
    Route::post('/{slug}/comments/{comment}/like', [ApiBlogController::class, 'likeComment'])->name('comments.like')->where(['slug' => '[a-zA-Z0-9\-_]+', 'comment' => '[0-9]+']);
    Route::post('/{slug}/comments/{comment}/dislike', [ApiBlogController::class, 'dislikeComment'])->name('comments.dislike')->where(['slug' => '[a-zA-Z0-9\-_]+', 'comment' => '[0-9]+']);
});

Route::get('/legal/{slug}', [ApiLegalController::class, 'show'])->name('api.legal.show')->where('slug', '[a-z0-9\-]+');
Route::get('/static/{slug}', [ApiStaticPageController::class, 'show'])->name('api.static.show')->where('slug', '[a-z0-9\-]+');
Route::get('/settings', [HomepageController::class, 'settings'])->name('api.settings');
Route::get('/homepage', [HomepageController::class, 'homepage'])->name('api.homepage');
Route::get('/docs', [ApiDocController::class, 'index'])->name('api.docs.index');
Route::get('/docs/search', [ApiDocController::class, 'search'])->middleware('throttle:search')->name('api.docs.search');
Route::get('/docs/{section}/{page}', [ApiDocController::class, 'showPage'])->name('api.docs.page')->where(['section' => '[a-z0-9\-]+', 'page' => '[a-z0-9\-]+']);
Route::get('/modules', [ApiModuleController::class, 'index'])->name('api.modules.index');
Route::get('/modules/{slug}', [ApiModuleController::class, 'show'])->name('api.modules.show')->where('slug', '[a-z0-9\-]+');
Route::get('/features', [ApiFeatureController::class, 'index'])->name('api.features.index');
Route::get('/features/search', [ApiFeatureController::class, 'search'])->middleware('throttle:search')->name('api.features.search');
Route::get('/features/{anchor}', [ApiFeatureController::class, 'show'])->name('api.features.show')->where('anchor', '[a-z0-9\-]+');
Route::get('/solutions', [ApiSolutionController::class, 'index'])->name('api.solutions.index');
Route::get('/solutions/search', [ApiSolutionController::class, 'search'])->middleware('throttle:search')->name('api.solutions.search');
Route::get('/solutions/{anchor}', [ApiSolutionController::class, 'show'])->name('api.solutions.show')->where('anchor', '[a-z0-9\-]+');
Route::get('/partners', [ApiPartnersController::class, 'index'])->name('api.partners.index');
Route::get('/tech-stack', [ApiTechStackController::class, 'index'])->name('api.tech-stack.index');
Route::get('/sitemap', [ApiSitemapController::class, 'index'])->name('api.sitemap');
Route::get('/sitemap.xml', [ApiSitemapController::class, 'xml'])->name('api.sitemap.xml');
Route::get('/robots-txt', [ApiRobotsTxtController::class, 'index'])->name('api.robots-txt');
Route::get('/media', [ApiMediaController::class, 'index'])->name('api.media.index');
Route::get('/vacancies', [ApiVacancyController::class, 'index'])->name('api.vacancies.index');
Route::get('/vacancies/search', [ApiVacancyController::class, 'search'])->middleware('throttle:search')->name('api.vacancies.search');
Route::get('/vacancies/{slug}', [ApiVacancyController::class, 'show'])->name('api.vacancies.show')->where('slug', '[a-z0-9\-]+');
Route::get('/vacancies/{slug}/apply', [ApiVacancyController::class, 'apply'])->name('api.vacancies.apply')->where('slug', '[a-z0-9\-]+');
Route::post('/vacancies/{slug}/apply', [ApiVacancyController::class, 'submit'])->middleware('throttle:forms')->name('api.vacancies.submit')->where('slug', '[a-z0-9\-]+');

// Contact
Route::get('/contact', [ApiContactController::class, 'index'])->name('api.contact.index');
Route::get('/contact/subjects', [ApiContactController::class, 'subjects'])->name('api.contact.subjects');
Route::post('/contact/verstuur', [ApiContactController::class, 'storeContact'])->middleware('throttle:forms')->name('api.contact.submit');

// Pricing (prijzen)
Route::get('/prijzen', [ApiPricingController::class, 'index'])->name('api.pricing.index');
Route::get('/prijzen/configurator', [ApiPricingController::class, 'configurator'])->name('api.pricing.configurator');
Route::get('/prijzen/{slug}', [ApiPricingController::class, 'show'])->name('api.pricing.show')->where('slug', '[a-z0-9\-]+');

// Changelog
Route::get('/changelog', [ApiChangelogController::class, 'index'])->name('api.changelog.index');
Route::get('/changelog/search', [ApiChangelogController::class, 'search'])->middleware('throttle:search')->name('api.changelog.search');
Route::get('/changelog/{slug}', [ApiChangelogController::class, 'show'])->name('api.changelog.show')->where('slug', '[a-z0-9\-]+');

// Trial (proefversie)
Route::get('/proefversie', [ApiTrialController::class, 'index'])->name('api.trial.index');
Route::get('/proefversie/success', [ApiTrialController::class, 'success'])->name('api.trial.success');

// Course (categories, videos, live sessions)
Route::prefix('course')->group(function () {
    Route::get('/', [ApiCourseController::class, 'index'])->name('api.course.index');
    Route::get('/search', [ApiCourseController::class, 'search'])->middleware('throttle:search')->name('api.course.search');
    Route::get('/categories', [ApiCourseController::class, 'categories'])->name('api.course.categories');
    Route::get('/category/{slug}', [ApiCourseController::class, 'showCategory'])->name('api.course.category.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/video/{slug}', [ApiCourseController::class, 'showVideo'])->name('api.course.video.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/live-sessions', [ApiLiveSessionController::class, 'index'])->name('api.course.live-sessions.index');
    Route::get('/live-sessions/search', [ApiLiveSessionController::class, 'search'])->middleware('throttle:search')->name('api.course.live-sessions.search');
    Route::get('/live-sessions/recordings', [ApiLiveSessionController::class, 'recordings'])->name('api.course.live-sessions.recordings');
    Route::get('/live-sessions/{slug}', [ApiLiveSessionController::class, 'show'])->name('api.course.live-sessions.show')->where('slug', '[a-z0-9\-]+');
    Route::post('/live-sessions/{slug}/register', [ApiLiveSessionController::class, 'register'])->middleware('throttle:forms')->name('api.course.live-sessions.register')->where('slug', '[a-z0-9\-]+');
});

// Header and footer menu structures
Route::get('/menus', [ApiMenuController::class, 'index'])->name('api.menus.index');
Route::get('/menus/header', [ApiMenuController::class, 'header'])->name('api.menus.header');
Route::get('/menus/footer', [ApiMenuController::class, 'footer'])->name('api.menus.footer');
Route::get('/menus/sticky', [ApiMenuController::class, 'sticky'])->name('api.menus.sticky');

// v1 API: Media, Categories, Tags, Forms
Route::prefix('v1')->group(function () {
    Route::get('/media', [ApiV1MediaController::class, 'index'])->name('api.v1.media.index');
    Route::get('/media/{id}', [ApiV1MediaController::class, 'show'])->name('api.v1.media.show');
    Route::get('/categories', [ApiV1CategoryController::class, 'index'])->name('api.v1.categories.index');
    Route::get('/categories/{slug}', [ApiV1CategoryController::class, 'show'])->name('api.v1.categories.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/tags', [ApiV1TagController::class, 'index'])->name('api.v1.tags.index');
    Route::get('/tags/{slug}', [ApiV1TagController::class, 'show'])->name('api.v1.tags.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/forms/{slug}', [ApiV1FormController::class, 'show'])->name('api.v1.forms.show')->where('slug', '[a-z0-9\-]+');
    Route::post('/forms/{slug}/submit', [ApiV1FormController::class, 'submit'])->middleware('throttle:forms')->name('api.v1.forms.submit')->where('slug', '[a-z0-9\-]+');
});

// Analytics tracking routes (public, rate limited)
Route::prefix('analytics')->middleware('throttle:api')->group(function () {
    Route::post('track', [AnalyticsTrackingController::class, 'track'])->name('api.analytics.track');
    Route::post('batch-track', [AnalyticsTrackingController::class, 'batchTrack'])->name('api.analytics.batch-track');
    Route::post('guest-activity', [AnalyticsTrackingController::class, 'guestActivity'])->name('api.analytics.guest-activity');
    Route::post('performance', [AnalyticsTrackingController::class, 'performance'])->name('api.analytics.performance');
    Route::get('stats', [AnalyticsTrackingController::class, 'stats'])->name('api.analytics.stats');
});
