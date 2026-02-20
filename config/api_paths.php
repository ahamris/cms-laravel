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
        'blog' => 'blog-posts',
        'blog_post' => 'blog/%s',
        'contact' => 'contact',
        'vacancies' => 'vacancies',
        'vacancy' => 'vacancies/%s',
        'pages' => 'pages',
        'page' => 'pages/%s',
        'sitemap' => 'sitemap',
    ],
];
