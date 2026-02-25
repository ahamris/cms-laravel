<?php

namespace App\Http\Controllers\Admin;

use App\Models\MegaMenuItem;
use App\Models\MegaMenuSidebar;
use App\Models\Setting;
use App\View\Composers\MegaMenuComposer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MegaMenuController extends AdminBaseController
{
    use \App\Traits\HandlesNavigationLinks;

    /**
     * Display a listing of mega menu items.
     */
    public function index(): View
    {
        // Get all root level items with their children
        $menuItems = MegaMenuItem::with([
            'children' => function ($query) {
                $query->ordered();
            },
        ])
            ->rootLevel()
            ->ordered()
            ->get();

        return view('admin.settings.mega-menu.index', compact('menuItems'));
    }

    /**
     * Show the form for creating a new mega menu item.
     */
    public function create(Request $request): View
    {
        $parentId = $request->get('parent_id');
        $parent = $parentId ? MegaMenuItem::find($parentId) : null;

        // Get all root level items for parent dropdown
        $rootItems = MegaMenuItem::rootLevel()->ordered()->get();

        // Get available routes for dropdown
        $availableRoutes = MegaMenuItem::possibleMenuItems();

        // Get system content for dropdown
        $systemContent = MegaMenuItem::getSystemContent();

        $megaMenu = null; // Form is shared with edit; create has no existing model.

        return view('admin.settings.mega-menu.create', compact('parent', 'rootItems', 'availableRoutes', 'systemContent', 'megaMenu'));
    }

    /**
     * Store a newly created mega menu item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|integer|exists:mega_menu_items,id',
            'order' => 'required|integer|min:0',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|string|max:500',
            'page_id' => 'nullable|integer|exists:pages,id',
            'link_type' => 'nullable|string|in:predefined,custom,system,page',
            'icon' => 'nullable|string|max:255',
            'icon_bg_color' => 'nullable|string|max:50',
            'is_mega_menu' => 'boolean',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
            'tags' => 'nullable|string|max:500',
            'sidebar_bg_image' => 'nullable|file|image|max:8192',
        ]);

        $validated['tags'] = self::normalizeTags($request->input('tags'));

        $sidebarTitle = $request->input('sidebar_title');
        $sidebarDescription = $request->input('sidebar_description');
        $sidebarTags = self::normalizeTags($request->input('sidebar_tags'));

        // Validate max depth for new items
        if (! empty($validated['parent_id'])) {
            $parent = MegaMenuItem::find($validated['parent_id']);
            if ($parent && $this->getDepth($parent) >= 2) {
                return redirect()->back()->withErrors(['parent_id' => 'Maximum nesting depth of 3 levels reached.'])->withInput();
            }
        }

        // Process URL based on link type
        $validated = $this->processUrlByLinkType($request, $validated);

        // If parent_id is provided, this is a child item, so is_mega_menu should be false
        if (! empty($validated['parent_id'])) {
            $validated['is_mega_menu'] = false;
        }

        // Remove link_type from validated data as it's not a database field
        unset($validated['link_type']);

        $menuItem = MegaMenuItem::create($validated);

        $hasSidebarData = ($sidebarTitle !== null && $sidebarTitle !== '') || $request->hasFile('sidebar_bg_image');
        if (empty($validated['parent_id']) && $hasSidebarData) {
            $sidebarData = [
                'mega_menu_item_id' => $menuItem->id,
                'title' => $sidebarTitle ?? '',
                'description' => $sidebarDescription,
                'tags' => $sidebarTags,
            ];
            if ($request->hasFile('sidebar_bg_image')) {
                $sidebarData['bg_image'] = $request->file('sidebar_bg_image')->store('mega-menu-sidebar', 'public');
            }
            MegaMenuSidebar::create($sidebarData);
        }

        // Clear mega menu cache
        MegaMenuComposer::clearCache();

        return redirect()->route('admin.settings.mega-menu.index')
            ->with('success', 'Menu item created successfully.');
    }

    /**
     * Show the form for editing the specified mega menu item.
     */
    public function edit(MegaMenuItem $megaMenu): View
    {
        // Load children and sidebar (1-to-1 for root items) for sub-item management
        $megaMenu->load([
            'sidebar',
            'children' => function ($query) {
                $query->ordered();
            },
        ]);

        // Get all root level items for parent dropdown (excluding current item)
        $rootItems = MegaMenuItem::rootLevel()
            ->where('id', '!=', $megaMenu->id)
            ->ordered()
            ->get();

        // Get available routes for dropdown
        $availableRoutes = MegaMenuItem::possibleMenuItems();

        // Get system content for dropdown
        $systemContent = MegaMenuItem::getSystemContent();

        return view('admin.settings.mega-menu.edit', compact('megaMenu', 'rootItems', 'availableRoutes', 'systemContent'));
    }

    /**
     * Update the specified mega menu item in storage.
     */
    public function update(Request $request, MegaMenuItem $megaMenu)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|integer|exists:mega_menu_items,id',
            'order' => 'required|integer|min:0',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|string|max:500',
            'page_id' => 'nullable|integer|exists:pages,id',
            'link_type' => 'nullable|string|in:predefined,custom,system,page',
            'icon' => 'nullable|string|max:255',
            'icon_bg_color' => 'nullable|string|max:50',
            'is_mega_menu' => 'boolean',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
            'tags' => 'nullable|string|max:500',
            'sidebar_bg_image' => 'nullable|file|image|max:8192',
        ]);

        $validated['tags'] = self::normalizeTags($request->input('tags'));

        $sidebarTitle = $request->input('sidebar_title');
        $sidebarDescription = $request->input('sidebar_description');
        $sidebarTags = self::normalizeTags($request->input('sidebar_tags'));

        // Cycle Detection
        if (! empty($validated['parent_id'])) {
            if ($validated['parent_id'] == $megaMenu->id) {
                return redirect()->back()->withErrors(['parent_id' => 'An item cannot be its own parent.'])->withInput();
            }
            if ($this->isDescendant($megaMenu, $validated['parent_id'])) {
                return redirect()->back()->withErrors(['parent_id' => 'Cannot set a descendant as the parent.'])->withInput();
            }
            $parent = MegaMenuItem::find($validated['parent_id']);
            if ($parent && $this->getDepth($parent) >= 2) {
                return redirect()->back()->withErrors(['parent_id' => 'Maximum nesting depth of 3 levels reached.'])->withInput();
            }
        }

        // Process URL based on link type
        $validated = $this->processUrlByLinkType($request, $validated);

        // If parent_id is provided, this is a child item, so is_mega_menu should be false
        if (! empty($validated['parent_id'])) {
            $validated['is_mega_menu'] = false;
        }

        // Check if is_mega_menu changed to false - delete children
        if ($megaMenu->is_mega_menu && ! ($validated['is_mega_menu'] ?? false)) {
            $megaMenu->children()->delete();
        }

        // Remove link_type from validated data as it's not a database field
        unset($validated['link_type']);

        $megaMenu->update($validated);

        if ($megaMenu->parent_id === null) {
            $existingSidebar = $megaMenu->sidebar;
            $hasSidebarData = ($sidebarTitle !== null && $sidebarTitle !== '') || $request->hasFile('sidebar_bg_image') || $existingSidebar;

            if ($hasSidebarData) {
                $sidebarPayload = [
                    'title' => ($sidebarTitle !== null && $sidebarTitle !== '') ? $sidebarTitle : ($existingSidebar?->title ?? ''),
                    'description' => $sidebarDescription ?? $existingSidebar?->description,
                    'tags' => $sidebarTags ?? $existingSidebar?->tags ?? [],
                ];
                if ($request->hasFile('sidebar_bg_image')) {
                    $sidebarPayload['bg_image'] = $request->file('sidebar_bg_image')->store('mega-menu-sidebar', 'public');
                } elseif ($request->boolean('remove_sidebar_bg_image')) {
                    $sidebarPayload['bg_image'] = null;
                } elseif ($existingSidebar && $existingSidebar->getAttribute('bg_image') !== null) {
                    $sidebarPayload['bg_image'] = $existingSidebar->bg_image;
                }
                MegaMenuSidebar::updateOrCreate(
                    ['mega_menu_item_id' => $megaMenu->id],
                    $sidebarPayload
                );
            } else {
                $megaMenu->sidebar?->delete();
            }
        }

        // Clear mega menu cache
        MegaMenuComposer::clearCache();

        return redirect()->route('admin.settings.mega-menu.index')
            ->with('success', 'Menu item updated successfully.');
    }

    /**
     * Remove the specified mega menu item from storage.
     */
    public function destroy(MegaMenuItem $megaMenu)
    {
        // Children will be automatically deleted via cascade
        $megaMenu->delete();

        // Clear mega menu cache
        MegaMenuComposer::clearCache();

        return redirect()->route('admin.settings.mega-menu.index')
            ->with('success', 'Menu item deleted successfully.');
    }

    /**
     * Add a sub-item to the specified mega menu item.
     */
    public function addSubItem(Request $request, MegaMenuItem $megaMenu)
    {
        // Check depth limit
        if ($this->getDepth($megaMenu) >= 2) {
            return redirect()->route('admin.settings.mega-menu.edit', $megaMenu)
                ->with('error', 'Maximum nesting depth of 3 levels reached.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'icon_bg_color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'link_type' => 'nullable|string|in:predefined,custom,system,page',
            'tags' => 'nullable|string|max:500',
        ]);

        $validated = $this->processUrlByLinkType($request, $validated);
        unset($validated['link_type']);
        $validated['tags'] = self::normalizeTags($request->input('tags'));

        // Get the next order number for this parent
        $nextOrder = $megaMenu->children()->max('order') + 1;

        // Create the sub-item
        MegaMenuItem::create([
            'parent_id' => $megaMenu->id,
            'order' => $nextOrder,
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'url' => $validated['url'],
            'icon' => $validated['icon'] ?? null,
            'icon_bg_color' => $validated['icon_bg_color'] ?? '#3B82F6',
            'is_mega_menu' => false,
            'is_active' => $validated['is_active'] ?? true,
            'open_in_new_tab' => false,
            'tags' => $validated['tags'],
        ]);

        // Clear mega menu cache
        MegaMenuComposer::clearCache();

        return redirect()->route('admin.settings.mega-menu.edit', $megaMenu)
            ->with('success', 'Sub-item added successfully.');
    }

    /**
     * Edit a sub-item of the specified mega menu item.
     */
    public function editSubItem(MegaMenuItem $megaMenu, MegaMenuItem $subItem)
    {
        // Verify that the sub-item belongs to the parent
        if ($subItem->parent_id !== $megaMenu->id) {
            return response()->json(['error' => 'Invalid sub-item.'], 400);
        }

        // Get available routes for dropdown
        $availableRoutes = MegaMenuItem::possibleMenuItems();

        // Get system content for dropdown
        $systemContent = MegaMenuItem::getSystemContent();

        return response()->json([
            'success' => true,
            'subItem' => $subItem,
            'availableRoutes' => $availableRoutes,
            'systemContent' => $systemContent,
        ]);
    }

    /**
     * Update a sub-item of the specified mega menu item.
     */
    public function updateSubItem(Request $request, MegaMenuItem $megaMenu, MegaMenuItem $subItem)
    {
        // Verify that the sub-item belongs to the parent
        if ($subItem->parent_id !== $megaMenu->id) {
            return response()->json(['error' => 'Invalid sub-item.'], 400);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:500',
            'page_id' => 'nullable|integer|exists:pages,id',
            'link_type' => 'nullable|string|in:predefined,custom,system,page',
            'icon' => 'nullable|string|max:255',
            'icon_bg_color' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
            'tags' => 'nullable|string|max:500',
        ]);

        // Process URL based on link type
        $validated = $this->processUrlByLinkType($request, $validated);

        // Ensure this remains a child item
        $validated['parent_id'] = $megaMenu->id;
        $validated['is_mega_menu'] = false;

        // Remove link_type from validated data as it's not a database field
        unset($validated['link_type']);
        $validated['tags'] = self::normalizeTags($request->input('tags'));

        $subItem->update($validated);

        // Clear mega menu cache
        MegaMenuComposer::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Sub-item updated successfully.',
            'subItem' => $subItem->fresh(),
        ]);
    }

    /**
     * Remove a sub-item from the specified mega menu item.
     */
    public function removeSubItem(MegaMenuItem $megaMenu, MegaMenuItem $subItem)
    {
        // Verify that the sub-item belongs to the parent
        if ($subItem->parent_id !== $megaMenu->id) {
            return redirect()->route('admin.settings.mega-menu.edit', $megaMenu)
                ->with('error', 'Invalid sub-item.');
        }

        $subItem->delete();

        // Clear mega menu cache
        MegaMenuComposer::clearCache();

        return redirect()->route('admin.settings.mega-menu.edit', $megaMenu)
            ->with('success', 'Sub-item removed successfully.');
    }

    /**
     * Update the order of menu items.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:mega_menu_items,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            MegaMenuItem::where('id', $item['id'])
                ->update(['order' => $item['order']]);
        }

        // Clear mega menu cache
        MegaMenuComposer::clearCache();

        return response()->json(['success' => true]);
    }

    /**
     * Get module items for mega menu builder
     */
    public function getModuleItems(Request $request)
    {
        $availableRoutes = MegaMenuItem::possibleMenuItems();

        return response()->json([
            'routes' => $availableRoutes,
            'success' => true,
        ]);
    }

    /**
     * Update all settings at once (CTA only).
     */
    public function updateAllSettings(Request $request)
    {
        $validated = $request->validate([
            'header_cta_button_text' => 'nullable|string|max:255',
            'header_cta_button_url' => 'nullable|string|max:500',
        ]);

        $headerCtaButtonText = $validated['header_cta_button_text'] ?? 'Sign up';
        $headerCtaButtonUrl = $validated['header_cta_button_url'] ?? '#';

        Setting::setValue('header_cta_button_text', $headerCtaButtonText);
        Setting::setValue('header_cta_button_url', $headerCtaButtonUrl);

        Cache::forget('settings');
        Cache::forget('settings.header_cta_button_text');
        Cache::forget('settings.header_cta_button_url');
        MegaMenuComposer::clearCache();

        return redirect()->route('admin.settings.mega-menu.index')
            ->with('success', 'All settings updated successfully.');
    }

    /**
     * Update header CTA settings.
     */
    public function updateHeaderCtaSettings(Request $request)
    {
        $validated = $request->validate([
            'header_cta_button_text' => 'nullable|string|max:255',
            'header_cta_button_url' => 'nullable|string|max:500',
        ]);

        $buttonText = $validated['header_cta_button_text'] ?? 'Sign up';
        $buttonUrl = $validated['header_cta_button_url'] ?? '#';

        Setting::setValue('header_cta_button_text', $buttonText);
        Setting::setValue('header_cta_button_url', $buttonUrl);

        // Clear cache - setValue already clears individual cache, but clear all related caches
        Cache::forget('settings'); // Clear the main settings cache used by get_setting() helper
        Cache::forget('settings.header_cta_button_text');
        Cache::forget('settings.header_cta_button_url');
        MegaMenuComposer::clearCache();

        return redirect()->route('admin.settings.mega-menu.index')
            ->with('success', 'Header CTA settings updated successfully.');
    }

    /**
     * Normalize tags from request (comma-separated string or array) to array or null.
     */
    private static function normalizeTags($input): ?array
    {
        if ($input === null || $input === '') {
            return null;
        }
        if (is_array($input)) {
            $tags = array_values(array_filter(array_map('trim', $input)));
            return empty($tags) ? null : $tags;
        }
        $tags = array_values(array_filter(array_map('trim', explode(',', (string) $input))));
        return empty($tags) ? null : $tags;
    }

    /**
     * Recursive function to check for cycles
     */
    private function isDescendant($item, $potentialParentId)
    {
        if ($item->id == $potentialParentId) {
            return true;
        }
        foreach ($item->children as $child) {
            if ($child->id == $potentialParentId) {
                return true;
            }
            if ($this->isDescendant($child, $potentialParentId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate depth of an item (0-based)
     */
    private function getDepth($item)
    {
        $depth = 0;
        $parent = $item->parent;
        while ($parent) {
            $depth++;
            $parent = $parent->parent;
            if ($depth > 10) {
                break;
            }
        }

        return $depth;
    }
}
