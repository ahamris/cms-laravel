<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\MegaMenuItem;
use App\Models\Setting;
use App\Services\TailwindPlusComponentService;
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
    public function index(TailwindPlusComponentService $componentService): View
    {
        // Get all root level items with their children
        $menuItems = MegaMenuItem::with([
            'children' => function ($query) {
                $query->ordered();
            }
        ])
            ->rootLevel()
            ->ordered()
            ->get();

        // Get header components for selection
        $headerComponents = $componentService->getHeaderComponents();
        $selectedHeaderComponentId = Setting::getValue('site_header_component_id');
        $selectedHeaderSticky = Setting::getValue('site_header_sticky', false);
        $selectedHeaderLayoutType = Setting::getValue('site_header_layout_type');
        $headerLoginLinkEnabled = Setting::getValue('site_header_login_link_enabled', true);
        $headerLoginLinkUrl = Setting::getValue('site_header_login_link_url', '#');

        // Get flyout menu components for selection
        $flyoutMenuComponents = $componentService->getFlyoutMenuComponents();
        $selectedDefaultFlyoutMenuComponentId = Setting::getValue('site_default_flyout_menu_component_id');

        return view('admin.settings.mega-menu.index', compact('menuItems', 'headerComponents', 'selectedHeaderComponentId', 'selectedHeaderSticky', 'selectedHeaderLayoutType', 'headerLoginLinkEnabled', 'headerLoginLinkUrl', 'flyoutMenuComponents', 'selectedDefaultFlyoutMenuComponentId'));
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

        return view('admin.settings.mega-menu.create', compact('parent', 'rootItems', 'availableRoutes', 'systemContent'));
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
        ]);

        // Validate max depth for new items
        if (!empty($validated['parent_id'])) {
            $parent = MegaMenuItem::find($validated['parent_id']);
            if ($parent && $this->getDepth($parent) >= 2) {
                return redirect()->back()->withErrors(['parent_id' => 'Maximum nesting depth of 3 levels reached.'])->withInput();
            }
        }

        // Process URL based on link type
        $validated = $this->processUrlByLinkType($request, $validated);

        // If parent_id is provided, this is a child item, so is_mega_menu should be false
        if (!empty($validated['parent_id'])) {
            $validated['is_mega_menu'] = false;
        }

        // Remove link_type from validated data as it's not a database field
        unset($validated['link_type']);

        $menuItem = MegaMenuItem::create($validated);

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
        // Load children for sub-item management
        $megaMenu->load([
            'children' => function ($query) {
                $query->ordered();
            }
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
            'flyout_menu_component_id' => 'nullable|integer|exists:tailwind_plus,id',
            'footer_action_1_text' => 'nullable|string|max:255',
            'footer_action_1_icon' => 'nullable|string|max:50',
            'footer_action_1_url' => 'nullable|string|max:500',
            'footer_action_2_text' => 'nullable|string|max:255',
            'footer_action_2_icon' => 'nullable|string|max:50',
            'footer_action_2_url' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
        ]);

        // Cycle Detection
        if (!empty($validated['parent_id'])) {
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
        if (!empty($validated['parent_id'])) {
            $validated['is_mega_menu'] = false;
        }

        // Check if is_mega_menu changed to false - delete children
        if ($megaMenu->is_mega_menu && !($validated['is_mega_menu'] ?? false)) {
            $megaMenu->children()->delete();
        }

        // Remove link_type from validated data as it's not a database field
        unset($validated['link_type']);

        $megaMenu->update($validated);

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
        ]);

        $validated = $this->processUrlByLinkType($request, $validated);
        unset($validated['link_type']);

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
        ]);

        // Process URL based on link type
        $validated = $this->processUrlByLinkType($request, $validated);

        // Ensure this remains a child item
        $validated['parent_id'] = $megaMenu->id;
        $validated['is_mega_menu'] = false;

        // Remove link_type from validated data as it's not a database field
        unset($validated['link_type']);

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
     * Update the selected header component.
     */
    public function updateHeaderComponent(Request $request)
    {
        $validated = $request->validate([
            'header_component_id' => 'nullable|integer|exists:tailwind_plus,id',
            'header_sticky' => 'nullable|boolean',
        ]);

        $componentId = $validated['header_component_id'] ?? null;
        $headerSticky = $request->has('header_sticky') ? true : false;

        Setting::setValue('site_header_component_id', $componentId);
        Setting::setValue('site_header_sticky', $headerSticky);

        // Clear cache
        Cache::forget('settings.site_header_component_id');
        Cache::forget('settings.site_header_sticky');
        MegaMenuComposer::clearCache();

        return redirect()->route('admin.settings.mega-menu.index')
            ->with('success', 'Header component updated successfully.');
    }

    /**
     * Update the selected default flyout menu component.
     */
    public function updateDefaultFlyoutMenuComponent(Request $request)
    {
        $validated = $request->validate([
            'default_flyout_menu_component_id' => 'nullable|integer|exists:tailwind_plus,id',
        ]);

        $componentId = $validated['default_flyout_menu_component_id'] ?? null;

        Setting::setValue('site_default_flyout_menu_component_id', $componentId);

        // Clear cache
        Cache::forget('settings.site_default_flyout_menu_component_id');
        Cache::forget('mega_menu_data');
        MegaMenuComposer::clearCache();

        return redirect()->route('admin.settings.mega-menu.index')
            ->with('success', 'Default flyout menu component updated successfully.');
    }

    /**
     * Update all settings at once (header component, sticky, and default flyout menu).
     */
    public function updateAllSettings(Request $request)
    {
        $validated = $request->validate([
            'header_component_id' => 'nullable|integer|exists:tailwind_plus,id',
            'header_sticky' => 'nullable|boolean',
            'header_layout_type' => 'nullable|string|in:,full-width,container,max-w-2xl,max-w-4xl,max-w-6xl,max-w-7xl',
            'header_login_link_enabled' => 'nullable|boolean',
            'header_login_link_url' => 'nullable|string|max:500',
            'default_flyout_menu_component_id' => 'nullable|integer|exists:tailwind_plus,id',
            'header_cta_button_text' => 'nullable|string|max:255',
            'header_cta_button_url' => 'nullable|string|max:500',
        ]);

        $componentId = $validated['header_component_id'] ?? null;
        $headerSticky = $request->has('header_sticky') ? true : false;
        $headerLayoutType = $validated['header_layout_type'] ?? null;
        $headerLoginLinkEnabled = $request->has('header_login_link_enabled') ? true : false;
        $headerLoginLinkUrl = $validated['header_login_link_url'] ?? '#';
        $defaultFlyoutMenuComponentId = $validated['default_flyout_menu_component_id'] ?? null;
        $headerCtaButtonText = $validated['header_cta_button_text'] ?? 'Sign up';
        $headerCtaButtonUrl = $validated['header_cta_button_url'] ?? '#';

        Setting::setValue('site_header_component_id', $componentId);
        Setting::setValue('site_header_sticky', $headerSticky);
        Setting::setValue('site_header_layout_type', $headerLayoutType);
        Setting::setValue('site_header_login_link_enabled', $headerLoginLinkEnabled);
        Setting::setValue('site_header_login_link_url', $headerLoginLinkUrl);
        Setting::setValue('site_default_flyout_menu_component_id', $defaultFlyoutMenuComponentId);
        Setting::setValue('header_cta_button_text', $headerCtaButtonText);
        Setting::setValue('header_cta_button_url', $headerCtaButtonUrl);

        // Clear cache
        Cache::forget('settings'); // Clear the main settings cache used by get_setting() helper
        Cache::forget('settings.site_header_component_id');
        Cache::forget('settings.site_header_sticky');
        Cache::forget('settings.site_header_layout_type');
        Cache::forget('settings.site_header_login_link_enabled');
        Cache::forget('settings.site_header_login_link_url');
        Cache::forget('settings.site_default_flyout_menu_component_id');
        Cache::forget('settings.header_cta_button_text');
        Cache::forget('settings.header_cta_button_url');
        Cache::forget('mega_menu_data');
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
        Cache::forget('mega_menu_data');
        MegaMenuComposer::clearCache();

        return redirect()->route('admin.settings.mega-menu.index')
            ->with('success', 'Header CTA settings updated successfully.');
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
            if ($depth > 10)
                break;
        }
        return $depth;
    }
}
