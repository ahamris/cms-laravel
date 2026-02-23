<x-layouts.admin title="Add Course Category">
    <form action="{{ route('admin.content.course-category.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        


        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Course Category</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Create a new category for course videos</p>
            </div>
            <a href="{{ route('admin.content.course-category.index') }}">
                <x-button variant="secondary" icon="arrow-left" class="justify-center">Back to list</x-button>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Column --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Basic Information --}}
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Basic Information</h2>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-ui.input name="name" id="name" label="Name" :value="old('name')" required 
                                :error="$errors->has('name')" :errorMessage="$errors->first('name')" />
                            
                            <x-ui.input name="slug" id="slug" label="Slug" :value="old('slug')"
                                placeholder="url-friendly-name" required slug-from="name" 
                                :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-zinc-900 dark:text-white">Description</label>
                            <x-ui.textarea name="description" id="description" :value="old('description')" :rows="4" 
                                :error="$errors->has('description')" :errorMessage="$errors->first('description')" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Actions --}}
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Actions</h2>
                    <div class="space-y-4">
                        <x-button variant="primary" type="submit" class="w-full justify-center" icon="save">Create Category</x-button>
                        <a href="{{ route('admin.content.course-category.index') }}" class="block">
                            <x-button variant="secondary" type="button" class="w-full justify-center">Cancel</x-button>
                        </a>
                    </div>
                </div>

                {{-- Thumbnail --}}
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Thumbnail</h2>
                    <x-image-upload id="image" name="image" label="Category Image" :required="false" 
                        help-text="Upload JPG, PNG, or GIF. Max 8MB." :max-size="8192" size="small" />
                </div>

                {{-- Settings --}}
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Settings</h2>
                    <div class="space-y-4">
                        <x-ui.input name="sort_order" id="sort_order" label="Sort order" type="number"
                            :value="old('sort_order', 0)" min="0" :error="$errors->has('sort_order')"
                            :errorMessage="$errors->first('sort_order')" />
                            
                        <div class="pt-2">
                            <x-ui.toggle name="is_active" label="Visible in academy" :checked="old('is_active', true)" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
