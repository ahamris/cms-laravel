@if(in_array('view', $actions))
    <a href="{{ route('admin.session-registration.show', $item) }}" title="View">
        <x-button variant="sky" size="sm" icon="eye" title="View"></x-button>
    </a>
@endif

@if(in_array('edit', $actions))
    <a href="{{ route('admin.session-registration.edit', $item) }}" title="Edit">
        <x-button variant="warning" size="sm" icon="edit" title="Edit"></x-button>
    </a>
@endif

@if($item->status === 'registered')
    <form action="{{ route('admin.session-registration.mark-attended', $item) }}" method="POST" class="inline">
        @csrf
        <x-button variant="success" size="sm" type="submit" icon="check" title="Mark as Attended"></x-button>
    </form>
    <form action="{{ route('admin.session-registration.mark-no-show', $item) }}" method="POST" class="inline">
        @csrf
        <x-button variant="warning" size="sm" type="submit" icon="times" title="Mark as No Show"></x-button>
    </form>
@endif

@if(in_array('delete', $actions))
    @php
        $modelName = class_basename($this->modelClass ?? \App\Models\SessionRegistration::class);
    @endphp
    <x-button
        variant="error"
        size="sm"
        icon="trash"
        title="Delete"
        type="button"
        x-on:click="showDeleteModal{{ $item->id }} = true"
    ></x-button>

    <x-ui.modal modal-id="delete-modal-{{ $item->id }}" alpine-show="showDeleteModal{{ $item->id }}" size="sm">
        <x-slot:title>Delete {{ $modelName }}</x-slot:title>
        <div class="space-y-4">
            <p class="text-zinc-600 dark:text-zinc-400">
                Are you sure you want to delete this registration? This action cannot be undone.
            </p>
            <div class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 space-y-2">
                <div class="text-sm">
                    <span class="text-zinc-500 dark:text-zinc-400 font-medium">Participant:</span>
                    <span class="text-zinc-700 dark:text-zinc-300 ml-1">{{ $item->name }}</span>
                </div>
                <div class="text-sm">
                    <span class="text-zinc-500 dark:text-zinc-400 font-medium">Email:</span>
                    <span class="text-zinc-700 dark:text-zinc-300 ml-1">{{ $item->email }}</span>
                </div>
            </div>
        </div>
        <x-slot:footer>
            <x-button variant="secondary" x-on:click="showDeleteModal{{ $item->id }} = false">Cancel</x-button>
            <x-button
                variant="primary"
                color="red"
                type="button"
                wire:click="delete({{ $item->id }})"
                x-on:click="showDeleteModal{{ $item->id }} = false"
            >Delete</x-button>
        </x-slot:footer>
    </x-ui.modal>
@endif
