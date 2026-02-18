@props([
    'name',
    'label' => '',
    'options' => [],
    'multiple' => false,
    'placeholder' => 'Selecteer een optie',
    'searchable' => true,
    'required' => false,
    'disabled' => false,
    'value' => []
])

@php
    $formattedOptions = [];
    foreach ($options as $val => $optionLabel) {
        $formattedOptions[] = [
            'value' => (string)$val,
            'label' => (string)$optionLabel
        ];
    }
@endphp

@once
    @push('styles')
        <style>
            /* Combobox theme styles using CSS variables */
            .combobox-trigger:focus {
                outline: none;
                border-color: var(--color-accent);
            }
            .dark .combobox-trigger:focus {
                border-color: var(--color-accent-content);
            }
            .combobox-option:hover {
                background-color: var(--color-accent);
                color: white;
            }
            .dark .combobox-option:hover {
                background-color: var(--color-accent-content);
            }
            .combobox-option.selected {
                background-color: color-mix(in srgb, var(--color-accent) 10%, transparent);
            }
            .dark .combobox-option.selected {
                background-color: color-mix(in srgb, var(--color-accent-content) 15%, transparent);
            }
            .combobox-check {
                color: var(--color-accent);
            }
            .dark .combobox-check {
                color: var(--color-accent-content);
            }
            .combobox-option:hover .combobox-check {
                color: white;
            }
            .combobox-search:focus {
                outline: none;
                border-color: var(--color-accent);
            }
            .dark .combobox-search:focus {
                border-color: var(--color-accent-content);
            }
        </style>
    @endpush
@endonce

<div class="w-full relative" 
    x-data="{
        open: false,
        search: '',
        selected: [],
        allOptions: @js($formattedOptions),
        isMultiple: @js($multiple),
        placeholderText: @js($placeholder),
        
        init() {
            var initial = @js($value);
            if (initial && (Array.isArray(initial) ? initial.length : initial)) {
                this.selected = Array.isArray(initial) ? initial.map(String) : [String(initial)];
            }
        },
        
        filteredList() {
            if (!this.search) return this.allOptions;
            var s = this.search.toLowerCase();
            return this.allOptions.filter(function(o) { return o.label.toLowerCase().includes(s); });
        },
        
        toggleOpen() { this.open = !this.open; },
        closeDropdown() { this.open = false; this.search = ''; },
        
        isChecked(val) { return this.selected.includes(String(val)); },
        
        toggle(val) {
            var v = String(val);
            if (this.isMultiple) {
                if (this.selected.includes(v)) {
                    this.selected = this.selected.filter(function(s) { return s !== v; });
                } else {
                    this.selected.push(v);
                }
            } else {
                this.selected = [v];
                this.closeDropdown();
            }
        },
        
        displayText() {
            if (!this.selected.length) return this.placeholderText;
            var self = this;
            var labels = this.selected.map(function(v) {
                var opt = self.allOptions.find(function(o) { return o.value === v; });
                return opt ? opt.label : v;
            });
            return labels.join(', ');
        }
    }"
    @keydown.escape.window="closeDropdown()"
>
    @if($label)
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
            {{ $label }}
            @if($required)<span class="text-red-600 dark:text-red-400">*</span>@endif
        </label>
    @endif

    <div class="relative">
        <button type="button" 
            @click="toggleOpen()" 
            class="combobox-trigger relative w-full cursor-pointer rounded-md border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 py-2 pl-3 pr-10 text-left text-zinc-900 dark:text-zinc-100 transition-colors duration-200 text-base leading-6">
            <span class="block truncate" x-text="displayText()"></span>
            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                <svg class="h-5 w-5 text-zinc-500 dark:text-zinc-400 transition-transform duration-200" :class="{ 'rotate-180': open }" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </span>
        </button>

        @if($multiple)
            <template x-for="v in selected" :key="v">
                <input type="hidden" name="{{ $name }}[]" :value="v">
            </template>
        @else
            <input type="hidden" name="{{ $name }}" x-bind:value="selected[0] || ''">
        @endif

        <div x-show="open" 
            x-cloak
            @click.outside="closeDropdown()"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 mt-1 w-full rounded-md bg-white dark:bg-zinc-800 shadow-lg border border-zinc-200 dark:border-zinc-700">
            
            @if($searchable)
                <div class="p-2 border-b border-zinc-200 dark:border-zinc-700">
                    <input type="text" 
                        x-model="search" 
                        @keydown.stop
                        placeholder="Zoeken..." 
                        class="combobox-search w-full rounded-md border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-3 py-1.5 text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-500 dark:placeholder-zinc-400 transition-colors duration-200">
                </div>
            @endif

            <ul class="max-h-56 overflow-auto py-1 text-base">
                <template x-for="opt in filteredList()" :key="opt.value">
                    <li @click="toggle(opt.value)" 
                        class="combobox-option cursor-pointer select-none relative py-2 pl-3 pr-9 text-zinc-900 dark:text-zinc-100 transition-colors duration-150"
                        :class="{ 'selected': isChecked(opt.value) }">
                        <span class="block truncate" :class="{ 'font-semibold': isChecked(opt.value) }" x-text="opt.label"></span>
                        <span x-show="isChecked(opt.value)" class="combobox-check absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </li>
                </template>
                <li x-show="filteredList().length === 0" class="py-2 pl-3 pr-9 text-zinc-500 dark:text-zinc-400 italic text-sm">
                    Geen resultaten
                </li>
            </ul>
        </div>
    </div>
</div>
