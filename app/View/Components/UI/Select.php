<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    public string $selectId = '';
    public string $classes = '';

    public function __construct(
        public string $label = '',
        public string $name = '',
        public ?string $id = null,
        public string|int|null $value = null,
        public string $placeholder = 'Please select...',
        public string $hint = '',
        public bool $error = false,
        public string $errorMessage = '',
        public bool $required = false,
        public bool $disabled = false,
        public string $size = 'full', // xs, sm, md, lg, full
        public array $options = [], // [['id' => 1, 'label' => 'Label', 'value' => 'value']] format
        public bool $autoError = true,
    ) {
        // Normalize value
        if ($this->value === null) {
            $this->value = '';
        } else {
            $this->value = (string) $this->value;
        }

        // Size classes - Synchronized with Input
        if ($size === 'sm') {
            $classes[] = 'px-3 py-1 text-sm leading-5 tracking-wide';
        } elseif ($size === 'lg') {
            $classes[] = 'px-5 py-2.5 text-base leading-6 tracking-wide';
        } else {
            $classes[] = 'px-4 py-2 text-sm leading-5 tracking-wide';
        }
        if (!is_array($this->options)) {
            $this->options = [];
        }

        $this->classes = implode(' ', $classes);

        // Auto-detect error if autoError is enabled and name is provided
        if ($this->autoError && $this->name && !$this->error && empty($this->errorMessage)) {
            $errors = session()->get('errors');
            if ($errors && $errors->has($this->name)) {
                $this->error = true;
                $this->errorMessage = $errors->first($this->name);
            }
        }

        $this->selectId = $id ?? ($name ?: 'select-' . uniqid());
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.select');
    }
}
