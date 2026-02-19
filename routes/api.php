<?php

use App\Http\Controllers\Api\AnalyticsTrackingController;
use Illuminate\Support\Facades\Route;

// Analytics tracking routes (public, no auth required; rate limited)
Route::prefix('analytics')->middleware('throttle:api')->group(function () {
    Route::post('track', [AnalyticsTrackingController::class, 'track'])->name('api.analytics.track');
    Route::post('batch-track', [AnalyticsTrackingController::class, 'batchTrack'])->name('api.analytics.batch-track');
    Route::post('guest-activity', [AnalyticsTrackingController::class, 'guestActivity'])->name('api.analytics.guest-activity');
    Route::post('performance', [AnalyticsTrackingController::class, 'performance'])->name('api.analytics.performance');
    Route::get('stats', [AnalyticsTrackingController::class, 'stats'])->name('api.analytics.stats');
});
