<div class="space-y-6" x-data="{ showDeleteModal: false }">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">{{ $heading }} · preview</h1>
            <p class="mt-1 text-[12.5px] text-zinc-600 dark:text-zinc-400">Record #{{ $element->id }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <x-ui.button variant="primary" icon="pen" icon-position="left" href="{{ route($routeBase . '.edit', $element->id) }}">
                {{ __('Edit') }}
            </x-ui.button>
            @if (\Illuminate\Support\Facades\Route::has($routeBase . '.clone'))
                <form action="{{ route($routeBase . '.clone', $element->id) }}" method="POST" class="inline">
                    @csrf
                    <x-ui.button variant="secondary" type="submit" icon="copy" icon-position="left">
                        {{ __('Duplicate') }}
                    </x-ui.button>
                </form>
            @endif
            <x-ui.button variant="error" type="button" icon="trash" icon-position="left" x-on:click="showDeleteModal = true">
                {{ __('Delete') }}
            </x-ui.button>
            <x-ui.button variant="secondary" href="{{ route($routeBase . '.index') }}">
                {{ __('Back to list') }}
            </x-ui.button>
        </div>
    </div>

    @if ($typeHelp)
        <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900 dark:border-blue-800/60 dark:bg-blue-950/40 dark:text-blue-100">
            {{ $typeHelp }}
        </div>
    @endif

    <div class="space-y-4 rounded-lg border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800/40">
        <div><span class="font-semibold text-zinc-700 dark:text-zinc-300">Type:</span> <code class="text-sm">{{ $element->type->value }}</code></div>
        <div><span class="font-semibold text-zinc-700 dark:text-zinc-300">Title:</span> {{ $element->title ?: '—' }}</div>
        <div><span class="font-semibold text-zinc-700 dark:text-zinc-300">Sub title:</span> {{ $element->sub_title ?: '—' }}</div>
        <div>
            <span class="font-semibold text-zinc-700 dark:text-zinc-300">Description:</span>
            <p class="mt-2 whitespace-pre-wrap text-zinc-600 dark:text-zinc-400">{{ $element->description ?: '—' }}</p>
        </div>
        <div>
            <span class="mb-2 block font-semibold text-zinc-700 dark:text-zinc-300">Options</span>
            @include($showOptionsView, ['element' => $element])
        </div>
    </div>

    <x-ui.modal alpine-show="showDeleteModal" size="sm" modal-id="element-show-delete">
        <x-slot:title>{{ __('Delete element') }}</x-slot:title>
        <p class="text-[13px] text-zinc-600 dark:text-zinc-400">{{ __('Are you sure you want to delete this element? This cannot be undone.') }}</p>
        <x-slot:footer>
            <x-ui.button variant="secondary" type="button" x-on:click="showDeleteModal = false">{{ __('Cancel') }}</x-ui.button>
            <form action="{{ route($routeBase . '.destroy', $element->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <x-ui.button variant="primary" color="red" type="submit" x-on:click="showDeleteModal = false">{{ __('Delete') }}</x-ui.button>
            </form>
        </x-slot:footer>
    </x-ui.modal>
</div>
