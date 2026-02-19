@if(in_array('view', $actions))
    <x-button 
        variant="sky" 
        size="sm" 
        icon="eye" 
        title="View"
        x-on:click="$dispatch('open-view-drawer', { id: {{ $item->id }} })"
    ></x-button>
@endif

@if(in_array('edit', $actions))
    <x-button 
        variant="warning" 
        size="sm" 
        icon="edit" 
        title="Edit"
        x-on:click="$dispatch('open-edit-drawer', { id: {{ $item->id }} })"
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
                Are you sure you want to delete this item? This action cannot be undone.
            </p>
            
            <div class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 space-y-2">
                @foreach($columns as $index => $column)
                    @php
                        $field = is_array($column) ? ($column['key'] ?? $index) : $index;
                        $label = is_array($column) ? ($column['label'] ?? ucfirst(str_replace('_', ' ', $field))) : ucfirst(str_replace('_', ' ', $field));
                        $value = data_get($item, $field);
                        
                        // Skip if value is null or empty, or if it's a toggle/action column
                        $columnType = is_array($column) && isset($column['type']) ? $column['type'] : null;
                        if ($columnType === 'toggle' || $columnType === 'custom' || $value === null || $value === '') {
                            continue;
                        }
                        
                        // Format date fields
                        if ($value instanceof \Carbon\Carbon) {
                            $value = $value->format('d.m.Y H:i');
                        } elseif (is_array($column) && isset($column['format']) && $column['format'] === 'date' && $value) {
                            $value = \Carbon\Carbon::parse($value)->format('d.m.Y');
                        }
                        
                        // Format file-size
                        if ($columnType === 'file-size' && $value) {
                            $value = number_format($value / 1024, 2) . ' KB';
                        }
                    @endphp
                    <div class="text-sm">
                        <span class="text-zinc-500 dark:text-zinc-400 font-medium">{{ $label }}:</span>
                        <span class="text-zinc-700 dark:text-zinc-300 ml-1">{{ $value }}</span>
                    </div>
                @endforeach
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

