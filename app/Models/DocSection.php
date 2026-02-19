<?php

namespace App\Models;

use App\Models\Traits\ClearsSitemapCache;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperDocSection
 */
class DocSection extends BaseModel
{
    use Sluggable, ClearsSitemapCache;

    protected $fillable = [
        'doc_version_id',
        'title',
        'slug',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => true,
            ],
        ];
    }

    /**
     * Get the version that owns this section.
     */
    public function version(): BelongsTo
    {
        return $this->belongsTo(DocVersion::class, 'doc_version_id');
    }

    /**
     * Get all pages for this section.
     */
    public function pages(): HasMany
    {
        return $this->hasMany(DocPage::class)->orderBy('sort_order');
    }

    /**
     * Get active pages for this section.
     */
    public function activePages(): HasMany
    {
        return $this->hasMany(DocPage::class)->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Scope for active sections.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered sections.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get route key name for model binding.
     */
    public function getRouteKeyName()
    {
        if (request()->is('admin/*')) {
            return 'id';
        }

        return 'slug';
    }
}
