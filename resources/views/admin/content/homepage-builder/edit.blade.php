<x-layouts.admin title="Edit Widget">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Widget</h1>
            <p class="text-gray-600">Update widget: {{ $widget->title ?: 'Untitled Widget' }}</p>
        </div>
        <a href="{{ route('admin.content.page-builder.manage', ['pageType' => $widget->section_identifier ?? 'homepage']) }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to {{ ucfirst($widget->section_identifier ?? 'homepage') }} Builder
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form action="{{ route('admin.content.homepage-builder.update', $widget) }}" method="POST" enctype="multipart/form-data" id="widgetForm" x-data="urlSelector()">
            @csrf
            @method('PUT')

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Basic Information Card --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 space-y-6">
                                <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Section Identifier --}}
                                    <div>
                                        <label for="section_identifier" class="block text-sm font-medium text-gray-700 mb-2">
                                            Section <span class="text-red-500">*</span>
                                        </label>
                                        <select id="section_identifier"
                                                name="section_identifier"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('section_identifier') border-red-500 @enderror"
                                                required>
                                            <option value="">Select a section</option>
                                            @foreach($sections as $key => $label)
                                                <option value="{{ $key }}" {{ old('section_identifier', $widget->section_identifier) == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('section_identifier')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Template --}}
                                    <div>
                                        <label for="template" class="block text-sm font-medium text-gray-700 mb-2">
                                            Template <span class="text-red-500">*</span>
                                        </label>
                                        <select id="template"
                                                name="template"
                                                x-model="selectedTemplate"
                                                @change="loadTemplateParameters()"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('template') border-red-500 @enderror"
                                                required>
                                            <option value="">Select a template</option>
                                            @foreach($templates as $key => $label)
                                                <option value="{{ $key }}" {{ old('template', $widget->template) == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('template')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Template Parameters --}}
                                    <div x-show="parameterLabel" x-transition>
                                        <label for="template_parameter_select" class="block text-sm font-medium text-gray-700 mb-2">
                                            <span x-text="parameterLabel"></span> <span class="text-red-500">*</span>
                                        </label>
                                        <select id="template_parameter_select"
                                                x-model="selectedParameter"
                                                @change="updateParameterFields()"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('template_parameter') border-red-500 @enderror @error('template_parameter_id') border-red-500 @enderror"
                                                :disabled="templateParameters.length === 0">
                                            <option value="" x-text="templateParameters.length > 0 ? 'Select an option' : 'No options available'"></option>
                                            <template x-for="option in templateParameters" :key="option.value">
                                                <option :value="option.value" x-text="option.label"></option>
                                            </template>
                                        </select>
                                        <p x-show="templateParameters.length === 0" class="mt-1 text-sm text-gray-500">
                                            No active forms available. Please create a form in Form Builder first.
                                        </p>
                                        {{-- Hidden fields for submission --}}
                                        <input type="hidden" name="template_parameter" x-model="templateParameterString">
                                        <input type="hidden" name="template_parameter_id" x-model="templateParameterId">
                                        @error('template_parameter')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        @error('template_parameter_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Title --}}
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Title
                                    </label>
                                    <input type="text"
                                           id="title"
                                           name="title"
                                           value="{{ old('title', $widget->title) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('title') border-red-500 @enderror"
                                           placeholder="Enter widget title">
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Subtitle --}}
                                <div>
                                    <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                                        Subtitle
                                    </label>
                                    <input type="text"
                                           id="subtitle"
                                           name="subtitle"
                                           value="{{ old('subtitle', $widget->subtitle) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('subtitle') border-red-500 @enderror"
                                           placeholder="Enter widget subtitle">
                                    @error('subtitle')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Content --}}
                                <div>
                                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                        Content
                                    </label>
                                    <textarea id="content"
                                              name="content"
                                              rows="6"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('content') border-red-500 @enderror"
                                              placeholder="Enter widget content">{{ old('content', $widget->content) }}</textarea>
                                    @error('content')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Button Settings --}}
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">
                                            Button Text
                                        </label>
                                        <input type="text"
                                               id="button_text"
                                               name="button_text"
                                               value="{{ old('button_text', $widget->button_text) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('button_text') border-red-500 @enderror"
                                               placeholder="Button text">
                                        @error('button_text')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Button URL
                                        </label>

                                        <!-- URL Type Selection -->
                                        <div class="mb-3">
                                            <div class="flex space-x-4">
                                                <label class="flex items-center">
                                                    <input type="radio" name="url_type" value="predefined" x-model="urlType" class="text-primary focus:ring-primary">
                                                    <span class="ml-2 text-sm text-gray-700">Predefined Routes</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="radio" name="url_type" value="system" x-model="urlType" class="text-primary focus:ring-primary">
                                                    <span class="ml-2 text-sm text-gray-700">System Content</span>
                                                </label>
                                                <label class="flex items-center">
                                                    <input type="radio" name="url_type" value="custom" x-model="urlType" class="text-primary focus:ring-primary">
                                                    <span class="ml-2 text-sm text-gray-700">Custom URL</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Predefined Routes Dropdown -->
                                        <div x-show="urlType === 'predefined'" x-transition class="space-y-2">
                                            <select x-model="selectedRoute" @change="updateUrl()"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                                <option value="">Choose route...</option>
                                                @if(isset($availableRoutes))
                                                    @foreach($availableRoutes as $name => $url)
                                                        <option value="{{ $url }}">{{ $name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p class="text-xs text-gray-500">Select from predefined application routes</p>
                                        </div>

                                        <!-- System Content Dropdown -->
                                        <div x-show="urlType === 'system'" x-transition class="space-y-2">
                                            <select x-model="selectedSystemContent" @change="updateUrl()"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                                <option value="">Choose content...</option>
                                                @if(isset($systemContent))
                                                    @foreach($systemContent as $category => $items)
                                                        <optgroup label="{{ $category }}">
                                                            @foreach($items as $item)
                                                                <option value="{{ $item['url'] }}" data-type="{{ $item['type'] }}">
                                                                    {{ $item['title'] }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p class="text-xs text-gray-500">Select from existing pages, blog posts, solutions, or services</p>
                                        </div>

                                        <!-- Custom URL Input -->
                                        <div x-show="urlType === 'custom'" x-transition class="space-y-2">
                                            <input type="text" x-model="customUrl" @input="updateUrl()"
                                                   placeholder="/custom-page or https://external-site.com or # for no link"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                            <p class="text-xs text-gray-500">
                                                Enter a relative path (/custom-page), full URL (https://...), or # for no link
                                            </p>
                                        </div>

                                        <!-- Hidden URL Field (actual form field) -->
                                        <input type="hidden" name="button_url" id="button_url" x-model="finalUrl">

                                        <!-- External Link Checkbox -->
                                        <div class="mt-3">
                                            <label class="flex items-center">
                                                <input type="checkbox"
                                                       name="button_external"
                                                       value="1"
                                                       x-model="isExternal"
                                                       class="rounded border-gray-300 text-primary focus:ring-primary">
                                                <span class="ml-2 text-sm text-gray-700">External Link</span>
                                            </label>
                                        </div>

                                        @error('button_url')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Status Card --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>

                                <x-ui.toggle 
                                    name="is_active"
                                    :checked="old('is_active', $widget->is_active)"
                                    label="Active"
                                    description="Only active widgets will be displayed."
                                />
                            </div>
                        </div>

                        {{-- Ordering Card --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Ordering</h3>

                                <div>
                                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sort Order
                                    </label>
                                    <input type="number"
                                           id="sort_order"
                                           name="sort_order"
                                           value="{{ old('sort_order', $widget->sort_order) }}"
                                           min="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('sort_order') border-red-500 @enderror">
                                    @error('sort_order')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Lower numbers appear first.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Styling Card --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Styling</h3>

                                <div>
                                    <label for="background_color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Background Color
                                    </label>
                                    <input type="color"
                                           id="background_color"
                                           name="background_color"
                                           value="{{ old('background_color', $widget->background_color ?: '#ffffff') }}"
                                           class="w-full h-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('background_color') border-red-500 @enderror">
                                    @error('background_color')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="text_color" class="block text-sm font-medium text-gray-700 mb-2">
                                        Text Color
                                    </label>
                                    <input type="color"
                                           id="text_color"
                                           name="text_color"
                                           value="{{ old('text_color', $widget->text_color ?: '#000000') }}"
                                           class="w-full h-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('text_color') border-red-500 @enderror">
                                    @error('text_color')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Image Card --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Image</h3>

                                @if($widget->image)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $widget->image) }}"
                                             alt="Current image"
                                             class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                    </div>
                                @endif

                                <div>
                                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $widget->image ? 'Replace Image' : 'Widget Image' }}
                                    </label>
                                    <input type="file"
                                           id="image"
                                           name="image"
                                           accept="image/*"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('image') border-red-500 @enderror">
                                    @error('image')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF, SVG up to 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.content.homepage-builder.index') }}"
                       class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200">
                        Update Widget
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('urlSelector', () => ({
        urlType: 'custom',
        selectedRoute: '',
        selectedSystemContent: '',
        customUrl: '',
        finalUrl: '',
        isExternal: false,

        // Template parameters
        selectedTemplate: '',
        templateParameters: [],
        parameterLabel: '',
        selectedParameter: '',
        templateParameterString: '',
        templateParameterId: '',

        init() {
            // Set initial values from widget data
            const currentUrl = '{{ old("button_url", $widget->button_url) }}';
            const currentExternal = {{ old('button_external', $widget->button_external) ? 'true' : 'false' }};
            const currentTemplate = '{{ old("template", $widget->template) }}';
            // Check both template_parameter (string) and template_parameter_id (integer)
            const currentParameter = '{{ old("template_parameter", $widget->template_parameter) }}' || '{{ old("template_parameter_id", $widget->template_parameter_id) }}';

            this.finalUrl = currentUrl;
            this.isExternal = currentExternal;
            this.selectedTemplate = currentTemplate;
            // Don't set selectedParameter here - we'll set it after template parameters are loaded
            this.selectedParameter = '';
            
            console.log('Initialization values:');
            console.log('Template:', currentTemplate);
            console.log('Parameter (string):', '{{ $widget->template_parameter }}');
            console.log('Parameter (id):', '{{ $widget->template_parameter_id }}');
            console.log('Current Parameter:', currentParameter);

            // Determine URL type based on current URL
            this.determineUrlType(currentUrl);

            // Set initial URL
            this.updateUrl();

            // Load template parameters if template is selected
            if (currentTemplate) {
                // Use nextTick to ensure the selectedParameter is set after Alpine.js processes the initial values
                this.$nextTick(() => {
                    this.loadTemplateParameters();
                });
            }
        },

        determineUrlType(url) {
            if (!url) {
                this.urlType = 'custom';
                return;
            }

            // Check if URL matches any predefined route
            const availableRoutes = @json($availableRoutes ?? []);
            for (const [name, routeUrl] of Object.entries(availableRoutes)) {
                if (routeUrl === url) {
                    this.urlType = 'predefined';
                    this.selectedRoute = url;
                    return;
                }
            }

            // Check if URL matches any system content
            const systemContent = @json($systemContent ?? []);
            for (const [category, items] of Object.entries(systemContent)) {
                for (const item of items) {
                    if (item.url === url) {
                        this.urlType = 'system';
                        this.selectedSystemContent = url;
                        return;
                    }
                }
            }

            // Default to custom
            this.urlType = 'custom';
            this.customUrl = url;
        },

        updateUrl() {
            if (this.urlType === 'predefined') {
                this.finalUrl = this.selectedRoute;
                this.customUrl = '';
                this.selectedSystemContent = '';
            } else if (this.urlType === 'system') {
                this.finalUrl = this.selectedSystemContent;
                this.customUrl = '';
                this.selectedRoute = '';
            } else {
                this.finalUrl = this.customUrl;
                this.selectedRoute = '';
                this.selectedSystemContent = '';
            }
        },

        async loadTemplateParameters() {
            if (!this.selectedTemplate) {
                this.templateParameters = [];
                this.parameterLabel = '';
                return;
            }

            // Get the parameter value from the database (check both string and integer fields)
            const currentSelectedParameter = '{{ old("template_parameter", $widget->template_parameter) }}' || '{{ old("template_parameter_id", $widget->template_parameter_id) }}';

            try {
                const response = await fetch(`{{ route('api.homepage-builder.template-parameters') }}?template=${this.selectedTemplate}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                this.templateParameters = data.options || [];

                // Get parameter label from template configuration
                const templateConfig = @json(App\Models\Widget::getTemplateParameters());
                if (templateConfig[this.selectedTemplate]) {
                    this.parameterLabel = templateConfig[this.selectedTemplate].parameter_label;
                }

                // Set the selected parameter after options are loaded
                if (currentSelectedParameter) {
                    console.log('Setting selected parameter:', currentSelectedParameter, 'Type:', typeof currentSelectedParameter);
                    console.log('Available options:', this.templateParameters);
                    
                    // Set the selected parameter
                    this.selectedParameter = currentSelectedParameter;
                    console.log('Selected parameter set to:', this.selectedParameter);
                    
                    // Update hidden fields
                    this.updateParameterFields();
                }
            } catch (error) {
                console.error('Error loading template parameters:', error);
                this.templateParameters = [];
                this.parameterLabel = '';
            }
        },

        updateParameterFields() {
            // Determine if current template uses ID or string identifier
            const templateConfig = @json(App\Models\Widget::getTemplateParameters());
            const config = templateConfig[this.selectedTemplate];
            
            if (config && config.value_field === 'id') {
                // Template uses integer ID (Feature Blocks, Service Grids, etc.)
                this.templateParameterId = this.selectedParameter;
                this.templateParameterString = '';
            } else {
                // Template uses string identifier (FAQ, etc.)
                this.templateParameterString = this.selectedParameter;
                this.templateParameterId = '';
            }
            
            console.log('Updated parameter fields:', {
                string: this.templateParameterString,
                id: this.templateParameterId
            });
        }
    }));
});
</script>
</x-layouts.admin>
