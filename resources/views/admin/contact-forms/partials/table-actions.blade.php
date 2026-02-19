@if(in_array('view', $actions))
    <x-ui.button variant="secondary" size="sm" icon="eye" title="View"
        href="{{ route('admin.administrator.contact-forms.show', $item->id) }}"></x-ui.button>
@endif

@if(in_array('delete', $actions))
    @php
        $modelName = class_basename($this->modelClass);
    @endphp
    <x-ui.button variant="danger" size="sm" icon="trash" title="Delete" type="button"
        x-on:click="showDeleteModal{{ $item->id }} = true"></x-ui.button>

    <x-ui.modal modal-id="delete-modal-{{ $item->id }}" alpine-show="showDeleteModal{{ $item->id }}" size="sm">
        <x-slot:title>Delete Contact Form</x-slot:title>
        <div class="space-y-4">
            <p class="text-zinc-600 dark:text-zinc-400">
                Are you sure you want to delete this contact form? This action cannot be undone.
            </p>

            <div
                class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 space-y-2">
                <div class="text-sm">
                    <span class="text-zinc-500 dark:text-zinc-400 font-medium">Name:</span>
                    <span class="text-zinc-700 dark:text-zinc-300 ml-1">{{ $item->full_name }}</span>
                </div>
                <div class="text-sm">
                    <span class="text-zinc-500 dark:text-zinc-400 font-medium">Email:</span>
                    <span class="text-zinc-700 dark:text-zinc-300 ml-1">{{ $item->email }}</span>
                </div>
                <div class="text-sm">
                    <span class="text-zinc-500 dark:text-zinc-400 font-medium">Status:</span>
                    <span class="text-zinc-700 dark:text-zinc-300 ml-1">{{ $item->status_text }}</span>
                </div>
            </div>
        </div>
        <x-slot:footer>
            <x-ui.button variant="secondary" x-on:click="showDeleteModal{{ $item->id }} = false">Cancel</x-ui.button>
            <x-ui.button variant="danger" type="button" wire:click="delete({{ $item->id }})"
                x-on:click="showDeleteModal{{ $item->id }} = false">Delete</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
@endif