<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Collection;

/**
 * Static sidebar menu item: same interface as AdminMenuItem for the blade partial.
 *
 * @property-read string $item_type
 * @property-read string $label
 * @property-read string|null $slug
 * @property-read string|null $route_name
 * @property-read array $route_parameters
 * @property-read string|null $url
 * @property-read string|null $icon
 * @property-read string|null $badge_text
 * @property-read string|null $badge_color
 * @property-read string|null $active_pattern
 * @property-read string|null $target
 * @property-read bool $is_active
 * @property-read Collection<int, StaticSidebarItem> $childrenRecursive
 * @property-read Collection<int, StaticSidebarItem> $children
 */
final class StaticSidebarItem
{
    public function __construct(
        public readonly string $item_type,
        public readonly string $label,
        public readonly ?string $slug = null,
        public readonly ?string $route_name = null,
        public readonly array $route_parameters = [],
        public readonly ?string $url = null,
        public readonly ?string $icon = null,
        public readonly ?string $badge_text = null,
        public readonly ?string $badge_color = 'primary',
        public readonly ?string $active_pattern = null,
        public readonly ?string $target = null,
        public readonly bool $is_active = true,
        /** @var array<int, StaticSidebarItem> */
        public readonly array $children = [],
    ) {}

    public function isCurrentlyActive(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->active_pattern) {
            $patterns = array_map('trim', explode(',', $this->active_pattern));

            foreach ($patterns as $pattern) {
                if (request()->routeIs($pattern)) {
                    return true;
                }
            }

            return false;
        }

        if ($this->route_name) {
            return request()->routeIs($this->route_name)
                || request()->routeIs($this->route_name.'.*');
        }

        return false;
    }

    public function shouldExpand(): bool
    {
        if ($this->isCurrentlyActive()) {
            return true;
        }

        foreach ($this->children as $child) {
            if ($child->shouldExpand()) {
                return true;
            }
        }

        return false;
    }

    public function resolvedRouteParameters(): array
    {
        return $this->route_parameters;
    }

    public function getResolvedBadgeTextAttribute(): ?string
    {
        return $this->badge_text;
    }

    /**
     * Blade expects relationLoaded('childrenRecursive') and ->childrenRecursive / ->children.
     */
    public function getChildrenRecursiveAttribute(): Collection
    {
        return collect($this->children);
    }

    public function getChildrenAttribute(): Collection
    {
        return collect($this->children);
    }

    /**
     * Allow Blade to access resolvedBadgeText and childrenRecursive as properties.
     */
    public function __get(string $key): mixed
    {
        return match ($key) {
            'resolvedBadgeText' => $this->getResolvedBadgeTextAttribute(),
            'childrenRecursive' => $this->getChildrenRecursiveAttribute(),
            'children' => $this->getChildrenAttribute(),
            default => null,
        };
    }

    public function relationLoaded(string $relation): bool
    {
        return $relation === 'childrenRecursive' || $relation === 'children';
    }

    /**
     * Build a tree from a static definition. Each node: item_type, label, route_name?, url?, icon?, badge_text?, badge_color?, active_pattern?, target?, children? (array of same shape).
     *
     * @param  array<int, array<string, mixed>>  $nodes
     * @return array<int, StaticSidebarItem>
     */
    public static function fromArray(array $nodes): array
    {
        $items = [];

        foreach ($nodes as $node) {
            $children = isset($node['children']) && is_array($node['children'])
                ? self::fromArray($node['children'])
                : [];

            $items[] = new self(
                item_type: $node['item_type'] ?? 'link',
                label: $node['label'],
                slug: $node['slug'] ?? null,
                route_name: $node['route_name'] ?? null,
                route_parameters: $node['route_parameters'] ?? [],
                url: $node['url'] ?? null,
                icon: $node['icon'] ?? null,
                badge_text: $node['badge_text'] ?? null,
                badge_color: $node['badge_color'] ?? 'primary',
                active_pattern: $node['active_pattern'] ?? null,
                target: $node['target'] ?? null,
                is_active: $node['is_active'] ?? true,
                children: $children,
            );
        }

        return $items;
    }
}
