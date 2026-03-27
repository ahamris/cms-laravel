<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default page template
    |--------------------------------------------------------------------------
    | Used when a page has no template set (NULL). Existing pages behave as
    | this template (full form, all sections visible).
    */
    'default' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Template key for “legal” CMS pages (cookie banner links, mega menu, etc.)
    |--------------------------------------------------------------------------
    */
    'cookie_legal_template' => 'legal',

    /*
    |--------------------------------------------------------------------------
    | Page templates
    |--------------------------------------------------------------------------
    | Each template defines which form sections are visible in the admin.
    | Sections: page_info, page_rows, body, marketing, sidebar_settings, sidebar_image, seo
    */
    'templates' => [
        'default' => [
            'label' => 'Default (full)',
            'sections' => ['page_info', 'page_rows', 'body', 'marketing', 'sidebar_settings', 'sidebar_image', 'seo'],
        ],
        'landing' => [
            'label' => 'Landing page',
            'sections' => ['page_info', 'page_rows', 'body', 'marketing', 'sidebar_settings', 'sidebar_image', 'seo'],
        ],
        'minimal' => [
            'label' => 'Minimal',
            'sections' => ['page_info', 'page_rows', 'body', 'sidebar_settings'],
        ],
        'legal' => [
            'label' => 'Legal',
            'sections' => ['page_info', 'body', 'seo'],
        ],
    ],
];
