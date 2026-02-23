<x-layouts.admin title="Create Pricing Feature">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Pricing Feature</h1>
            <p class="text-gray-600">Add a new feature to the comparison table</p>
        </div>
        <a href="{{ route('admin.pricing-feature.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>Back to List
        </a>
    </div>

    <form action="{{ route('admin.pricing-feature.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Feature Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="category" name="category" value="{{ old('category') }}" required
                                   placeholder="e.g., Invoices, All Features, Expenses"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('category') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Features will be grouped by category</p>
                            @error('category')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Feature Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label for="badge" class="block text-sm font-medium text-gray-700 mb-1">Badge</label>
                            <input type="text" id="badge" name="badge" value="{{ old('badge') }}" 
                                   placeholder="e.g., Coming soon"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            <p class="text-xs text-gray-500 mt-1">Optional badge to display next to feature name</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Available in Plans
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="available_in_plans[]" value="smart" 
                                           {{ is_array(old('available_in_plans')) && in_array('smart', old('available_in_plans')) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-700">SMART Plan</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="available_in_plans[]" value="grow" 
                                           {{ is_array(old('available_in_plans')) && in_array('grow', old('available_in_plans')) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-700">GROW Plan</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="available_in_plans[]" value="flow" 
                                           {{ is_array(old('available_in_plans')) && in_array('flow', old('available_in_plans')) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-700">FLOW Plan</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
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
                            :checked="old('is_active', true)"
                            label="Active"
                        />
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                    <button type="submit" class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                        <i class="fa-solid fa-save mr-2"></i>Create Feature
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
</x-layouts.admin>
