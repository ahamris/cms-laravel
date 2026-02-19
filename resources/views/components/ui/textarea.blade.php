@once
    @push('styles')
        <style>
            /* Textarea focus styles using CSS variables */
            .block.w-full.border.rounded-md[class*="resize-y"]:focus,
            .block.w-full.border.rounded-md[class*="resize-none"]:focus {
                @apply outline-none ring-1;
                border-color: var(--color-accent);
                --tw-ring-color: var(--color-accent);
            }

            .dark .block.w-full.border.rounded-md[class*="resize-y"]:focus,
            .dark .block.w-full.border.rounded-md[class*="resize-none"]:focus {
                border-color: var(--color-accent-content);
                --tw-ring-color: var(--color-accent-content);
            }
        </style>
    @endpush
@endonce

<div class="space-y-1.5"
    @if($showCharacterCount)
        x-data="{ 
            count: {{ strlen($value) }},
            max: {{ $maxLength ?? 'null' }},
            updateCount(e) {
                this.count = e.target.value.length;
            }
        }"
    @endif
>
    @if($label)
        <div class="flex justify-between items-center">
            <label for="{{ $textareaId }}" class="{{ $error ? 'text-red-600 dark:text-red-400' : '' }}">
                {{ $label }}
                @if($required)
                    <span class="text-red-600 dark:text-red-400">*</span>
                @endif
            </label>
            @if($showCharacterCount)
                <span class="text-xs font-mono" :class="max && count > max ? 'text-red-500' : 'text-gray-500'">
                    <span x-text="count"></span>@if($maxLength)/{{ $maxLength }}@endif
                </span>
            @endif
        </div>
    @endif

    <textarea 
        name="{{ $name }}" 
        id="{{ $textareaId }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($rows) rows="{{ $rows }}" @endif
        @if($cols) cols="{{ $cols }}" @endif
        @if($showCharacterCount) @input="updateCount" @endif
        @if($maxLength) maxlength="{{ $maxLength }}" @endif
        class="{{ $classes }}"
        {{ $attributes->except(['class']) }}
    >{{ $value }}</textarea>

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

