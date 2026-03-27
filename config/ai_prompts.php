<?php

/**
 * Versioned prompt identifiers for CMS/CRM AI flows. Bump the inner `version` when copy changes materially.
 */
return [
    'registry_version' => '2026.03.27',

    'prompts' => [
        'blog_content_writer' => [
            'version' => '1',
            'description' => 'Admin blog draft from topic/keywords',
        ],
        'article_structured' => [
            'version' => '1',
            'description' => 'Structured article JSON (title, body, SEO fields)',
        ],
        'plan_blog_content' => [
            'version' => '1',
            'description' => 'Plan-driven blog from intent brief',
        ],
        'crm_support' => [
            'version' => '1',
            'description' => 'CRM thread summary, reply draft, risk flags',
        ],
        'http_page_blocks' => [
            'version' => '1',
            'description' => 'AIService JSON page blocks (HTTP fallback chain)',
        ],
        'http_seo_optimize' => [
            'version' => '1',
            'description' => 'AIService SEO JSON analysis',
        ],
    ],
];
