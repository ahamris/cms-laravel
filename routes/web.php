<?php

use Illuminate\Support\Facades\Route;

// Admin Routes
require 'admin.php';

// Headless CMS: site root redirects to API documentation (React SPA consumes /api on another domain)
Route::get('/', fn () => redirect('/api/documentation'))->name('home');
Route::get('/zoeken', fn () => redirect('/api/documentation'))->name('search');
