@php
    $o = old('options', $element?->options ?? []);
@endphp
<div class="border-t border-gray-200 pt-4 mt-4 space-y-4">
    <div class="font-semibold text-gray-900">Hero section options</div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Variant key</label>
            <input type="text" name="options[variant]" value="{{ $o['variant'] ?? 'hero_generic' }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.variant') border-red-500 @enderror"
                placeholder="hero_split_image">
            @error('options.variant')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Layout key</label>
            <input type="text" name="options[layout]" value="{{ $o['layout'] ?? 'split' }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.layout') border-red-500 @enderror"
                placeholder="centered | split | stacked">
            @error('options.layout')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Eyebrow</label>
        <input type="text" name="options[eyebrow]" value="{{ $o['eyebrow'] ?? '' }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.eyebrow') border-red-500 @enderror"
            placeholder="Introducing">
        @error('options.eyebrow')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Media type</label>
            <select name="options[media_type]" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.media_type') border-red-500 @enderror">
                @php $mediaType = $o['media_type'] ?? 'image'; @endphp
                <option value="none" @selected($mediaType === 'none')>None</option>
                <option value="image" @selected($mediaType === 'image')>Image</option>
                <option value="video" @selected($mediaType === 'video')>Video</option>
            </select>
            @error('options.media_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Media URL (optional)</label>
            <input type="text" name="options[media_url]" value="{{ $o['media_url'] ?? '' }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.media_url') border-red-500 @enderror"
                placeholder="https://...">
            @error('options.media_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <x-ui.image-upload
            id="hero_section_image_file"
            name="image_file"
            label="Hero image upload"
            :required="false"
            help-text="Optional. Used when media type is image."
            :max-size="5120"
            size="small"
            :current-image="!empty($o['image_path']) ? get_image($o['image_path']) : null"
            current-image-alt="Hero image"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
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
                    placeholder="/contact">
                @error('options.primary_button_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="space-y-3">
            <div class="font-medium text-gray-800">Secondary button</div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Text</label>
                <input type="text" name="options[secondary_button_text]" value="{{ $o['secondary_button_text'] ?? '' }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.secondary_button_text') border-red-500 @enderror">
                @error('options.secondary_button_text')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                <input type="text" name="options[secondary_button_url]" value="{{ $o['secondary_button_url'] ?? '' }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.secondary_button_url') border-red-500 @enderror"
                    placeholder="/learn-more">
                @error('options.secondary_button_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Background style</label>
            <input type="text" name="options[background_style]" value="{{ $o['background_style'] ?? 'default' }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.background_style') border-red-500 @enderror"
                placeholder="default | gradient | image">
            @error('options.background_style')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Text alignment</label>
            <select name="options[text_alignment]" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.text_alignment') border-red-500 @enderror">
                @php $textAlignment = $o['text_alignment'] ?? 'left'; @endphp
                <option value="left" @selected($textAlignment === 'left')>Left</option>
                <option value="center" @selected($textAlignment === 'center')>Center</option>
                <option value="right" @selected($textAlignment === 'right')>Right</option>
            </select>
            @error('options.text_alignment')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
