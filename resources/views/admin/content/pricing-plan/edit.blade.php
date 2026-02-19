<x-layouts.admin title="Edit Pricing Plan">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Pricing Plan</h1>
            <p class="text-gray-600">Update pricing plan details</p>
        </div>
        <a href="{{ route('admin.content.pricing-plan.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.content.pricing-plan.update', $pricingPlan) }}" method="POST" class="space-y-6">
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
                        {{-- Plan Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Plan Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $pricingPlan->name) }}"
                                   required
                                   placeholder="e.g., SMART, GROW, FLOW"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                                Slug
                            </label>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   value="{{ old('slug', $pricingPlan->slug) }}"
                                   placeholder="Auto-generated from name (e.g., smart)"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('slug') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate</p>
                            @error('slug')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Short description of the plan"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('description') border-red-500 @enderror">{{ old('description', $pricingPlan->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Pricing</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Price Row --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                    Regular Price <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">€</span>
                                    <input type="number"
                                           id="price"
                                           name="price"
                                           value="{{ old('price', $pricingPlan->price) }}"
                                           required
                                           step="0.01"
                                           min="0"
                                           placeholder="50.00"
                                           class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('price') border-red-500 @enderror">
                                </div>
                                @error('price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="discounted_price" class="block text-sm font-medium text-gray-700 mb-1">
                                    Discounted Price
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">€</span>
                                    <input type="number"
                                           id="discounted_price"
                                           name="discounted_price"
                                           value="{{ old('discounted_price', $pricingPlan->discounted_price) }}"
                                           step="0.01"
                                           min="0"
                                           placeholder="37.50"
                                           class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('discounted_price') border-red-500 @enderror">
                                </div>
                                @error('discounted_price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-1">
                                    Discount %
                                </label>
                                <input type="number"
                                       id="discount_percentage"
                                       name="discount_percentage"
                                       value="{{ old('discount_percentage', $pricingPlan->discount_percentage) }}"
                                       min="0"
                                       max="100"
                                       placeholder="25"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('discount_percentage') border-red-500 @enderror">
                                @error('discount_percentage')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Footnote --}}
                        <div>
                            <label for="footnote" class="block text-sm font-medium text-gray-700 mb-1">
                                Footnote
                            </label>
                            <input type="text"
                                   id="footnote"
                                   name="footnote"
                                   value="{{ old('footnote', $pricingPlan->footnote) }}"
                                   placeholder="e.g., *Prices exclude VAT"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('footnote') border-red-500 @enderror">
                            @error('footnote')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Features --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Features</h3>
                    </div>
                    <div class="p-6">
                        <div id="features-container">
                            @php
                                $features = old('features', $pricingPlan->features ?? []);
                            @endphp
                            @if(!empty($features))
                                @foreach($features as $feature)
                                    <div class="feature-item flex items-center gap-2 mb-2">
                                        <input type="text"
                                               name="features[]"
                                               value="{{ $feature }}"
                                               placeholder="Feature description"
                                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                        <button type="button" onclick="removeFeature(this)" class="text-red-600 hover:text-red-800">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="feature-item flex items-center gap-2 mb-2">
                                    <input type="text"
                                           name="features[]"
                                           placeholder="Feature description"
                                           class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                    <button type="button" onclick="removeFeature(this)" class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addFeature()" class="mt-2 text-primary hover:text-primary/80">
                            <i class="fa-solid fa-plus mr-2"></i>
                            Add Feature
                        </button>
                    </div>
                </div>

                {{-- Button Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Button Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="button_text" class="block text-sm font-medium text-gray-700 mb-1">
                                    Button Text
                                </label>
                                <input type="text"
                                       id="button_text"
                                       name="button_text"
                                       value="{{ old('button_text', $pricingPlan->button_text) }}"
                                       placeholder="Start today →"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('button_text') border-red-500 @enderror">
                                @error('button_text')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="button_url" class="block text-sm font-medium text-gray-700 mb-1">
                                    Button URL
                                </label>
                                <input type="text"
                                       id="button_url"
                                       name="button_url"
                                       value="{{ old('button_url', $pricingPlan->button_url) }}"
                                       placeholder="/trial"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('button_url') border-red-500 @enderror">
                                @error('button_url')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Status --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Sort Order --}}
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                                Sort Order
                            </label>
                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', $pricingPlan->sort_order) }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('sort_order') border-red-500 @enderror">
                            @error('sort_order')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Active Status --}}
                        <div>
                            <x-ui.toggle 
                                name="is_active"
                                :checked="old('is_active', $pricingPlan->is_active)"
                                label="Active"
                            />
                        </div>

                        {{-- Popular Badge --}}
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="is_popular"
                                       value="1"
                                       {{ old('is_popular', $pricingPlan->is_popular) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="ml-2 text-sm font-medium text-gray-700">Mark as Popular</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <button type="submit"
                            class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                        <i class="fa-solid fa-save mr-2"></i>
                        Update Plan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const div = document.createElement('div');
    div.className = 'feature-item flex items-center gap-2 mb-2';
    div.innerHTML = `
        <input type="text"
               name="features[]"
               placeholder="Feature description"
               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
        <button type="button" onclick="removeFeature(this)" class="text-red-600 hover:text-red-800">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeFeature(button) {
    const container = document.getElementById('features-container');
    if (container.children.length > 1) {
        button.closest('.feature-item').remove();
    }
}

</script>
</x-layouts.admin>
