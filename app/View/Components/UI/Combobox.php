<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Combobox extends Component
{
    public string $comboboxId;

    public string $buttonClasses;

    public string|array|null $value;

    public function __construct(
        public string $label = '',
        public string $name = '',
        public ?string $id = null,
        string|array|null $value = null,
        public string $placeholder = 'Select an option',
        public string $hint = '',
        public bool $error = false,
        public string $errorMessage = '',
        public bool $required = false,
        public bool $disabled = false,
        public bool $multiple = false,
        public bool $searchable = true,
        public ?string $size = null, // sm, lg
        public array $options = [], // ['value' => 'label'] or [['value' => '', 'label' => '']]
        public string $searchPlaceholder = 'Search...',
        public string $noResultsText = 'No results',
        public bool $clearable = false,
    ) {
        $this->comboboxId = $id ?? ($name ?: 'combobox-'.uniqid());

        // Normalize value for Alpine
        if ($value === null) {
            $this->value = $multiple ? [] : null;
        } elseif ($multiple && ! is_array($value)) {
            $this->value = $value === '' || $value === null ? [] : [(string) $value];
        } elseif (! $multiple && is_array($value)) {
            $this->value = isset($value[0]) ? (string) $value[0] : null;
        } else {
            $this->value = $multiple ? array_map('strval', (array) $value) : ($value === '' ? null : (string) $value);
        }

        // Button/trigger classes - match input/select
        $classes = [];
        $baseClasses = 'block w-full border rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 transition-all duration-200 border-zinc-300 dark:border-zinc-700 focus:outline-none cursor-pointer text-left';
        $classes[] = $baseClasses;

        if ($size === 'sm') {
            $classes[] = 'px-3 py-1.5 text-sm leading-5 tracking-[0.25px]';
        } elseif ($size === 'lg') {
            $classes[] = 'px-5 py-3 text-base leading-6 tracking-[0.5px]';
        } else {
            $classes[] = 'px-3 py-1.5 text-base sm:text-sm/6';
        }

        if ($error) {
            $classes[] = 'bg-red-50 dark:bg-red-900/20 border-red-500 dark:border-red-400 text-red-600 dark:text-red-400';
        } elseif ($disabled) {
            $classes[] = 'bg-zinc-100 dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 text-zinc-500 dark:text-zinc-500 cursor-not-allowed';
        }

        $this->buttonClasses = implode(' ', $classes);
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.combobox');
    }
}
