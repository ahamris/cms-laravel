@if(in_array('view', $actions))
    <a href="{{ route('admin.content.academy-video.show', $item) }}">
        <x-button variant="sky" size="sm" icon="eye" title="View"></x-button>
    </a>
@endif

@if(in_array('edit', $actions))
    <a href="{{ route('admin.content.academy-video.edit', $item) }}">
        <x-button variant="warning" size="sm" icon="edit" title="Edit"></x-button>
    </a>
@endif

@if(in_array('delete', $actions))
    @php $modelName = class_basename($this->modelClass); @endphp
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
                Are you sure you want to delete this video? This action cannot be undone.
            </p>
            <div class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4">
                <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $item->title }}</div>
                @if($item->category)
                    <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ $item->category->name }}</div>
                @endif
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
