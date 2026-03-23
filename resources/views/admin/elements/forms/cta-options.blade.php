@php
    $o = old('options', $element?->options ?? []);
@endphp
<div class="border-t border-gray-200 pt-4 mt-4 space-y-4">
    <h3 class="text-lg font-semibold text-gray-900">Call to action</h3>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Button text</label>
            <input type="text" name="options[button_text]" value="{{ $o['button_text'] ?? '' }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.button_text') border-red-500 @enderror">
            @error('options.button_text')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Button URL</label>
            <input type="text" name="options[button_url]" value="{{ $o['button_url'] ?? '' }}" placeholder="/contact"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.button_url') border-red-500 @enderror">
            @error('options.button_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Button style</label>
            <select name="options[button_style]" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.button_style') border-red-500 @enderror">
                @foreach(['primary' => 'Primary', 'secondary' => 'Secondary'] as $val => $label)
                    <option value="{{ $val }}" @selected(($o['button_style'] ?? 'primary') === $val)>{{ $label }}</option>
                @endforeach
            </select>
            @error('options.button_style')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Background</label>
            <select name="options[background]" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.background') border-red-500 @enderror">
                @foreach(['gradient' => 'Gradient', 'light' => 'Light', 'dark' => 'Dark', 'none' => 'None'] as $val => $label)
                    <option value="{{ $val }}" @selected(($o['background'] ?? 'gradient') === $val)>{{ $label }}</option>
                @endforeach
            </select>
            @error('options.background')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alignment</label>
            <select name="options[alignment]" class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.alignment') border-red-500 @enderror">
                @foreach(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'] as $val => $label)
                    <option value="{{ $val }}" @selected(($o['alignment'] ?? 'center') === $val)>{{ $label }}</option>
                @endforeach
            </select>
            @error('options.alignment')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
