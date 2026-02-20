<?php

use App\Http\Controllers\Api\Frontend\BlogController;
use App\Http\Controllers\Api\Frontend\ContactController;
use Illuminate\Support\Facades\Route;

// Admin Routes
require 'admin.php';

// Site root: redirect to API documentation
Route::get('/', fn () => redirect('/api/documentation'))->name('home');

// Search: redirect to API (suggestions at /api/search/suggestions)
Route::get('/zoeken', fn () => redirect('/api/documentation'))->name('search');

// Contact
Route::get('/contact', [ContactController::class, 'contactPage'])->name('contact');
Route::post('/contact/verstuur', [ContactController::class, 'storeContact'])->name('contact.submit');

// Blog (Blade listing/detail; load-more returns HTML for Blade)
Route::group(['prefix' => 'artikelen'], function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog');
    Route::get('/load-more', [BlogController::class, 'loadMoreHtml'])->name('blog.load-more');
    Route::get('/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');
});
