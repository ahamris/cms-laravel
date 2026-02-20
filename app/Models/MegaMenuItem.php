<?php

namespace App\Models;

use App\Helpers\Variable;
use Exception;
use Illuminate\Support\Facades\Cache;
use Log;

/**
 * @mixin IdeHelperMegaMenuItem
 */
class MegaMenuItem extends BaseModel
{
    protected $table = 'mega_menu_items';

    public const CACHE_KEY = 'mega_menu_data';

    /** Alignment for child items: left */
    public const ALIGN_LEFT = 1;

    /** Alignment for child items: right */
    public const ALIGN_RIGHT = 2;

    protected $fillable = [
        'parent_id',
        'order',
        'title',
        'subtitle',
        'description',
        'url',
        'page_id',
        'icon',
        'icon_bg_color',
        'is_mega_menu',
        'is_active',
        'open_in_new_tab',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'is_mega_menu' => 'boolean',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
            'tags' => 'array',
            'align' => 'integer',
            'order' => 'integer',
            'parent_id' => 'integer',
            'page_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the parent menu item
     */
    public function parent()
    {
        return $this->belongsTo(MegaMenuItem::class, 'parent_id');
    }

    /**
     * Get child menu items (submenu)
     */
    public function children()
    {
        return $this->hasMany(MegaMenuItem::class, 'parent_id')->ordered();
    }

    /**
     * Get the page associated with this menu item
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(fn () => static::clearCache());
        static::updated(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }

    /**
     * Get mega menu tree from cache; only hits the database when cache is cold (or after create/update/delete).
     * Same 3-level structure as before: parent > children > grandchildren.
     */
    public static function getCached(): array
    {
        if (! Cache::has(self::CACHE_KEY)) {
            return Cache::remember(self::CACHE_KEY, Variable::CACHE_TTL, function () {
                $items = self::active()
                    ->rootLevel()
                    ->ordered()
                    ->with([
                        'page',
                        'children' => fn ($query) => $query->active()->ordered()->with([
                            'page',
                            'children' => fn ($subQuery) => $subQuery->active()->ordered()->with('page'),
                        ]),
                    ])
                    ->get();

                return $items->map(fn ($item) => self::itemToCachedArray($item))->toArray();
            });
        }

        return Cache::get(self::CACHE_KEY);
    }

    /**
     * Clear mega menu cache (e.g. after reorder or header settings change).
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Convert one menu item (and its children) to cached array with resolved url.
     */
    private static function itemToCachedArray(self $item): array
    {
        $arr = $item->toArray();
        $arr['url'] = $item->full_url;
        if (! empty($arr['children']) && $item->relationLoaded('children')) {
            $arr['children'] = $item->children->map(fn ($child) => self::itemToCachedArray($child))->toArray();
        }

        return $arr;
    }

    /**
     * Scope for active items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for root level items only
     */
    public function scopeRootLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for child items only
     */
    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }

    /**
     * Check if this is a root level item
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Check if this item has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get all root menu items with their children (recursive)
     */
    public static function getMenuStructure(): array
    {
        return self::active()
            ->rootLevel()
            ->ordered()
            ->with([
                'children' => function ($query) {
                    $query->active()->ordered()->with([
                        'children' => function ($subQuery) {
                            $subQuery->active()->ordered()->with([
                                'children' => function ($subSubQuery) {
                                    $subSubQuery->active()->ordered();
                                },
                            ]);
                        },
                    ]);
                },
            ])
            ->get()
            ->toArray();
    }

    /**
     * Get children recursively with unlimited depth
     */
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * Check if this item has children (at any level)
     */
    public function hasChildrenRecursive(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Get the depth level of this menu item
     */
    public function getDepthLevel(): int
    {
        $level = 0;
        $parent = $this->parent;

        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }

        return $level;
    }

    /**
     * Get the full URL for this menu item.
     * Priority: page reference > custom URL > fallback to #
     */
    public function getFullUrlAttribute(): string
    {
        // If page_id is set, use the page's URL
        if ($this->page_id && $this->page) {
            return $this->page->link_url ?? '#';
        }

        // Otherwise, use custom URL if available
        if (! empty($this->url)) {
            return $this->url;
        }

        // Fallback to #
        return '#';
    }

