<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Exact path => template (path without leading slash; no query string)
    | Used when the menu link URL matches exactly (e.g. /api/contact → contact).
    |--------------------------------------------------------------------------
    */
    'exact' => [
        'api/contact' => 'contact',
        'api/blog' => 'blog-list',
        'api/prijzen' => 'pricing',
        'api/changelog' => 'changelog',
        'api/settings' => 'home',
        'api/solutions' => 'solutions-list',
        'api/modules' => 'modules-list',
        'api/features' => 'features-list',
        'api/academy' => 'academy',
        'api/live-sessions' => 'live-sessions',
        'api/vacancies' => 'vacancies-list',
        'api/pages' => 'pages-list',
        'api/proefversie' => 'trial',
    ],

    /*
    |--------------------------------------------------------------------------
    | Path prefix => template (path must start with prefix; first match wins)
    | Used for detail/segment routes (e.g. /api/blog/my-post → blog-detail).
    |--------------------------------------------------------------------------
    */
    'prefix' => [
        'api/blog/' => 'blog-detail',
        'api/solutions/' => 'solution-detail',
        'api/modules/' => 'module-detail',
        'api/features/' => 'feature-detail',
        'api/vacancies/' => 'vacancy-detail',
        'api/pages/' => null, // resolved via page_slugs when page slug is known
    ],

    /*
    |--------------------------------------------------------------------------
    | Page slug => template (when link is a page reference and slug is known)
    | Used for api/pages/{slug} or when page_id is set; fallback is 'page'.
    |--------------------------------------------------------------------------
    */
    'page_slugs' => [
        'contact' => 'contact',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default template when no rule matches
    |--------------------------------------------------------------------------
    */
    'default' => 'page',
];
