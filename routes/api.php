<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\UserController;
use App\Http\Controllers\Api\V1\PageController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('login', LoginController::class)->name('api.v1.login');
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('logout', LogoutController::class)->name('api.v1.logout');
        Route::get('user', UserController::class)->name('api.v1.user');
    });

    Route::get('pages', [PageController::class, 'index'])->name('api.v1.pages.index');
    Route::get('pages/{slug}', [PageController::class, 'show'])->name('api.v1.pages.show');
});
