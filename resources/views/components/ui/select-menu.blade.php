@props([
    'name' => '',
    'id' => null,
    'label' => '',
    'value' => null,
    'placeholder' => 'Select...',
    'options' => [],
    'size' => 'full',
    'closeOnSelection' => true,
    'required' => false,
    'disabled' => false,
    'hint' => '',
    'error' => false,
    'errorMessage' => '',
])

@php
    $uniqueId = $id ?? 'select-menu-' . uniqid();
    
    // Convert simple array to options format if needed
    $formattedOptions = [];
    $selectedId = 0;
    $optCounter = 1;
    
    foreach ($options as $key => $opt) {
        if (is_array($opt) && isset($opt['id'])) {
            $formattedOptions[] = $opt;
            if ((string)$opt['value'] === (string)$value) {
                $selectedId = $opt['id'];
            }
        } else {
            $optId = $optCounter++;
            // For associative arrays (key => label format), use the key as value
            // This handles both string keys ('status_1') and integer keys (1, 2, 3)
            $optValue = $key;
            $formattedOptions[] = [
                'id' => $optId,
                'label' => $opt,
                'value' => $optValue,
            ];
            if ($value !== null && (string)$optValue === (string)$value) {
                $selectedId = $optId;
            }
        }
    }
@endphp

