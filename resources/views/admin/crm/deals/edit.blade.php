<x-layouts.admin title="Edit Deal: {{ $deal->title }}">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Edit Deal</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ $deal->title }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.crm.deals.show', $deal) }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">View</a>
            <a href="{{ route('admin.crm.deals.index') }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">Back</a>
        </div>
    </div>

    <form action="{{ route('admin.crm.deals.update', $deal) }}" method="POST" class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $deal->title) }}" required class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="contact_id" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Contact <span class="text-red-500">*</span></label>
                <select name="contact_id" id="contact_id" required class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    <option value="">Select contact...</option>
                    @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}" {{ old('contact_id', $deal->contact_id) == $contact->id ? 'selected' : '' }}>{{ $contact->organization_name }}</option>
                    @endforeach
                </select>
                @error('contact_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="stage" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Stage <span class="text-red-500">*</span></label>
                <select name="stage" id="stage" required class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    @foreach(['lead' => 'Lead', 'qualified' => 'Qualified', 'proposal' => 'Proposal', 'negotiation' => 'Negotiation', 'won' => 'Won', 'lost' => 'Lost'] as $val => $label)
                        <option value="{{ $val }}" {{ old('stage', $deal->stage) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('stage')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="assigned_to" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Assigned To</label>
                <select name="assigned_to" id="assigned_to" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to', $deal->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="value" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Value (cents)</label>
                <input type="number" name="value" id="value" value="{{ old('value', $deal->value) }}" min="0" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                @error('value')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="probability" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Probability (%)</label>
                <input type="number" name="probability" id="probability" value="{{ old('probability', $deal->probability) }}" min="0" max="100" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                @error('probability')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="expected_close_date" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Expected Close Date</label>
                <input type="date" name="expected_close_date" id="expected_close_date" value="{{ old('expected_close_date', $deal->expected_close_date?->format('Y-m-d')) }}" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">{{ old('description', $deal->description) }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-2 rounded-md bg-[var(--color-accent)] text-white font-semibold hover:opacity-90">Update Deal</button>
                <a href="{{ route('admin.crm.deals.show', $deal) }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">Cancel</a>
            </div>
            <form action="{{ route('admin.crm.deals.destroy', $deal) }}" method="POST" onsubmit="return confirm('Delete this deal?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-md bg-red-600 text-white font-medium hover:bg-red-700">Delete</button>
            </form>
        </div>
    </form>
</div>
</x-layouts.admin>
