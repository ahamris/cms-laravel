<?php

namespace App\Models\Traits;

trait MegaMenuModuleTrait
{
    /**
     * Get a hierarchical tree structure of this item and its related children
     * Useful for mega menus and navigation structures
     */
    public function getLinksTreeAttribute()
    {
        return [
            'id' => $this->id,
            'title' => $this->title ?? $this->name,
            'slug' => $this->slug ?? null,
            'parent' => [
                'id' => $this->id,
                'title' => $this->title ?? $this->name,
                'url' => $this->getUrl(),
            ],
            'children' => $this->getChildrenItems(),
        ];
    }

    /**
     * Get URL for this item
     */
    protected function getUrl(): string
    {
        $modelName = strtolower(class_basename($this));

        return "/{$modelName}/{$this->slug}";
    }

    /**
     * Get children items if any related items exist
     * Override this method in models that have relationships
     */
    protected function getChildrenItems(): array
    {
        // Check if model has common relationship methods
        if (method_exists($this, 'children')) {
            return $this->children()->where('is_active', true)->get()->map(function ($child) {
                return [
                    'id' => $child->id,
                    'title' => $child->title ?? $child->name ?? 'Untitled',
                    'url' => method_exists($child, 'getUrl') ? $child->getUrl() : '#',
                ];
            })->toArray();
        }

        // For models without children, return empty array
        // The parent item itself will still be available
        return [];
    }

    /**
     * Scope to get only active items for mega menu
     */
    public function scopeForMegaMenu($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }
}
