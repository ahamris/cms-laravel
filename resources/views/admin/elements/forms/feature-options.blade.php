@php
    $o = old('options', $element?->options ?? []);
@endphp
<div class="border-t border-gray-200 pt-4 mt-4 space-y-4">
    <div class="font-semibold text-gray-900">Feature options</div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Section label</label>
        <input type="text" name="options[section_label]" value="{{ $o['section_label'] ?? 'Het probleem' }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.section_label') border-red-500 @enderror"
               placeholder="Het probleem">
        @error('options.section_label')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="space-y-3">
        <div class="font-medium text-gray-800">Primary button</div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Text</label>
            <input type="text" name="options[primary_button_text]" value="{{ $o['primary_button_text'] ?? '' }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.primary_button_text') border-red-500 @enderror">
            @error('options.primary_button_text')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
            <input type="text" name="options[primary_button_url]" value="{{ $o['primary_button_url'] ?? '' }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.primary_button_url') border-red-500 @enderror"
                   placeholder="#">
            @error('options.primary_button_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="space-y-3">
        <div class="font-medium text-gray-800">Right image</div>
        <div>
            <x-ui.image-upload
                id="image_file"
                name="image_file"
                label="Image upload"
                :required="false"
                help-text="Optional. JPEG, PNG, WebP, SVG up to 5MB."
                :max-size="5120"
                size="small"
                :current-image="!empty($o['image_path']) ? get_image($o['image_path']) : null"
                current-image-alt="Feature image"
            />
        </div>
    </div>
</div>
