<x-layouts.admin title="Edit Ticket: {{ $ticket->subject }}">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Edit Ticket</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ $ticket->subject }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.crm.tickets.show', $ticket) }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">View</a>
            <a href="{{ route('admin.crm.tickets.index') }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">Back</a>
        </div>
    </div>

    <form action="{{ route('admin.crm.tickets.update', $ticket) }}" method="POST" class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="subject" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Subject <span class="text-red-500">*</span></label>
                <input type="text" name="subject" id="subject" value="{{ old('subject', $ticket->subject) }}" required maxlength="300" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                @error('subject')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="assigned_to" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Assigned To</label>
                <select name="assigned_to" id="assigned_to" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to', $ticket->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="priority" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Priority <span class="text-red-500">*</span></label>
                <select name="priority" id="priority" required class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'] as $val => $label)
                        <option value="{{ $val }}" {{ old('priority', $ticket->priority) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('priority')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="status" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" required class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    @foreach(['open' => 'Open', 'in_progress' => 'In Progress', 'waiting' => 'Waiting', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $ticket->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" id="description" rows="5" required class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">{{ old('description', $ticket->description) }}</textarea>
                @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-2 rounded-md bg-[var(--color-accent)] text-white font-semibold hover:opacity-90">Update Ticket</button>
                <a href="{{ route('admin.crm.tickets.show', $ticket) }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">Cancel</a>
            </div>
            <form action="{{ route('admin.crm.tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Delete this ticket?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-md bg-red-600 text-white font-medium hover:bg-red-700">Delete</button>
            </form>
        </div>
    </form>
</div>
</x-layouts.admin>
