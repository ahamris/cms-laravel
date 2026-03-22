<?php

namespace App\Models;

use App\Helpers\Variable;
use App\Models\Traits\ClearsSitemapCache;
use App\Models\Traits\ImageGetterTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * @mixin IdeHelperBlog
 */
class Blog extends BaseModel
{
    use HasFactory, ImageGetterTrait, Sluggable, ClearsSitemapCache;

    const string CAROUSEL_CACHE_KEY = 'carousel_blogs';

    protected $fillable = [
        'blog_category_id',
        'blog_type_id',
        'author_id',
        'title',
        'slug',
        'short_body',
        'long_body',
        'image',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
        // Marketing Automation fields
        'funnel_fase',
        'marketing_persona_id',
        'content_type_id',
        'primary_keyword',
        'secondary_keywords',
        'ai_briefing',
        'seo_analysis',
        // Content Plan fields
        'content_plan_id',
        'autopilot_mode',
        'seo_score',
        'seo_status',
        'published_at',
        // v1.0 additions
        'type',
        'category_id',
        'reading_time',
        'media_url',
        'media_embed_code',
        'media_duration',
        'media_provider',
        'transcript',
        'show_notes',
        'featured_image_id',
        'allow_comments',
        'view_count',
        'series_id',
        'series_order',
    ];

    protected $casts = [
        'is_active'          => 'boolean',
        'is_featured'        => 'boolean',
        'allow_comments'     => 'boolean',
        'secondary_keywords' => 'array',
        'seo_analysis'       => 'array',
        'seo_score'          => 'integer',
        'reading_time'       => 'integer',
        'media_duration'     => 'integer',
        'view_count'         => 'integer',
        'series_order'       => 'integer',
        'published_at'       => 'datetime',
    ];



    /**
     * Boot the model and set up event listeners
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (Blog $article) {
            if ($article->isDirty('long_body') && $article->long_body) {
                $wordCount = str_word_count(strip_tags($article->long_body));
                $article->reading_time = max(1, (int) ceil($wordCount / 200));
            }
        });

        static::created(function () {
            self::clearCarouselCache();
        });

        static::updated(function () {
            self::clearCarouselCache();
        });

        static::deleted(function () {
            self::clearCarouselCache();
        });

        static::saved(function () {
            self::clearCarouselCache();
        });
    }

    public function blog_category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function blog_type(): BelongsTo
    {
        return $this->belongsTo(BlogType::class, 'blog_type_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(ArticleSeries::class, 'series_id');
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function relatedInSeries(): HasMany
    {
        return $this->hasMany(self::class, 'series_id', 'series_id')
                    ->where('id', '!=', $this->id)
                    ->orderBy('series_order');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, string $categorySlug)
    {
        return $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
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

    public function socialMediaPosts(): MorphMany
    {
        return $this->morphMany(SocialMediaPost::class, 'postable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'entity');
    }

    // Content Plan relationships
    public function contentPlan(): BelongsTo
    {
        return $this->belongsTo(ContentPlan::class);
    }

    public function performances(): MorphMany
    {
        return $this->morphMany(ContentPerformance::class, 'contentable');
    }

    /**
     * API path for this blog post (headless; frontend uses this to fetch or build URL).
     */
    public function getLinkUrlAttribute(): string
    {
        $slug = $this->slug ?? '';

        return $slug !== '' ? api_path('blog_post', $slug) : api_path('blog');
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
     * Set the slug attribute with proper cleaning
     */
    public function setSlugAttribute($value)
    {
        if (!empty($value)) {
            // Clean the slug using the sluggable package helper
            $this->attributes['slug'] = \Illuminate\Support\Str::slug($value, '-');
        } else {
            // Let the sluggable trait handle auto-generation
            $this->attributes['slug'] = null;
        }
    }

    /**
     * Get cached carousel blogs (latest active blogs)
     */
    public static function getCachedCarouselBlogs($limit = 6)
    {
        $cacheKey = self::CAROUSEL_CACHE_KEY . '_limit_' . $limit;

        return Cache::remember(
            $cacheKey,
            Variable::CACHE_TTL,
            fn() => self::with(['blog_category', 'author'])
                ->where('is_active', true)
                ->latest()
                ->take($limit)
                ->get()
        );
    }

    /**
     * Get cached carousel blogs filtered by category (latest active blogs)
     */
    public static function getCachedCarouselBlogsByCategory($categoryId, $limit = 6)
    {
        $cacheKey = self::CAROUSEL_CACHE_KEY . '_category_' . $categoryId . '_limit_' . $limit;

        return Cache::remember(
            $cacheKey,
            Variable::CACHE_TTL,
            fn() => self::with(['blog_category', 'author'])
                ->where('is_active', true)
                ->where('blog_category_id', $categoryId)
                ->latest()
                ->take($limit)
                ->get()
        );
    }

    /**
     * Clear carousel cache
     */
    public static function clearCarouselCache(): void
    {
        try {
            // Clear all possible cache variations
            $categories = BlogCategory::pluck('id')->toArray();
            $commonLimits = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 15, 18, 20, 21, 24, 27, 30, 50];

            // Clear base cache
            Cache::forget(self::CAROUSEL_CACHE_KEY);

            // Clear limit-specific caches (for all possible limits)
            foreach ($commonLimits as $limit) {
                Cache::forget(self::CAROUSEL_CACHE_KEY . '_limit_' . $limit);
            }

            // Clear category-specific caches
            if (!empty($categories)) {
                foreach ($categories as $categoryId) {
                    // Clear category cache without limit
                    Cache::forget(self::CAROUSEL_CACHE_KEY . '_category_' . $categoryId);

                    // Clear category cache with all possible limits
                    foreach ($commonLimits as $limit) {
                        Cache::forget(self::CAROUSEL_CACHE_KEY . '_category_' . $categoryId . '_limit_' . $limit);
                    }
                }
            }

            // Also clear sitemap cache
            Cache::forget('sitemap_xml');

            // If using cache tags (Redis/Memcached), clear by tag
            if (method_exists(Cache::getStore(), 'tags')) {
                try {
                    Cache::tags(['blog_carousel'])->flush();
                } catch (\Exception $e) {
                    // Tags not supported, continue with individual forgets
                }
            }
        } catch (\Exception $e) {
            // Log error but don't break the update process
        }
    }
}
