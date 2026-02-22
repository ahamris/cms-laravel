<?php

namespace App\View\Components\UI;

use App\Models\MegaMenuItem;
use App\Models\Page;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\Component;

class UrlSelector extends Component
{
    public function __construct(
        public string $name,
        public string $value = '',
        public string $id = '',
        public string $label = 'URL'
    ) {
        if ($id === '') {
            $this->id = 'url_'.str_replace(['[', ']'], '_', $name);
        }
    }

    public function getAvailableRoutes(): array
    {
        $shared = ViewFacade::getShared();
        if (array_key_exists('urlSelectorAvailableRoutes', $shared)) {
            return ViewFacade::shared('urlSelectorAvailableRoutes');
        }
        return class_exists(MegaMenuItem::class) ? MegaMenuItem::possibleMenuItems() : [];
    }

    public function getSystemContent(): array
    {
        $shared = ViewFacade::getShared();
        if (array_key_exists('urlSelectorSystemContent', $shared)) {
            return ViewFacade::shared('urlSelectorSystemContent');
        }
        return class_exists(MegaMenuItem::class) ? MegaMenuItem::getSystemContent() : [];
    }

    public function getPages(): array
    {
        $shared = ViewFacade::getShared();
        if (array_key_exists('urlSelectorPages', $shared)) {
            return ViewFacade::shared('urlSelectorPages');
        }
        if (! class_exists(Page::class)) {
            return [];
        }
        return Page::where('is_active', true)
            ->select('id', 'title', 'slug')
            ->orderBy('title')
            ->get()
            ->map(fn ($p) => ['id' => $p->id, 'title' => $p->title, 'slug' => $p->slug, 'url' => $p->link_url])
            ->values()
            ->all();
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.url-selector', [
            'availableRoutes' => $this->getAvailableRoutes(),
            'systemContent' => $this->getSystemContent(),
            'pages' => $this->getPages(),
        ]);
    }
}
