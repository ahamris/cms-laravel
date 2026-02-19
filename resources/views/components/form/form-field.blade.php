@php
    $fieldId = 'field_' . ($field['id'] ?? uniqid('', true));
    $fieldName = $field['name'] ?? '';
    $fieldType = $field['type'] ?? 'text';
    $fieldLabel = $field['label'] ?? '';
    $fieldPlaceholder = $field['placeholder'] ?? '';
    $isRequired = isset($field['required']) && $field['required'];
    $fieldOptions = $field['options'] ?? [];
    $hasError = $errors->has($fieldName);
    $errorClass = $hasError ? 'border-red-500' : '';
@endphp

<div class="form-field space-y-2">
    {{-- Label --}}
    @if($fieldLabel)
        <label for="{{ $fieldId }}" class="block text-sm font-medium text-gray-700">
            {{ $fieldLabel }}
            @if($isRequired)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- Text, Email, Tel Input --}}
    @if(in_array($fieldType, ['text', 'email', 'tel']))
        <input type="{{ $fieldType }}" 
               id="{{ $fieldId }}" 
               name="{{ $fieldName }}" 
               value="{{ $value }}"
               placeholder="{{ $fieldPlaceholder }}"
               @if($isRequired) required @endif
               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 {{ $errorClass }}">
        @error($fieldName)
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

    {{-- Textarea --}}
    @elseif($fieldType === 'textarea')
        <textarea id="{{ $fieldId }}" 
                  name="{{ $fieldName }}" 
                  rows="4"
                  placeholder="{{ $fieldPlaceholder }}"
                  @if($isRequired) required @endif
                  class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 resize-y {{ $errorClass }}">{{ $value }}</textarea>
        @error($fieldName)
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

    {{-- Select Dropdown --}}
    @elseif($fieldType === 'select')
        <select id="{{ $fieldId }}" 
                name="{{ $fieldName }}" 
                @if($isRequired) required @endif
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-white {{ $errorClass }}">
            <option value="">{{ $fieldPlaceholder ?: 'Select an option' }}</option>
            @if(is_array($fieldOptions) && count($fieldOptions) > 0)
                @foreach($fieldOptions as $option)
                    <option value="{{ $option }}" {{ $value === $option ? 'selected' : '' }}>
                        {{ $option }}
                    </option>
                @endforeach
            @endif
        </select>
        @error($fieldName)
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

    {{-- Radio Buttons --}}
    @elseif($fieldType === 'radio')
        <div class="space-y-2">
            @if(is_array($fieldOptions) && count($fieldOptions) > 0)
                @foreach($fieldOptions as $option)
                    <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                        <input type="radio" 
                               name="{{ $fieldName }}" 
                               value="{{ $option }}"
                               {{ $value === $option ? 'checked' : '' }}
                               @if($isRequired) required @endif
                               class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                        <span class="text-gray-700">{{ $option }}</span>
                    </label>
                @endforeach
            @endif
        </div>
        @error($fieldName)
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

    {{-- Checkbox --}}
    @elseif($fieldType === 'checkbox')
        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
            <input type="checkbox" 
                   name="{{ $fieldName }}" 
                   value="1"
                   {{ $value ? 'checked' : '' }}
                   @if($isRequired) required @endif
                   class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
            <span class="text-gray-700">{{ $fieldLabel ?: 'I agree to the terms and conditions' }}</span>
        </label>
        @error($fieldName)
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

    {{-- Default: Text Input --}}
    @else
        <input type="text" 
               id="{{ $fieldId }}" 
               name="{{ $fieldName }}" 
               value="{{ $value }}"
               placeholder="{{ $fieldPlaceholder }}"
               @if($isRequired) required @endif
               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 {{ $errorClass }}">
        @error($fieldName)
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    @endif
</div>

