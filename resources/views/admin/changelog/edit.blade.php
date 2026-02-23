<x-layouts.admin title="Edit Changelog Entry">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-edit text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Edit Changelog Entry</h2>
                <p>Update changelog entry information</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.content.changelog.show', $changelog) }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-eye"></i>
                <span>View</span>
            </a>
            <a href="{{ route('admin.content.changelog.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <form action="{{ route('admin.content.changelog.update', $changelog) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                        Basic Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-3">
                            <label for="title" class="block text-xs font-medium text-gray-700 mb-1">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $changelog->title) }}" 
                                   class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('title') border-red-500 @enderror" 
                                   placeholder="Enter changelog title"
                                   maxlength="255"
                                   required>
                            @error('title')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="date" class="block text-xs font-medium text-gray-700 mb-1">
                                Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', $changelog->date->format('Y-m-d')) }}" 
                                   class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('date') border-red-500 @enderror" 
                                   required>
                            @error('date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-3">
                            <label for="description" class="block text-xs font-medium text-gray-700 mb-1">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3" 
                                      class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('description') border-red-500 @enderror" 
                                      placeholder="Brief description for listing page"
                                      maxlength="1000"
                                      required>{{ old('description', $changelog->description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Maximum 1000 characters</p>
                            @error('description')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-xs font-medium text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" 
                                    name="status" 
                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('status') border-red-500 @enderror" 
                                    required>
                                <option value="">Select Status</option>
                                <option value="new" {{ old('status', $changelog->status) == 'new' ? 'selected' : '' }}>New Feature</option>
                                <option value="improved" {{ old('status', $changelog->status) == 'improved' ? 'selected' : '' }}>Improvement</option>
                                <option value="fixed" {{ old('status', $changelog->status) == 'fixed' ? 'selected' : '' }}>Bug Fix</option>
                                <option value="api" {{ old('status', $changelog->status) == 'api' ? 'selected' : '' }}>API Update</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="content" class="block text-xs font-medium text-gray-700 mb-1">
                            Detailed Content
                        </label>
                        <x-editor 
                            id="content"
                            name="content"
                            :value="old('content', $changelog->content)"
                            placeholder="Detailed content for the changelog entry page" />
                        <p class="mt-1 text-xs text-gray-500">Detailed description shown on the individual changelog page</p>
                        @error('content')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="video_url" class="block text-xs font-medium text-gray-700 mb-1">
                            Video URL
                        </label>
                        <input type="url" 
                               id="video_url" 
                               name="video_url" 
                               value="{{ old('video_url', $changelog->video_url) }}" 
                               class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('video_url') border-red-500 @enderror" 
                               placeholder="https://example.com/video.mp4 or YouTube/Vimeo URL"
                               maxlength="500">
                        <p class="mt-1 text-xs text-gray-500">Optional video to display before the title. Supports direct video URLs, YouTube, and Vimeo links.</p>
                        @error('video_url')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div class="md:col-span-3">
                            <x-ui.input id="slug" name="slug" label="Slug" :value="old('slug', $changelog->slug)"
                                placeholder="url-friendly-title"
                                slug-from="title"
                                hint="Leave empty to auto-generate from title."
                                :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />
                        </div>
                        <div class="md:col-span-2">
                            <label for="sort_order" class="block text-xs font-medium text-gray-700 mb-1">
                                Sort Order
                            </label>
                            <input type="number" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   value="{{ old('sort_order', $changelog->sort_order) }}" 
                                   class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('sort_order') border-red-500 @enderror" 
                                   min="0"
                                   placeholder="0">
                            @error('sort_order')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-end">
                            <div class="flex items-center h-10">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       class="h-4 w-4 text-primary border-gray-300 rounded focus:outline-none" 
                                       {{ old('is_active', $changelog->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 text-xs text-gray-700">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Features --}}
                <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-star mr-2 text-yellow-500"></i>
                        Features
                    </h3>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Feature List</label>
                        <div id="features-container">
                            @php
                                $features = old('features', $changelog->features ?? []);
                            @endphp
                            @if(!empty($features))
                                @foreach($features as $index => $feature)
                                    <div class="flex mb-2 feature-item">
                                        <input type="text" 
                                               class="flex-1 px-3 py-2 text-sm bg-white border border-gray-200 rounded-l-md focus:outline-none" 
                                               name="features[]" 
                                               value="{{ $feature }}" 
                                               placeholder="Enter feature description"
                                               maxlength="500">
                                        <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-r-md hover:bg-red-600 remove-feature">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex mb-2 feature-item">
                                    <input type="text" 
                                           class="flex-1 px-3 py-2 text-sm bg-white border border-gray-200 rounded-l-md focus:outline-none" 
                                           name="features[]" 
                                           placeholder="Enter feature description"
                                           maxlength="500">
                                    <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-r-md hover:bg-red-600 remove-feature">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="mt-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm" id="add-feature">
                            <i class="fas fa-plus mr-1"></i>
                            Add Feature
                        </button>
                        <p class="mt-2 text-xs text-gray-500">Add key features or highlights of this changelog entry</p>
                    </div>
                </div>

                {{-- Implementation Steps --}}
                <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center">
                        <i class="fa-solid fa-list-ol mr-2 text-green-500"></i>
                        Implementation Steps
                    </h3>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Step-by-Step Guide</label>
                        <div id="steps-container">
                            @php
                                $steps = old('steps', $changelog->steps ?? []);
                            @endphp
                            @if(!empty($steps))
                                @foreach($steps as $index => $step)
                                    <div class="flex mb-2 step-item">
                                        <span class="px-3 py-2 bg-gray-100 border border-r-0 border-gray-200 text-xs font-medium text-gray-700 rounded-l-md">{{ $index + 1 }}</span>
                                        <input type="text" 
                                               class="flex-1 px-3 py-2 text-sm bg-white border border-gray-200 focus:outline-none" 
                                               name="steps[]" 
                                               value="{{ $step }}" 
                                               placeholder="Enter implementation step"
                                               maxlength="500">
                                        <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-r-md hover:bg-red-600 remove-step">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex mb-2 step-item">
                                    <span class="px-3 py-2 bg-gray-100 border border-r-0 border-gray-200 text-xs font-medium text-gray-700 rounded-l-md">1</span>
                                    <input type="text" 
                                           class="flex-1 px-3 py-2 text-sm bg-white border border-gray-200 focus:outline-none" 
                                           name="steps[]" 
                                           placeholder="Enter implementation step"
                                           maxlength="500">
                                    <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-r-md hover:bg-red-600 remove-step">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="mt-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm" id="add-step">
                            <i class="fas fa-plus mr-1"></i>
                            Add Step
                        </button>
                        <p class="mt-2 text-xs text-gray-500">Add implementation or usage steps for this feature</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/80 flex justify-between rounded-b-md">
                <a href="{{ route('admin.content.changelog.index') }}" class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200">
                    Update Changelog Entry
                </button>
            </div>
        </form>
    </div>
</div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Features management
    const featuresContainer = document.getElementById('features-container');
    const addFeatureBtn = document.getElementById('add-feature');

    addFeatureBtn.addEventListener('click', function() {
        const featureItem = document.createElement('div');
        featureItem.className = 'flex mb-2 feature-item';
        featureItem.innerHTML = `
            <input type="text" 
                   class="flex-1 px-3 py-2 text-sm bg-white border border-gray-200 rounded-l-md focus:outline-none" 
                   name="features[]" 
                   placeholder="Enter feature description"
                   maxlength="500">
            <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-r-md hover:bg-red-600 remove-feature">
                <i class="fas fa-times"></i>
            </button>
        `;
        featuresContainer.appendChild(featureItem);
    });

    featuresContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-feature') || e.target.parentElement.classList.contains('remove-feature')) {
            const featureItem = e.target.closest('.feature-item');
            if (featuresContainer.children.length > 1) {
                featureItem.remove();
            }
        }
    });

    // Steps management
    const stepsContainer = document.getElementById('steps-container');
    const addStepBtn = document.getElementById('add-step');

    function updateStepNumbers() {
        const stepItems = stepsContainer.querySelectorAll('.step-item');
        stepItems.forEach((item, index) => {
            const numberSpan = item.querySelector('span');
            numberSpan.textContent = index + 1;
        });
    }

    addStepBtn.addEventListener('click', function() {
        const stepCount = stepsContainer.children.length + 1;
        const stepItem = document.createElement('div');
        stepItem.className = 'flex mb-2 step-item';
        stepItem.innerHTML = `
            <span class="px-3 py-2 bg-gray-100 border border-r-0 border-gray-200 text-xs font-medium text-gray-700 rounded-l-md">${stepCount}</span>
            <input type="text" 
                   class="flex-1 px-3 py-2 text-sm bg-white border border-gray-200 focus:outline-none" 
                   name="steps[]" 
                   placeholder="Enter implementation step"
                   maxlength="500">
            <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-r-md hover:bg-red-600 remove-step">
                <i class="fas fa-times"></i>
            </button>
        `;
        stepsContainer.appendChild(stepItem);
    });

    stepsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-step') || e.target.parentElement.classList.contains('remove-step')) {
            const stepItem = e.target.closest('.step-item');
            if (stepsContainer.children.length > 1) {
                stepItem.remove();
                updateStepNumbers();
            }
        }
    });

    // Auto-generate slug from title (only if slug is empty)
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const originalSlug = slugInput.value;

    titleInput.addEventListener('input', function() {
        if (!slugInput.value || (!originalSlug && slugInput.dataset.manual !== 'true')) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
        }
    });

    slugInput.addEventListener('input', function() {
        this.dataset.manual = 'true';
    });
});
</script>
    </script>
</x-layouts.admin>
