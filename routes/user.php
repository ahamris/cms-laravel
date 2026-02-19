<?php

use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\User\UserMessageController;
use App\Http\Controllers\User\UserWooRequestController;
use App\Http\Controllers\User\UserSubscriptionController;
use App\Http\Controllers\User\UserIdentityController;

// Dashboard Auth Routes
Route::group(['prefix' => 'user', 'as' => 'dashboard.auth.'], function () {
    // Guest routes (not authenticated)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserAuthController::class, 'login'])->name('login.post');
        Route::get('/register', [UserAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [UserAuthController::class, 'register'])->name('register.post');
        Route::get('/forgot-password', [UserAuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [UserAuthController::class, 'forgotPassword'])->name('password.email');
        Route::get('/reset-password/{token}', [UserAuthController::class, 'showResetPasswordForm'])->name('password.reset');
        Route::post('/reset-password', [UserAuthController::class, 'resetPassword'])->name('password.update');
    });

});

// Dashboard Routes (Protected)
Route::group(['prefix' => 'user', 'as' => 'dashboard.', 'middleware' => 'user_role'], function () {

    Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
    Route::get('/verify-email', [UserAuthController::class, 'showEmailVerificationNotice'])->name('verification.notice');
    Route::post('/verify-email', [UserAuthController::class, 'resendEmailVerification'])->name('verification.send');

    Route::get('/', [DashboardController::class, 'index'])->name('index');


    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::get('/personal', [DashboardController::class, 'personal'])->name('personal');
});
