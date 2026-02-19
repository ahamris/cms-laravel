<x-layouts.admin title="Create Content Type">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Content Type</h1>
            <p class="text-gray-600">Define a new content classification for strategic content planning</p>
        </div>
        <a href="{{ route('admin.marketing.content-type.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.marketing.content-type.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Content Type Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="e.g., Blog Artikel, Whitepaper, Case Study"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Slug --}}
                        <x-ui.input id="slug" name="slug" label="Slug" :value="old('slug')"
                            placeholder="e.g. blog-artikel"
                            slug-from="name"
                            hint="Leave empty to auto-generate from name."
                            :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Brief description of this content type and its purpose"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Visual Design --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Visual Design</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Icon --}}
                            <div>
                                <x-icon-picker
                                    id="icon"
                                    name="icon"
                                    :value="old('icon')"
                                    label="FontAwesome Icon"
                                    help-text="FontAwesome class without 'fa-solid' prefix"
                                    :required="false"
                                />
                                @error('icon')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Color --}}
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Theme Color</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color"
                                           id="color"
                                           name="color"
                                           value="{{ old('color', '#6366f1') }}"
                                           class="h-10 w-16 border border-gray-300 rounded-lg cursor-pointer">
                                    <input type="text"
                                           id="color_text"
                                           value="{{ old('color', '#6366f1') }}"
                                           placeholder="#6366f1"
                                           class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('color') border-red-500 @enderror">
                                </div>
                                @error('color')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Preview --}}
                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                            <div id="preview" class="flex items-center">
                                <div id="preview-icon" class="h-10 w-10 rounded-lg flex items-center justify-center mr-3" style="background-color: #6366f120;">
                                    <i id="preview-icon-element" class="fa-solid fa-tag" style="color: #6366f1;"></i>
                                </div>
                                <div>
                                    <div id="preview-name" class="text-sm font-medium text-gray-900">Content Type Name</div>
                                    <div class="text-sm text-gray-500">Preview of how it will appear</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Applicable Models --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Applicable Models</h3>
                        <p class="text-sm text-gray-600">Select which content models can use this content type</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="model_blog"
                                       name="applicable_models[]"
                                       value="App\Models\Blog"
                                       {{ in_array('App\Models\Blog', old('applicable_models', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <label for="model_blog" class="ml-2 block text-sm text-gray-900">
                                    <i class="fa-solid fa-newspaper mr-1"></i> Blog Articles
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="model_page"
                                       name="applicable_models[]"
                                       value="App\Models\Page"
                                       {{ in_array('App\Models\Page', old('applicable_models', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <label for="model_page" class="ml-2 block text-sm text-gray-900">
                                    <i class="fa-solid fa-file mr-1"></i> Pages
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="model_service"
                                       name="applicable_models[]"
                                       value="App\Models\Service"
                                       {{ in_array('App\Models\Service', old('applicable_models', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <label for="model_service" class="ml-2 block text-sm text-gray-900">
                                    <i class="fa-solid fa-briefcase mr-1"></i> Services
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="model_case_study"
                                       name="applicable_models[]"
                                       value="App\Models\CaseStudy"
                                       {{ in_array('App\Models\CaseStudy', old('applicable_models', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <label for="model_case_study" class="ml-2 block text-sm text-gray-900">
                                    <i class="fa-solid fa-chart-line mr-1"></i> Case Studies
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="model_help_article"
                                       name="applicable_models[]"
                                       value="App\Models\HelpArticle"
                                       {{ in_array('App\Models\HelpArticle', old('applicable_models', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <label for="model_help_article" class="ml-2 block text-sm text-gray-900">
                                    <i class="fa-solid fa-question-circle mr-1"></i> Help Articles
                                </label>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">Leave all unchecked to make this content type available for all models</p>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Active Status --}}
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                        </div>

                        {{-- Sort Order --}}
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', 0) }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <button type="submit"
                                class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                            <i class="fa-solid fa-save mr-2"></i>
                            Create Content Type
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Update preview when inputs change
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const iconInput = document.getElementById('icon');
    const colorInput = document.getElementById('color');
    const colorTextInput = document.getElementById('color_text');
    
    const previewName = document.getElementById('preview-name');
    const previewIcon = document.getElementById('preview-icon');
    const previewIconElement = document.getElementById('preview-icon-element');

    function updatePreview() {
        // Update name
        previewName.textContent = nameInput.value || 'Content Type Name';
        
        // Update icon
        const iconClass = iconInput.value || 'fa-tag';
        previewIconElement.className = `fa-solid ${iconClass}`;
        
        // Update color
        const color = colorInput.value || '#6366f1';
        previewIcon.style.backgroundColor = color + '20';
        previewIconElement.style.color = color;
        colorTextInput.value = color;
    }

    // Sync color inputs
    colorInput.addEventListener('input', function() {
        colorTextInput.value = this.value;
        updatePreview();
    });

    colorTextInput.addEventListener('input', function() {
        if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(this.value)) {
            colorInput.value = this.value;
            updatePreview();
        }
    });

    nameInput.addEventListener('input', updatePreview);
    iconInput.addEventListener('input', updatePreview);
    
    // Initial preview update
    updatePreview();
});
</script>
</x-layouts.admin>