<div class="space-y-1">
    <div
        x-data="{
            open: false,
            selectedId: {{ $selectedId }},
            label: '{{ $label }}',
            placeholderText: '{{ $placeholder }}',
            closeOnSelection: {{ $closeOnSelection ? 'true' : 'false' }},
            size: '{{ $size }}',
            disabled: {{ $disabled ? 'true' : 'false' }},
            options: {{ json_encode($formattedOptions) }},
            selectedOption: null,
            keyboardTimeout: false,

            init() {
                this.setSelected(this.selectedId, false);
                
                // Ensure hidden select has correct initial value
                if (this.$refs.hiddenSelect && this.selectedOption) {
                    this.$refs.hiddenSelect.value = String(this.selectedOption.value);
                }
                
                if (this.open) {
                    this.openMenu();
                }
            },

            openMenu() {
                if (this.disabled) return;
                this.open = true;

                $nextTick(() => {
                    let selectedEl = this.$refs.selectMenu?.querySelector('li[data-selected]');
                    
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

            setSelected(id, closeMenu = this.closeOnSelection) {
                this.selectedId = id;
                this.selectedOption = this.getSelected();
                
                // Update hidden select value directly
                if (this.$refs.hiddenSelect) {
                    if (this.selectedOption) {
                        this.$refs.hiddenSelect.value = String(this.selectedOption.value);
                    } else {
                        this.$refs.hiddenSelect.value = '';
                    }
                }
                
                if (closeMenu) {
                    this.closeMenu();
                }
            },

            getSelected() {
                return this.selectedId !== 0 ? this.options.find(opt => opt.id === this.selectedId) || null : null;
            },

            isSelected(id) {
                return id === this.selectedOption?.id || false;
            },
            
            keyboardNavigation(e) {
                clearTimeout(this.keyboardTimeout);

                this.keyboardTimeout = setTimeout(() => { 
                    if (e.key.toUpperCase().match(/^[A-Z]$/)) {
                        let elements = this.$refs.selectMenu?.querySelectorAll('li[data-label^=' + e.key.toUpperCase() + ']');
                        if (!elements || elements.length === 0) return;
                        
                        let focusedEl, focusedIndex;

                        elements.forEach((el, index) => {
                            if (document.activeElement === el) {
                                focusedEl = el;
                                focusedIndex = index;
                            }
                        });

                        if (focusedEl) {
                            if ((elements.length - 1) === focusedIndex) {
                                elements[0].focus();
                            } else {
                                elements[focusedIndex + 1].focus();
                            }
                        } else {
                            elements[0].focus();
                        }
                    }
                }, 50);
            },
            
            focusPrevious() {
                const items = this.$refs.selectMenu?.querySelectorAll('li');
                const current = Array.from(items || []).indexOf(document.activeElement);
                if (current > 0) items[current - 1].focus();
                else if (items?.length) items[items.length - 1].focus();
            },
            
            focusNext() {
                const items = this.$refs.selectMenu?.querySelectorAll('li');
                const current = Array.from(items || []).indexOf(document.activeElement);
                if (current < items.length - 1) items[current + 1].focus();
                else if (items?.length) items[0].focus();
            },
            
            focusFirst() {
                this.$refs.selectMenu?.querySelector('li')?.focus();
            },
            
            focusLast() {
                const items = this.$refs.selectMenu?.querySelectorAll('li');
                if (items?.length) items[items.length - 1].focus();
            }
        }"
        class="relative w-full"
    >
        {{-- Hidden native select for form submission --}}
        <select
            id="{{ $uniqueId }}"
            name="{{ $name }}"
            x-ref="hiddenSelect"
            class="pointer-events-none absolute start-0 top-0 appearance-none opacity-0"
            tabindex="-1"
            aria-hidden="true"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
        >
            <option value=""></option>
            @foreach($formattedOptions as $option)
                <option value="{{ $option['value'] }}" {{ (string)$option['value'] === (string)$value ? 'selected' : '' }}>
                    {{ $option['label'] }}
                </option>
            @endforeach
        </select>

        {{-- Select Menu Toggle --}}
        <div class="space-y-1.5">
            @if($label)
                <label
                    x-on:click="$refs.selectMenuButton?.focus()"
                    class="inline-block text-sm font-medium text-zinc-700 dark:text-zinc-300"
                >
                    {{ $label }}
                    @if($required)
                        <span class="text-red-500">*</span>
                    @endif
                </label>
            @endif
            
            <button
                x-on:click="openMenu()"
                x-on:keydown.down.prevent.stop="openMenu()"
                x-on:keydown.up.prevent.stop="openMenu()"
                x-bind:aria-expanded="open"
                type="button"
                class="group flex w-full items-center justify-between gap-2 rounded-md border px-4 py-2 text-start text-sm leading-5 tracking-wide transition-colors
                    {{ $error 
                        ? 'border-red-500 focus:border-red-500 focus:ring-3 focus:ring-red-500/20' 
                        : 'border-zinc-200 dark:border-zinc-600 focus:border-zinc-500 focus:ring-3 focus:ring-zinc-500/50' 
                    }}
                    {{ $disabled 
                        ? 'bg-zinc-100 dark:bg-zinc-800 cursor-not-allowed opacity-60' 
                        : 'bg-white dark:bg-zinc-800/50 cursor-pointer hover:border-zinc-300 dark:hover:border-zinc-500' 
                    }}
                    focus:outline-hidden"
                x-ref="selectMenuButton"
                aria-haspopup="listbox"
                :aria-controls="'{{ $uniqueId }}-list'"
                :disabled="disabled"
            >
                <span
                    x-text="selectedOption ? selectedOption.label : placeholderText"
                    x-bind:class="{
                        'text-zinc-500 dark:text-zinc-400': !selectedOption,
                        'text-zinc-900 dark:text-white': selectedOption
                    }"
                    class="grow truncate"
                ></span>
                <svg
                    class="size-5 flex-none opacity-40 transition group-hover:opacity-60"
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

        {{-- Select Menu Dropdown --}}
        <ul
            x-cloak
            x-ref="selectMenu"
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            x-on:click.outside="closeMenu()"
            x-on:keydown="keyboardNavigation($event)"
            x-on:keydown.esc.prevent.stop="closeMenu()"
            x-on:keydown.up.prevent.stop="focusPrevious()"
            x-on:keydown.down.prevent.stop="focusNext()"
            x-on:keydown.home.prevent.stop="focusFirst()"
            x-on:keydown.end.prevent.stop="focusLast()"
            x-on:keydown.page-up.prevent.stop="focusFirst()"
            x-on:keydown.page-down.prevent.stop="focusLast()"
            :id="'{{ $uniqueId }}-list'"
            class="absolute inset-x-0 z-50 mt-1.5 max-h-60 origin-top overflow-y-auto rounded-lg bg-white dark:bg-zinc-800 py-1.5 ring-1 shadow-lg ring-zinc-200 dark:ring-zinc-700 focus:outline-hidden"
            aria-labelledby="{{ $uniqueId }}-button"
            aria-orientation="vertical"
            role="listbox"
            tabindex="0"
        >
            <template x-for="option in options" :key="option.id">
                <li
                    x-on:click="setSelected(option.id)"
                    x-on:keydown.enter.prevent.stop="setSelected(option.id)"
                    x-on:keydown.space.prevent.stop="setSelected(option.id)"
                    x-bind:class="{
                        'font-semibold text-zinc-950 bg-zinc-50 dark:font-medium dark:text-white dark:bg-zinc-700/50': isSelected(option.id),
                        'text-zinc-600 hover:text-zinc-950 hover:bg-zinc-50 focus:text-zinc-950 focus:bg-zinc-50 dark:text-zinc-300 dark:hover:text-white dark:hover:bg-zinc-700/50 dark:focus:text-white dark:focus:bg-zinc-700/50': !isSelected(option.id),
                    }"
                    x-bind:data-selected="isSelected(option.id) ? true : null"
                    x-bind:data-label="option.label"
                    x-bind:data-value="option.value"
                    x-bind:aria-selected="isSelected(option.id)"
                    x-bind:title="option.label"
                    class="group flex cursor-pointer items-center justify-between gap-2 px-3 py-2 text-sm focus:outline-hidden"
                    role="option"
                    tabindex="-1"
                >
                    <span x-text="option.label" class="grow truncate"></span>
                    <svg
                        x-cloak
                        x-show="isSelected(option.id)"
                        class="size-5 flex-none text-primary dark:text-primary-400"
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
    </div>
    
    {{-- Hint text --}}
    @if($hint && !$error)
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ $hint }}</p>
    @endif
    
    {{-- Error message --}}
    @if($error && $errorMessage)
        <p class="text-xs text-red-500 dark:text-red-400 mt-1">{{ $errorMessage }}</p>
    @endif
</div>
