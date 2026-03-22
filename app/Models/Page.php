<?php

namespace App\Models;

use App\Models\Traits\ClearsSitemapCache;
use App\Models\Traits\ElementTrait;
use App\Models\Traits\MegaMenuModuleTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @mixin IdeHelperPage
 */
class Page extends BaseModel
{
    use ClearsSitemapCache, ElementTrait, HasFactory, MegaMenuModuleTrait, Sluggable;

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
        'layout',
        'parent_id',
        'sort_order',
        'is_homepage',
        'published_at',
        'og_image_id',
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
            'is_active'          => 'boolean',
            'is_homepage'        => 'boolean',
            'sort_order'         => 'integer',
            'published_at'       => 'datetime',
            'secondary_keywords' => 'array',
            'seo_analysis'       => 'array',
        ];
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(PageBlock::class)->orderBy('sort_order');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function ogImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'og_image_id');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function scopePublished($query)
    {
        return $query->where('is_active', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
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
