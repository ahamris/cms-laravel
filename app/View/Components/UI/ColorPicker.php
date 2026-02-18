<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ColorPicker extends Component
{
    public string $classes;

    public string $inputId = '';

    public array $presetColors = [];

    public function __construct(
        public string $label = '',
        public string $name = '',
        public ?string $id = null,
        public ?string $value = null,
        public string $placeholder = '#000000',
        public string $hint = '',
        public bool $error = false,
        public string $errorMessage = '',
        public bool $required = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public ?string $size = null, // sm, lg
        public bool $showPresets = true,
        public bool $showInput = true,
        public ?array $presets = null, // Custom preset colors
        public string $format = 'hex', // hex, rgb, hsl
    ) {
        // Normalize value
        if ($this->value === null) {
            $this->value = '#000000';
        }

        // Ensure presets is always an array
        if ($this->presets !== null && !is_array($this->presets)) {
            $this->presets = [];
        }
        $classes = [];

        // Base input classes
        $baseClasses = 'block w-full border rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 placeholder:text-zinc-500 dark:placeholder:text-zinc-400 transition-all duration-200 focus:outline-none';
        $classes[] = $baseClasses;

        // Size classes - Synchronized with Input
        if ($size === 'sm') {
            $classes[] = 'px-3 py-1 text-sm leading-5 tracking-wide';
        } elseif ($size === 'lg') {
            $classes[] = 'px-5 py-2.5 text-base leading-6 tracking-wide';
        } else {
            $classes[] = 'px-4 py-2 text-sm leading-5 tracking-wide';
        }

        // State classes
        if ($error) {
            $classes[] = 'border-red-500 focus:border-red-500 focus:ring-red-500/20';
        } else {
            $classes[] = 'border-zinc-300 dark:border-zinc-700 focus:border-primary-500 focus:ring-primary-500/20';
        }

        $this->classes = implode(' ', $classes);
        $this->inputId = $id ?? ($name ?: 'colorpicker-' . uniqid());

        // Default preset colors if not provided
        $this->presetColors = $presets ?? [
            '#000000',
            '#FFFFFF',
            '#FF0000',
            '#00FF00',
            '#0000FF',
            '#FFFF00',
            '#FF00FF',
            '#00FFFF',
            '#FFA500',
            '#800080',
            '#FFC0CB',
            '#A52A2A',
            '#808080',
            '#000080',
            '#008000',
        ];
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.color-picker');
    }
}
