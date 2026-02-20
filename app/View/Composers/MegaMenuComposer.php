<?php

namespace App\View\Composers;

use App\Models\MegaMenuItem;
use App\Models\Setting;
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
     * Get mega menu data with caching (delegates to model; cache invalidated on create/update/delete).
     * 3-level structure - parent > children > grandchildren
     */
    public function getMegaMenuData(): array
    {
        return MegaMenuItem::getCached();
    }

    /**
     * Clear mega menu cache
     */
    public static function clearCache(): void
    {
        MegaMenuItem::clearCache();
    }
}
