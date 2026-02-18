@php
    // Ensure variables are defined (defensive programming for server environments)
    $inputId = $inputId ?? $id ?? $name ?? 'slug';
    $classes = $classes ?? '';
    $error = $error ?? false;
    $errorMessage = $errorMessage ?? '';
    $label = $label ?? '';
    $hint = $hint ?? '';
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $readonly = $readonly ?? false;
    $placeholder = $placeholder ?? '';
    $value = $value ?? '';
    $name = $name ?? 'slug';
    $sourceField = $sourceField ?? 'title';
@endphp

<div>
    @if($label)
        <label for="{{ $inputId }}"
            class="{{ $error ? 'text-red-600 dark:text-red-400' : 'text-zinc-700 dark:text-zinc-300' }}">
            {{ $label }}
            @if($required)
                <span class="text-red-600 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    <div class="relative {{ $label ? 'mt-2' : '' }}">
        {{-- Link icon on left --}}
        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-500 dark:text-zinc-400 pointer-events-none z-10">
            <i class="fa-solid fa-link"></i>
        </div>

        <input type="text" name="{{ $name }}" id="{{ $inputId }}" value="{{ $value }}"
            placeholder="{{ $placeholder ?: __('admin.inputs.slug.placeholder') }}" @if($required) required @endif
            @if($disabled) disabled @endif @if($readonly) readonly @endif class="{{ $classes }}"
            data-source-field="{{ $sourceField }}" data-manual-edit="false"
            data-slug-url="{{ route('admin.generate-slug') }}" {{ $attributes->except(['class', 'value', 'source-field', 'label', 'hint', 'placeholder', 'required', 'disabled', 'readonly', 'error', 'error-message', 'size']) }}
            x-data="{
                sourceFieldId: {{ json_encode($sourceField) }},
                slugUrl: $el.dataset.slugUrl,
                slugInput: null,
                sourceInput: null,
                async generateSlug() {
                    if (this.slugInput.dataset.manualEdit === 'true') {
                        return;
                    }
                    
                    if (!this.sourceInput || !this.sourceInput.value) {
                        return;
                    }
                    
                    try {
                        const csrfToken = document.querySelector('meta[name=csrf-token]')?.getAttribute('content');
                        const response = await fetch(this.slugUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify({
                                text: this.sourceInput.value
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success && data.slug) {
                            this.slugInput.value = data.slug;
                        }
                    } catch (error) {
                        console.error('Error generating slug:', error);
                    }
                },
                init() {
                    this.slugInput = $el;
                    this.sourceInput = document.getElementById(this.sourceFieldId);
                    
                    if (this.sourceInput) {
                        this.sourceInput.addEventListener('blur', () => {
                            this.generateSlug();
                        });
                    }
                    
                    this.slugInput.addEventListener('input', () => {
                        this.slugInput.dataset.manualEdit = 'true';
                    });
                }
            }">
    </div>

    @if($hint && !$error)
        <div class="text-xs leading-4 tracking-[0.4px] text-zinc-600 dark:text-zinc-400 mt-1.5">{{ $hint }}</div>
    @endif

    @if($error && $errorMessage)
        <div class="text-xs leading-4 tracking-[0.4px] text-red-600 dark:text-red-400 flex items-center gap-1 mt-1.5">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errorMessage }}
        </div>
    @endif
</div>