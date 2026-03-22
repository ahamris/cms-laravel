<x-layouts.admin title="Form: {{ $form->name }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-zinc-900 dark:text-white mb-2">{{ $form->name }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ $form->submissions_count }} submissions &middot; {{ $form->type }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.form.submissions.index', $form) }}"
                    class="inline-flex items-center gap-2 rounded-md bg-zinc-100 dark:bg-zinc-700 px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors">
                    <i class="fa-solid fa-inbox"></i>
                    View Submissions
                </a>
                <a href="{{ route('admin.form.edit', $form) }}"
                    class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
                    <i class="fa-solid fa-edit"></i>
                    Edit
                </a>
                <a href="{{ route('admin.form.index') }}"
                    class="inline-flex items-center gap-2 rounded-md border border-zinc-300 dark:border-zinc-600 px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                    Back
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
            <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Fields ({{ $form->fields->count() }})</h2>
            <div class="space-y-2">
                @foreach($form->fields as $field)
                    <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                        <div>
                            <span class="font-medium text-zinc-900 dark:text-white">{{ $field->label }}</span>
                            <span class="text-zinc-500 dark:text-zinc-400 ml-2">({{ $field->name }})</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-0.5 rounded bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400">{{ $field->type }}</span>
                            @if($field->is_required)
                                <span class="px-2 py-0.5 rounded bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">Required</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.admin>
