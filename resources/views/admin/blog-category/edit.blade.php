<x-layouts.admin title="Edit Blog Category">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Blog Category</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Update blog category information</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.blog-category.index') }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Blog Categories
            </a>
            <a href="{{ route('admin.blog-category.show', $blogCategory) }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-eye"></i>
                View
            </a>
        </div>
    </div>

    <form action="{{ route('admin.blog-category.update', $blogCategory) }}" method="POST" id="blog-category-form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - 2/3 --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Basic Information Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Essential details for the blog category.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-ui.input id="name" name="name" label="Name" :value="old('name', $blogCategory->name)"
                                placeholder="Enter blog category name" required :error="$errors->has('name')"
                                :errorMessage="$errors->first('name')" />

                            <x-ui.input id="slug" name="slug" label="Slug" :value="old('slug', $blogCategory->slug)"
                                placeholder="blog-category-slug" required
                                slug-from="name"
                                hint="URL-friendly version of the name" :error="$errors->has('slug')"
                                :errorMessage="$errors->first('slug')" />
                        </div>
                    </div>
                </div>

                {{-- Description Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Description</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">A brief overview of what this category covers.</p>
                    </div>

                    <x-ui.textarea id="description" name="description" label="Description" rows="4"
                        :value="old('description', $blogCategory->description)" placeholder="Describe the blog category" required
                        :error="$errors->has('description')"
                        :errorMessage="$errors->first('description')" />
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Settings Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6">
                        <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Settings</h2>
                        <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Configure status and appearance.</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Color --}}
                        <div>
                            <label for="color" class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">Category Color</label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="color" name="color"
                                    value="{{ old('color', $blogCategory->color) }}"
                                    class="h-10 w-12 rounded-md cursor-pointer border-none bg-transparent p-0 dark:bg-white/5" />
                                <x-ui.input type="text" id="colorText" name="color_text"
                                    :value="old('color', $blogCategory->color)" placeholder="#3B82F6"
                                    pattern="^#[0-9A-Fa-f]{6}$" class="flex-1" />
                            </div>
                            @error('color')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Active</label>
                                <p class="text-sm/6 text-gray-600 dark:text-gray-400">Visible in lists and searches</p>
                            </div>
                            <x-ui.toggle name="is_active" :checked="old('is_active', $blogCategory->is_active)" />
                        </div>
                        @error('is_active')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Action Buttons --}}
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-3 px-4 sm:px-0">
            <a href="{{ route('admin.blog-category.index') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                Cancel
            </a>
            <button type="submit" name="submit_action" value="edit"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                Save
            </button>
            <button type="submit" name="submit_action" value="index"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-md bg-[var(--color-accent)] px-6 py-2 text-sm font-semibold text-white shadow-xs ring-1 ring-inset ring-[var(--color-accent)] hover:opacity-90 transition-opacity">
                Save & close
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Color picker synchronization
            const colorInput = document.getElementById('color');
            const colorTextInput = document.getElementById('colorText');

            if (colorInput && colorTextInput) {
                colorInput.addEventListener('input', function () {
                    colorTextInput.value = this.value.toUpperCase();
                });

                colorTextInput.addEventListener('input', function () {
                    if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                        colorInput.value = this.value;
                    }
                });
            }
        });
    </script>
</x-layouts.admin>
