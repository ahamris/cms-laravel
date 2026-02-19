<x-layouts.admin title="Edit About Section">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-edit text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Edit About Section</h2>
                <p>Update about section information</p>
            </div>
        </div>
        <a href="{{ route('admin.content.about.index') }}"
           class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
            <i class="fa fa-arrow-left"></i>
            <span>Back to List</span>
        </a>
    </div>

    {{-- Form --}}
    <div>
        <form action="{{ route('admin.content.about.update', $about) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Basic Information --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                                Basic Information
                            </h3>
                            <div class="space-y-4">
                                {{-- Anchor & Nav Title --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="anchor" class="block text-xs font-medium text-gray-700 mb-1">
                                            Anchor ID <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               id="anchor"
                                               name="anchor"
                                               value="{{ old('anchor', $about->anchor) }}"
                                               required
                                               placeholder="e.g., about-us"
                                               class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('anchor') border-red-500 @enderror">
                                        @error('anchor')
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="nav_title" class="block text-xs font-medium text-gray-700 mb-1">
                                            Navigation Title <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                               id="nav_title"
                                               name="nav_title"
                                               value="{{ old('nav_title', $about->nav_title) }}"
                                               required
                                               placeholder="About Us"
                                               class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('nav_title') border-red-500 @enderror">
                                        @error('nav_title')
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Title --}}
                                <div>
                                    <label for="title" class="block text-xs font-medium text-gray-700 mb-1">
                                        Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           id="title"
                                           name="title"
                                           value="{{ old('title', $about->title) }}"
                                           required
                                           placeholder="Main heading for the about section"
                                           class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('title') border-red-500 @enderror">
                                    @error('title')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Subtitle --}}
                                <div>
                                    <label for="subtitle" class="block text-xs font-medium text-gray-700 mb-1">
                                        Subtitle
                                    </label>
                                    <textarea id="subtitle"
                                              name="subtitle"
                                              rows="3"
                                              placeholder="Brief description or subtitle"
                                              class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('subtitle') border-red-500 @enderror">{{ old('subtitle', $about->subtitle) }}</textarea>
                                    @error('subtitle')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Short Body --}}
                                <div>
                                    <label for="short_body" class="block text-xs font-medium text-gray-700 mb-1">
                                        Short Body
                                    </label>
                                    <textarea id="short_body"
                                              name="short_body"
                                              rows="4"
                                              placeholder="Brief content summary"
                                              class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('short_body') border-red-500 @enderror">{{ old('short_body', $about->short_body) }}</textarea>
                                    @error('short_body')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Long Body --}}
                                <div>
                                    <label for="long_body" class="block text-xs font-medium text-gray-700 mb-1">
                                        Long Body
                                    </label>
                                    <x-editor 
                                        id="long_body"
                                        name="long_body"
                                        :value="old('long_body', $about->long_body)"
                                        placeholder="Detailed content description" />
                                    @error('long_body')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- List Items --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">
                                        List Items
                                    </label>
                                    <div id="list-items-container" class="space-y-2">
                                        @if(old('list_items') || $about->list_items)
                                            @foreach(old('list_items', $about->list_items ?? []) as $index => $item)
                                                <div class="flex gap-2">
                                                    <input type="text"
                                                           name="list_items[{{ $index }}]"
                                                           value="{{ $item }}"
                                                           placeholder="Enter list item"
                                                           class="flex-1 px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                                    <button type="button" onclick="removeListItem(this)" class="text-xs px-3 py-2 text-red-600 hover:text-red-800 rounded-md focus:outline-none">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="flex gap-2">
                                                <input type="text"
                                                       name="list_items[0]"
                                                       placeholder="Enter list item"
                                                       class="flex-1 px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                                <button type="button" onclick="removeListItem(this)" class="text-xs px-3 py-2 text-red-600 hover:text-red-800 rounded-md focus:outline-none">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" onclick="addListItem()" class="mt-3 px-4 py-2 text-sm bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200 focus:outline-none">
                                        <i class="fa-solid fa-plus mr-2"></i>
                                        Add List Item
                                    </button>
                                    @error('list_items')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Link Text --}}
                                <div>
                                    <label for="link_text" class="block text-xs font-medium text-gray-700 mb-1">
                                        Link Text
                                    </label>
                                    <input type="text"
                                           id="link_text"
                                           name="link_text"
                                           value="{{ old('link_text', $about->link_text) }}"
                                           placeholder="e.g., Learn More →"
                                           class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('link_text') border-red-500 @enderror">
                                    @error('link_text')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Testimonial Section --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fa-solid fa-quote-left mr-2 text-blue-500"></i>
                                Testimonial
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="testimonial_quote" class="block text-xs font-medium text-gray-700 mb-1">
                                        Quote
                                    </label>
                                    <textarea id="testimonial_quote"
                                              name="testimonial_quote"
                                              rows="3"
                                              placeholder="Customer testimonial quote"
                                              class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('testimonial_quote') border-red-500 @enderror">{{ old('testimonial_quote', $about->testimonial_quote) }}</textarea>
                                    @error('testimonial_quote')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="testimonial_author" class="block text-xs font-medium text-gray-700 mb-1">
                                            Author
                                        </label>
                                        <input type="text"
                                               id="testimonial_author"
                                               name="testimonial_author"
                                               value="{{ old('testimonial_author', $about->testimonial_author) }}"
                                               placeholder="Author name"
                                               class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('testimonial_author') border-red-500 @enderror">
                                        @error('testimonial_author')
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="testimonial_company" class="block text-xs font-medium text-gray-700 mb-1">
                                            Company
                                        </label>
                                        <input type="text"
                                               id="testimonial_company"
                                               name="testimonial_company"
                                               value="{{ old('testimonial_company', $about->testimonial_company) }}"
                                               placeholder="Company name"
                                               class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('testimonial_company') border-red-500 @enderror">
                                        @error('testimonial_company')
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SEO Section --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fa-solid fa-search mr-2 text-blue-500"></i>
                                SEO Settings
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="meta_title" class="block text-xs font-medium text-gray-700 mb-1">
                                        Meta Title
                                    </label>
                                    <input type="text"
                                           id="meta_title"
                                           name="meta_title"
                                           value="{{ old('meta_title', $about->meta_title) }}"
                                           placeholder="SEO meta title (max 60 characters)"
                                           maxlength="60"
                                           class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('meta_title') border-red-500 @enderror">
                                    @error('meta_title')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="meta_description" class="block text-xs font-medium text-gray-700 mb-1">
                                        Meta Description
                                    </label>
                                    <textarea id="meta_description"
                                              name="meta_description"
                                              rows="3"
                                              placeholder="SEO meta description (max 160 characters)"
                                              maxlength="160"
                                              class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('meta_description') border-red-500 @enderror">{{ old('meta_description', $about->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="meta_keywords" class="block text-xs font-medium text-gray-700 mb-1">
                                        Meta Keywords
                                    </label>
                                    <input type="text"
                                           id="meta_keywords"
                                           name="meta_keywords"
                                           value="{{ old('meta_keywords', $about->meta_keywords) }}"
                                           placeholder="Comma-separated keywords"
                                           class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('meta_keywords') border-red-500 @enderror">
                                    @error('meta_keywords')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.content.about.show', $about) }}"
                               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200">
                                View
                            </a>
                            <button type="submit"
                                    class="px-5 py-2 rounded-md bg-primary text-white text-sm hover:bg-primary/80 transition-colors duration-200">
                                Update About Section
                            </button>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Image Upload --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fa-solid fa-image mr-2 text-blue-500"></i>
                                Image
                            </h3>
                            <div class="space-y-4">
                                <x-image-upload
                                    id="image"
                                    name="image"
                                    label=""
                                    :required="false"
                                    help-text="PNG, JPG, GIF, WEBP, SVG up to 2MB"
                                    :max-size="2048"
                                    :current-image="$about->image ? Storage::disk('public')->url($about->image) : null"
                                    :current-image-alt="$about->title"
                                />

                                <div>
                                    <label for="image_position" class="block text-xs font-medium text-gray-700 mb-1">
                                        Image Position
                                    </label>
                                    <select id="image_position" name="image_position" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                        <option value="left" {{ old('image_position', $about->image_position) === 'left' ? 'selected' : '' }}>Left</option>
                                        <option value="right" {{ old('image_position', $about->image_position) === 'right' ? 'selected' : '' }}>Right</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Settings --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fa-solid fa-cog mr-2 text-blue-500"></i>
                                Settings
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="sort_order" class="block text-xs font-medium text-gray-700 mb-1">
                                        Sort Order
                                    </label>
                                    <input type="number"
                                           id="sort_order"
                                           name="sort_order"
                                           value="{{ old('sort_order', $about->sort_order ?? 0) }}"
                                           min="0"
                                           class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                </div>

                                <x-ui.toggle 
                                    name="is_active"
                                    :checked="old('is_active', $about->is_active)"
                                    label="Active"
                                />
                            </div>
                        </div>
                    </div>
                </div>


        </form>
    </div>
</div>

<script>
let listItemIndex = {{ count($about->list_items ?? []) }};

function addListItem() {
    const container = document.getElementById('list-items-container');
    const listItemHtml = `
        <div class="flex gap-2">
            <input type="text"
                   name="list_items[${listItemIndex}]"
                   placeholder="Enter list item"
                   class="flex-1 px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
            <button type="button" onclick="removeListItem(this)" class="text-xs px-3 py-2 text-red-600 hover:text-red-800 rounded-md focus:outline-none">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', listItemHtml);
    listItemIndex++;
}

function removeListItem(button) {
    button.parentElement.remove();
}
</script>
</x-layouts.admin>
