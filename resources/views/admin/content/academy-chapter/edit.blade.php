<x-layouts.admin title="Edit Chapter">
    <form action="{{ route('admin.content.academy-chapter.update', $academyChapter) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Chapter</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Update chapter details</p>
            </div>
            <a href="{{ route('admin.content.academy-chapter.index') }}">
                <x-button variant="secondary" icon="arrow-left" class="justify-center">Back to list</x-button>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Chapter details</h2>
                    <div class="space-y-6">
                        <x-ui.select name="academy_category_id" label="Category" required
                            :options="['' => 'Select category'] + $categories->mapWithKeys(fn($c) => [$c->id => $c->name])->all()"
                            :value="(string) old('academy_category_id', $academyChapter->academy_category_id)"
                            :error="$errors->has('academy_category_id')"
                            :errorMessage="$errors->first('academy_category_id')" />

                        <x-ui.input name="name" id="name" label="Name" :value="old('name', $academyChapter->name)" required
                            :error="$errors->has('name')" :errorMessage="$errors->first('name')" />

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-zinc-900 dark:text-white">Description</label>
                            <x-ui.textarea name="description" id="description" :value="old('description', $academyChapter->description)" :rows="4"
                                :error="$errors->has('description')" :errorMessage="$errors->first('description')" />
                        </div>

                        <x-ui.input name="sort_order" id="sort_order" label="Sort order" type="number"
                            :value="old('sort_order', $academyChapter->sort_order)" min="0"
                            :error="$errors->has('sort_order')" :errorMessage="$errors->first('sort_order')" />
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-8">
                <div class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Actions</h2>
                    <div class="space-y-4">
                        <x-button variant="primary" type="submit" class="w-full justify-center" icon="save">Update Chapter</x-button>
                        <a href="{{ route('admin.content.academy-chapter.index') }}" class="block">
                            <x-button variant="secondary" type="button" class="w-full justify-center">Cancel</x-button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
