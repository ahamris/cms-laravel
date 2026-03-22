@php
    $currentSize = $sizeClasses[$size] ?? $sizeClasses['small'];
@endphp

<div class="bg-white dark:bg-white/5 rounded-md border border-gray-200 dark:border-white/10 p-4">
    @if(!empty($label))
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- Square Dropzone/Preview Container --}}
    <div class="flex items-start gap-4">
        {{-- Square Dropzone --}}
        <div id="{{ $id }}_dropzone" class="relative w-1/2 h-30 flex-shrink-0 border border-dashed border-gray-300 dark:border-white/10 rounded-lg transition-colors duration-200 cursor-pointer bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 flex items-center justify-center group overflow-hidden">
            <input type="file"
                   id="{{ $id }}"
                   name="{{ $name }}"
                   accept="{{ $accept }}"
                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                   style="font-size: 0;"
                   {{ $required && !$currentImage ? 'required' : '' }}
                   {{ $attributes }}>

            {{-- Current Image Preview (for edit mode) --}}
            @if($currentImage)
                <div id="{{ $id }}_current_preview" class="absolute inset-0 w-full h-full p-2 flex items-center justify-center">
                    <img src="{{ $currentImage }}"
                         alt="{{ $currentImageAlt }}"
                         class="max-w-full max-h-full object-contain rounded">
                    <div class="absolute inset-0 bg-black/0 hover:bg-black/10 rounded-lg transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                        <button type="button"
                                onclick="event.stopPropagation(); removeCurrentImage('{{ $id }}');"
                                class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg z-20">
                            <i class="fa-solid fa-times text-sm"></i>
                        </button>
                    </div>
                </div>
            @endif

            {{-- No Image Preview State --}}
            <div id="{{ $id }}_default" class="absolute inset-0 w-full h-full {{ $currentImage ? 'hidden' : '' }}">
                <div class="w-full h-full bg-gray-100 dark:bg-white/5 flex flex-col items-center justify-center rounded-lg">
                    <div class="mb-2">
                        <i class="fa-solid fa-image text-gray-400 dark:text-gray-500 text-4xl"></i>
                    </div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">No Image</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Click to upload</p>
                </div>
            </div>

            {{-- Preview State (hidden by default) --}}
            <div id="{{ $id }}_preview" class="hidden absolute inset-0 w-full h-full p-2 flex items-center justify-center">
                <img id="{{ $id }}_preview_img"
                     src=""
                     alt="Preview"
                     class="max-w-full max-h-full object-contain rounded">
                <div class="absolute inset-0 bg-black/0 hover:bg-black/30 rounded-lg transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                    <button type="button"
                            onclick="event.stopPropagation(); removeImagePreview('{{ $id }}');"
                            class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg z-20">
                        <i class="fa-solid fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Help Text --}}
        @if($helpText)
        <div class="flex-1">
            <div>
                <p class="text-sm text-[var(--color-accent)] dark:text-[var(--color-accent)] mb-1">{{ $helpText }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Click the square to upload or change the image</p>
            </div>
        </div>
        @endif
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

@push('styles')
<style>
    #{{ $id }}::file-selector-button {
        display: none !important;
    }
    #{{ $id }}::-webkit-file-upload-button {
        display: none !important;
    }

    /* Drag and drop states with theme color */
    #{{ $id }}_dropzone.drag-over {
        border-color: rgb(var(--color-accent)) !important;
        background-color: rgb(var(--color-accent) / 0.1) !important;
    }

    /* Hover state with theme color */
    #{{ $id }}_dropzone:hover {
        border-color: rgb(var(--color-accent) / 0.5) !important;
    }

    .dark #{{ $id }}_dropzone:hover {
        border-color: rgb(var(--color-accent) / 0.5) !important;
    }
</style>
@endpush

@push('scripts')
<script>
(function() {
    'use strict';

    const dropzoneId = '{{ $id }}_dropzone';
    const inputId = '{{ $id }}';
    const fileInput = document.getElementById(inputId);
    const dropzone = document.getElementById(dropzoneId);
    const defaultState = document.getElementById(inputId + '_default');
    const previewState = document.getElementById(inputId + '_preview');
    const previewImg = document.getElementById(inputId + '_preview_img');
    const previewName = document.getElementById(inputId + '_preview_name');
    const currentPreview = document.getElementById(inputId + '_current_preview');
    const maxSize = {{ $maxSize }} * 1024; // Convert KB to bytes

    if (!fileInput || !dropzone) return;

    // File input change
    fileInput.addEventListener('change', function(e) {
        handleFileSelect(e.target.files[0]);
    });

    // Drag and drop
    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.add('drag-over');
    });

    dropzone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.remove('drag-over');
    });

    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.remove('drag-over');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelect(files[0]);
        }
    });

    function handleFileSelect(file) {
        if (!file) return;

        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file.');
            fileInput.value = '';
            return;
        }

        // Validate file size
        if (file.size > maxSize) {
            alert('File size exceeds {{ $maxSize }}KB limit.');
            fileInput.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            if (previewImg) previewImg.src = e.target.result;
            if (previewName) previewName.textContent = file.name;
            if (defaultState) defaultState.classList.add('hidden');
            if (previewState) previewState.classList.remove('hidden');

            // Hide current image preview if exists
            if (currentPreview) {
                currentPreview.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    }

    // Make removeImagePreview available globally
    window['removeImagePreview_' + inputId] = function() {
        fileInput.value = '';
        defaultState.classList.remove('hidden');
        previewState.classList.add('hidden');
        if (previewImg) previewImg.src = '';
        if (previewName) previewName.textContent = '';
    };

    // Make removeCurrentImage available globally
    window['removeCurrentImage_' + inputId] = function() {
        if (confirm('Are you sure you want to remove the current image?')) {
            if (currentPreview) {
                currentPreview.classList.add('hidden');
            }
            // Show dropzone default state if no preview
            if (previewState.classList.contains('hidden')) {
                defaultState.classList.remove('hidden');
            }
            // Add a hidden input to indicate removal
            const removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.name = 'remove_' + inputId;
            removeInput.value = '1';
            fileInput.parentElement.appendChild(removeInput);
        }
    };

    // Shorthand aliases for easier access
    window.removeImagePreview = function(id) {
        if (window['removeImagePreview_' + id]) {
            window['removeImagePreview_' + id]();
        }
    };

    window.removeCurrentImage = function(id) {
        if (window['removeCurrentImage_' + id]) {
            window['removeCurrentImage_' + id]();
        }
    };
})();
</script>
@endpush


