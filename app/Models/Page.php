<?php

namespace App\Models;

use App\Models\Traits\ClearsSitemapCache;
use App\Models\Traits\MegaMenuModuleTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPage
 */
class Page extends BaseModel
{
    use ClearsSitemapCache, HasFactory, MegaMenuModuleTrait, Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'short_body',
        'long_body',
        'meta_title',
        'meta_body',
        'meta_keywords',
        'image',
        'icon',
        'is_active',
        'template',
        // Marketing Automation fields
        'funnel_fase',
        'marketing_persona_id',
        'content_type_id',
        'primary_keyword',
        'secondary_keywords',
        'ai_briefing',
        'seo_analysis',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            // Marketing Automation casts
            'secondary_keywords' => 'array',
            'seo_analysis' => 'array',
        ];
    }

    /**
     * Get all social media posts for this page
     */
    public function socialMediaPosts()
    {
        return $this->morphMany(\App\Models\SocialMediaPost::class, 'postable');
    }

    // Marketing Automation relationships
    public function marketingPersona(): BelongsTo
    {
        return $this->belongsTo(MarketingPersona::class);
    }

    public function contentType(): BelongsTo
    {
        return $this->belongsTo(ContentType::class);
    }

    /**
     * Accessor for long_body: wraps inline <script> contents in IIFEs
     * to prevent global scope variable conflicts when multiple blocks
     * contain the same const/let declarations (e.g. resizeObserver).
     */
    public function getLongBodyAttribute($value): ?string
    {
        if (empty($value)) {
            return $value;
        }

        // Wrap contents of inline <script> tags (without src) in IIFEs
        return preg_replace_callback(
            '/<script(?![^>]*\bsrc\b)([^>]*)>([\s\S]*?)<\/script>/i',
            function ($matches) {
                $attrs = $matches[1];
                $code = $matches[2];

                // Skip if already wrapped in IIFE or if script is empty/whitespace
                if (empty(trim($code)) || str_contains($code, '(function()')) {
                    return $matches[0];
                }

                return "<script{$attrs}>(function(){".$code.'})();</script>';
            },
            $value
        );
    }

    /**
     * Effective template key (NULL/missing treated as default).
     */
    public function getEffectiveTemplateAttribute(): string
    {
        return $this->template ?? config('page_templates.default', 'default');
    }

    /**
     * Config for the current template (label, sections).
     */
    public function getTemplateConfig(): ?array
    {
        $key = $this->effective_template;

        return config("page_templates.templates.{$key}");
    }

    /**
     * API path for this page (headless; frontend uses this to fetch or build URL).
     */
    public function getLinkUrlAttribute(): string
    {
        $slug = $this->slug ?? '';

        return $slug !== '' ? api_path('page', $slug) : api_path('pages');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'maxLength' => 255,
                'separator' => '-',
                'includeTrashed' => true,
            ],
        ];
    }
}
