@props([
    'label' => '',
    'name' => '',
    'id' => null,
    'value' => null,
    'placeholder' => 'Please select...',
    'hint' => '',
    'error' => false,
    'errorMessage' => '',
    'required' => false,
    'disabled' => false,
    'size' => 'full',
    'options' => [],
])

@php
    $selectId = $id ?? ($name ?: 'select-' . uniqid());
    $selectedValue = old($name, $value);
    $optionsJson = json_encode($options);
@endphp

<div
    x-data="{
        open: false,
        selectedValue: '{{ $selectedValue }}',
        label: '{{ $label }}',
        placeholderText: '{{ $placeholder }}',
        options: {{ $optionsJson }},
        selectedOption: null,
        keyboardTimeout: false,

        init() {
            this.setSelectedByValue(this.selectedValue, false);
        },

        openMenu() {
            if ({{ $disabled ? 'true' : 'false' }}) return;
            this.open = true;

            $nextTick(() => {
                let selectedEl = this.$refs.selectMenu?.querySelector('li[data-selected=true]');
                if (selectedEl) {
                    selectedEl.focus();
                } else {
                    this.$refs.selectMenu?.querySelector('li')?.focus();
                }
            });
        },

        closeMenu() {
            this.open = false;
            $nextTick(() => { this.$refs.selectMenuButton?.focus(); });
        },

        setSelected(option, closeMenu = true) {
            this.selectedValue = option.value;
            this.selectedOption = option;
            this.$refs.hiddenInput.value = option.value;
            
            // Notify Livewire of the change
            this.$refs.hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
            
            if (closeMenu) {
                this.closeMenu();
            }
        },

        setSelectedByValue(value, closeMenu = true) {
            if (value) {
                const option = this.options.find(o => o.value == value);
                if (option) {
                    this.selectedOption = option;
                    this.selectedValue = option.value;
                }
            }
        },

        isSelected(option) {
            return option.value == this.selectedValue;
        },

        keyboardNavigation(e) {
            clearTimeout(this.keyboardTimeout);
            this.keyboardTimeout = setTimeout(() => { 
                if (e.key.toUpperCase().match(/^[A-Z]$/)) {
                    let elements = this.$refs.selectMenu?.querySelectorAll('li[data-label^=' + e.key.toUpperCase() + ']');
                    if (elements?.length) {
                        elements[0].focus();
                    }
                }
            }, 50);
        }
    }"
    class="relative w-full"
    x-bind:class="{
        'w-48': '{{ $size }}' === 'xs',
        'w-56': '{{ $size }}' === 'sm',
        'w-64': '{{ $size }}' === 'md',
        'w-72': '{{ $size }}' === 'lg',
        'w-full': '{{ $size }}' === 'full'
    }"
