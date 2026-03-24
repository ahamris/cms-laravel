@php
    $o = old('options', $element?->options ?? []);
@endphp
<div class="border-t border-gray-200 pt-4 mt-4 space-y-4">
    <h3 class="font-semibold text-gray-900">Newsletter options</h3>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email placeholder</label>
            <input type="text" name="options[email_placeholder]" value="{{ $o['email_placeholder'] ?? 'Je e-mailadres' }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.email_placeholder') border-red-500 @enderror">
            @error('options.email_placeholder')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Button text</label>
            <input type="text" name="options[button_text]" value="{{ $o['button_text'] ?? 'Aanmelden' }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.button_text') border-red-500 @enderror">
            @error('options.button_text')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Terms text</label>
        <input type="text" name="options[terms_text]" value="{{ $o['terms_text'] ?? 'Door aan te melden ga je akkoord met onze voorwaarden.' }}"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 @error('options.terms_text') border-red-500 @enderror">
        @error('options.terms_text')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
</div>
