<?php

use App\Http\Controllers\Admin\AdminController;
use App\Livewire\Admin\MenuManager;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

// 2FA Recovery Code (before auth middleware)
Route::get('/admin/two-factor-recovery', function () {
    if (!session('login.id')) {
        return redirect()->route('login');
    }
    return view('admin.auth.two-factor-recovery');
})->name('two-factor.recovery');

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/', 'home')->name('home');
        Route::get('/analytics', 'analytics')->name('analytics');
    });

    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::get('/settings/general', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/menu', MenuManager::class)->name('settings.menu');
    Route::get('/settings/theme', \App\Livewire\Admin\ThemeManager::class)->name('settings.theme');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // Profile routes
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

    // Roles & Permissions routes
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
});
