<x-layouts.admin title="Create Testimonial">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Testimonial</h1>
            <p class="text-gray-600">Add a new customer testimonial for social proof</p>
        </div>
        <a href="{{ route('admin.marketing.testimonial.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.marketing.testimonial.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Customer Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Customer Name --}}
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Customer Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="customer_name"
                                       name="customer_name"
                                       value="{{ old('customer_name') }}"
                                       required
                                       placeholder="e.g., Jan van der Berg"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('customer_name') border-red-500 @enderror">
                                @error('customer_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Company --}}
                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                                <input type="text"
                                       id="company"
                                       name="company"
                                       value="{{ old('company') }}"
                                       placeholder="e.g., Berg Consultancy"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('company') border-red-500 @enderror">
                                @error('company')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Position --}}
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position/Title</label>
                            <input type="text"
                                   id="position"
                                   name="position"
                                   value="{{ old('position') }}"
                                   placeholder="e.g., CEO, Directeur, Manager"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('position') border-red-500 @enderror">
                            @error('position')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Testimonial Content --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Testimonial Content</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Quote --}}
                        <div>
                            <label for="quote" class="block text-sm font-medium text-gray-700 mb-2">
                                Quote <span class="text-red-500">*</span>
                            </label>
                            <textarea id="quote"
                                      name="quote"
                                      rows="4"
                                      required
                                      placeholder="Enter the customer's testimonial quote..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('quote') border-red-500 @enderror">{{ old('quote') }}</textarea>
                            @error('quote')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Rating --}}
                        <div>
                            <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating (1-5 stars)</label>
                            <div class="flex items-center space-x-2">
                                <select id="rating"
                                        name="rating"
                                        class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('rating') border-red-500 @enderror">
                                    <option value="">No rating</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                            {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                                <div id="star-preview" class="flex items-center ml-2">
                                    <!-- Stars will be shown here -->
                                </div>
                            </div>
                            @error('rating')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Tags --}}
                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                            <div id="tags-container">
                                <div class="tag-item flex items-center space-x-2 mb-2">
                                    <input type="text"
                                           name="tags[]"
                                           value="{{ old('tags.0') }}"
                                           placeholder="e.g., Automatische Facturatie, Tijdsbesparing"
                                           class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                    <button type="button" onclick="removeTag(this)" class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" onclick="addTag()" class="text-primary hover:text-primary/80 text-sm">
                                <i class="fa-solid fa-plus mr-1"></i> Add Tag
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Images --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Images</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Customer Photo --}}
                        <div>
                            <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Customer Photo</label>
                            <input type="file"
                                   id="photo"
                                   name="photo"
                                   accept="image/*"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('photo') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Max 2MB. JPG, PNG, GIF supported.</p>
                            @error('photo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Company Logo --}}
                        <div>
                            <label for="company_logo" class="block text-sm font-medium text-gray-700 mb-2">Company Logo</label>
                            <input type="file"
                                   id="company_logo"
                                   name="company_logo"
                                   accept="image/*"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('company_logo') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Max 2MB. JPG, PNG, GIF supported.</p>
                            @error('company_logo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Featured --}}
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="featured"
                                   name="featured"
                                   value="1"
                                   {{ old('featured') ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="featured" class="ml-2 block text-sm text-gray-900">Featured Testimonial</label>
                        </div>

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
                            Create Testimonial
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Update star preview when rating changes
document.getElementById('rating').addEventListener('change', function() {
    const rating = parseInt(this.value) || 0;
    const preview = document.getElementById('star-preview');
    let stars = '';
    
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fa-solid fa-star text-yellow-400"></i>';
        } else {
            stars += '<i class="fa-solid fa-star text-gray-300"></i>';
        }
    }
    
    preview.innerHTML = stars;
});

// Tag management functions
function addTag() {
    const container = document.getElementById('tags-container');
    const div = document.createElement('div');
    div.className = 'tag-item flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="tags[]" placeholder="Enter a tag" 
               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
        <button type="button" onclick="removeTag(this)" class="text-red-600 hover:text-red-800">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeTag(button) {
    button.closest('.tag-item').remove();
}

// Initialize star preview on page load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('rating').dispatchEvent(new Event('change'));
});
</script>
</x-layouts.admin>
