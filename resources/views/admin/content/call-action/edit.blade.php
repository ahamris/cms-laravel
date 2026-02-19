<x-layouts.admin title="Edit Call Action">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-primary">Edit Call Action</h1>
            <p class="text-gray-600">Edit call action: {{ $callAction->title }}</p>
        </div>
        <a href="{{ route('admin.content.call-action.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.content.call-action.update', $callAction) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-zinc-50/50 rounded-lg">
                    <div class="p-3 bg-primary/10 overflow-hidden">
                        <h3 class="text-primary">Basic Information</h3>
                    </div>
                    <div class="border-2 border-gray-200 border-t-0 p-3 space-y-4 rounded-b-lg">
                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $callAction->title) }}"
                                   placeholder="Enter call action title"
                                   class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary/10 @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Content --}}
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content"
                                      name="content"
                                      rows="4"
                                      placeholder="Enter call action content"
                                      class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary/10 @error('content') border-red-500 @enderror">{{ old('content', $callAction->content) }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Section Identifier --}}
                        <div>
                            <label for="section_identifier" class="block text-sm font-medium text-gray-700 mb-1">
                                Section Identifier <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="section_identifier"
                                   name="section_identifier"
                                   value="{{ old('section_identifier', $callAction->section_identifier) }}"
                                   placeholder="e.g., homepage_main, pricing_cta"
                                   class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary/10 @error('section_identifier') border-red-500 @enderror">
                            @error('section_identifier')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Unique identifier for this call action section</p>
                        </div>
                    </div>
                </div>

                {{-- Primary Button --}}
                <div class="bg-zinc-50/50 rounded-lg">
                    <div class="p-3 bg-primary/10 overflow-hidden">
                        <h3 class="text-primary">Primary Button</h3>
                    </div>
                    <div class="border-2 border-gray-200 border-t-0 p-3 space-y-4 rounded-b-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Primary Button Text --}}
                            <div>
                                <label for="primary_button_text" class="block text-sm font-medium text-gray-700 mb-1">
                                    Button Text
                                </label>
                                <input type="text"
                                       id="primary_button_text"
                                       name="primary_button_text"
                                       value="{{ old('primary_button_text', $callAction->primary_button_text) }}"
                                       placeholder="e.g., Get Started"
                                       class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary/10 @error('primary_button_text') border-red-500 @enderror">
                                @error('primary_button_text')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Primary Button URL --}}
                            <div>
                                <label for="primary_button_url" class="block text-sm font-medium text-gray-700 mb-1">
                                    Button URL
                                </label>
                                <input type="text"
                                       id="primary_button_url"
                                       name="primary_button_url"
                                       value="{{ old('primary_button_url', $callAction->primary_button_url) }}"
                                       placeholder="/contact or https://example.com"
                                       class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary/10 @error('primary_button_url') border-red-500 @enderror">
                                @error('primary_button_url')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Primary Button External --}}
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="primary_button_external"
                                       value="1"
                                       {{ old('primary_button_external', $callAction->primary_button_external) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="ml-2 text-sm text-gray-700">Open in new tab</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Secondary Button --}}
                <div class="bg-zinc-50/50 rounded-lg">
                    <div class="p-3 bg-primary/10 overflow-hidden">
                        <h3 class="text-primary">Secondary Button</h3>
                    </div>
                    <div class="border-2 border-gray-200 border-t-0 p-3 space-y-4 rounded-b-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Secondary Button Text --}}
                            <div>
                                <label for="secondary_button_text" class="block text-sm font-medium text-gray-700 mb-1">
                                    Button Text
                                </label>
                                <input type="text"
                                       id="secondary_button_text"
                                       name="secondary_button_text"
                                       value="{{ old('secondary_button_text', $callAction->secondary_button_text) }}"
                                       placeholder="e.g., Learn More"
                                       class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary/10 @error('secondary_button_text') border-red-500 @enderror">
                                @error('secondary_button_text')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Secondary Button URL --}}
                            <div>
                                <label for="secondary_button_url" class="block text-sm font-medium text-gray-700 mb-1">
                                    Button URL
                                </label>
                                <input type="text"
                                       id="secondary_button_url"
                                       name="secondary_button_url"
                                       value="{{ old('secondary_button_url', $callAction->secondary_button_url) }}"
                                       placeholder="/about or https://example.com"
                                       class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary/10 @error('secondary_button_url') border-red-500 @enderror">
                                @error('secondary_button_url')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Secondary Button External --}}
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="secondary_button_external"
                                       value="1"
                                       {{ old('secondary_button_external', $callAction->secondary_button_external) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="ml-2 text-sm text-gray-700">Open in new tab</span>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Design Settings --}}
                <div class="bg-zinc-50/50 rounded-lg">
                    <div class="p-3 bg-primary/10 overflow-hidden">
                        <h3 class="text-primary">Design Settings</h3>
                    </div>
                    <div class="border-2 border-gray-200 border-t-0 p-3 space-y-4 rounded-b-lg">
                        {{-- Background Color --}}
                        <div>
                            <label for="background_color" class="block text-sm font-medium text-gray-700 mb-1">
                                Background Color <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="color"
                                       id="background_color"
                                       name="background_color"
                                       value="{{ old('background_color', $callAction->background_color) }}"
                                       class="w-10 h-10 cursor-pointer">
                                <input type="text"
                                       id="background_color_text"
                                       value="{{ old('background_color', $callAction->background_color) }}"
                                       class="flex-1 border border-gray-200 rounded-md px-3 py-2 text-sm focus:outline-none @error('background_color') border-red-500 @enderror"
                                       placeholder="#1e40af">
                            </div>
                            @error('background_color')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Text Color --}}
                        <div>
                            <label for="text_color" class="block text-sm font-medium text-gray-700 mb-1">
                                Text Color <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="color"
                                       id="text_color"
                                       name="text_color"
                                       value="{{ old('text_color', $callAction->text_color) }}"
                                       class="w-10 h-10 cursor-pointer">
                                <input type="text"
                                       id="text_color_text"
                                       value="{{ old('text_color', $callAction->text_color) }}"
                                       class="flex-1 border border-gray-200 rounded-md px-3 py-2 text-sm focus:outline-none @error('text_color') border-red-500 @enderror"
                                       placeholder="#ffffff">
                            </div>
                            @error('text_color')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="bg-zinc-50/50 rounded-lg">
                    <div class="p-3 bg-primary/10 overflow-hidden">
                        <h3 class="text-primary">Settings</h3>
                    </div>
                    <div class="border-2 border-gray-200 border-t-0 p-3 space-y-4 rounded-b-lg">
                        {{-- Sort Order --}}
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                                Sort Order
                            </label>
                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', $callAction->sort_order) }}"
                                   min="0"
                                   class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary/10 @error('sort_order') border-red-500 @enderror">
                            @error('sort_order')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Active Status --}}
                        <div>
                            <x-ui.toggle 
                                name="is_active"
                                :checked="old('is_active', $callAction->is_active)"
                                label="Active"
                            />
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-zinc-50/50">
                    <div class="border-2 border-gray-200 p-3">
                        <div class="space-y-3">
                            <button type="submit"
                                    class="w-full bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/80 transition-colors duration-200">
                                <i class="fa-solid fa-save mr-2"></i>
                                Update Call Action
                            </button>
                            <a href="{{ route('admin.content.call-action.index') }}"
                               class="w-full bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors duration-200 text-center block">
                                <i class="fa-solid fa-times mr-2"></i>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Color picker synchronization
(function () {
    const pairs = [
        { color: 'background_color', text: 'background_color_text' },
        { color: 'text_color', text: 'text_color_text' },
    ];

    const hexPattern = /^#([0-9a-fA-F]{3}){1,2}$/;

    pairs.forEach(({ color, text }) => {
        const colorInput = document.getElementById(color);
        const textInput = document.getElementById(text);

        if (!colorInput || !textInput) {
            return;
        }

        const syncText = () => {
            textInput.value = colorInput.value;
        };

        const syncColor = () => {
            const value = textInput.value.trim();
            if (hexPattern.test(value)) {
                colorInput.value = value;
            }
        };

        // Initial sync
        syncText();

        colorInput.addEventListener('input', syncText);
        textInput.addEventListener('input', syncColor);
        textInput.addEventListener('blur', () => {
            if (!hexPattern.test(textInput.value.trim())) {
                syncText();
            }
        });
    });
})();
</script>
</x-layouts.admin>
