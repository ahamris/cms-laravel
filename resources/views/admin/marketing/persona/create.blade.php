<x-layouts.admin title="Create Marketing Persona">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Marketing Persona</h1>
            <p class="text-gray-600">Define a new customer persona for targeted content strategy</p>
        </div>
        <a href="{{ route('admin.marketing.persona.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.marketing.persona.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Persona Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="e.g., Startende Ondernemer"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Slug --}}
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   value="{{ old('slug') }}"
                                   placeholder="Auto-generated from name (e.g., startende-ondernemer)"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('slug') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate</p>
                            @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Brief description of this persona"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Demographics --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Demographics</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="age_range" class="block text-sm font-medium text-gray-700 mb-2">Age Range</label>
                                <input type="text"
                                       id="age_range"
                                       name="demographics[age_range]"
                                       value="{{ old('demographics.age_range') }}"
                                       placeholder="e.g., 25-40"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label for="company_size" class="block text-sm font-medium text-gray-700 mb-2">Company Size</label>
                                <input type="text"
                                       id="company_size"
                                       name="demographics[company_size]"
                                       value="{{ old('demographics.company_size') }}"
                                       placeholder="e.g., 1-5 employees"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label for="industry" class="block text-sm font-medium text-gray-700 mb-2">Industry</label>
                                <input type="text"
                                       id="industry"
                                       name="demographics[industry]"
                                       value="{{ old('demographics.industry') }}"
                                       placeholder="e.g., Technology, Healthcare"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                <input type="text"
                                       id="location"
                                       name="demographics[location]"
                                       value="{{ old('demographics.location') }}"
                                       placeholder="e.g., Netherlands, Europe"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pain Points & Goals --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Pain Points --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Pain Points</h3>
                        </div>
                        <div class="p-6">
                            <div id="pain-points-container">
                                <div class="pain-point-item flex items-center space-x-2 mb-2">
                                    <input type="text"
                                           name="pain_points[]"
                                           value="{{ old('pain_points.0') }}"
                                           placeholder="Enter a pain point"
                                           class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                    <button type="button" onclick="removePainPoint(this)" class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" onclick="addPainPoint()" class="text-primary hover:text-primary/80 text-sm">
                                <i class="fa-solid fa-plus mr-1"></i> Add Pain Point
                            </button>
                        </div>
                    </div>

                    {{-- Goals --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Goals</h3>
                        </div>
                        <div class="p-6">
                            <div id="goals-container">
                                <div class="goal-item flex items-center space-x-2 mb-2">
                                    <input type="text"
                                           name="goals[]"
                                           value="{{ old('goals.0') }}"
                                           placeholder="Enter a goal"
                                           class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                    <button type="button" onclick="removeGoal(this)" class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" onclick="addGoal()" class="text-primary hover:text-primary/80 text-sm">
                                <i class="fa-solid fa-plus mr-1"></i> Add Goal
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Avatar --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Avatar Image</h3>
                    </div>
                    <div class="p-6">
                        <input type="file"
                               id="avatar_image"
                               name="avatar_image"
                               accept="image/*"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('avatar_image') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Max 20MB. JPG, PNG, GIF supported.</p>
                        @error('avatar_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
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
                            Create Persona
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function addPainPoint() {
    const container = document.getElementById('pain-points-container');
    const div = document.createElement('div');
    div.className = 'pain-point-item flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="pain_points[]" placeholder="Enter a pain point" 
               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
        <button type="button" onclick="removePainPoint(this)" class="text-red-600 hover:text-red-800">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removePainPoint(button) {
    button.closest('.pain-point-item').remove();
}

function addGoal() {
    const container = document.getElementById('goals-container');
    const div = document.createElement('div');
    div.className = 'goal-item flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="goals[]" placeholder="Enter a goal" 
               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
        <button type="button" onclick="removeGoal(this)" class="text-red-600 hover:text-red-800">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeGoal(button) {
    button.closest('.goal-item').remove();
}
</script>
</x-layouts.admin>
