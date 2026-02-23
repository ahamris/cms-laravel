<x-layouts.admin title="Edit Static Page">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-edit text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Edit Static Page</h2>
                <p>Update static page information</p>
            </div>
        </div>
        <a href="{{ route('admin.static-page.index') }}" 
           class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
            <i class="fa fa-arrow-left"></i>
            <span>Back to List</span>
        </a>
    </div>

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <form action="{{ route('admin.static-page.update', $staticPage) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fa-solid fa-file-alt mr-2 text-blue-500"></i>
                                Basic Information
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="title" class="block text-xs font-medium text-gray-700 mb-1">
                                        Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $staticPage->title) }}"
                                           class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('title') border-red-500 @enderror"
                                           placeholder="Enter page title"
                                           required>
                                    @error('title')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <x-ui.input id="slug" name="slug" label="Slug" :value="old('slug', $staticPage->slug)"
                                    placeholder="page-slug" required
                                    slug-from="title"
                                    hint="URL-friendly version of the title. Auto-generated if left blank."
                                    :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />

                        <div>
                            <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <x-editor 
                                name="body" 
                                id="body" 
                                :value="old('body', $staticPage->body)"
                                placeholder="Enter page content" />
                            @error('body')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                        <!-- SEO Settings -->
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fa-solid fa-search mr-2 text-green-500"></i>
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
                                           value="{{ old('meta_title', $staticPage->meta_title) }}"
                                           class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('meta_title') border-red-500 @enderror"
                                           placeholder="Enter meta title">
                                    @error('meta_title')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="meta_description" class="block text-xs font-medium text-gray-700 mb-1">
                                        Meta Description
                                    </label>
                                    <textarea id="meta_description" 
                                              name="meta_description" 
                                              rows="3"
                                              class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('meta_description') border-red-500 @enderror"
                                              placeholder="Enter meta description">{{ old('meta_description', $staticPage->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="keywords" class="block text-xs font-medium text-gray-700 mb-1">
                                        SEO Keywords
                                    </label>
                                    <textarea id="keywords" 
                                              name="keywords" 
                                              rows="3"
                                              class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('keywords') border-red-500 @enderror"
                                              placeholder="Enter keywords separated by commas">{{ old('keywords', $staticPage->keywords) }}</textarea>
                                    @error('keywords')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

            </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Status -->
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fa-solid fa-toggle-on mr-2 text-blue-500"></i>
                                Status
                            </h3>
                            
                            <div class="space-y-4">
                                <x-ui.toggle 
                                    name="is_active"
                                    :checked="old('is_active', $staticPage->is_active)"
                                    label="Active"
                                />
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fa-solid fa-image mr-2 text-orange-500"></i>
                                Featured Image
                            </h3>
                            <x-image-upload 
                                id="image"
                                name="image"
                                label=""
                                :required="false"
                                help-text="PNG, JPG, GIF up to 2MB"
                                :max-size="2048"
                                :current-image="$staticPage->image ? Storage::disk('public')->url($staticPage->image) : null"
                                :current-image-alt="$staticPage->title"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 rounded-b-md flex items-center justify-end space-x-3">
                <a href="{{ route('admin.static-page.index') }}" 
                   class="px-5 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-5 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80 transition-colors duration-200">
                    Update Static Page
                </button>
            </div>
        </form>
    </div>

    <script>
    </script>

</x-layouts.admin>
