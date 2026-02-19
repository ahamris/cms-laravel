<?php

namespace App\View\Composers;

use App\Models\MegaMenuItem;
use App\Models\Setting;
use App\Models\TailwindPlus;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MegaMenuComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $megaMenuData = $this->getMegaMenuData();
        $view->with('megaMenuData', $megaMenuData);
    }

    /**
     * Get mega menu data with caching
     * 3-level structure - parent > children > grandchildren
     */
    public function getMegaMenuData(): array
    {
        return Cache::remember('mega_menu_data', 3600, function () {
            // Get default flyout menu component ID
            $defaultFlyoutMenuComponentId = Setting::getValue('site_default_flyout_menu_component_id');
            $defaultFlyoutMenuComponent = null;
            if ($defaultFlyoutMenuComponentId) {
                $defaultFlyoutMenuComponent = TailwindPlus::find($defaultFlyoutMenuComponentId);
            }
            
            // Get all root level menu items with their children and grandchildren
            $menuItems = MegaMenuItem::active()
                ->rootLevel()
                ->ordered()
                ->with(['children' => function ($query) {
                    $query->active()->ordered()->with(['children' => function ($subQuery) {
                        $subQuery->active()->ordered();
                    }]);
                }, 'flyoutMenuComponent'])
                ->get()
                ->map(function ($item) use ($defaultFlyoutMenuComponentId, $defaultFlyoutMenuComponent) {
                    $itemArray = $item->toArray();
                    // Add flyout menu component ID (use item's specific one or default)
                    $itemArray['flyout_menu_component_id'] = $item->flyout_menu_component_id ?? $defaultFlyoutMenuComponentId;
                    // Add flyout menu component raw name if exists
                    if ($item->flyoutMenuComponent) {
                        $itemArray['flyout_menu_component_name'] = $item->flyoutMenuComponent->component_name;
                    } elseif ($defaultFlyoutMenuComponent) {
                        // Use default component name if item doesn't have one
                        $itemArray['flyout_menu_component_name'] = $defaultFlyoutMenuComponent->component_name;
                    }
                    return $itemArray;
                })
                ->toArray();
            
            return $menuItems;
        });
    }

    /**
     * Clear mega menu cache
     */
    public static function clearCache(): void
    {
        Cache::forget('mega_menu_data');
    }
}
