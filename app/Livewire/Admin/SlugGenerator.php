<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Str;

class SlugGenerator extends Component
{
    public string $slug = '';
    public string $sourceValue = '';
    public string $sourceField = 'title';
    public string $name = 'slug';
    public string $label = '';
    public string $placeholder = '';
    public string $hint = '';
    public bool $required = false;
    public bool $disabled = false;
    public bool $readonly = false;
    public ?string $id = null;
    public bool $manualEdit = false; // Track if user manually edited slug

    public function mount(
        string $slug = '',
        string $sourceField = 'title',
        string $name = 'slug',
        string $label = '',
        string $placeholder = '',
        string $hint = '',
        bool $required = false,
        bool $disabled = false,
        bool $readonly = false,
        ?string $id = null
    ) {
        $this->slug = $slug;
        $this->sourceField = $sourceField;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->hint = $hint;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->readonly = $readonly;
        $this->id = $id ?? $name;
    }

    public function generateFromSource()
    {
        if (!$this->manualEdit && !empty($this->sourceValue)) {
            $this->slug = Str::slug($this->sourceValue);
        }
    }

    public function updatedSlug()
    {
        // Mark as manually edited when user types
        $this->manualEdit = true;
    }

    public function render()
    {
        return view('livewire.admin.slug-generator');
    }
}
