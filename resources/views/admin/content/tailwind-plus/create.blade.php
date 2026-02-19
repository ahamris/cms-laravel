<x-layouts.admin title="Create TailwindPlus Component">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-code text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Create Component</h2>
                <p>Add a new Tailwind UI component</p>
            </div>
        </div>
        <a href="{{ route('admin.content.tailwind-plus.index') }}"
           class="px-5 py-2 rounded-md bg-gray-500 text-white text-sm hover:bg-gray-600 transition-colors duration-200 flex items-center space-x-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Components</span>
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
            <i class="fa-solid fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
            <i class="fa-solid fa-exclamation-circle mr-2"></i>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form action="{{ route('admin.content.tailwind-plus.store') }}" method="POST">
            @csrf

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        {{-- Category --}}
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select id="category"
                                    name="category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('category') border-red-500 @enderror"
                                    required>
                                <option value="">Select a category...</option>
                                <option value="MARKETING" {{ old('category') === 'MARKETING' ? 'selected' : '' }}>MARKETING</option>
                                <option value="APPLICATION UI" {{ old('category') === 'APPLICATION UI' ? 'selected' : '' }}>APPLICATION UI</option>
                                <option value="ECOMMERCE" {{ old('category') === 'ECOMMERCE' ? 'selected' : '' }}>ECOMMERCE</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Component Group --}}
                        <div x-data="{ showNewGroupInput: false, selectedGroup: '{{ old('component_group') ?? '' }}', newGroup: '' }">
                            <label for="component_group" class="block text-sm font-medium text-gray-700 mb-2">
                                Component Group
                            </label>
                            <div class="flex gap-2">
                                <select x-model="selectedGroup"
                                        @change="showNewGroupInput = false; newGroup = ''"
                                        :name="showNewGroupInput ? '' : 'component_group'"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('component_group') border-red-500 @enderror">
                                    <option value="">Select a group...</option>
                                    @foreach($componentGroups ?? [] as $group)
                                        <option value="{{ $group }}" {{ old('component_group') === $group ? 'selected' : '' }}>{{ $group }}</option>
                                    @endforeach
                                </select>
                                <button type="button"
                                        @click="showNewGroupInput = !showNewGroupInput; if (!showNewGroupInput) { selectedGroup = ''; newGroup = ''; }"
                                        class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center"
                                        :class="showNewGroupInput ? 'bg-primary text-white border-primary hover:bg-primary/80' : 'bg-white text-gray-700'">
                                    <i class="fa-solid" :class="showNewGroupInput ? 'fa-times' : 'fa-plus'"></i>
                                </button>
                            </div>
                            <div x-show="showNewGroupInput" x-transition class="mt-2">
                                <input type="text"
                                       x-model="newGroup"
                                       :name="showNewGroupInput ? 'component_group' : ''"
                                       value=""
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('component_group') border-red-500 @enderror"
                                       placeholder="Enter new group name...">
                            </div>
                            @error('component_group')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Select existing group or add a new one</p>
                        </div>

                        {{-- Component Name --}}
                        <div>
                            <label for="component_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Component Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="component_name"
                                   name="component_name"
                                   value="{{ old('component_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('component_name') border-red-500 @enderror"
                                   placeholder="e.g., Primary Button"
                                   required>
                            @error('component_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Version --}}
                        <div>
                            <label for="version" class="block text-sm font-medium text-gray-700 mb-2">
                                Version <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   id="version"
                                   name="version"
                                   value="{{ old('version', 1) }}"
                                   min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('version') border-red-500 @enderror"
                                   required>
                            @error('version')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Component version number</p>
                        </div>

                        {{-- Is Active --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <x-ui.toggle name="is_active" label="Active" :checked="old('is_active', true)" />
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Code --}}
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                Code <span class="text-red-500">*</span>
                            </label>
                            <textarea id="code"
                                      name="code"
                                      rows="20"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 font-mono text-xs @error('code') border-red-500 @enderror"
                                      placeholder="Paste your HTML/Tailwind code here..."
                                      required>{{ old('code') }}</textarea>
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">HTML/Tailwind CSS code for the component</p>
                        </div>

                        {{-- Preview --}}
                        <div>
                            <label for="preview" class="block text-sm font-medium text-gray-700 mb-2">
                                Preview
                            </label>
                            <textarea id="preview"
                                      name="preview"
                                      rows="8"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors duration-200 @error('preview') border-red-500 @enderror"
                                      placeholder="Optional preview description or notes">{{ old('preview') }}</textarea>
                            @error('preview')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Optional preview description or notes</p>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.content.tailwind-plus.index') }}"
                       class="px-5 py-2 rounded-md bg-gray-200 text-gray-700 text-sm hover:bg-gray-300 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fa-solid fa-save"></i>
                        <span>Create Component</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</x-layouts.admin>

