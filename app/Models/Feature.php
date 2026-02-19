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
        return $this->morphMany(\App\Models\SocialMediaPost::class, 'postable');
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
     * Many-to-many relationship with modules
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_feature');
    }

    public function getLinkUrlAttribute(): string
    {
        return "/feature/{$this->id}";
    }

    protected static function boot()
    {
        parent::boot();

        // Clear cache on model events
        static::created(fn () => Cache::forget(self::CACHE_KEY));
        static::updated(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    public static function getCached()
    {
        if (! Cache::has(self::CACHE_KEY)) {
            return Cache::remember(self::CACHE_KEY, 60 * 60,
                fn () => self::query()
                    ->where('is_active', true)
                    ->with('modules')
                    ->ordered()
                    ->get()
            );
        }

        return Cache::get(self::CACHE_KEY);
    }
}
