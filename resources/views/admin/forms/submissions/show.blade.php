<x-layouts.admin title="Submission #{{ $submission->id }}">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Submission #{{ $submission->id }}</h1>
                <p class="text-zinc-600 dark:text-zinc-400">{{ $form->name }} &middot; {{ $submission->created_at->format('M j, Y H:i') }}</p>
            </div>
            <a href="{{ route('admin.form.submissions.index', $form) }}"
                class="inline-flex items-center gap-2 rounded-md border border-zinc-300 dark:border-zinc-600 px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Submission Data --}}
            <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
                <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Field Values</h2>
                <dl class="space-y-3">
                    @foreach($form->fields as $field)
                        @if(!in_array($field->type, ['heading', 'divider']))
                            <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                                <dt class="font-medium text-zinc-500 dark:text-zinc-400">{{ $field->label }}</dt>
                                <dd class="text-zinc-900 dark:text-white">{{ $submission->data[$field->name] ?? '—' }}</dd>
                            </div>
                        @endif
                    @endforeach
                </dl>
            </div>

            {{-- Status & Actions --}}
            <div class="space-y-6">
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
                    <h2 class="font-semibold text-zinc-900 dark:text-white mb-4">Status</h2>
                    <form action="{{ route('admin.form.submissions.update', [$form, $submission]) }}" method="POST" class="space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="block font-medium text-zinc-600 dark:text-zinc-400 mb-1">Status</label>
                            <select name="status" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                                @foreach(['new', 'read', 'processed', 'spam', 'archived'] as $status)
                                    <option value="{{ $status }}" {{ $submission->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium text-zinc-600 dark:text-zinc-400 mb-1">Admin Notes</label>
                            <textarea name="admin_notes" rows="3" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">{{ $submission->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">
                            Update
                        </button>
                    </form>
                </div>

                @unless($submission->converted_contact_id)
                    <form action="{{ route('admin.form.submissions.convert', [$form, $submission]) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-md bg-green-600 px-4 py-2 font-semibold text-white shadow-xs hover:bg-green-700 transition-colors">
                            <i class="fa-solid fa-user-plus"></i>
                            Convert to CRM Contact
                        </button>
                    </form>
                @endunless

                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
                    <h2 class="font-semibold text-zinc-900 dark:text-white mb-3">Metadata</h2>
                    <dl class="space-y-2">
                        <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">IP Address</dt><dd class="text-zinc-900 dark:text-white">{{ $submission->ip_address }}</dd></div>
                        <div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Lead Score</dt><dd class="text-zinc-900 dark:text-white">{{ $submission->lead_score }}</dd></div>
                        @if($submission->utm_source)<div><dt class="font-medium text-zinc-500 dark:text-zinc-400">UTM Source</dt><dd class="text-zinc-900 dark:text-white">{{ $submission->utm_source }}</dd></div>@endif
                        @if($submission->referrer_url)<div><dt class="font-medium text-zinc-500 dark:text-zinc-400">Referrer</dt><dd class="text-zinc-900 dark:text-white truncate">{{ $submission->referrer_url }}</dd></div>@endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
