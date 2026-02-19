<?php

namespace App\View\Components\Front;

use App\Models\StickyMenuItem;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InfoCard extends Component
{
    public $items;

    public function __construct()
    {
        $this->items = StickyMenuItem::getActiveItems();
    }

    public function render(): View
    {
        return view('components.front.info-card');
    }
}
