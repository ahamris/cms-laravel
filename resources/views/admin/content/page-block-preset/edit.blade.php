<x-layouts.admin title="Edit Page Block Preset">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Page Block Preset</h1>
            <p class="text-gray-600">Update block preset configuration</p>
        </div>
        <a href="{{ route('admin.content.page-block-preset.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.content.page-block-preset.update', $pageBlockPreset) }}" method="POST" class="space-y-6">
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
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $pageBlockPreset->name) }}"
                                   placeholder="Enter preset name"
                                   required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror">
                            @error('name')
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
                                      placeholder="Enter description (optional)"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('description') border-red-500 @enderror">{{ old('description', $pageBlockPreset->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Type --}}
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                                Type <span class="text-red-500">*</span>
                            </label>
                            <select id="type"
                                    name="type"
                                    required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('type') border-red-500 @enderror">
                                <option value="">Select type</option>
                                <option value="header" {{ old('type', $pageBlockPreset->type) === 'header' ? 'selected' : '' }}>Header</option>
                                <option value="body" {{ old('type', $pageBlockPreset->type) === 'body' ? 'selected' : '' }}>Body</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Blocks (JSON) --}}
                        <div>
                            <label for="blocks" class="block text-sm font-medium text-gray-700 mb-1">
                                Blocks (JSON) <span class="text-red-500">*</span>
                            </label>
                            <textarea id="blocks"
                                      name="blocks"
                                      rows="15"
                                      placeholder='[{"id": 123, "category": "marketing", "section": "header-sections", "name": "Centered", "path": "marketing/header-sections/centered", "html": "...", "position": 0}]'
                                      required
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 font-mono text-xs focus:ring-2 focus:ring-primary focus:border-primary @error('blocks') border-red-500 @enderror">{{ old('blocks', is_string($pageBlockPreset->blocks) ? $pageBlockPreset->blocks : json_encode($pageBlockPreset->blocks, JSON_PRETTY_PRINT)) }}</textarea>
                            @error('blocks')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Enter blocks as JSON array. Each block should have: id, category, section, name, path, html, position</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Status --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $pageBlockPreset->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Active
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Only active presets will be available in the block selector</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('admin.content.page-block-preset.index') }}"
               class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit"
                    class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                Update Preset
            </button>
        </div>
    </form>
</div>
</x-layouts.admin>

