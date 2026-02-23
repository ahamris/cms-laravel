<x-layouts.admin title="Add Chapter">
    <form action="{{ route('admin.course.store') }}" method="POST">
        @csrf

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Chapter</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Create a new chapter for a document category</p>
            </div>
            <a href="{{ route('admin.course.index') }}">
                <x-button variant="secondary" icon="arrow-left" class="justify-center">Back to list</x-button>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Chapter details</h2>
                    <div class="space-y-6">
                        <x-ui.select name="course_category_id" label="Category" required
                            :options="['' => 'Select category'] + $categories->mapWithKeys(fn($c) => [$c->id => $c->name])->all()"
                            :value="(string) old('course_category_id')"
                            :error="$errors->has('course_category_id')"
                            :errorMessage="$errors->first('course_category_id')" />

                        <x-ui.input name="name" id="name" label="Name" :value="old('name')" required
                            :error="$errors->has('name')" :errorMessage="$errors->first('name')" />

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-zinc-900 dark:text-white">Description</label>
                            <x-ui.textarea name="description" id="description" :value="old('description')" :rows="4"
                                :error="$errors->has('description')" :errorMessage="$errors->first('description')" />
                        </div>

                        <x-ui.input name="sort_order" id="sort_order" label="Sort order" type="number"
                            :value="old('sort_order', 0)" min="0"
                            :error="$errors->has('sort_order')" :errorMessage="$errors->first('sort_order')" />
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-8">
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Actions</h2>
                    <div class="space-y-4">
                        <x-button variant="primary" type="submit" class="w-full justify-center" icon="save">Create Chapter</x-button>
                        <a href="{{ route('admin.course.index') }}" class="block">
                            <x-button variant="secondary" type="button" class="w-full justify-center">Cancel</x-button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
