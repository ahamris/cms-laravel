<div class="flex items-center justify-end gap-2">
    <a href="{{ route('admin.content.comment.show', $item) }}"
        class="p-1.5 text-gray-400 hover:text-[var(--color-accent)] transition-colors duration-200 rounded"
        title="View">
        <i class="fas fa-eye"></i>
    </a>

    <form action="{{ route('admin.content.comment.toggle-approve', $item) }}" method="POST" class="inline">
        @csrf
        <button type="submit"
            class="p-1.5 transition-colors duration-200 rounded {{ $item->is_approved ? 'text-green-500 hover:text-green-600' : 'text-yellow-500 hover:text-yellow-600' }}"
            title="{{ $item->is_approved ? 'Unapprove' : 'Approve' }}">
            <i class="fas {{ $item->is_approved ? 'fa-check-circle' : 'fa-clock' }}"></i>
        </button>
    </form>
</div>