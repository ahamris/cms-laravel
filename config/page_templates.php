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
    | Page templates
    |--------------------------------------------------------------------------
    | Each template defines which form sections are visible in the admin.
    | Sections: page_info, body, marketing, sidebar_settings, sidebar_image, sidebar_elements, seo
    */
    'templates' => [
        'default' => [
            'label' => 'Default (full)',
            'sections' => ['page_info', 'body', 'marketing', 'sidebar_settings', 'sidebar_image', 'sidebar_elements', 'seo'],
        ],
        'landing' => [
            'label' => 'Landing page',
            'sections' => ['page_info', 'body', 'sidebar_settings', 'sidebar_image', 'sidebar_elements', 'seo'],
        ],
        'minimal' => [
            'label' => 'Minimal',
            'sections' => ['page_info', 'body', 'sidebar_settings'],
        ],
    ],
];
