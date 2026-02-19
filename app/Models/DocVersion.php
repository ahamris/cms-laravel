<?php

namespace App\Models;

use App\Models\Traits\ClearsSitemapCache;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperDocVersion
 */
class DocVersion extends BaseModel
{
    use ClearsSitemapCache;

    protected $fillable = [
        'version',
        'name',
        'is_active',
        'is_default',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Ensure only one default version exists
        static::saving(function ($version) {
            if ($version->is_default) {
                static::where('id', '!=', $version->id)->update(['is_default' => false]);
            }
        });
    }

    /**
     * Get all sections for this version.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(DocSection::class)->orderBy('sort_order');
    }

    /**
     * Get active sections for this version.
     */
    public function activeSections(): HasMany
    {
        return $this->hasMany(DocSection::class)->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Scope for active versions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered versions.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('version', 'desc');
    }

    /**
     * Get the default version.
     */
    public static function getDefault()
    {
        return static::where('is_default', true)->where('is_active', true)->first();
    }

    /**
     * Get route key name for model binding.
     */
    public function getRouteKeyName()
    {
        if (request()->is('admin/*')) {
            return 'id';
        }

        return 'version';
    }
}
