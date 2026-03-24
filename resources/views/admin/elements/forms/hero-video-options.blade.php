@php
    $o = old('options', $element?->options ?? []);
@endphp
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Video upload</label>
        <input type="file" name="video_file" accept="video/*"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('video_file') border-red-500 @enderror">
        @error('video_file')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror

        @if (!empty($o['video_path']))
            <p class="text-xs text-gray-500 mt-2">Current video: <code>{{ $o['video_path'] }}</code></p>
            <label class="inline-flex items-center gap-2 mt-2 text-sm text-gray-700">
                <input type="checkbox" name="remove_video_file" value="1" class="rounded border-gray-300">
                Remove current video
            </label>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="space-y-3">
            <h4 class="text-lg font-semibold text-gray-900">Primary button</h4>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Text</label>
                <input type="text" name="options[primary_button_text]" value="{{ $o['primary_button_text'] ?? '' }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.primary_button_text') border-red-500 @enderror">
                @error('options.primary_button_text')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                <input type="text" name="options[primary_button_url]" value="{{ $o['primary_button_url'] ?? '' }}"
                    placeholder="/contact"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.primary_button_url') border-red-500 @enderror">
                @error('options.primary_button_url')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="space-y-3">
            <h4 class="text-lg font-semibold text-gray-900">Secondary button</h4>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Text</label>
                <input type="text" name="options[secondary_button_text]"
                    value="{{ $o['secondary_button_text'] ?? '' }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.secondary_button_text') border-red-500 @enderror">
                @error('options.secondary_button_text')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                <input type="text" name="options[secondary_button_url]"
                    value="{{ $o['secondary_button_url'] ?? '' }}" placeholder="/aanpak"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.secondary_button_url') border-red-500 @enderror">
                @error('options.secondary_button_url')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>
