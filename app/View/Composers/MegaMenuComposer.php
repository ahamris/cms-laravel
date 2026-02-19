<?php

namespace App\View\Composers;

use App\Models\MegaMenuItem;
use App\Models\Setting;
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
            $defaultFlyoutMenuComponentId = Setting::getValue('site_default_flyout_menu_component_id');

            $menuItems = MegaMenuItem::active()
                ->rootLevel()
                ->ordered()
                ->with(['children' => function ($query) {
                    $query->active()->ordered()->with(['children' => function ($subQuery) {
                        $subQuery->active()->ordered();
                    }]);
                }])
                ->get()
                ->map(function ($item) use ($defaultFlyoutMenuComponentId) {
                    $itemArray = $item->toArray();
                    $itemArray['flyout_menu_component_id'] = $item->flyout_menu_component_id ?? $defaultFlyoutMenuComponentId;
                    $itemArray['flyout_menu_component_name'] = null;

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
