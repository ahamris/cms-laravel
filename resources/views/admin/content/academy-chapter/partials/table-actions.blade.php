@if(in_array('edit', $actions))
    <a href="{{ route('admin.content.academy-chapter.edit', $item) }}">
        <x-button variant="warning" size="sm" icon="edit" title="Edit"></x-button>
    </a>
@endif

@if(in_array('delete', $actions))
    @php
        $modelName = class_basename($this->modelClass);
    @endphp
    <x-button variant="error" size="sm" icon="trash" title="Delete" type="button"
        x-on:click="showDeleteModal{{ $item->id }} = true"></x-button>

    <x-ui.modal modal-id="delete-modal-{{ $item->id }}" alpine-show="showDeleteModal{{ $item->id }}" size="sm">
        <x-slot:title>Delete {{ $modelName }}</x-slot:title>
        <div class="space-y-4">
            <p class="text-zinc-600 dark:text-zinc-400">
                Are you sure you want to delete this chapter?
            </p>
            <div
                class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 space-y-2">
                <div class="text-sm">
                    <span class="text-zinc-500 dark:text-zinc-400 font-medium">Name:</span>
                    <span class="text-zinc-700 dark:text-zinc-300 ml-1">{{ $item->name }}</span>
                </div>
                @if($item->category)
                    <div class="text-sm">
                        <span class="text-zinc-500 dark:text-zinc-400 font-medium">Category:</span>
                        <span class="text-zinc-700 dark:text-zinc-300 ml-1">{{ $item->category->name }}</span>
                    </div>
                @endif
            </div>
        </div>
        <x-slot:footer>
            <x-button variant="secondary" x-on:click="showDeleteModal{{ $item->id }} = false">Cancel</x-button>
            <x-button variant="primary" color="red" type="button" wire:click="delete({{ $item->id }})"
                x-on:click="showDeleteModal{{ $item->id }} = false">Delete</x-button>
        </x-slot:footer>
    </x-ui.modal>
@endif