    /**
     * Get possible menu items from available routes and content types.
     * This method dynamically discovers available routes and content types.
     */
    public static function possibleMenuItems(): array
    {
        $links = [];

        // Dynamically check for available routes
        $routeNames = [
            'home' => 'Home',
            'contact' => 'Contact',
            'pricing' => 'Pricing',
            'blog' => 'Blog',
            'trial' => 'Trial',
            'changelog.index' => 'Changelog',
            'academy.index' => 'Academy',
            'module.index' => 'Modules',
        ];

        foreach ($routeNames as $routeName => $label) {
            try {
                if (\Route::has($routeName)) {
                    $links[$label] = route($routeName);
                }
            } catch (\Exception $e) {
                // Route doesn't exist or is not accessible, skip it
                continue;
            }
        }

        // Add solutions if the model and table exist
        try {
            if (class_exists(Solution::class) && Solution::exists()) {
                $solutionRoute = '/solutions';
                // Try to use route if available, otherwise use path
                try {
                    if (\Route::has('solution.index')) {
                        $solutionRoute = route('solution.index');
                    }
                } catch (\Exception $e) {
                    // Use default path
                }
                $links['Solutions'] = $solutionRoute;
            }
        } catch (\Exception $e) {
            // Solutions table might not exist yet
        }

        // Add Legal Pages if they exist
        try {
            if (class_exists(Legal::class) && Legal::exists()) {
                $legalRoute = '/legal';
                try {
                    if (\Route::has('legal.index')) {
                        $legalRoute = route('legal.index');
                    }
                } catch (\Exception $e) {
                    // Use default path
                }
                $links['Legal Pages'] = $legalRoute;
            }
        } catch (\Exception $e) {
            // Legal table might not exist yet
        }

        // Add Static Pages if they exist
        try {
            if (class_exists(StaticPage::class) && StaticPage::exists()) {
                $staticRoute = '/static';
                try {
                    if (\Route::has('static.index')) {
                        $staticRoute = route('static.index');
                    }
                } catch (\Exception $e) {
                    // Use default path
                }
                $links['Static Pages'] = $staticRoute;
            }
        } catch (\Exception $e) {
            // StaticPage table might not exist yet
        }

        return $links;
    }

    public static function getSystemContent(): array
    {
        $content = [];

        // Get Pages
        try {
            if (class_exists(Page::class)) {
                $pages = Page::where('is_active', true)
                    ->select('id', 'title', 'slug')
                    ->orderBy('title')
                    ->get();

                foreach ($pages as $page) {
                    $content['Pages'][] = [
                        'id' => $page->id,
                        'title' => $page->title,
                        'url' => $page->link_url,
                        'type' => 'page',
                    ];
                }
            }
        } catch (Exception $e) {
            Log::info('Pages table not available: '.$e->getMessage());
        }

        // Get Blog Posts
        try {
            if (class_exists(Blog::class)) {
                $blogs = Blog::where('is_active', true)
                    ->select('id', 'title', 'slug')
                    ->orderBy('created_at', 'desc')
                    ->limit(20) // Limit to recent posts
                    ->get();

                foreach ($blogs as $blog) {
                    $content['Blog Posts'][] = [
                        'id' => $blog->id,
                        'title' => $blog->title,
                        'url' => $blog->link_url,
                        'type' => 'blog',
                    ];
                }
            }
        } catch (Exception $e) {
            Log::info('Blog table not available: '.$e->getMessage());
        }

        // Get Solutions
        try {
            if (class_exists(Solution::class)) {
                $solutions = Solution::where('is_active', true)
                    ->select('id', 'title', 'anchor')
                    ->orderBy('title')
                    ->get();

                foreach ($solutions as $solution) {
                    $content['Solutions'][] = [
                        'id' => $solution->id,
                        'title' => $solution->title,
                        'url' => $solution->link_url,
                        'type' => 'solution',
                    ];
                }
            }
        } catch (Exception $e) {
            Log::info('Solution table not available: '.$e->getMessage());
        }

        // Get Modules
        try {
            if (class_exists(Module::class)) {
                $modules = Module::where('is_active', true)
                    ->select('id', 'title', 'slug', 'nav_title')
                    ->orderBy('sort_order')
                    ->orderBy('title')
                    ->get();

                foreach ($modules as $module) {
                    $content['Modules'][] = [
                        'id' => $module->id,
                        'title' => $module->nav_title ?: $module->title,
                        'url' => $module->link_url,
                        'type' => 'module',
                    ];
                }
            }
        } catch (Exception $e) {
            Log::info('Module table not available: '.$e->getMessage());
        }

        // Get Legal Pages
        try {
            if (class_exists(Legal::class)) {
                $legalPages = Legal::where('is_active', true)
                    ->select('id', 'title', 'slug')
                    ->orderBy('title')
                    ->get();

                foreach ($legalPages as $legal) {
                    $content['Legal Pages'][] = [
                        'id' => $legal->id,
                        'title' => $legal->title,
                        'url' => route('legal.show', $legal->slug),
                        'type' => 'legal',
                    ];
                }
            }
        } catch (Exception $e) {
            Log::info('Legal table not available: '.$e->getMessage());
        }

        // Get Static Pages
        try {
            if (class_exists(StaticPage::class)) {
                $staticPages = StaticPage::where('is_active', true)
                    ->select('id', 'title', 'slug')
                    ->orderBy('title')
                    ->get();

                foreach ($staticPages as $staticPage) {
                    $content['Static Pages'][] = [
                        'id' => $staticPage->id,
                        'title' => $staticPage->title,
                        'url' => route('static.show', $staticPage->slug),
                        'type' => 'static_page',
                    ];
                }
            }
        } catch (Exception $e) {
            Log::info('StaticPage table not available: '.$e->getMessage());
        }

        return $content;
    }
}
