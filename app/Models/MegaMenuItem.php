<?php

namespace App\Models;

use Exception;
use Log;

/**
 * @mixin IdeHelperMegaMenuItem
 */
class MegaMenuItem extends BaseModel
{
    protected $table = 'mega_menu_items';

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
        'footer_action_1_text',
        'footer_action_1_icon',
        'footer_action_1_url',
        'footer_action_2_text',
        'footer_action_2_icon',
        'footer_action_2_url',
        'is_active',
        'open_in_new_tab',
    ];

    protected function casts(): array
    {
        return [
            'is_mega_menu' => 'boolean',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
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
