<?php

namespace App\Models;

use App\Models\Traits\ImageGetterTrait;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperFeature
 */
class Feature extends BaseModel
{
    use ImageGetterTrait;

    const CACHE_KEY = 'features';

    protected $fillable = [
        'solution_id',
        'title',
        'anchor',
        'description',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get all social media posts for this feature
     */
    public function socialMediaPosts()
    {
        return $this->morphMany(SocialMediaPost::class, 'postable');
    }

    /**
     * Scope for active features
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered features by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Solution this feature belongs to.
     */
    public function solution()
    {
        return $this->belongsTo(Solution::class);
    }

    /**
     * Modules belonging to this feature.
     */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    /**
     * API path for this feature (headless; frontend uses this to fetch or build URL).
     */
    public function getLinkUrlAttribute(): string
    {
        $anchor = $this->anchor ?? '';

        return $anchor !== '' ? api_path('feature', $anchor) : api_path('features');
    }

    public static function forgetFeatureCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_rows_v1');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(fn () => self::forgetFeatureCache());
        static::updated(fn () => self::forgetFeatureCache());
        static::deleted(fn () => self::forgetFeatureCache());
    }

    public static function getCached()
    {
        return self::cacheRememberManyRows(
            self::CACHE_KEY.'_rows_v1',
            60 * 60,
            fn () => self::query()
                ->where('is_active', true)
                ->ordered()
                ->get(),
            [self::CACHE_KEY],
            ['solution', 'modules'],
        );
    }
}
