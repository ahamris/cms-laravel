<x-layouts.admin title="Create Module List Widget">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Module List Widget</h1>
            <p class="text-gray-600">Create a new module list widget for displaying module information</p>
        </div>
        <a href="{{ route('admin.content.module-list-widgets.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.content.module-list-widgets.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Title
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   placeholder="e.g., Modules Header"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('title') border-red-500 @enderror">
                            @error('title')
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
                                      placeholder="Optional description text"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Modules Configuration --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Modules Configuration</h3>
                        <p class="text-sm text-gray-600 mt-1">Configure the modules and their items</p>
                    </div>
                    <div class="p-6">
                        <div id="modules-container" class="space-y-6">
                            <!-- Modules will be added here dynamically -->
                        </div>
                        <button type="button" onclick="addModule()" 
                                class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fa-solid fa-plus mr-2"></i>
                            Add Module
                        </button>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status & Order --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Status & Order</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Active Status --}}
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                                Active
                            </label>
                        </div>

                        {{-- Sort Order --}}
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                                Sort Order
                            </label>
                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', 0) }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('sort_order') border-red-500 @enderror">
                            @error('sort_order')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <div class="space-y-3">
                            <button type="submit"
                                    class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                                <i class="fa-solid fa-save mr-2"></i>
                                Create Module List Widget
                            </button>
                            <a href="{{ route('admin.content.module-list-widgets.index') }}"
                               class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200 text-center block">
                                <i class="fa-solid fa-times mr-2"></i>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let moduleIndex = 0;

function addModule() {
    const container = document.getElementById('modules-container');
    const moduleHtml = `
        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50" data-module-index="${moduleIndex}">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-medium text-gray-900">Module ${moduleIndex + 1}</h4>
                <button type="button" onclick="removeModule(this)" class="text-red-600 hover:text-red-800">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Module Name</label>
                    <input type="text" 
                           name="modules[${moduleIndex}][name]" 
                           placeholder="e.g., Module 1"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Module Items</label>
                    <div id="items-container-${moduleIndex}" class="space-y-2">
                        <!-- Items will be added here -->
                    </div>
                    <button type="button" onclick="addItem(${moduleIndex})" 
                            class="mt-2 text-sm bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition-colors duration-200">
                        <i class="fa-solid fa-plus mr-1"></i>
                        Add Item
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', moduleHtml);
    addItem(moduleIndex); // Add one default item
    moduleIndex++;
}

function addItem(moduleIndex) {
    const container = document.getElementById(`items-container-${moduleIndex}`);
    const itemIndex = container.children.length;
    const itemHtml = `
        <div class="flex gap-2">
            <input type="text" 
                   name="modules[${moduleIndex}][items][${itemIndex}]" 
                   placeholder="Enter item name"
                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
            <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 px-2">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', itemHtml);
}

function removeItem(button) {
    button.parentElement.remove();
}

function removeModule(button) {
    button.closest('[data-module-index]').remove();
}

// Add initial module on page load
document.addEventListener('DOMContentLoaded', function() {
    addModule();
});
</script>
</x-layouts.admin>
