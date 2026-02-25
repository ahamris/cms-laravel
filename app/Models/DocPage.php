<?php

namespace App\Models;

use App\Models\Traits\ClearsSitemapCache;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperDocPage
 */
class DocPage extends BaseModel
{
    use Sluggable, ClearsSitemapCache;

    protected $fillable = [
        'doc_section_id',
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
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
     * Get the section that owns this page.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(DocSection::class, 'doc_section_id');
    }

    /**
     * Scope for active pages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered pages.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the next page in the same section.
     */
    public function getNextPage()
    {
        return static::where('doc_section_id', $this->doc_section_id)
            ->where('is_active', true)
            ->where('sort_order', '>', $this->sort_order)
            ->orderBy('sort_order')
            ->first();
    }

    /**
     * Get the previous page in the same section.
     */
    public function getPreviousPage()
    {
        return static::where('doc_section_id', $this->doc_section_id)
            ->where('is_active', true)
            ->where('sort_order', '<', $this->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();
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
