<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AcademyController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\FormBuilderController;
use App\Http\Controllers\HeroSectionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\LiveSessionController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\RobotsTxtController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SolutionController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\TrialController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;

// Admin Routes
require 'admin.php';

// Language switching route (outside locale group to avoid conflicts)
Route::post('/language/switch', [LanguageController::class, 'switch'])->name('language.switch');

// Frontend Routes with optional locale prefix (locale handled by SetLocaleMiddleware)

Route::get('/', [HomeController::class, 'index'])->name('home');

// API Routes for Hero Sections
Route::prefix('api')->group(function () {
    Route::get('/hero-sections', [HeroSectionController::class, 'index'])->name('api.hero-sections.index');
    Route::get('/hero-sections/{heroSection}', [HeroSectionController::class, 'show'])->name('api.hero-sections.show');
    Route::get('/blog-posts', [BlogController::class, 'apiPosts'])->name('api.blog-posts');
});

// Search Routes
Route::get('/zoeken', [SearchController::class, 'index'])->name('search');
Route::get('/api/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/demo', [ContactController::class, 'storeDemo'])->name('contact.demo.store');
Route::post('/contact/verstuur', [ContactController::class, 'storeContact'])->name('contact.submit');

// Form Builder Submission
Route::post('/form-builder/{identifier}/submit', [FormBuilderController::class, 'submit'])->name('form-builder.submit');

Route::group(['prefix' => 'artikelen'], function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog');
    Route::get('/load-more', [BlogController::class, 'loadMore'])->name('blog.load-more');
    Route::get('/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::post('/reactie', [CommentController::class, 'store'])->name('comment.store');
    Route::post('/reactie/{comment}/like', [CommentController::class, 'like'])->name('comment.like');
    Route::post('/reactie/{comment}/dislike', [CommentController::class, 'dislike'])->name('comment.dislike');
});

Route::group(['prefix' => 'prijzen'], function () {
    Route::get('/', [PricingController::class, 'index'])->name('pricing');
    Route::get('/configurator', [PricingController::class, 'configurator'])->name('pricing.configurator');
    Route::get('/{slug}', [PricingController::class, 'show'])->name('pricing.show');
});

Route::group(['prefix' => 'pagina', 'as' => 'page.'], function () {
    Route::get('/', [PageController::class, 'index'])->name('index');
    Route::get('/{page:slug}', [PageController::class, 'show'])->name('show');
});

// Legal Pages Routes
Route::group(['prefix' => 'legal', 'as' => 'legal.'], function () {
    Route::get('/{legal:slug}', [LegalController::class, 'show'])->name('show');
});

// Static Pages Routes
Route::group(['prefix' => 'static', 'as' => 'static.'], function () {
    Route::get('/{staticPage:slug}', [StaticPageController::class, 'show'])->name('show');
});

// About Us
Route::get('/over-ons', AboutController::class)->name('about');

// Solution Details
Route::group(['prefix' => 'oplossing'], function () {
    Route::get('/', [SolutionController::class, 'index'])->name('solutions.index');
    Route::get('/{solution:anchor}', [SolutionController::class, 'show'])->name('solutions.show');
});

// Feature Details
Route::group(['prefix' => 'functie'], function () {
    Route::get('/', [FeatureController::class, 'index'])->name('features.index');
    Route::get('/{feature:anchor}', [FeatureController::class, 'show'])->name('features.show');
});

// Module Routes
Route::group(['prefix' => 'modules'], function () {
    Route::get('/', [ModuleController::class, 'index'])->name('module.index');
    Route::get('/{module:slug}', [ModuleController::class, 'show'])->name('module.show');
});

Route::group(['prefix' => 'proefversie'], function () {
    Route::get('/', [TrialController::class, 'index'])->name('trial');
    Route::get('/success', [TrialController::class, 'success'])->name('trial.success');

});

// Changelog Routes
Route::group(['prefix' => 'changelog'], function () {
    Route::get('/', [ChangelogController::class, 'index'])->name('changelog.index');
    Route::get('/api', [ChangelogController::class, 'indexApi'])->name('changelog.index.api');
    Route::get('/{changelog:slug}', [ChangelogController::class, 'show'])->name('changelog.show');
    Route::get('/api/{changelog:slug}', [ChangelogController::class, 'showApi'])->name('changelog.show.api');
});

Route::group(['prefix' => 'careers', 'as' => 'career.'], static function () {
    Route::get('/', [VacancyController::class, 'index'])->name('index');
    Route::get('{vacancy:slug}', [VacancyController::class, 'show'])->name('detail');
    Route::get('{vacancy:slug}/apply', [VacancyController::class, 'apply'])->name('apply');
    Route::post('{vacancy:slug}/apply', [VacancyController::class, 'submit'])->name('apply.submit');
});


// Academy Routes
Route::group(['prefix' => 'academy'], function () {
    Route::get('/', [AcademyController::class, 'index'])->name('academy.index');
    Route::get('live-sessions', [LiveSessionController::class, 'index'])->name('academy.live-sessions.index');
    Route::get('live-sessions/recordings', [LiveSessionController::class, 'recordings'])->name('academy.live-sessions.recordings');
    Route::get('live-sessions/{liveSession:slug}', [LiveSessionController::class, 'show'])->name('academy.live-sessions.show');
    Route::post('live-sessions/{liveSession:slug}/register', [LiveSessionController::class, 'store'])->name('academy.live-sessions.register');
    Route::get('categories', [AcademyController::class, 'indexCategories'])->name('academy.categories.index');
    Route::get('category/{academyCategory:slug}', [AcademyController::class, 'showCategory'])->name('academy.category.show');
    Route::get('video/{academyVideo:slug}', [AcademyController::class, 'showVideo'])->name('academy.video.show');
});

// Sitemap Route
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Robots.txt Route
Route::get('/robots.txt', [RobotsTxtController::class, 'index'])->name('robots');


// Documentation Routes
Route::prefix('docs')->name('docs.')->group(function () {
    Route::get('/', [DocController::class, 'index'])->name('index');
    Route::get('/search', [DocController::class, 'search'])->name('search');
    Route::get('/{version}', [DocController::class, 'showVersion'])
        ->where('version', '[^/]+')
        ->name('version');
    Route::get('/{version}/{section}', [DocController::class, 'showSection'])
        ->where(['version' => '[^/]+', 'section' => '[^/]+'])
        ->name('section');
    Route::get('/{version}/{section}/{page}', [DocController::class, 'showPage'])
        ->where(['version' => '[^/]+', 'section' => '[^/]+', 'page' => '[^/]+'])
        ->name('page');
});
