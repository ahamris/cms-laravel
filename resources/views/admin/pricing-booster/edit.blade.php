<x-layouts.admin title="Edit Pricing Booster">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Pricing Booster</h1>
            <p class="text-gray-600">Update booster details</p>
        </div>
        <a href="{{ route('admin.pricing-booster.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>Back to List
        </a>
    </div>

    <form action="{{ route('admin.pricing-booster.update', $pricingBooster) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Booster Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Booster Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $pricingBooster->name) }}" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $pricingBooster->slug) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">{{ old('description', $pricingBooster->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                    Price <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">€</span>
                                    <input type="number" id="price" name="price" value="{{ old('price', $pricingBooster->price) }}" required step="0.01" min="0"
                                           class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                </div>
                            </div>

                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $pricingBooster->sort_order) }}" min="0"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="link_text" class="block text-sm font-medium text-gray-700 mb-1">Link Text</label>
                                <input type="text" id="link_text" name="link_text" value="{{ old('link_text', $pricingBooster->link_text) }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label for="link_url" class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
                                <input type="text" id="link_url" name="link_url" value="{{ old('link_url', $pricingBooster->link_url) }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                        </div>

                        <div>
                            <label for="footnote" class="block text-sm font-medium text-gray-700 mb-1">Footnote</label>
                            <input type="text" id="footnote" name="footnote" value="{{ old('footnote', $pricingBooster->footnote) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                    </div>
                    <div class="p-6">
                        <x-ui.toggle 
                            name="is_active"
                            :checked="old('is_active', $pricingBooster->is_active)"
                            label="Active"
                        />
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                    <button type="submit" class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                        <i class="fa-solid fa-save mr-2"></i>Update Booster
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
</x-layouts.admin>
