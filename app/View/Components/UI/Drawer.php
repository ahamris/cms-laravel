<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Drawer extends Component
{
    public string $drawerId;

    public string $maxWidthClass;

    public function __construct(
        string $drawerId,
        public string $title = 'Panel',
        public ?string $description = null,
        public string $maxWidth = 'md', // sm, md, lg, xl, 2xl
        public bool $withBackdrop = true,
    ) {
        $this->drawerId = $drawerId;

        $maxWidthClasses = [
            'sm' => 'max-w-sm',
            'md' => 'max-w-md',
            'lg' => 'max-w-lg',
            'xl' => 'max-w-xl',
            '2xl' => 'max-w-2xl',
        ];

        $this->maxWidthClass = $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['md'];
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.drawer');
    }
}
