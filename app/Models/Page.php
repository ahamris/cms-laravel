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
        'page_type',
        'design_type',
        'header_block',
        'footer_block',
        'hide_header',
        'hide_footer',
        'widget_config',
        'layout_type',
        'short_body',
        'long_body',
        'meta_title',
        'meta_body',
        'meta_keywords',
        'image',
        'icon',
        'is_active',
        'home_page',
        // Marketing Automation fields
        'funnel_fase',
        'marketing_persona_id',
        'content_type_id',
        'primary_keyword',
        'secondary_keywords',
        'ai_briefing',
        'seo_analysis',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hide_header' => 'boolean',
        'hide_footer' => 'boolean',
        'home_page' => 'boolean',
        'page_type' => 'string',
        'widget_config' => 'array',
        // Marketing Automation casts
        'secondary_keywords' => 'array',
        'seo_analysis' => 'array',
    ];

    protected $attributes = [
        'page_type' => 'static',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($page) {
            // If this page is being set as homepage, unset all other homepages
            if ($page->home_page === true && $page->isDirty('home_page')) {
                // Use DB facade to avoid triggering model events
                $query = \DB::table('pages')->where('home_page', true);

                // Exclude current page if it exists (for updates)
                if ($page->exists && $page->id) {
                    $query->where('id', '!=', $page->id);
                }

                $query->update(['home_page' => false]);
            }
        });
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
     * Scope for showcase pages
     */
    public function scopeShowcase($query)
    {
        return $query->where('page_type', 'showcase');
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

    public function getLinkUrlAttribute(): string
    {
        return route('page.show', $this->slug);
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

    /**
     * Check if page is showcase type
     */
    public function isShowcase(): bool
    {
        return $this->page_type === 'showcase';
    }

    /**
     * Check if page is static type
     */
    public function isStatic(): bool
    {
        return $this->page_type === 'static';
    }

    /**
     * Check if page is homepage
     */
    public function isHomepage(): bool
    {
        return $this->home_page === true;
    }

    /**
     * Get the homepage page
     */
    public static function getHomepage(): ?self
    {
        return self::where('home_page', true)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Set a page as homepage
     * The boot method will automatically unset any existing homepage
     */
    public static function setAsHomepage(self $page): bool
    {
        // Ensure page is active
        if (! $page->is_active) {
            return false;
        }

        // Set this page as homepage (boot method will handle unsetting others)
        $page->update(['home_page' => true]);

        return true;
    }

    /**
     * Remove homepage status from a page
     */
    public static function removeHomepage(self $page): bool
    {
        if ($page->isHomepage()) {
            $page->update(['home_page' => false]);

            return true;
        }

        return false;
    }
}