>
    <!-- Hidden Input for Form Submission -->
    <input 
        type="hidden" 
        name="{{ $name }}" 
        x-ref="hiddenInput"
        value="{{ $selectedValue }}"
        {{ $attributes->whereStartsWith('wire:model') }}
    />

    <!-- Select Menu Toggle -->
    <div class="space-y-1">
        @if($label)
            <label
                x-on:click="$refs.selectMenuButton.focus()"
                class="inline-block text-sm font-medium text-zinc-700 dark:text-zinc-300 {{ $error ? 'text-red-600 dark:text-red-400' : '' }}"
            >
                {{ $label }}
                @if($required)
                    <span class="text-red-600 dark:text-red-400">*</span>
                @endif
            </label>
        @endif
        <button
            x-on:click="openMenu()"
            x-on:keydown.down.prevent.stop="openMenu()"
            x-on:keydown.up.prevent.stop="openMenu()"
            x-bind:aria-expanded="open"
            id="{{ $selectId }}-button"
            type="button"
            class="group flex w-full items-center justify-between gap-2 rounded-lg border px-3 py-2.5 text-start text-sm/6 focus:ring-2 focus:outline-none transition-colors
                {{ $error 
                    ? 'border-red-500 dark:border-red-400 bg-red-50 dark:bg-red-900/20 focus:ring-red-500/50' 
                    : ($disabled 
                        ? 'border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 cursor-not-allowed opacity-60' 
                        : 'border-zinc-200 dark:border-zinc-600 bg-white dark:bg-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-500 focus:border-[var(--color-accent)] focus:ring-[var(--color-accent)]/50') }}"
            x-ref="selectMenuButton"
            aria-haspopup="listbox"
            aria-controls="{{ $selectId }}-list"
            {{ $disabled ? 'disabled' : '' }}
        >
            <span
                x-text="selectedOption ? selectedOption.label : placeholderText"
                x-bind:class="{
                    'text-zinc-500 dark:text-zinc-400': !selectedOption
                }"
                class="grow truncate text-zinc-900 dark:text-zinc-100"
            ></span>
            <svg
                class="size-5 flex-none opacity-50 transition group-hover:opacity-70"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                aria-hidden="true"
            >
                <path
                    fill-rule="evenodd"
                    d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                    clip-rule="evenodd"
                />
            </svg>
        </button>
    </div>

    <!-- Select Menu Container -->
    <ul
        x-cloak
        x-ref="selectMenu"
        x-show="open"
        x-trap="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        x-on:click.outside="closeMenu()"
        x-on:keydown="keyboardNavigation($event)"
        x-on:keydown.esc.prevent.stop="closeMenu()"
        x-on:keydown.up.prevent.stop="$focus.previous()"
        x-on:keydown.down.prevent.stop="$focus.next()"
        x-on:keydown.home.prevent.stop="$focus.first()"
        x-on:keydown.end.prevent.stop="$focus.last()"
        id="{{ $selectId }}-list"
        class="absolute inset-x-0 z-50 mt-2 max-h-60 origin-top overflow-y-auto rounded-lg bg-white py-2 shadow-lg ring-1 ring-black/5 focus:outline-none dark:bg-zinc-800 dark:ring-zinc-700"
        role="listbox"
        tabindex="0"
    >
        <template x-for="option in options" :key="option.value">
            <li
                x-on:click="setSelected(option)"
                x-on:keydown.enter.prevent.stop="setSelected(option)"
                x-on:keydown.space.prevent.stop="setSelected(option)"
                x-bind:class="{
                    'font-semibold text-zinc-950 bg-zinc-50 dark:text-white dark:bg-zinc-700/50': isSelected(option),
                    'text-zinc-700 hover:text-zinc-950 hover:bg-zinc-50 focus:text-zinc-950 focus:bg-zinc-50 dark:text-zinc-300 dark:hover:text-white dark:hover:bg-zinc-700/50 dark:focus:text-white dark:focus:bg-zinc-700/50': !isSelected(option),
                }"
                x-bind:data-selected="isSelected(option)"
                x-bind:data-label="option.label"
                x-bind:data-value="option.value"
                x-bind:aria-selected="isSelected(option)"
                x-bind:title="option.label"
                class="group flex cursor-pointer items-center justify-between gap-2 px-3 py-2 text-sm focus:outline-none"
                role="option"
                tabindex="-1"
            >
                <span x-text="option.label" class="grow truncate"></span>
                <svg
                    x-cloak
                    x-show="isSelected(option)"
                    class="size-5 flex-none text-[var(--color-accent)]"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                        clip-rule="evenodd"
                    />
                </svg>
            </li>
        </template>
    </ul>

    <!-- Hint & Error -->
    @if($hint && !$error && !$errorMessage)
        <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1.5">{{ $hint }}</div>
    @endif

    @if($error && $errorMessage)
        <div class="text-xs text-red-600 dark:text-red-400 flex items-center gap-1 mt-1.5">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errorMessage }}
        </div>
    @endif
</div>