<x-layouts.admin title="Edit Product Feature">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Product Feature</h1>
            <p class="text-gray-600">Update {{ $productFeature->name }} details</p>
        </div>
        <a href="{{ route('admin.marketing.product-feature.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.marketing.product-feature.update', $productFeature) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

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
                                Feature Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $productFeature->name) }}"
                                   required
                                   placeholder="e.g., Automatische Facturatie, CRM Integratie"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Slug --}}
                        <x-ui.input id="slug" name="slug" label="Slug" :value="old('slug', $productFeature->slug)"
                            placeholder="e.g. automatische-facturatie"
                            slug-from="name"
                            hint="Auto-generated from name if left blank."
                            :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Brief description of this product feature"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('description') border-red-500 @enderror">{{ old('description', $productFeature->description) }}</textarea>
                            @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Icon --}}
                            <div>
                                <x-icon-picker
                                    id="icon"
                                    name="icon"
                                    :value="old('icon', $productFeature->icon)"
                                    label="FontAwesome Icon"
                                    help-text="FontAwesome class without 'fa-solid' prefix"
                                    :required="false"
                                />
                                @error('icon')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Category --}}
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <input type="text"
                                       id="category"
                                       name="category"
                                       value="{{ old('category', $productFeature->category) }}"
                                       placeholder="e.g., Financieel, Integraties, Analytics"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('category') border-red-500 @enderror">
                                @error('category')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Benefits --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Benefits</h3>
                        <p class="text-sm text-gray-600">List the key benefits this feature provides</p>
                    </div>
                    <div class="p-6">
                        <div id="benefits-container">
                            @if($productFeature->benefits && count($productFeature->benefits) > 0)
                                @foreach($productFeature->benefits as $index => $benefit)
                                    <div class="benefit-item flex items-center space-x-2 mb-2">
                                        <input type="text"
                                               name="benefits[]"
                                               value="{{ old('benefits.' . $index, $benefit) }}"
                                               placeholder="Enter a benefit"
                                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                        <button type="button" onclick="removeBenefit(this)" class="text-red-600 hover:text-red-800">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="benefit-item flex items-center space-x-2 mb-2">
                                    <input type="text"
                                           name="benefits[]"
                                           placeholder="Enter a benefit"
                                           class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                    <button type="button" onclick="removeBenefit(this)" class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addBenefit()" class="text-primary hover:text-primary/80 text-sm">
                            <i class="fa-solid fa-plus mr-1"></i> Add Benefit
                        </button>
                    </div>
                </div>

                {{-- Preview --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Preview</h3>
                    </div>
                    <div class="p-6">
                        <div id="preview" class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div id="preview-icon" class="h-12 w-12 rounded-lg bg-blue-50 flex items-center justify-center mr-4">
                                    <i id="preview-icon-element" class="fa-solid {{ $productFeature->icon ?? 'fa-star' }} text-blue-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <h4 id="preview-name" class="text-lg font-semibold text-gray-900">{{ $productFeature->name }}</h4>
                                        <span id="preview-type" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $productFeature->is_premium ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            <i class="fa-solid fa-{{ $productFeature->is_premium ? 'crown' : 'check' }} mr-1"></i>{{ $productFeature->is_premium ? 'Premium' : 'Free' }}
                                        </span>
                                    </div>
                                    <p id="preview-category" class="text-sm text-gray-500 mb-2">{{ $productFeature->category ?? 'Category' }}</p>
                                    <p id="preview-description" class="text-sm text-gray-700">{{ $productFeature->description ?? 'Feature description will appear here' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Current Feature Type --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Current Type</h3>
                    </div>
                    <div class="p-6 text-center">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $productFeature->is_premium ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                            <i class="fa-solid fa-{{ $productFeature->is_premium ? 'crown' : 'check' }} mr-2"></i>
                            {{ $productFeature->is_premium ? 'Premium Feature' : 'Free Feature' }}
                        </span>
                    </div>
                </div>

                {{-- Feature Type --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Update Feature Type</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="radio"
                                       id="type_free"
                                       name="is_premium"
                                       value="0"
                                       {{ old('is_premium', $productFeature->is_premium ? '1' : '0') == '0' ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                <label for="type_free" class="ml-2 block text-sm text-gray-900">
                                    <i class="fa-solid fa-check text-green-600 mr-1"></i>
                                    Free Feature
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio"
                                       id="type_premium"
                                       name="is_premium"
                                       value="1"
                                       {{ old('is_premium', $productFeature->is_premium ? '1' : '0') == '1' ? 'checked' : '' }}
                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                <label for="type_premium" class="ml-2 block text-sm text-gray-900">
                                    <i class="fa-solid fa-crown text-yellow-600 mr-1"></i>
                                    Premium Feature
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

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
                                   {{ old('is_active', $productFeature->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                        </div>

                        {{-- Sort Order --}}
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', $productFeature->sort_order) }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>

                        {{-- Metadata --}}
                        <div class="pt-4 border-t border-gray-200">
                            <div class="text-sm text-gray-600">
                                <p><strong>Created:</strong> {{ $productFeature->created_at->format('M j, Y \a\t g:i A') }}</p>
                                @if($productFeature->updated_at != $productFeature->created_at)
                                    <p><strong>Updated:</strong> {{ $productFeature->updated_at->format('M j, Y \a\t g:i A') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <button type="submit"
                                class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                            <i class="fa-solid fa-save mr-2"></i>
                            Update Feature
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
    const categoryInput = document.getElementById('category');
    const descriptionInput = document.getElementById('description');
    const typeFree = document.getElementById('type_free');
    const typePremium = document.getElementById('type_premium');
    
    const previewName = document.getElementById('preview-name');
    const previewIconElement = document.getElementById('preview-icon-element');
    const previewCategory = document.getElementById('preview-category');
    const previewDescription = document.getElementById('preview-description');
    const previewType = document.getElementById('preview-type');

    function updatePreview() {
        // Update name
        previewName.textContent = nameInput.value || '{{ $productFeature->name }}';
        
        // Update icon
        const iconClass = iconInput.value || '{{ $productFeature->icon ?? 'fa-star' }}';
        previewIconElement.className = `fa-solid ${iconClass} text-blue-600 text-xl`;
        
        // Update category
        previewCategory.textContent = categoryInput.value || '{{ $productFeature->category ?? 'Category' }}';
        
        // Update description
        previewDescription.textContent = descriptionInput.value || '{{ $productFeature->description ?? 'Feature description will appear here' }}';
        
        // Update type
        const isPremium = typePremium.checked;
        if (isPremium) {
            previewType.innerHTML = '<i class="fa-solid fa-crown mr-1"></i>Premium';
            previewType.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
        } else {
            previewType.innerHTML = '<i class="fa-solid fa-check mr-1"></i>Free';
            previewType.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
        }
    }

    nameInput.addEventListener('input', updatePreview);
    iconInput.addEventListener('input', updatePreview);
    categoryInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
    typeFree.addEventListener('change', updatePreview);
    typePremium.addEventListener('change', updatePreview);
    
    // Initial preview update
    updatePreview();
});

// Benefit management functions
function addBenefit() {
    const container = document.getElementById('benefits-container');
    const div = document.createElement('div');
    div.className = 'benefit-item flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="benefits[]" placeholder="Enter a benefit" 
               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
        <button type="button" onclick="removeBenefit(this)" class="text-red-600 hover:text-red-800">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeBenefit(button) {
    button.closest('.benefit-item').remove();
}
</script>
</x-layouts.admin>
