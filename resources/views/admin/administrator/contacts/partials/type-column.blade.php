<div class="flex flex-wrap gap-1.5">
    @if($item->is_customer)
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
            Customer
        </span>
    @endif
    @if($item->is_supplier)
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
            Supplier
        </span>
    @endif
    @if(!$item->is_customer && !$item->is_supplier)
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400">
            -
        </span>
    @endif
</div>

