<x-layouts.admin title="Create Form">

    <style>
<style>
    .field-item {
        transition: all 0.3s ease;
    }
    .field-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }
    .sortable-ghost {
        opacity: 0.4;
        background: #f3f4f6;
    }
</style>
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Create New Form</h2>
                <p class="text-gray-600 mt-2">Build a custom form with drag-and-drop fields</p>
            </div>
            <a href="{{ route('admin.content.form-builder.index') }}"
               class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200">
                <i class="fa fa-arrow-left mr-2"></i>
                Back to Forms
            </a>
        </div>
    </div>

    <form action="{{ route('admin.content.form-builder.store') }}" method="POST" id="formBuilderForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form Settings --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fa fa-info-circle text-purple-600 mr-2"></i>
                        Basic Information
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Form Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title"
                                   value="{{ old('title') }}"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200 @error('title') border-red-500 @enderror"
                                   placeholder="Contact Form"
                                   required>
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="identifier" class="block text-sm font-medium text-gray-700 mb-2">
                                Identifier <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="identifier" 
                                   id="identifier"
                                   value="{{ old('identifier') }}"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200 @error('identifier') border-red-500 @enderror"
                                   placeholder="contact_form"
                                   required>
                            <p class="mt-2 text-sm text-gray-500">Unique identifier for this form (e.g., contact_form, newsletter_signup)</p>
                            @error('identifier')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      id="description"
                                      rows="3"
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200 @error('description') border-red-500 @enderror"
                                      placeholder="Brief description of this form">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Form Fields Builder --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fa fa-list text-purple-600 mr-2"></i>
                        Form Fields
                    </h3>

                    <div id="formFields" class="space-y-4 mb-6">
                        {{-- Fields will be added here dynamically --}}
                    </div>

                    <button type="button" 
                            onclick="addField()"
                            class="w-full px-4 py-3 border-2 border-dashed border-gray-300 hover:border-purple-500 text-gray-600 hover:text-purple-600 font-semibold rounded-xl transition-all duration-200">
                        <i class="fa fa-plus mr-2"></i>
                        Add Field
                    </button>
                </div>

                {{-- Success Settings --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fa fa-check-circle text-purple-600 mr-2"></i>
                        Success Settings
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label for="success_message" class="block text-sm font-medium text-gray-700 mb-2">
                                Success Message
                            </label>
                            <textarea name="success_message" 
                                      id="success_message"
                                      rows="3"
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200"
                                      placeholder="Thank you for your submission!">{{ old('success_message', 'Thank you for your submission!') }}</textarea>
                        </div>

                        <div>
                            <label for="redirect_url" class="block text-sm font-medium text-gray-700 mb-2">
                                Redirect URL (Optional)
                            </label>
                            <input type="url" 
                                   name="redirect_url" 
                                   id="redirect_url"
                                   value="{{ old('redirect_url') }}"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200"
                                   placeholder="https://example.com/thank-you">
                            <p class="mt-2 text-sm text-gray-500">Redirect users to this URL after successful submission</p>
                        </div>

                        <div>
                            <label for="submit_button_text" class="block text-sm font-medium text-gray-700 mb-2">
                                Submit Button Text
                            </label>
                            <input type="text" 
                                   name="submit_button_text" 
                                   id="submit_button_text"
                                   value="{{ old('submit_button_text', 'Submit') }}"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200"
                                   placeholder="Submit">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Available Field Types --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Fields</h3>
                    <div class="space-y-2">
                        <button type="button" onclick="addFieldType('text')" class="w-full px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors text-left">
                            <i class="fa fa-font mr-2"></i> Text Input
                        </button>
                        <button type="button" onclick="addFieldType('email')" class="w-full px-4 py-2 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg transition-colors text-left">
                            <i class="fa fa-envelope mr-2"></i> Email
                        </button>
                        <button type="button" onclick="addFieldType('textarea')" class="w-full px-4 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg transition-colors text-left">
                            <i class="fa fa-align-left mr-2"></i> Textarea
                        </button>
                        <button type="button" onclick="addFieldType('select')" class="w-full px-4 py-2 bg-yellow-50 hover:bg-yellow-100 text-yellow-700 rounded-lg transition-colors text-left">
                            <i class="fa fa-list mr-2"></i> Select Dropdown
                        </button>
                        <button type="button" onclick="addFieldType('checkbox')" class="w-full px-4 py-2 bg-pink-50 hover:bg-pink-100 text-pink-700 rounded-lg transition-colors text-left">
                            <i class="fa fa-check-square mr-2"></i> Checkbox
                        </button>
                        <button type="button" onclick="addFieldType('radio')" class="w-full px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors text-left">
                            <i class="fa fa-dot-circle mr-2"></i> Radio Buttons
                        </button>
                    </div>
                </div>

                {{-- Email Notifications --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Notifications</h3>
                    
                    <div class="space-y-4">
                        <x-ui.toggle
                                   name="send_email_notification" 
                                   id="send_email_notification"
                            :checked="old('send_email_notification')"
                            label="Send email notifications"
                        />

                        <div>
                            <label for="notification_emails" class="block text-sm font-medium text-gray-700 mb-2">
                                Notification Emails
                            </label>
                            <textarea name="notification_emails" 
                                      id="notification_emails"
                                      rows="3"
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-200"
                                      placeholder="admin@example.com, sales@example.com">{{ old('notification_emails') }}</textarea>
                            <p class="mt-2 text-sm text-gray-500">Separate multiple emails with commas</p>
                        </div>
                    </div>
                </div>

                {{-- API Form Settings --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <i class="fa fa-code text-purple-600 mr-2 text-sm"></i>
                            <label for="is_api_form" class="text-sm font-semibold text-gray-900 cursor-pointer">
                                API Form
                            </label>
                        </div>
                        <x-ui.toggle
                                   name="is_api_form" 
                                   id="is_api_form"
                            :checked="old('is_api_form')"
                                   onchange="toggleApiFields()"
                        />
                    </div>
                    
                    <div id="apiFormFields" class="mt-3 space-y-3 transition-all duration-200" style="display: {{ old('is_api_form') ? 'block' : 'none' }}; max-height: {{ old('is_api_form') ? '500px' : '0' }}; overflow: hidden;">
                        <div>
                            <label for="api_url" class="block text-xs font-medium text-gray-700 mb-1">
                                API URL <span class="text-red-500">*</span>
                            </label>
                            <input type="url" 
                                   name="api_url" 
                                   id="api_url"
                                   value="{{ old('api_url') }}"
                                   class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all @error('api_url') border-red-500 @enderror"
                                   placeholder="https://api.example.com/submit">
                            @error('api_url')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="api_token" class="block text-xs font-medium text-gray-700 mb-1">
                                API Token <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="api_token" 
                                   id="api_token"
                                   value="{{ old('api_token') }}"
                                   class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all @error('api_token') border-red-500 @enderror"
                                   placeholder="your-api-token-here">
                            @error('api_token')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Submissions forwarded to external API with token verification</p>
                    </div>
                </div>

                {{-- Status --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                    
                    <x-ui.toggle
                               name="is_active" 
                               id="is_active"
                        :checked="old('is_active', true)"
                        label="Active (form is visible and accepting submissions)"
                    />
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                        class="w-full px-6 py-4 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fa fa-save mr-2"></i>
                    Create Form
                </button>
            </div>
        </div>

        <input type="hidden" name="fields" id="fieldsData">
    </form>

    <script>
    let fieldCounter = 0;
    let fields = [];

    function addFieldType(type) {
        const field = {
            id: ++fieldCounter,
            type: type,
            label: '',
            name: '',
            placeholder: '',
            required: false,
            options: []
        };
        
        fields.push(field);
        renderFields();
    }

    function addField() {
        addFieldType('text');
    }

    function removeField(id) {
        fields = fields.filter(f => f.id !== id);
        renderFields();
    }

    function updateField(id, property, value) {
        const field = fields.find(f => f.id === id);
        if (field) {
            field[property] = value;
        }
    }

    function renderFields() {
        const container = document.getElementById('formFields');
        container.innerHTML = '';

        fields.forEach((field, index) => {
            const fieldHtml = `
                <div class="field-item bg-gray-50 border border-gray-200 rounded-xl p-4" data-field-id="${field.id}">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <i class="fa fa-grip-vertical text-gray-400 cursor-move"></i>
                            <span class="font-semibold text-gray-900">Field ${index + 1}: ${getFieldTypeLabel(field.type)}</span>
                        </div>
                        <button type="button" onclick="removeField(${field.id})" class="text-red-600 hover:text-red-800">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>

                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Label</label>
                                <input type="text" 
                                       value="${field.label}"
                                       onchange="updateField(${field.id}, 'label', this.value)"
                                       class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500/20"
                                       placeholder="Full Name">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" 
                                       value="${field.name}"
                                       onchange="updateField(${field.id}, 'name', this.value)"
                                       class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500/20"
                                       placeholder="full_name">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Placeholder</label>
                            <input type="text" 
                                   value="${field.placeholder}"
                                   onchange="updateField(${field.id}, 'placeholder', this.value)"
                                   class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500/20"
                                   placeholder="Enter your full name">
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   ${field.required ? 'checked' : ''}
                                   onchange="updateField(${field.id}, 'required', this.checked)"
                                   class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <label class="ml-2 text-sm text-gray-700">Required field</label>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', fieldHtml);
        });

        // Update hidden input
        document.getElementById('fieldsData').value = JSON.stringify(fields);
    }

    function getFieldTypeLabel(type) {
        const labels = {
            'text': 'Text Input',
            'email': 'Email',
            'textarea': 'Textarea',
            'select': 'Select Dropdown',
            'checkbox': 'Checkbox',
            'radio': 'Radio Buttons'
        };
        return labels[type] || type;
    }

    // Form submission
    document.getElementById('formBuilderForm').addEventListener('submit', function(e) {
        document.getElementById('fieldsData').value = JSON.stringify(fields);
    });

    // Toggle API form fields visibility
    function toggleApiFields() {
        const checkbox = document.getElementById('is_api_form');
        const apiFields = document.getElementById('apiFormFields');
        
        if (checkbox.checked) {
            apiFields.style.display = 'block';
            apiFields.style.maxHeight = '500px';
            document.getElementById('api_url').required = true;
            document.getElementById('api_token').required = true;
        } else {
            apiFields.style.maxHeight = '0';
            setTimeout(() => {
                if (!checkbox.checked) {
                    apiFields.style.display = 'none';
                }
            }, 200);
            document.getElementById('api_url').required = false;
            document.getElementById('api_token').required = false;
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleApiFields();
    });
</script>
    </script>
</x-layouts.admin>
