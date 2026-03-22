<x-layouts.admin title="Edit Appointment: {{ $appointment->title }}">
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-bold text-zinc-900 dark:text-white mb-2">Edit Appointment</h1>
            <p class="text-zinc-600 dark:text-zinc-400">{{ $appointment->title }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.crm.appointments.show', $appointment) }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">View</a>
            <a href="{{ route('admin.crm.appointments.index') }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">Back</a>
        </div>
    </div>

    <form action="{{ route('admin.crm.appointments.update', $appointment) }}" method="POST" class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="title" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $appointment->title) }}" required maxlength="200" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="contact_id" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Contact</label>
                <select name="contact_id" id="contact_id" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    <option value="">No contact</option>
                    @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}" {{ old('contact_id', $appointment->contact_id) == $contact->id ? 'selected' : '' }}>{{ $contact->organization_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="deal_id" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Deal</label>
                <select name="deal_id" id="deal_id" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    <option value="">No deal</option>
                    @foreach($deals as $deal)
                        <option value="{{ $deal->id }}" {{ old('deal_id', $appointment->deal_id) == $deal->id ? 'selected' : '' }}>{{ $deal->title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="assigned_to" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Assigned To</label>
                <select name="assigned_to" id="assigned_to" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to', $appointment->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="type" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Type <span class="text-red-500">*</span></label>
                <select name="type" id="type" required class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    @foreach(['demo' => 'Demo', 'call' => 'Call', 'follow_up' => 'Follow Up', 'onboarding' => 'Onboarding', 'meeting' => 'Meeting', 'other' => 'Other'] as $val => $label)
                        <option value="{{ $val }}" {{ old('type', $appointment->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('type')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="status" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" required class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                    @foreach(['scheduled' => 'Scheduled', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'no_show' => 'No Show'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $appointment->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="starts_at" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Start Date & Time <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $appointment->starts_at?->format('Y-m-d\TH:i')) }}" required class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                @error('starts_at')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="ends_at" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">End Date & Time</label>
                <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', $appointment->ends_at?->format('Y-m-d\TH:i')) }}" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
                @error('ends_at')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="location" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Location</label>
                <input type="text" name="location" id="location" value="{{ old('location', $appointment->location) }}" maxlength="500" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">
            </div>

            <div class="flex items-center">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="is_online" value="0">
                    <input type="checkbox" name="is_online" value="1" {{ old('is_online', $appointment->is_online) ? 'checked' : '' }} class="rounded border-zinc-300 dark:border-zinc-600 text-[var(--color-accent)]">
                    <span class="font-medium text-zinc-700 dark:text-zinc-300">Online meeting</span>
                </label>
            </div>

            <div class="md:col-span-2">
                <label for="notes" class="block font-medium text-zinc-700 dark:text-zinc-300 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="3" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm">{{ old('notes', $appointment->notes) }}</textarea>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-2 rounded-md bg-[var(--color-accent)] text-white font-semibold hover:opacity-90">Update Appointment</button>
                <a href="{{ route('admin.crm.appointments.index') }}" class="px-4 py-2 rounded-md border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 font-medium">Cancel</a>
            </div>
            <form action="{{ route('admin.crm.appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('Delete this appointment?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-md bg-red-600 text-white font-medium hover:bg-red-700">Delete</button>
            </form>
        </div>
    </form>
</div>
</x-layouts.admin>
