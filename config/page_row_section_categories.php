<?php

/**
 * Section types for page row templates.
 *
 * element_types: ElementType enum values allowed when picking an element for that row
 * (see App\Enums\ElementType).
 */
return [
    'categories' => [
        'hero' => [
            'label' => 'Hero Sections',
            'default_row_label' => 'Hero',
            'component_count' => 12,
            'element_types' => ['hero_video'],
        ],
        'features' => [
            'label' => 'Feature Sections',
            'default_row_label' => 'Features',
            'component_count' => 10,
            'element_types' => ['feature'],
        ],
        'cta' => [
            'label' => 'CTA Sections',
            'default_row_label' => 'Call to action',
            'component_count' => 8,
            'element_types' => ['cta'],
        ],
        'bento' => [
            'label' => 'Bento Grids',
            'default_row_label' => 'Bento grid',
            'component_count' => 6,
            'element_types' => ['card_grid'],
        ],
        'pricing' => [
            'label' => 'Pricing Sections',
            'default_row_label' => 'Pricing',
            'component_count' => 9,
            'element_types' => ['card_grid', 'feature'],
        ],
        'header' => [
            'label' => 'Header Sections',
            'default_row_label' => 'Header',
            'component_count' => 7,
            'element_types' => ['related_content'],
        ],
        'newsletter' => [
            'label' => 'Newsletter Sections',
            'default_row_label' => 'Newsletter',
            'component_count' => 5,
            'element_types' => ['newsletter'],
        ],
        'stats' => [
            'label' => 'Stats',
            'default_row_label' => 'Stats',
            'component_count' => 8,
            'element_types' => ['feature'],
        ],
        'testimonials' => [
            'label' => 'Testimonials',
            'default_row_label' => 'Testimonials',
            'component_count' => 11,
            'element_types' => ['feature', 'card_grid'],
        ],
        'blog' => [
            'label' => 'Blog Sections',
            'default_row_label' => 'Blog',
            'component_count' => 10,
            'element_types' => ['related_content', 'card_grid'],
        ],
        'contact' => [
            'label' => 'Contact Sections',
            'default_row_label' => 'Contact',
            'component_count' => 7,
            'element_types' => ['feature', 'cta'],
        ],
        'team' => [
            'label' => 'Team Sections',
            'default_row_label' => 'Team',
            'component_count' => 8,
            'element_types' => ['feature', 'card_grid'],
        ],
        'content' => [
            'label' => 'Content Sections',
            'default_row_label' => 'Content',
            'component_count' => 14,
            'element_types' => ['feature', 'related_content'],
        ],
        'logo_cloud' => [
            'label' => 'Logo Clouds',
            'default_row_label' => 'Logo cloud',
            'component_count' => 6,
            'element_types' => ['card_grid'],
        ],
        'faqs' => [
            'label' => 'FAQs',
            'default_row_label' => 'FAQ',
            'component_count' => 9,
            'element_types' => ['faq'],
        ],
        'footer' => [
            'label' => 'Footers',
            'default_row_label' => 'Footer',
            'component_count' => 12,
            'element_types' => ['cta', 'feature', 'related_content'],
        ],
    ],
];
