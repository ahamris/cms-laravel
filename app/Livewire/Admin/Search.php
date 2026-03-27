<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Search extends Component
{
    public string $query = '';

    public bool $isOpen = false;

    public bool $dropdownMode = false;

    public function mount(bool $dropdownMode = false): void
    {
        $this->dropdownMode = $dropdownMode;
    }

    public function open(): void
    {
        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->query = '';
    }

    #[Computed]
    public function results(): array
    {
        if (mb_strlen(trim($this->query)) < 2) {
            return [];
        }

        $results = [];

        // Menü araması
        $menuResults = $this->searchMenus();
        if ($menuResults->isNotEmpty()) {
            $results[] = [
                'type' => 'menu',
                'title' => 'Menus',
                'items' => $menuResults->toArray(),
            ];
        }

        return $results;
    }

    protected function searchMenus(): Collection
    {
        try {
            $menuItems = collect($this->cachedMenuIndex());
            $items = collect();
            $query = mb_strtolower($this->query);

            return $menuItems
                ->filter(function (array $item) use ($query) {
                    return str_contains(mb_strtolower($item['title']), $query)
                        || str_contains(mb_strtolower($item['full_title']), $query)
                        || (isset($item['route']) && str_contains(mb_strtolower($item['route']), $query));
                })
                ->map(function (array $item) {
                    return [
                        'title' => $item['full_title'],
                        'url' => $item['url'],
                        'route' => $item['route'] ?? null,
                        'icon' => $item['icon'] ?? null,
                    ];
                })
                ->take(5)
                ->values();
        } catch (\Exception $e) {
            Log::error('Search component: Error searching menus', [
                'error' => $e->getMessage(),
                'query' => $this->query,
            ]);

            return collect();
        }
    }

    /**
     * Build and cache a flattened menu index per user for faster search requests.
     *
     * @return array<int, array{title:string,full_title:string,url:?string,route:?string,icon:?string}>
     */
    private function cachedMenuIndex(): array
    {
        $userId = Auth::id() ?? 'guest';
        $cacheKey = "admin.search.menu-index.v1.user.{$userId}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () {
            $menuItems = StaticSidebarItem::fromArray(Sidebar::filteredMenuDefinition());
            $index = [];

            $flatten = function (array $nodes, string $parentLabel = '') use (&$flatten, &$index): void {
                foreach ($nodes as $item) {
                    if (! $item->is_active) {
                        continue;
                    }

                    $label = $item->label;
                    $fullLabel = $parentLabel ? "{$parentLabel} → {$label}" : $label;
                    $url = $item->route_name ? route($item->route_name, $item->resolvedRouteParameters()) : $item->url;

                    $index[] = [
                        'title' => $label,
                        'full_title' => $fullLabel,
                        'url' => $url,
                        'route' => $item->route_name,
                        'icon' => $item->icon,
                    ];

                    $children = collect($item->children ?? []);
                    if ($children->isNotEmpty()) {
                        $flatten($children->all(), $fullLabel);
                    }
                }
            };

            $flatten($menuItems, '');

            return $index;
        });
    }

    protected function searchRoutes(): Collection
    {
        try {
            $routes = collect(Route::getRoutes())
                ->filter(function ($route) {
                    $name = $route->getName();

                    if (! $name ||
                        ! str_starts_with($name, 'admin.') ||
                        $name === 'admin.index' ||
                        str_contains($name, '.files.') ||
                        str_contains($name, 'api.')) {
                        return false;
                    }

                    // Parametre gerektiren route'ları filtrele
                    $uri = $route->uri();
                    if (preg_match('/\{[^}]+\}/', $uri)) {
                        return false;
                    }

                    return true;
                })
                ->map(function ($route) {
                    try {
                        $uri = $route->uri();
                        $uri = str_replace(['{', '}'], '', $uri);

                        return [
                            'title' => $this->formatRouteName($route->getName()),
                            'url' => $uri,
                            'route' => $route->getName(),
                        ];
                    } catch (\Exception $e) {
                        Log::warning('Search component: Error processing route', [
                            'route' => $route->getName(),
                            'error' => $e->getMessage(),
                        ]);

                        return null;
                    }
                })
                ->filter(function ($item) {
                    if (! $item) {
                        return false;
                    }

                    return stripos($item['title'], $this->query) !== false ||
                           stripos($item['route'], $this->query) !== false ||
                           stripos($item['url'], $this->query) !== false;
                })
                ->values()
                ->take(5);

            return $routes;
        } catch (\Exception $e) {
            Log::error('Search component: Error searching routes', [
                'error' => $e->getMessage(),
                'query' => $this->query,
            ]);

            return collect();
        }
    }

    protected function formatRouteName(?string $name): string
    {
        if (! $name) {
            return '';
        }

        // admin.settings.menu -> Settings Menu
        $name = str_replace('admin.', '', $name);
        $parts = explode('.', $name);
        $formatted = array_map('ucfirst', $parts);

        return implode(' → ', $formatted);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        if ($this->dropdownMode) {
            return view('livewire.admin.search-dropdown');
        }

        return view('livewire.admin.search');
    }
}
