<x-layouts.admin title="New Ticket">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">New Ticket</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Create a new support ticket</p>
        </div>
        <a href="{{ route('admin.crm.tickets.index') }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">Back</a>
    </div>

    <form action="{{ route('admin.crm.tickets.store') }}" method="POST" class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="subject" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Subject <span class="text-red-500">*</span></label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required maxlength="300" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                @error('subject')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="contact_id" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Contact</label>
                <select name="contact_id" id="contact_id" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    <option value="">No contact</option>
                    @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}" {{ old('contact_id') == $contact->id ? 'selected' : '' }}>{{ $contact->organization_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="assigned_to" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Assigned To</label>
                <select name="assigned_to" id="assigned_to" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="priority" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Priority <span class="text-red-500">*</span></label>
                <select name="priority" id="priority" required class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'] as $val => $label)
                        <option value="{{ $val }}" {{ old('priority', 'medium') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('priority')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="source" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Source</label>
                <select name="source" id="source" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    <option value="">Select source...</option>
                    @foreach(['form' => 'Form', 'email' => 'Email', 'phone' => 'Phone', 'chat' => 'Chat'] as $val => $label)
                        <option value="{{ $val }}" {{ old('source') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" id="description" rows="5" required class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="px-6 py-2 rounded-md bg-[var(--color-accent)] text-white font-semibold hover:opacity-90">Create Ticket</button>
            <a href="{{ route('admin.crm.tickets.index') }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">Cancel</a>
        </div>
    </form>
</div>
</x-layouts.admin>
