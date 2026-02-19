@php
    $name = $name ?? 'combobox';
    $id = $id ?? null;
    $comboboxId = $comboboxId ?? $id ?? $name ?? 'combobox-' . uniqid();
    $options = $options ?? [];
    $multiple = $multiple ?? false;
    $placeholder = $placeholder ?? 'Select an option';
    $label = $label ?? '';
    $error = $error ?? false;
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $clearable = $clearable ?? false;
    $searchable = $searchable ?? true;
    $searchPlaceholder = $searchPlaceholder ?? 'Search...';
    $noResultsText = $noResultsText ?? 'No results';
    $hint = $hint ?? '';
    $errorMessage = $errorMessage ?? '';
    $buttonClasses = $buttonClasses ?? 'block w-full border rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 px-3 py-1.5 text-base sm:text-sm/6 border-zinc-300 dark:border-zinc-700';
    $value = $value ?? null;
    $formattedOptions = [];
    foreach ($options as $val => $optionLabel) {
        if (is_array($optionLabel) && (isset($optionLabel['value']) || isset($optionLabel['label']))) {
            $formattedOptions[] = array_merge([
                'value' => (string) ($optionLabel['value'] ?? $val),
                'label' => (string) ($optionLabel['label'] ?? $optionLabel['value'] ?? $val),
            ], array_diff_key($optionLabel, array_flip(['value', 'label'])));
        } else {
            $formattedOptions[] = [
                'value' => (string) $val,
                'label' => (string) $optionLabel,
            ];
        }
    }
@endphp

@once
    @push('styles')
        <style>
            .combobox-trigger:focus {
                @apply outline-none ring-1;
                border-color: var(--color-accent);
                --tw-ring-color: var(--color-accent);
            }
            .dark .combobox-trigger:focus {
                border-color: var(--color-accent-content);
                --tw-ring-color: var(--color-accent-content);
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

<div
    class="w-full relative"
    x-data="{
        open: false,
        search: '',
        selected: [],
        allOptions: @js($formattedOptions),
        isMultiple: @js($multiple ?? false),
        placeholderText: @js($placeholder),

        init() {
            var initial = @js($value);
            if (initial && (Array.isArray(initial) ? initial.length : initial !== null && initial !== '')) {
                this.selected = Array.isArray(initial) ? initial.map(String) : [String(initial)];
            }
            if (!this.isMultiple) {
                this.$watch('selected', () => {
                    this.$nextTick(() => {
                        var el = this.$refs.hiddenInput;
                        if (el) el.dispatchEvent(new Event('input', { bubbles: true }));
                    });
                });
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

        toggle(opt) {
            var v = String(opt.value);
            if (this.isMultiple) {
                if (this.selected.includes(v)) {
                    this.selected = this.selected.filter(function(s) { return s !== v; });
                } else {
                    this.selected.push(v);
                }
            } else {
                this.selected = [v];
                this.closeDropdown();
                this.$dispatch('combobox-selected', opt);
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
        },

        clearSelection() {
            this.selected = [];
            this.$dispatch('combobox-cleared');
        }
    }"
    @keydown.escape.window="closeDropdown()"
>
    @if($label)
        <label for="{{ $comboboxId }}-trigger" class="{{ $error ? 'text-red-600 dark:text-red-400' : '' }}">
            {{ $label }}
            @if($required)
                <span class="text-red-600 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    <div class="relative {{ $label ? 'mt-2' : '' }}">
        <button
            type="button"
            id="{{ $comboboxId }}-trigger"
            @click="toggleOpen()"
            @if($disabled) disabled @endif
            class="combobox-trigger {{ $buttonClasses }} pr-10"
            :aria-expanded="open"
            aria-haspopup="listbox"
        >
            <span class="block truncate" x-text="displayText()" :class="selected.length ? '' : 'text-zinc-500 dark:text-zinc-400'"></span>
            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-500 dark:text-zinc-400">
                <svg class="h-5 w-5 transition-transform duration-200" :class="{ 'rotate-180': open }" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </span>
        </button>
        @if($clearable)
            <button
                type="button"
                x-show="selected.length"
                x-cloak
                @click.stop="clearSelection()"
                class="absolute right-9 top-1/2 -translate-y-1/2 p-1 rounded text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors"
                title="Change organization"
            >
                <i class="fas fa-times text-xs"></i>
            </button>
        @endif

        @if($multiple)
            <template x-for="v in selected" :key="v">
                <input type="hidden" name="{{ $name }}[]" :value="v">
            </template>
        @else
            <input type="hidden" id="{{ $comboboxId }}" name="{{ $name }}" x-ref="hiddenInput" x-bind:value="selected[0] || ''">
        @endif

        <div
            x-show="open"
            x-cloak
            @click.outside="closeDropdown()"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 mt-1 w-full rounded-md bg-white dark:bg-zinc-800 shadow-lg border border-zinc-300 dark:border-zinc-700"
        >
            @if($searchable)
                <div class="p-2 border-b border-zinc-200 dark:border-zinc-700">
                    <input
                        type="text"
                        x-model="search"
                        @keydown.stop
                        placeholder="{{ $searchPlaceholder }}"
                        class="combobox-search w-full rounded-md border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 px-3 py-1.5 text-sm text-zinc-900 dark:text-zinc-100 placeholder:text-zinc-500 dark:placeholder:text-zinc-400 transition-colors duration-200"
                    >
                </div>
            @endif

            <ul class="max-h-56 overflow-auto py-1 text-base" role="listbox">
                <template x-for="opt in filteredList()" :key="opt.value">
                    <li
                        @click="toggle(opt)"
                        role="option"
                        :aria-selected="isChecked(opt.value)"
                        class="combobox-option cursor-pointer select-none relative py-2 pl-3 pr-9 text-zinc-900 dark:text-zinc-100 transition-colors duration-150"
                        :class="{ 'selected': isChecked(opt.value) }"
                    >
                        <span class="block truncate" :class="{ 'font-semibold': isChecked(opt.value) }" x-text="opt.label"></span>
                        <span x-show="isChecked(opt.value)" class="combobox-check absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </li>
                </template>
                <li x-show="filteredList().length === 0" class="py-2 pl-3 pr-9 text-zinc-500 dark:text-zinc-400 italic text-sm">
                    {{ $noResultsText }}
                </li>
            </ul>
        </div>
    </div>

    @if($hint && !$error)
        <div class="text-xs leading-4 tracking-[0.4px] text-gray-600 dark:text-gray-400 mt-1.5">{{ $hint }}</div>
    @endif

    @if($error && $errorMessage)
        <div class="text-xs leading-4 tracking-[0.4px] text-red-600 dark:text-red-400 flex items-center gap-1 mt-1.5">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errorMessage }}
        </div>
    @endif
</div>
