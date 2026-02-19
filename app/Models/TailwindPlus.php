<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperTailwindPlus
 */
class TailwindPlus extends BaseModel
{
    protected $table = 'tailwind_plus';

    protected $fillable = [
        'category',
        'component_group',
        'component_name',
        'code',
        'preview',
        'version',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'version' => 'integer',
    ];

    /**
     * Scope for active components
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered components
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('component_name');
    }

    /**
     * Scope for specific category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for specific component name
     */
    public function scopeByComponentName($query, string $componentName)
    {
        return $query->where('component_name', $componentName);
    }

    /**
     * Get pages that use this component
     */
    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'page_tailwind_plus')
            ->withPivot('sort_order', 'is_active', 'custom_config')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }
}
