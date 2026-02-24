<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API path prefix
    |--------------------------------------------------------------------------
    | Prefix for all frontend API routes (used by seeders, sitemap, menus).
    */
    'prefix' => 'api',

    /*
    |--------------------------------------------------------------------------
    | API endpoint paths (relative to prefix)
    |--------------------------------------------------------------------------
    | Named keys for building API URLs. Use api_path('key') or api_path('key', $param).
    | Values with %s are templates for api_path('key', $segment).
    */
    'endpoints' => [
        'home' => 'settings',
        'solutions' => 'solutions',
        'solution' => 'solutions/%s',
        'modules' => 'modules',
        'module' => 'modules/%s',
        'features' => 'features',
        'feature' => 'features/%s',
        'pricing' => 'prijzen',
        'changelog' => 'changelog',
        'blog' => 'blog',
        'blog_post' => 'blog/%s',
        'contact' => 'contact',
        'trial' => 'proefversie',
        'academy' => 'academy',
        'course' => 'course',
        'course_categories' => 'course/categories',
        'live_sessions' => 'course/live-sessions',
        'live_sessions_recordings' => 'course/live-sessions/recordings',
        'vacancies' => 'vacancies',
        'vacancy' => 'vacancies/%s',
        'pages' => 'pages',
        'page' => 'pages/%s',
        'legal' => 'legal/%s',
        'static_page' => 'static/%s',
        'docs' => 'docs',
        'search' => 'search',
        'sitemap' => 'sitemap',
    ],

    /*
    |--------------------------------------------------------------------------
    | Predefined URLs for menus (mega menu, footer links, login settings)
    |--------------------------------------------------------------------------
    | Label => endpoint key. Only list/index endpoints (no %s). Uses api_path().
    */
    'predefined' => [
        'Home' => 'home',
        'Pages' => 'pages',
        'Blog' => 'blog',
        'Solutions' => 'solutions',
        'Modules' => 'modules',
        'Features' => 'features',
        'Pricing' => 'pricing',
        'Changelog' => 'changelog',
        'Contact' => 'contact',
        'Trial' => 'trial',
        'Academy' => 'course',
        'Course categories' => 'course_categories',
        'Live sessions' => 'live_sessions',
        'Live sessions recordings' => 'live_sessions_recordings',
        'Vacancies' => 'vacancies',
        'Docs' => 'docs',
        'Search' => 'search',
    ],
];
