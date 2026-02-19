<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Frontend\LegalController as ApiLegalController;
use App\Http\Controllers\Api\Frontend\PageController as ApiPageController;
use App\Http\Controllers\Api\Frontend\StaticPageController as ApiStaticPageController;
use App\Http\Controllers\Api\AnalyticsTrackingController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

// Sanctum auth (no auth required for login)
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('api.logout');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum')->name('api.user');

// Frontend content API (Sanctum protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pages', [ApiPageController::class, 'index'])->name('api.pages.index');
    Route::get('/pages/{slug}', [ApiPageController::class, 'show'])->name('api.pages.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/blog-posts', [BlogController::class, 'apiPosts'])->name('api.blog-posts');
    Route::get('/blog/{slug}', [BlogController::class, 'apiShow'])->name('api.blog.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/legal/{slug}', [ApiLegalController::class, 'show'])->name('api.legal.show')->where('slug', '[a-z0-9\-]+');
    Route::get('/static/{slug}', [ApiStaticPageController::class, 'show'])->name('api.static.show')->where('slug', '[a-z0-9\-]+');
});

// Analytics tracking routes (public, rate limited)
Route::prefix('analytics')->middleware('throttle:api')->group(function () {
    Route::post('track', [AnalyticsTrackingController::class, 'track'])->name('api.analytics.track');
    Route::post('batch-track', [AnalyticsTrackingController::class, 'batchTrack'])->name('api.analytics.batch-track');
    Route::post('guest-activity', [AnalyticsTrackingController::class, 'guestActivity'])->name('api.analytics.guest-activity');
    Route::post('performance', [AnalyticsTrackingController::class, 'performance'])->name('api.analytics.performance');
    Route::get('stats', [AnalyticsTrackingController::class, 'stats'])->name('api.analytics.stats');
});
