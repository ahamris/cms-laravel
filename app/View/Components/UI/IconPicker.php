<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class IconPicker extends Component
{
    public string $id;
    public string $name;
    public ?string $value;
    public string $label;
    public ?string $helpText;
    public bool $required;

    public function __construct(
        string $id = 'icon',
        string $name = 'icon',
        ?string $value = null,
        string $label = 'Icon',
        ?string $helpText = 'Select a FontAwesome icon',
        bool $required = false
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->helpText = $helpText;
        $this->required = $required;
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.icon-picker');
    }
}
