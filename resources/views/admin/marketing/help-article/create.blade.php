<x-layouts.admin title="Create Help Article">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Help Article</h1>
            <p class="text-gray-600">Add a new article to the knowledge base</p>
        </div>
        <a href="{{ route('admin.marketing.help-article.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.marketing.help-article.store') }}" method="POST" class="space-y-6">
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
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Article Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   required
                                   placeholder="e.g., How to Set Up Automatic Invoicing"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('title') border-red-500 @enderror">
                            @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Slug --}}
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   value="{{ old('slug') }}"
                                   data-slug-from="title"
                                   placeholder="Auto-generated from title"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('slug') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate</p>
                            @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Excerpt --}}
                        <div>
                            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                            <textarea id="excerpt"
                                      name="excerpt"
                                      rows="2"
                                      placeholder="Brief summary of the article"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('excerpt') border-red-500 @enderror">{{ old('excerpt') }}</textarea>
                            @error('excerpt')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Article Content</h3>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content"
                                      name="content"
                                      rows="15"
                                      required
                                      placeholder="Write your help article content here..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                            @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Product Features --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Related Product Features</h3>
                        <p class="text-sm text-gray-600">Select features this article covers</p>
                    </div>
                    <div class="p-6">
                        @if($productFeatures->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($productFeatures as $feature)
                                    <div class="flex items-center">
                                        <input type="checkbox"
                                               id="feature_{{ $feature->id }}"
                                               name="product_features[]"
                                               value="{{ $feature->id }}"
                                               {{ in_array($feature->id, old('product_features', [])) ? 'checked' : '' }}
                                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                        <label for="feature_{{ $feature->id }}" class="ml-2 block text-sm text-gray-900">
                                            @if($feature->icon)
                                                <i class="fa-solid {{ $feature->icon }} mr-1"></i>
                                            @endif
                                            {{ $feature->name }}
                                            @if($feature->is_premium)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 ml-1">
                                                    <i class="fa-solid fa-crown mr-1"></i>Premium
                                                </span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No product features available. Create some features first.</p>
                        @endif
                    </div>
                </div>

                {{-- Tags --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Tags</h3>
                    </div>
                    <div class="p-6">
                        <div id="tags-container">
                            <div class="tag-item flex items-center space-x-2 mb-2">
                                <input type="text"
                                       name="tags[]"
                                       value="{{ old('tags.0') }}"
                                       placeholder="e.g., Setup, Configuration, Beginner"
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

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Article Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Article Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Difficulty Level --}}
                        <div>
                            <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-2">
                                Difficulty Level <span class="text-red-500">*</span>
                            </label>
                            <select id="difficulty_level"
                                    name="difficulty_level"
                                    required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('difficulty_level') border-red-500 @enderror">
                                <option value="">Select difficulty</option>
                                <option value="beginner" {{ old('difficulty_level') == 'beginner' ? 'selected' : '' }}>
                                    <i class="fa-solid fa-seedling"></i> Beginner
                                </option>
                                <option value="intermediate" {{ old('difficulty_level') == 'intermediate' ? 'selected' : '' }}>
                                    <i class="fa-solid fa-graduation-cap"></i> Intermediate
                                </option>
                                <option value="advanced" {{ old('difficulty_level') == 'advanced' ? 'selected' : '' }}>
                                    <i class="fa-solid fa-rocket"></i> Advanced
                                </option>
                            </select>
                            @error('difficulty_level')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Estimated Read Time --}}
                        <div>
                            <label for="estimated_read_time" class="block text-sm font-medium text-gray-700 mb-2">Estimated Read Time (minutes)</label>
                            <input type="number"
                                   id="estimated_read_time"
                                   name="estimated_read_time"
                                   value="{{ old('estimated_read_time') }}"
                                   min="1"
                                   placeholder="e.g., 5"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('estimated_read_time') border-red-500 @enderror">
                            @error('estimated_read_time')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
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

                {{-- Status Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Status</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Featured --}}
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="is_featured"
                                   name="is_featured"
                                   value="1"
                                   {{ old('is_featured') ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                <i class="fa-solid fa-star text-yellow-500 mr-1"></i>
                                Featured Article
                            </label>
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
                    </div>
                </div>

                {{-- Submit --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6">
                        <button type="submit"
                                class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary/80 transition-colors duration-200">
                            <i class="fa-solid fa-save mr-2"></i>
                            Create Article
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
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
</script>
@push('scripts')
<x-ui.slug-script />
@endpush
</x-layouts.admin>
