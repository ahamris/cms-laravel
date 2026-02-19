<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperCarouselWidget
 */
class CarouselWidget extends BaseModel
{
    protected $fillable = [
        'name',
        'identifier',
        'title',
        'description',
        'data_source',
        'blog_category_id',
        'items_per_row',
        'total_items',
        'show_arrows',
        'show_dots',
        'show_author',
        'autoplay',
        'autoplay_speed',
        'infinite_loop',
        'show_view_all_button',
        'view_all_title',
        'view_all_description',
        'is_active',
        'sort_order',
        'settings',
    ];

    protected $casts = [
        'items_per_row' => 'integer',
        'total_items' => 'integer',
        'show_arrows' => 'boolean',
        'show_dots' => 'boolean',
        'show_author' => 'boolean',
        'autoplay' => 'boolean',
        'autoplay_speed' => 'integer',
        'infinite_loop' => 'boolean',
        'show_view_all_button' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'settings' => 'array',
    ];

    /**
     * Get the blog category associated with this carousel widget.
     */
    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Scope for active carousel widgets
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered carousel widgets
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Get carousel widget by identifier
     */
    public static function getByIdentifier(string $identifier): ?self
    {
        return static::where('identifier', $identifier)
            ->where('is_active', true)
            ->first();
    }
}

