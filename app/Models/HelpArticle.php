<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperHelpArticle
 */
class HelpArticle extends BaseModel
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'product_feature_ids',
        'difficulty_level',
        'estimated_read_time',
        'tags',
        'related_articles',
        'is_featured',
        'is_published',
        'views_count',
        'helpful_vote',
        'sort_order',
        'published_at',
    ];

    protected $casts = [
        'product_feature_ids' => 'array',
        'tags' => 'array',
        'related_articles' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'views_count' => 'integer',
        'helpful_vote' => 'integer',
        'sort_order' => 'integer',
        'published_at' => 'datetime',
    ];

    /**
     * Cache configuration
     */
    const CACHE_KEY = 'help_articles';

    const CACHE_DURATION = 3600; // 1 hour

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            if (empty($article->published_at) && $article->is_published) {
                $article->published_at = now();
            }
        });

        static::created(fn () => self::clearCache());
        static::updated(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
    }

    /**
     * Get cached articles
     */
    public static function getCached()
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY.'_rows_v1',
            self::CACHE_DURATION,
            fn () => self::published()->ordered()->get(),
            [self::CACHE_KEY],
        );
    }

    /**
     * Get featured articles
     */
    public static function getFeatured()
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY.'_featured_rows_v1',
            self::CACHE_DURATION,
            fn () => self::published()->featured()->ordered()->limit(5)->get(),
            [self::CACHE_KEY.'_featured'],
        );
    }

    /**
     * Clear cache
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_rows_v1');
        Cache::forget(self::CACHE_KEY.'_featured');
        Cache::forget(self::CACHE_KEY.'_featured_rows_v1');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('published_at', 'desc');
    }

    public function scopeByDifficulty($query, string $level)
    {
        return $query->where('difficulty_level', $level);
    }

    public function scopeByProductFeature($query, int $featureId)
    {
        return $query->whereJsonContains('product_feature_ids', $featureId);
    }

    /**
     * Relationships
     */
    public function productFeatures()
    {
        if (empty($this->product_feature_ids)) {
            return collect();
        }

        return ProductFeature::whereIn('id', $this->product_feature_ids)->get();
    }

    public function relatedArticles()
    {
        if (empty($this->related_articles)) {
            return collect();
        }

        return self::whereIn('id', $this->related_articles)->published()->get();
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
        self::clearCache();
    }

    /**
     * Increment helpful votes
     */
    public function incrementHelpfulVotes()
    {
        $this->increment('helpful_vote');
        self::clearCache();
    }

    /**
     * Get difficulty badge color
     */
    public function getDifficultyColorAttribute()
    {
        return match ($this->difficulty_level) {
            'beginner' => 'green',
            'intermediate' => 'yellow',
            'advanced' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get estimated read time in minutes
     */
    public function getReadTimeAttribute()
    {
        if ($this->estimated_read_time) {
            return $this->estimated_read_time.' min read';
        }

        // Calculate based on content length (average 200 words per minute)
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200);

        return $minutes.' min read';
    }

    /**
     * Check if article has product features
     */
    public function hasProductFeatures(): bool
    {
        return ! empty($this->product_feature_ids);
    }

    /**
     * Check if article has related articles
     */
    public function hasRelatedArticles(): bool
    {
        return ! empty($this->related_articles);
    }
}
