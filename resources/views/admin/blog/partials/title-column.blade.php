@php
    $isDraft = ! ($item->is_active ?? false);
    $editUrl = $this->edit($item->id);
    $viewUrl = $this->view($item->id);
    $modelName = class_basename($this->modelClass);
@endphp
<div class="min-w-0 max-w-xl">
    <div class="flex flex-wrap items-baseline gap-x-2 gap-y-0.5">
        @if($editUrl)
            <a href="{{ $editUrl }}" class="text-[13px] font-semibold text-sky-700 hover:text-sky-900 dark:text-sky-400 dark:hover:text-sky-300">
                {{ $item->title }}
            </a>
        @else
            <span class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-100">{{ $item->title }}</span>
        @endif
        @if($isDraft)
            <span class="text-[12px] font-normal text-zinc-500 dark:text-zinc-400">— {{ __('Draft') }}</span>
        @endif
    </div>
    <div class="mt-0.5 flex flex-wrap items-center gap-x-2 gap-y-0.5 text-[12px] text-zinc-600 opacity-0 transition-opacity group-hover/tr:opacity-100 max-sm:opacity-100 dark:text-zinc-400">
        @if($editUrl && in_array('edit', $this->actions))
            <a href="{{ $editUrl }}" class="text-sky-700 hover:underline dark:text-sky-400">{{ __('Edit') }}</a>
        @endif
        @if($viewUrl && in_array('view', $this->actions))
            @if($editUrl && in_array('edit', $this->actions))
                <span class="select-none text-zinc-300 dark:text-zinc-600" aria-hidden="true">|</span>
            @endif
            <a
                href="{{ $viewUrl }}"
                class="text-sky-700 hover:underline dark:text-sky-400"
                @if(str_starts_with((string) $viewUrl, 'http')) target="_blank" rel="noopener noreferrer" @endif
            >{{ __('View') }}</a>
        @endif
        @if(in_array('delete', $this->actions))
            @if(($editUrl && in_array('edit', $this->actions)) || ($viewUrl && in_array('view', $this->actions)))
                <span class="select-none text-zinc-300 dark:text-zinc-600" aria-hidden="true">|</span>
            @endif
            <button
                type="button"
                class="text-red-600 hover:underline dark:text-red-400"
                x-on:click="showDeleteModal{{ $item->id }} = true"
            >{{ __('Trash') }}</button>
        @endif
    </div>

    @if(in_array('delete', $this->actions))
        <x-ui.modal modal-id="delete-modal-{{ $item->id }}" alpine-show="showDeleteModal{{ $item->id }}" size="sm">
            <x-slot:title>{{ __('Delete :model', ['model' => $modelName]) }}</x-slot:title>
            <div class="space-y-4">
                <p class="text-[13px] text-zinc-600 dark:text-zinc-400">
                    {{ __('Are you sure you want to delete this article? This cannot be undone.') }}
                </p>
                <div class="space-y-2 rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-600 dark:bg-zinc-900/50">
                    <div class="text-[12.5px]">
                        <span class="font-medium text-zinc-500 dark:text-zinc-400">{{ __('Title') }}:</span>
                        <span class="ml-1 text-zinc-800 dark:text-zinc-200">{{ $item->title }}</span>
                    </div>
                    @if($item->slug)
                        <div class="text-[12.5px]">
                            <span class="font-medium text-zinc-500 dark:text-zinc-400">{{ __('Slug') }}:</span>
                            <span class="ml-1 font-mono text-xs text-zinc-700 dark:text-zinc-300">{{ $item->slug }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <x-slot:footer>
                <x-ui.button variant="secondary" type="button" x-on:click="showDeleteModal{{ $item->id }} = false">{{ __('Cancel') }}</x-ui.button>
                <x-ui.button
                    variant="primary"
                    color="red"
                    type="button"
                    wire:click="delete({{ $item->id }})"
                    x-on:click="showDeleteModal{{ $item->id }} = false"
                >{{ __('Delete') }}</x-ui.button>
            </x-slot:footer>
        </x-ui.modal>
    @endif
</div>
