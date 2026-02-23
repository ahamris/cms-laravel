@if(in_array('view', $actions))
    <x-button 
        variant="sky" 
        size="sm" 
        icon="eye" 
        title="View"
        href="{{ route('admin.content.feature.show', $item->id) }}"
    ></x-button>
@endif

@if(in_array('edit', $actions))
    <x-button 
        variant="warning" 
        size="sm" 
        icon="edit" 
        title="Edit"
        href="{{ route('admin.content.feature.edit', $item->id) }}"
    ></x-button>
@endif

@if(in_array('delete', $actions))
    @php
        $modelName = class_basename($this->modelClass);
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
                Are you sure you want to delete this feature? This action cannot be undone.
            </p>
            
            <div class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 space-y-2">
                <div class="text-sm">
                    <span class="text-zinc-500 dark:text-zinc-400 font-medium">Title:</span>
                    <span class="text-zinc-700 dark:text-zinc-300 ml-1">{{ $item->title }}</span>
                </div>
            </div>
        </div>
        <x-slot:footer>
            <x-button variant="secondary" x-on:click="showDeleteModal{{ $item->id }} = false">Cancel</x-button>
            <form wire:submit.prevent="delete({{ $item->id }})" class="inline">
                @csrf
                @method('DELETE')
                <x-button 
                    variant="primary"
                    color="red"
                    type="submit"
                    x-on:click="showDeleteModal{{ $item->id }} = false"
                >Delete</x-button>
            </form>
        </x-slot:footer>
    </x-ui.modal>
@endif

