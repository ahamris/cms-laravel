<div class="flex items-center justify-end gap-2">
    <a href="{{ route('admin.administrator.subscriptions.show', $item) }}"
       class="p-1.5 text-gray-400 hover:text-[var(--color-accent)] transition-colors duration-200 rounded"
       title="View">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('admin.administrator.subscriptions.edit', $item) }}"
       class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 rounded"
       title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <button wire:click="delete({{ $item->id }})"
            wire:confirm="Are you sure you want to delete this subscription?"
            class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200 rounded"
            title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>

