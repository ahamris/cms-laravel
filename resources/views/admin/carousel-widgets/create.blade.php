<x-layouts.admin title="Create Carousel Widget">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Carousel Widget</h1>
            <p class="text-gray-600">Create a new carousel widget for displaying content</p>
        </div>
        <a href="{{ route('admin.carousel-widgets.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.carousel-widgets.store') }}" method="POST" class="space-y-6">
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
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="identifier" class="block text-sm font-medium text-gray-700 mb-1">
                                Identifier <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="identifier"
                                   name="identifier"
                                   value="{{ old('identifier') }}"
                                   placeholder="blog_carousel"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('identifier') border-red-500 @enderror"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Unique identifier (e.g., blog_carousel, news_carousel)</p>
                            @error('identifier')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                Title
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   placeholder="Latest Articles"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea id="description"
                                      name="description"
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
                                      placeholder="Display latest blog articles">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Data Source Configuration --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Data Source</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="data_source" class="block text-sm font-medium text-gray-700 mb-1">
                                Data Source <span class="text-red-500">*</span>
                            </label>
                            <select id="data_source"
                                    name="data_source"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
                                    onchange="toggleDataSourceFields()">
                                <option value="blog" {{ old('data_source', 'blog') === 'blog' ? 'selected' : '' }}>Blog Articles</option>
                                <option value="custom" {{ old('data_source') === 'custom' ? 'selected' : '' }}>Custom Content</option>
                            </select>
                        </div>

                        <div id="blogCategoryField" style="display: {{ old('data_source', 'blog') === 'blog' ? 'block' : 'none' }};">
                            <label for="blog_category_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Blog Category (Optional)
                            </label>
                            <select id="blog_category_id"
                                    name="blog_category_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                <option value="">All Categories</option>
                                @foreach($blogCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('blog_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Filter articles by category. Leave empty to show all articles.</p>
                        </div>
                    </div>
                </div>

                {{-- Carousel Layout --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Layout Configuration</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="items_per_row" class="block text-sm font-medium text-gray-700 mb-1">
                                    Items Per Row <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="items_per_row"
                                       name="items_per_row"
                                       value="{{ old('items_per_row', 3) }}"
                                       min="1"
                                       max="12"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('items_per_row') border-red-500 @enderror"
                                       required>
                                <p class="text-xs text-gray-500 mt-1">Number of items to display per row (1-12)</p>
                                @error('items_per_row')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="total_items" class="block text-sm font-medium text-gray-700 mb-1">
                                    Total Items <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="total_items"
                                       name="total_items"
                                       value="{{ old('total_items', 6) }}"
                                       min="1"
                                       max="50"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
                                       required>
                                <p class="text-xs text-gray-500 mt-1">Maximum items to display</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Carousel Behavior --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Carousel Behavior</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <x-ui.toggle
                            name="show_arrows"
                            :checked="old('show_arrows', true)"
                            label="Show Navigation Arrows"
                            description="Display left/right navigation arrows"
                        />

                        <x-ui.toggle
                            name="show_dots"
                            :checked="old('show_dots', true)"
                            label="Show Dots Indicator"
                            description="Display pagination dots below carousel"
                        />

                        <x-ui.toggle
                            name="show_author"
                            :checked="old('show_author', true)"
                            label="Show Author"
                            description="Display author name in article meta"
                        />

                        <x-ui.toggle
                            name="autoplay"
                            id="autoplay"
                            :checked="old('autoplay', false)"
                            label="Autoplay"
                            description="Automatically advance slides"
                            onchange="toggleAutoplaySpeed()"
                        />

                        <div id="autoplaySpeedField" style="display: {{ old('autoplay') ? 'block' : 'none' }};">
                            <label for="autoplay_speed" class="block text-sm font-medium text-gray-700 mb-1">
                                Autoplay Speed (milliseconds)
                            </label>
                            <input type="number"
                                   id="autoplay_speed"
                                   name="autoplay_speed"
                                   value="{{ old('autoplay_speed', 3000) }}"
                                   min="1000"
                                   max="10000"
                                   step="500"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            <p class="text-xs text-gray-500 mt-1">Time between slides (1000-10000ms)</p>
                        </div>

                        <x-ui.toggle
                            name="infinite_loop"
                            :checked="old('infinite_loop', true)"
                            label="Infinite Loop"
                            description="Loop back to first slide after last"
                        />

                        <x-ui.toggle
                            name="show_view_all_button"
                            id="show_view_all_button"
                            :checked="old('show_view_all_button', false)"
                            label="Show View All Button"
                            description="Display a 'View All' card as the last item in the carousel"
                            onchange="toggleViewAllFields()"
                        />

                        <div id="viewAllFields" style="display: {{ old('show_view_all_button') ? 'block' : 'none' }};">
                            <div class="space-y-4">
                                <div>
                                    <label for="view_all_title" class="block text-sm font-medium text-gray-700 mb-1">
                                        View All Title
                                    </label>
                                    <input type="text"
                                           id="view_all_title"
                                           name="view_all_title"
                                           value="{{ old('view_all_title', 'View All Articles') }}"
                                           placeholder="View All Articles"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                                    <p class="text-xs text-gray-500 mt-1">Title for the view all button card</p>
                                </div>

                                <div>
                                    <label for="view_all_description" class="block text-sm font-medium text-gray-700 mb-1">
                                        View All Description
                                    </label>
                                    <textarea id="view_all_description"
                                              name="view_all_description"
                                              rows="2"
                                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary"
                                              placeholder="Discover all our blog articles">{{ old('view_all_description') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Description text for the view all button card</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">
                                Sort Order
                            </label>
                            <input type="number"
                                   id="sort_order"
                                   name="sort_order"
                                   value="{{ old('sort_order', 0) }}"
                                   min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-primary">
                            <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                        </div>

                        <div>
                            <x-ui.toggle
                                name="is_active"
                                :checked="old('is_active', true)"
                                label="Set as Active Carousel Widget"
                                description="Activate this carousel widget. Only active widgets are displayed on the website."
                            />
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
                                Create Carousel Widget
                            </button>
                            <a href="{{ route('admin.carousel-widgets.index') }}"
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
    function toggleDataSourceFields() {
        const dataSource = document.getElementById('data_source').value;
        const blogCategoryField = document.getElementById('blogCategoryField');

        if (dataSource === 'blog') {
            blogCategoryField.style.display = 'block';
        } else {
            blogCategoryField.style.display = 'none';
        }
    }

    function toggleAutoplaySpeed() {
        const autoplay = document.getElementById('autoplay').checked;
        const autoplaySpeedField = document.getElementById('autoplaySpeedField');

        if (autoplay) {
            autoplaySpeedField.style.display = 'block';
        } else {
            autoplaySpeedField.style.display = 'none';
        }
    }

    function toggleViewAllFields() {
        const showViewAll = document.getElementById('show_view_all_button').checked;
        const viewAllFields = document.getElementById('viewAllFields');

        if (showViewAll) {
            viewAllFields.style.display = 'block';
        } else {
            viewAllFields.style.display = 'none';
        }
    }

</script>
</x-layouts.admin>
