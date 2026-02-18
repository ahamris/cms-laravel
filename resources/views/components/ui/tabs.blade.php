@props([
    'tabs' => [],
    'active' => null,
    'vertical' => false,
])

@php
    $defaultActive = $active ?? (count($tabs) > 0 ? array_key_first($tabs) : 'tab1');
@endphp

<div
    x-cloak
    x-data="{
        active: '{{ $defaultActive }}',
        vertical: {{ $vertical ? 'true' : 'false' }},
    }"
    class="flex flex-col"
    x-bind:class="{
        'sm:flex-row': vertical
    }"
    {{ $attributes }}
>
    <!-- Nav Tabs -->
    <div
        x-on:keydown.right.prevent.stop="$focus.wrap().next()"
        x-on:keydown.left.prevent.stop="$focus.wrap().previous()"
        x-on:keydown.home.prevent.stop="$focus.first()"
        x-on:keydown.end.prevent.stop="$focus.last()"
        x-bind:class="{
            'sm:w-48 sm:flex-none sm:flex-col sm:items-stretch': vertical
        }"
        class="flex items-center text-sm"
    >
        @foreach($tabs as $key => $label)
        @php
            $isActive = $key === $defaultActive;
        @endphp
        <button
            x-on:click="active = '{{ $key }}'"
            type="button"
            id="{{ $key }}-tab"
            role="tab"
            aria-controls="{{ $key }}-tab-pane"
            x-bind:aria-selected="active === '{{ $key }}' ? 'true' : 'false'"
            x-bind:tabindex="active === '{{ $key }}' ? '0' : '-1'"
            x-bind:class="{
                'text-zinc-950 dark:text-zinc-50 border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800': active === '{{ $key }}',
                'text-zinc-500 border-transparent hover:text-zinc-950 dark:text-zinc-400 dark:hover:text-zinc-50': active !== '{{ $key }}',
                'sm:border-e-0 sm:border-y sm:border-s sm:rounded-s-lg sm:rounded-e-none sm:-me-px': vertical,
            }"
            class="z-10 -mb-px flex items-center gap-2 rounded-t-lg border-x border-t px-5 py-3 font-medium transition-colors {{ $isActive ? 'text-zinc-950 dark:text-zinc-50 border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800' : 'text-zinc-500 border-transparent dark:text-zinc-400' }}"
        >
            {{ $label }}
        </button>
        @endforeach
    </div>
    <!-- END Nav Tabs -->

    <!-- Tab Content -->
    <div
        x-bind:class="{
            'rounded-b-lg rounded-tr-lg': !vertical,
            'sm:rounded-e-lg sm:rounded-s-none flex-1': vertical
        }"
        class="rounded-b-lg rounded-tr-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6"
    >
        {{ $slot }}
    </div>
    <!-- END Tab Content -->
</div>

