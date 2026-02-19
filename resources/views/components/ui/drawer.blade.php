@props([
    'drawerId',
    'title',
    'description' => null,
    'maxWidth' => 'md', // sm, md, lg, xl, 2xl
    'withBackdrop' => true,
])

@php
    $maxWidthClasses = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
    ];
    $maxWidthClass = $maxWidthClasses[$maxWidth] ?? 'max-w-md';
@endphp

<el-dialog>
    <dialog id="{{ $drawerId }}" aria-labelledby="{{ $drawerId }}-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-hidden bg-transparent {{ $withBackdrop ? 'not-open:hidden' : '' }} backdrop:bg-transparent">
        @if($withBackdrop)
            <el-dialog-backdrop class="absolute inset-0 bg-gray-500/75 transition-opacity duration-500 ease-in-out data-closed:opacity-0 dark:bg-gray-900/50"></el-dialog-backdrop>
        @endif

        <div tabindex="0" class="absolute inset-0 pl-10 focus:outline-none sm:pl-16">
            <el-dialog-panel class="ml-auto block size-full {{ $maxWidthClass }} transform transition duration-500 ease-in-out data-closed:translate-x-full sm:duration-700">
                {{ $slot }}
            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>

