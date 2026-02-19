<x-layouts.admin title="Create Legal Page">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-plus text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Create Legal Page</h2>
                <p>Add a new static page like Privacy Policy, Terms of Service, etc.</p>
            </div>
        </div>
        <a href="{{ route('admin.content.legal.index') }}"
           class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Legal Pages</span>
        </a>
    </div>

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <form action="{{ route('admin.content.legal.store') }}" method="POST" enctype="multipart/form-data" id="legalForm">
            @csrf

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
                                           value="{{ old('title') }}"
                                           class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('title') border-red-500 @enderror"
                                           placeholder="Enter page title"
                                           required>
                                    @error('title')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Slug --}}
                                <x-ui.input id="slug" name="slug" label="Slug" :value="old('slug')"
                                    placeholder="page-slug" required
                                    slug-from="title"
                                    hint="URL-friendly version of the title. Auto-generated if left blank."
                                    :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />

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
                                                :value="old('body')"
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

                                {{-- Call Actions Selector --}}
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                        <i class="fa-solid fa-bullhorn mr-2 text-purple-500"></i>
                                        Include Call to Actions
                                    </h3>
                                    <p class="text-xs text-gray-600 mb-4">Select which call to actions to include in this legal page.</p>

                                    <div class="space-y-3">
                                        @if($availableCallActions->count() > 0)
                                            @foreach($availableCallActions as $callAction)
                                                <label class="flex items-start p-4 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                                                    <input type="checkbox"
                                                           name="selected_call_actions[]"
                                                           value="{{ $callAction->id }}"
                                                           {{ in_array($callAction->id, old('selected_call_actions', [])) ? 'checked' : '' }}
                                                           class="mt-1 rounded border-gray-300 text-primary focus:outline-none">
                                                    <div class="ml-3 flex-1">
                                                        <div class="text-xs font-medium text-gray-900">{{ $callAction->title }}</div>
                                                        @if($callAction->content)
                                                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($callAction->content, 100) }}</div>
                                                        @endif
                                                        @if($callAction->section_identifier)
                                                            <div class="text-xs text-gray-400 mt-1">Section: {{ $callAction->section_identifier }}</div>
                                                        @endif
                                                    </div>
                                                </label>
                                            @endforeach
                                        @else
                                            <div class="text-center py-8 text-gray-500">
                                                <i class="fa-solid fa-bullhorn text-3xl text-gray-300 mb-2"></i>
                                                <p class="text-xs">No call to actions available</p>
                                                <p class="text-xs">Create some call to actions first to select them here.</p>
                                            </div>
                                        @endif
                                    </div>

                                    @error('selected_call_actions')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Status Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200">
                            <div class="p-6 space-y-4">
                                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fa-solid fa-toggle-on mr-2 text-blue-500"></i>
                                    Status
                                </h3>

<x-ui.toggle name="is_active" label="Active" :checked="old('is_active', true)" />
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
                                current-image-alt="Featured image"
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
                                           value="{{ old('meta_title') }}"
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
                                              placeholder="SEO description">{{ old('meta_description') }}</textarea>
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
                                              placeholder="Enter keywords separated by commas">{{ old('keywords') }}</textarea>
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
                <a href="{{ route('admin.content.legal.index') }}"
                   class="px-5 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit"
                        class="px-5 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80 transition-colors duration-200">
                    Create Legal Page
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
