<x-layouts.admin title="Edit Widget">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Widget</h1>
            <p class="text-gray-600">Update widget: {{ $widget->title ?: 'Untitled Widget' }}</p>
        </div>
        <a href="{{ route('admin.content.homepage-builder.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to Widgets
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form action="{{ route('admin.content.homepage-builder.update', $widget) }}" method="POST" enctype="multipart/form-data" id="widgetForm">
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
                                </div>

                                {{-- Template Parameter (FAQ Group, Feature Block, etc.) --}}
                                <div id="templateParameterField" style="display: none;">
                                    <label for="template_parameter_select" class="block text-sm font-medium text-gray-700 mb-2">
                                        <span id="templateParameterLabel">Parameter</span> <span class="text-red-500">*</span>
                                    </label>
                                    <select id="template_parameter_select" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('template_parameter') border-red-500 @enderror @error('template_parameter_id') border-red-500 @enderror">
                                        <option value="">Select an option</option>
                                    </select>
                                    {{-- Hidden inputs that will be populated based on template type --}}
                                    <input type="hidden" id="template_parameter" name="template_parameter" value="{{ old('template_parameter', $widget->template_parameter) }}">
                                    <input type="hidden" id="template_parameter_id" name="template_parameter_id" value="{{ old('template_parameter_id', $widget->template_parameter_id) }}">
                                    @error('template_parameter')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    @error('template_parameter_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
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
                                        <label for="button_url" class="block text-sm font-medium text-gray-700 mb-2">
                                            Button URL
                                        </label>
                                        <input type="url" 
                                               id="button_url" 
                                               name="button_url" 
                                               value="{{ old('button_url', $widget->button_url) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('button_url') border-red-500 @enderror"
                                               placeholder="https://example.com">
                                        @error('button_url')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex items-end">
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   name="button_external" 
                                                   value="1" 
                                                   {{ old('button_external', $widget->button_external) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="ml-2 text-sm text-gray-700">External Link</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Status Card --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="p-6 space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Status</h3>
                                
                                <div>
                                    <x-ui.toggle 
                                        name="is_active"
                                        :checked="old('is_active', $widget->is_active)"
                                        label="Active"
                                        description="Only active widgets will be displayed."
                                    />
                                </div>
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
                                <x-image-upload 
                                    id="image"
                                    name="image"
                                    label="{{ $widget->image ? 'Replace Image' : 'Widget Image' }}"
                                    :required="false"
                                    help-text="JPG, PNG, GIF, SVG up to 2MB"
                                    :max-size="2048"
                                    :current-image="$widget->image ? asset('storage/' . $widget->image) : null"
                                    current-image-alt="Widget image"
                                />
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
document.addEventListener('DOMContentLoaded', function() {
    const templateSelect = document.getElementById('template');
    const templateParameterField = document.getElementById('templateParameterField');
    const templateParameterSelect = document.getElementById('template_parameter_select');
    const templateParameterLabel = document.getElementById('templateParameterLabel');
    const templateParameterInput = document.getElementById('template_parameter');
    const templateParameterIdInput = document.getElementById('template_parameter_id');
    
    // Template parameters configuration
    const templateParameters = @json(\App\Models\Widget::getTemplateParameters());
    
    // Current widget data
    const currentTemplate = '{{ old("template", $widget->template) }}';
    const currentParameter = '{{ old("template_parameter", $widget->template_parameter) }}';
    const currentParameterId = '{{ old("template_parameter_id", $widget->template_parameter_id) }}';
    
    // Function to load template parameter options
    function loadTemplateParameters(template) {
        if (!templateParameters[template]) {
            templateParameterField.style.display = 'none';
            return;
        }
        
        const config = templateParameters[template];
        templateParameterLabel.textContent = config.parameter_label;
        
        // Determine if this template uses ID or string identifier
        const usesId = config.value_field === 'id';
        
        // Clear existing options
        templateParameterSelect.innerHTML = '<option value="">Select an option</option>';
        
        // Fetch options via AJAX
        console.log('Fetching options for template:', template);
        fetch(`/admin/content/homepage-builder/template-options/${template}`)
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(options => {
                console.log('Received options:', options);
                options.forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option.value;
                    optionElement.textContent = option.label;
                    
                    // Pre-select the current value (check both string and integer values)
                    const currentValue = currentParameter || currentParameterId;
                    if (option.value == currentValue) {  // Use == for loose comparison (string vs int)
                        optionElement.selected = true;
                    }
                    
                    templateParameterSelect.appendChild(optionElement);
                });
                
                // Handle select change to populate correct hidden field
                templateParameterSelect.onchange = function() {
                    if (usesId) {
                        templateParameterIdInput.value = this.value;
                        templateParameterInput.value = '';
                    } else {
                        templateParameterInput.value = this.value;
                        templateParameterIdInput.value = '';
                    }
                };
                
                // Trigger change to set initial values
                if (templateParameterSelect.value) {
                    templateParameterSelect.onchange();
                }
                
                templateParameterField.style.display = 'block';
            })
            .catch(error => {
                console.error('Error loading template options:', error);
                templateParameterField.style.display = 'none';
            });
    }
    
    // Handle template change
    templateSelect.addEventListener('change', function() {
        loadTemplateParameters(this.value);
    });
    
    // Load initial template parameters if template is selected
    console.log('Current template:', currentTemplate);
    console.log('Current parameter:', currentParameter);
    console.log('Current parameter ID:', currentParameterId);
    
    if (currentTemplate) {
        console.log('Loading template parameters for:', currentTemplate);
        loadTemplateParameters(currentTemplate);
    }
});
</script>
</x-layouts.admin>
