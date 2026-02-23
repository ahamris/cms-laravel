<x-layouts.admin title="Edit Legal Page">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-edit text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Edit Legal Page</h2>
                <p>Update the legal page: {{ $legal->title }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.legal.versions', $legal) }}"
               class="px-5 py-2 rounded-md bg-purple-600 text-white text-sm hover:bg-purple-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Version History ({{ $legal->getVersionsCount() }})</span>
            </a>
            <a href="{{ route('admin.legal.index') }}"
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Legal Pages</span>
            </a>
        </div>
    </div>

    {{-- Version Info Banner --}}
    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div>
                    <span class="text-blue-700 font-medium text-sm">Current Version:</span>
                    <span class="text-blue-900 ml-2 text-sm font-semibold">v{{ $legal->current_version }}</span>
                </div>
                <div>
                    <span class="text-blue-700 font-medium text-sm">Versioning:</span>
                    <span class="text-blue-900 ml-2 text-sm">{{ $legal->versioning_enabled ? 'Enabled' : 'Disabled' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <form action="{{ route('admin.legal.update', $legal) }}" method="POST" enctype="multipart/form-data" id="legalForm">
            @csrf
            @method('PUT')

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Page Details Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200">
                            <div class="p-6 space-y-6">
                                {{-- Title --}}
                                <div>
                                    <label for="title" class="block text-xs font-medium text-gray-700 mb-1">
                                        Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           id="title"
                                           name="title"
                                           value="{{ old('title', $legal->title) }}"
                                           class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('title') border-red-500 @enderror"
                                           placeholder="Enter page title"
                                           required>
                                    @error('title')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Slug --}}
                                <x-ui.input id="slug" name="slug" label="Slug" :value="old('slug', $legal->slug)"
                                    placeholder="page-slug" required
                                    slug-from="title"
                                    hint="URL-friendly version of the title. Auto-generated if left blank."
                                    :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />

                                {{-- Version Notes --}}
                                @if($legal->versioning_enabled)
                                <div>
                                    <label for="version_notes" class="block text-xs font-medium text-gray-700 mb-1">
                                        Version Notes (Optional)
                                    </label>
                                    <textarea id="version_notes"
                                              name="version_notes"
                                              rows="2"
                                              class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('version_notes') border-red-500 @enderror"
                                              placeholder="Add notes about what changed in this version...">{{ old('version_notes') }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Optional notes that will be saved with this version snapshot.</p>
                                    @error('version_notes')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                {{-- Body Content --}}
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label for="body" class="block text-xs font-medium text-gray-700">
                                            Content <span class="text-red-500">*</span>
                                        </label>
                                    </div>
                                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                                        <div class="lg:col-span-3">
                                            <x-editor
                                                id="body"
                                                name="body"
                                                :value="old('body', $legal->body)"
                                                placeholder="Enter page content..." />
                                            @error('body')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="lg:col-span-1" id="template-variables-panel">
                                            <div class="bg-gray-50 rounded-md border border-gray-200 p-4 sticky top-4">
                                                <h4 class="text-xs font-semibold text-gray-900 mb-3 flex items-center">
                                                    <i class="fa-solid fa-code mr-2 text-blue-500"></i>
                                                    Template Variables
                                                </h4>
                                                <p class="text-xs text-gray-600 mb-3">Click to insert at cursor</p>
                                                <div class="space-y-2" id="template-variables">
                                                    <button type="button" 
                                                            class="template-variable w-full text-center px-3 py-2 text-xs bg-white border border-gray-300 rounded-md hover:bg-blue-50 hover:border-blue-300 transition-colors duration-150"
                                                            data-variable="@{{first_name}}">
                                                        <code class="text-blue-600 font-mono">@{{first_name}}</code>
                                                    </button>
                                                    <button type="button" 
                                                            class="template-variable w-full text-center px-3 py-2 text-xs bg-white border border-gray-300 rounded-md hover:bg-blue-50 hover:border-blue-300 transition-colors duration-150"
                                                            data-variable="@{{last_name}}">
                                                        <code class="text-blue-600 font-mono">@{{last_name}}</code>
                                                    </button>
                                                    <button type="button" 
                                                            class="template-variable w-full text-center px-3 py-2 text-xs bg-white border border-gray-300 rounded-md hover:bg-blue-50 hover:border-blue-300 transition-colors duration-150"
                                                            data-variable="@{{email}}">
                                                        <code class="text-blue-600 font-mono">@{{email}}</code>
                                                    </button>
                                                    <button type="button" 
                                                            class="template-variable w-full text-center px-3 py-2 text-xs bg-white border border-gray-300 rounded-md hover:bg-blue-50 hover:border-blue-300 transition-colors duration-150"
                                                            data-variable="@{{company_name}}">
                                                        <code class="text-blue-600 font-mono">@{{company_name}}</code>
                                                    </button>
                                                    <button type="button" 
                                                            class="template-variable w-full text-center px-3 py-2 text-xs bg-white border border-gray-300 rounded-md hover:bg-blue-50 hover:border-blue-300 transition-colors duration-150"
                                                            data-variable="@{{phone}}">
                                                        <code class="text-blue-600 font-mono">@{{phone}}</code>
                                                    </button>
                                                    <button type="button" 
                                                            class="template-variable w-full text-center px-3 py-2 text-xs bg-white border border-gray-300 rounded-md hover:bg-blue-50 hover:border-blue-300 transition-colors duration-150"
                                                            data-variable="@{{date}}">
                                                        <code class="text-blue-600 font-mono">@{{date}}</code>
                                                    </button>
                                                    <button type="button" 
                                                            class="template-variable w-full text-center px-3 py-2 text-xs bg-white border border-gray-300 rounded-md hover:bg-blue-50 hover:border-blue-300 transition-colors duration-150"
                                                            data-variable="@{{site_name}}">
                                                        <code class="text-blue-600 font-mono">@{{site_name}}</code>
                                                    </button>
                                                    <button type="button" 
                                                            class="template-variable w-full text-center px-3 py-2 text-xs bg-white border border-gray-300 rounded-md hover:bg-blue-50 hover:border-blue-300 transition-colors duration-150"
                                                            data-variable="@{{site_url}}">
                                                        <code class="text-blue-600 font-mono">@{{site_url}}</code>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Version Info Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200">
                            <div class="p-6 space-y-4">
                                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fa-solid fa-clock-rotate-left mr-2 text-purple-500"></i>
                                    Versioning
                                </h3>

                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Current Version</label>
                                        <p class="text-sm text-gray-900 font-semibold">v{{ $legal->current_version }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Total Versions</label>
                                        <p class="text-sm text-gray-900">{{ $legal->getVersionsCount() }}</p>
                                    </div>
                                    <x-ui.toggle name="versioning_enabled" label="Enable Versioning" :checked="old('versioning_enabled', $legal->versioning_enabled)" />
                                    <a href="{{ route('admin.legal.versions', $legal) }}"
                                       class="block w-full text-center px-4 py-2 text-sm bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors duration-200">
                                        <i class="fa-solid fa-clock-rotate-left mr-2"></i>
                                        View Version History
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Status Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200">
                            <div class="p-6 space-y-4">
                                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fa-solid fa-toggle-on mr-2 text-blue-500"></i>
                                    Status
                                </h3>

                                <x-ui.toggle name="is_active" label="Active" :checked="old('is_active', $legal->is_active)" />
                            </div>
                        </div>

                        {{-- Featured Image Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fa-solid fa-image mr-2 text-orange-500"></i>
                                Featured Image
                            </h3>
                            <x-image-upload 
                                id="image"
                                name="image"
                                label=""
                                :required="false"
                                help-text="PNG, JPG, GIF, SVG up to 2MB"
                                :max-size="2048"
                                :current-image="$legal->image ? Storage::disk('public')->url($legal->image) : null"
                                :current-image-alt="$legal->title"
                            />
                        </div>

                        {{-- SEO Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200">
                            <div class="p-6 space-y-4">
                                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fa-solid fa-search mr-2 text-green-500"></i>
                                    SEO Settings
                                </h3>

                                {{-- Meta Title --}}
                                <div>
                                    <label for="meta_title" class="block text-xs font-medium text-gray-700 mb-1">
                                        Meta Title
                                    </label>
                                    <input type="text"
                                           id="meta_title"
                                           name="meta_title"
                                           value="{{ old('meta_title', $legal->meta_title) }}"
                                           class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('meta_title') border-red-500 @enderror"
                                           placeholder="SEO title">
                                    @error('meta_title')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Meta Description --}}
                                <div>
                                    <label for="meta_description" class="block text-xs font-medium text-gray-700 mb-1">
                                        Meta Description
                                    </label>
                                    <textarea id="meta_description"
                                              name="meta_description"
                                              rows="3"
                                              class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('meta_description') border-red-500 @enderror"
                                              placeholder="SEO description">{{ old('meta_description', $legal->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Keywords --}}
                                <div>
                                    <label for="keywords" class="block text-xs font-medium text-gray-700 mb-1">
                                        SEO Keywords
                                    </label>
                                    <textarea id="keywords"
                                              name="keywords"
                                              rows="3"
                                              class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('keywords') border-red-500 @enderror"
                                              placeholder="Enter keywords separated by commas">{{ old('keywords', $legal->keywords) }}</textarea>
                                    <p class="mt-1 text-xs text-gray-500">Separate keywords with commas (e.g., privacy policy, terms, legal)</p>
                                    @error('keywords')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 rounded-b-md flex items-center justify-end space-x-3">
                <a href="{{ route('admin.legal.index') }}"
                   class="px-5 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80 transition-colors duration-200">
                    Update Legal Page
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');

    document.getElementById('slug').value = slug;
});

// Template variable insertion into TipTap editor (only for legal pages)
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if template variables panel exists
    const templatePanel = document.getElementById('template-variables-panel');
    if (!templatePanel) {
        return; // No template variables panel, skip initialization
    }

    // Wait for TipTap editor to be initialized
    function initTemplateVariables() {
        // Find the TipTap editor by input ID (body-input)
        const hiddenInput = document.getElementById('input-body') || document.getElementById('body-input');
        if (!hiddenInput) {
            setTimeout(initTemplateVariables, 100);
            return;
        }

        // Get TipTap editor instance from Alpine.js component
        const editorWrapper = hiddenInput.closest('.tiptap-editor-wrapper');
        if (!editorWrapper || !editorWrapper._x_dataStack) {
            setTimeout(initTemplateVariables, 100);
            return;
        }

        const alpineData = editorWrapper._x_dataStack[0];
        if (!alpineData || !alpineData.editorInstance) {
            setTimeout(initTemplateVariables, 100);
            return;
        }

        const editor = alpineData.editorInstance;

        // Add click handlers to template variables (only within this panel)
        const templateButtons = templatePanel.querySelectorAll('.template-variable');
        templateButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const variable = this.getAttribute('data-variable');
                
                if (editor && variable) {
                    // Insert the variable at current cursor position
                    // TipTap automatically handles selection
                    editor.chain().focus().insertContent(variable).run();
                }
            });
        });
    }

    // Start initialization
    setTimeout(initTemplateVariables, 500);
});
</script>
</x-layouts.admin>
