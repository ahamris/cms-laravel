<x-layouts.admin title="Add Subject">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Add Subject</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Add an option for the contact form subject (Onderwerp) dropdown.</p>
        </div>
        <a href="{{ route('admin.administrator.contact-subjects.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Subjects
        </a>
    </div>

    <form action="{{ route('admin.administrator.contact-subjects.store') }}" method="POST">
        @csrf

        @if($errors->any())
            <div class="mb-6">
                <x-ui.alert variant="error" icon="circle-exclamation" :title="__('admin.form_fix_errors_title')">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-ui.alert>
            </div>
        @endif

        <div class="max-w-2xl">
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Subject details</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Label shown in the contact form dropdown (e.g. General question, Support).</p>
                </div>
                <div class="space-y-6">
                    <div>
                        <x-ui.input
                            id="title"
                            name="title"
                            :value="old('title')"
                            label="Title"
                            placeholder="e.g. General question, Support"
                            required
                            :error="$errors->has('title')"
                            :errorMessage="$errors->first('title')"
                        />
                    </div>
                    <div>
                        <x-ui.input
                            id="sort_order"
                            name="sort_order"
                            type="number"
                            :value="old('sort_order', 0)"
                            label="Sort order"
                            min="0"
                            :error="$errors->has('sort_order')"
                            :errorMessage="$errors->first('sort_order')"
                        />
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Active</label>
                            <p class="text-sm/6 text-gray-600 dark:text-gray-400">Show in contact form dropdown</p>
                        </div>
                        <input type="hidden" name="is_active" value="0">
                        <x-ui.toggle name="is_active" :checked="old('is_active', true)" />
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-4 border-t border-gray-200 dark:border-white/10 pt-6">
            <a href="{{ route('admin.administrator.contact-subjects.index') }}" class="text-sm/6 font-semibold text-gray-900 dark:text-white hover:text-gray-600 dark:hover:text-gray-300">Cancel</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)]">
                <i class="fa-solid fa-check"></i>
                Save subject
            </button>
        </div>
    </form>
</x-layouts.admin>
