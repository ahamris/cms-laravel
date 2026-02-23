@if($item->is_approved)
    <span
        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
        <i class="fa-solid fa-check-circle mr-1"></i> Approved
    </span>
@else
    <span
        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
        <i class="fa-solid fa-clock mr-1"></i> Pending
    </span>
@endif