<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->image_position === 'left' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' }}">
    <i class="fa-solid fa-image mr-1"></i>
    {{ ucfirst($item->image_position) }}
</span>

