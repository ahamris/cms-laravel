@php
    $totalRows = method_exists($this->items, 'total') ? $this->items->total() : $this->items->count();
    $countNoun = $entityCountLabel ?: __('items');
    $emptyTitle = $emptyStateTitle ?? __('No items found');
@endphp

<div>
    @php
        $formId = 'table-filter-form-' . md5(($this->resource ?? '') . '|' . ($this->routePrefix ?? ''));
    @endphp

    {{-- Toolbar: search / filters | count + bulk --}}
    <div class="mb-4 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div class="min-w-0 flex-1 space-y-3">
            <div class="relative w-full max-w-[280px]">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5">
                    <i class="fa-solid fa-search text-xs text-zinc-400" aria-hidden="true"></i>
                </div>
                @if($isExternal)
                    <form id="{{ $formId }}" action="{{ request()->url() }}" method="GET">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="{{ $searchPlaceholder }}"
                            class="h-8 w-full rounded-md border border-zinc-200 bg-white py-1 pl-8 pr-2.5 text-[12.5px] leading-5 text-zinc-900 placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500 dark:focus:border-zinc-500"
                        />
                        @foreach(request()->except(['search', 'page', 'template', 'status', 'home']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                    </form>
                @else
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="{{ $searchPlaceholder }}"
                        class="h-8 w-full rounded-md border border-zinc-200 bg-white py-1 pl-8 pr-2.5 text-[12.5px] leading-5 text-zinc-900 placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500 dark:focus:border-zinc-500"
                    />
                @endif
            </div>

            @if(count($this->templateFilterOptions ?? []) > 0 || count($this->statusFilterOptions ?? []) > 0 || count($this->homeFilterOptions ?? []) > 0)
                <div class="flex flex-wrap items-center gap-2">
                    @if(count($this->templateFilterOptions ?? []) > 0)
                        @if($isExternal)
                            <select
                                name="template"
                                form="{{ $formId }}"
                                onchange="this.form.submit()"
                                class="h-8 rounded-md border border-zinc-200 bg-white px-2.5 text-[12.5px] text-zinc-800 focus:border-zinc-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 sm:w-56"
                            >
                                @foreach($this->templateFilterOptions as $value => $label)
                                    <option value="{{ $value }}" {{ request('template') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <select
                                wire:model.live="templateFilter"
                                class="h-8 rounded-md border border-zinc-200 bg-white px-2.5 text-[12.5px] text-zinc-800 focus:border-zinc-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 sm:w-56"
                            >
                                @foreach($this->templateFilterOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        @endif
                    @endif

                    @if(count($this->statusFilterOptions ?? []) > 0)
                        @if($isExternal)
                            <select
                                name="status"
                                form="{{ $formId }}"
                                onchange="this.form.submit()"
                                class="h-8 rounded-md border border-zinc-200 bg-white px-2.5 text-[12.5px] text-zinc-800 focus:border-zinc-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 sm:w-44"
                            >
                                @foreach($this->statusFilterOptions as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <select
                                wire:model.live="statusFilter"
                                class="h-8 rounded-md border border-zinc-200 bg-white px-2.5 text-[12.5px] text-zinc-800 focus:border-zinc-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 sm:w-44"
                            >
                                @foreach($this->statusFilterOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        @endif
                    @endif

                    @if(count($this->homeFilterOptions ?? []) > 0)
                        @if($isExternal)
                            <select
                                name="home"
                                form="{{ $formId }}"
                                onchange="this.form.submit()"
                                class="h-8 rounded-md border border-zinc-200 bg-white px-2.5 text-[12.5px] text-zinc-800 focus:border-zinc-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 sm:w-44"
                            >
                                @foreach($this->homeFilterOptions as $value => $label)
                                    <option value="{{ $value }}" {{ request('home') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <select
                                wire:model.live="homeFilter"
                                class="h-8 rounded-md border border-zinc-200 bg-white px-2.5 text-[12.5px] text-zinc-800 focus:border-zinc-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 sm:w-44"
                            >
                                @foreach($this->homeFilterOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        @endif
                    @endif
                </div>
            @endif
        </div>

        <div class="flex flex-shrink-0 flex-col items-end gap-2 sm:flex-row sm:items-center sm:gap-4">
            <p class="text-[11.5px] text-zinc-500 dark:text-zinc-400" wire:key="table-count-{{ $totalRows }}">
                {{ number_format($totalRows) }} {{ $countNoun }}
            </p>

            <div x-data="{ showBulkDeleteModal: false }">
                @if($showBulkDelete && count($selected) > 0)
                    <div class="flex items-center gap-2">
                        <span class="text-[12.5px] text-zinc-600 dark:text-zinc-400">
                            {{ count($selected) }} {{ __('selected') }}
                        </span>
                        <x-ui.button variant="error" size="sm" type="button" x-on:click="showBulkDeleteModal = true">
                            {{ __('Delete selected') }}
                        </x-ui.button>
                    </div>
                @endif

                <x-ui.modal alpine-show="showBulkDeleteModal" size="sm">
                    <x-slot:title>{{ __('Delete selected items') }}</x-slot:title>
                    <p class="text-[13px] text-zinc-600 dark:text-zinc-400">
                        {{ __('Are you sure you want to delete the selected items? This cannot be undone.') }}
                        <span class="font-medium text-zinc-800 dark:text-zinc-200">({{ count($selected) }})</span>
                    </p>
                    <x-slot:footer>
                        <x-ui.button variant="secondary" type="button" x-on:click="showBulkDeleteModal = false">{{ __('Cancel') }}</x-ui.button>
                        <x-ui.button
                            variant="primary"
                            color="red"
                            type="button"
                            wire:click="deleteSelected"
                            x-on:click="showBulkDeleteModal = false"
                        >
                            {{ __('Delete') }}
                        </x-ui.button>
                    </x-slot:footer>
                </x-ui.modal>
            </div>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-[12px] text-emerald-900 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-100">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800/80">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-100 dark:bg-zinc-900/50">
                    <tr>
                        @if($showCheckbox)
                            <th scope="col" class="w-9 px-2 py-2.5 text-left">
                                <x-ui.checkbox
                                    name="selectAll"
                                    value="1"
                                    label=""
                                    color="primary"
                                    wire:model.live="selectAll"
                                />
                            </th>
                        @endif

                        @foreach($columns as $index => $column)
                            @php
                                $field = $column['key'] ?? $index;
                                $label = $column['label'] ?? ucfirst(str_replace('_', ' ', $field));
                                $sortable = $this->isColumnSortable($field);
                            @endphp
                            <th
                                scope="col"
                                class="px-3.5 py-2.5 text-left text-[10.5px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 {{ $sortable ? 'cursor-pointer select-none hover:bg-zinc-200/80 dark:hover:bg-zinc-700/80' : '' }}"
                                @if($sortable)
                                    @if($isExternal)
                                        onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort' => $field, 'direction' => request('sort') === $field && request('direction') === 'asc' ? 'desc' : 'asc']) }}'"
                                    @else
                                        wire:click="sortBy('{{ $field }}')"
                                    @endif
                                @endif
                            >
                                <div class="flex items-center gap-1.5">
                                    <span>{{ $label }}</span>
                                    @if($sortable)
                                        @if($isExternal)
                                            @if(request('sort') === $field)
                                                <i class="fa-solid fa-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }} text-[9px]" aria-hidden="true"></i>
                                            @endif
                                        @else
                                            @if($sortField === $field)
                                                <i class="fa-solid fa-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-[9px]" aria-hidden="true"></i>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </th>
                        @endforeach

                        @if($showActions)
                            <th scope="col" class="w-[90px] px-3.5 py-2.5 text-right text-[10.5px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Actions') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-800/40">
                    @if($this->items->count() > 0)
                        @foreach($this->items as $item)
                            <tr wire:key="row-{{ $item->id }}" class="transition-colors duration-100 hover:bg-zinc-50 dark:hover:bg-zinc-700/40 {{ in_array($item->id, $selected) ? 'bg-zinc-100 dark:bg-zinc-700/50' : '' }}" x-data="{ showDeleteModal{{ $item->id }}: false }">
                                @if($showCheckbox)
                                    <td class="px-2 py-3 align-middle">
                                        <x-ui.checkbox
                                            name="selected"
                                            value="{{ $item->id }}"
                                            label=""
                                            color="primary"
                                            wire:model.live="selected"
                                        />
                                    </td>
                                @endif

                                @foreach($columns as $index => $column)
                                    @php
                                        $field = $column['key'] ?? $index;
                                        $columnType = $column['type'] ?? null;
                                        $value = data_get($item, $field);
                                        if ($value instanceof \Carbon\Carbon) {
                                            $value = $value->format('Y-m-d');
                                        } elseif (is_array($column) && isset($column['format']) && $column['format'] === 'date' && $value) {
                                            $value = \Carbon\Carbon::parse($value)->format('Y-m-d');
                                        }
                                    @endphp
                                    <td class="min-h-[44px] px-3.5 py-3 align-middle text-[12.5px] text-zinc-900 dark:text-zinc-100">
                                        @if($columnType === 'toggle')
                                            <div class="flex justify-center">
                                                <x-ui.toggle
                                                    name="is_active_{{ $item->id }}"
                                                    :checked="(bool) $value"
                                                    label=""
                                                    wire:key="toggle-{{ $item->id }}-{{ $field }}"
                                                    wire:change="toggleField({{ $item->id }}, '{{ addslashes($field) }}')"
                                                />
                                            </div>
                                        @elseif($columnType === 'file-name')
                                            @php
                                                $icon = '';
                                                $iconClass = 'text-zinc-500 dark:text-zinc-400';

                                                if (str_starts_with($item->mime_type ?? '', 'image/')) {
                                                    $icon = 'fa-image';
                                                    $iconClass = 'text-sky-600 dark:text-sky-400';
                                                } elseif (str_starts_with($item->mime_type ?? '', 'video/')) {
                                                    $icon = 'fa-video';
                                                    $iconClass = 'text-sky-600 dark:text-sky-400';
                                                } elseif (str_starts_with($item->mime_type ?? '', 'audio/')) {
                                                    $icon = 'fa-music';
                                                    $iconClass = 'text-sky-600 dark:text-sky-400';
                                                } elseif (($item->mime_type ?? '') === 'application/pdf') {
                                                    $icon = 'fa-file-pdf';
                                                    $iconClass = 'text-red-600 dark:text-red-400';
                                                } elseif (in_array($item->extension ?? [], ['doc', 'docx'])) {
                                                    $icon = 'fa-file-word';
                                                    $iconClass = 'text-blue-600 dark:text-blue-400';
                                                } elseif (in_array($item->extension ?? [], ['xls', 'xlsx'])) {
                                                    $icon = 'fa-file-excel';
                                                    $iconClass = 'text-green-600 dark:text-green-400';
                                                } else {
                                                    $icon = 'fa-file';
                                                }

                                                $originalName = $item->original_name ?? $value ?? '';
                                                $fileName = $item->file_name ?? '';
                                            @endphp
                                            <div class="flex items-center gap-3">
                                                <i class="fa-solid {{ $icon }} {{ $iconClass }}" aria-hidden="true"></i>
                                                <div>
                                                    <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $originalName }}</div>
                                                    @if($fileName && $fileName !== $originalName)
                                                        <div class="mt-0.5 text-[11px] text-zinc-500 dark:text-zinc-400">{{ $fileName }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($columnType === 'file-size')
                                            @php
                                                $fileSize = $item->file_size ?? $value ?? 0;
                                            @endphp
                                            {{ $fileSize ? number_format($fileSize / 1024, 2) . ' KB' : 'N/A' }}
                                        @elseif($columnType === 'file-type')
                                            {{ $item->mime_type ?? $value ?? 'N/A' }}
                                        @elseif($columnType === 'color')
                                            <div class="flex items-center gap-2">
                                                <div class="h-6 w-6 rounded-full border border-zinc-200 dark:border-zinc-600" style="background-color: {{ $value ?? '#3B82F6' }}"></div>
                                                <span class="font-mono text-xs text-zinc-500 dark:text-zinc-400">{{ $value ?? 'N/A' }}</span>
                                            </div>
                                        @elseif($columnType === 'text' && isset($column['limit']))
                                            {{ Str::limit($value ?? '', $column['limit']) }}
                                        @elseif($columnType === 'custom')
                                            @if(isset($column['render']) && is_callable($column['render']))
                                                {!! $column['render']($item) !!}
                                            @elseif(isset($column['view']))
                                                @include($column['view'], ['item' => $item])
                                            @endif
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                @endforeach

                                @if($showActions)
                                    <td class="px-3.5 py-3 text-right align-middle">
                                        <div class="flex items-center justify-end gap-1">
                                            @if($customActionsView)
                                                @include($customActionsView, ['item' => $item, 'file' => $item])
                                            @else
                                                @if(in_array('view', $actions))
                                                    @php
                                                        $viewRoute = $this->view($item->id);
                                                        $isFile = isset($item->file_path) && $item->file_path;
                                                    @endphp
                                                    @if($viewRoute)
                                                        <a href="{{ $viewRoute }}" class="inline-flex h-[27px] w-[27px] items-center justify-center rounded border border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700" @if($isFile) target="_blank" rel="noopener noreferrer" @endif title="{{ __('View') }}" aria-label="{{ __('View') }}">
                                                            <i class="fa-solid fa-eye text-xs" aria-hidden="true"></i>
                                                        </a>
                                                    @else
                                                        <span class="inline-flex h-[27px] w-[27px] cursor-not-allowed items-center justify-center rounded border border-zinc-100 bg-zinc-50 text-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-600" title="{{ __('View') }}" aria-disabled="true"><i class="fa-solid fa-eye text-xs" aria-hidden="true"></i></span>
                                                    @endif
                                                @endif

                                                @if(in_array('download', $actions))
                                                    @php $downloadUrl = $this->download($item->id); @endphp
                                                    @if($downloadUrl)
                                                        <a href="{{ $downloadUrl }}" download class="inline-flex h-[27px] w-[27px] items-center justify-center rounded border border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700" title="{{ __('Download') }}" aria-label="{{ __('Download') }}">
                                                            <i class="fa-solid fa-download text-xs" aria-hidden="true"></i>
                                                        </a>
                                                    @else
                                                        <span class="inline-flex h-[27px] w-[27px] cursor-not-allowed items-center justify-center rounded border border-zinc-100 bg-zinc-50 text-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-600" title="{{ __('Download') }}" aria-disabled="true"><i class="fa-solid fa-download text-xs" aria-hidden="true"></i></span>
                                                    @endif
                                                @endif

                                                @if(in_array('edit', $actions))
                                                    @php $editRoute = $this->edit($item->id); @endphp
                                                    @if($editRoute)
                                                        <a href="{{ $editRoute }}" class="inline-flex h-[27px] w-[27px] items-center justify-center rounded border border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700" title="{{ __('Edit') }}" aria-label="{{ __('Edit') }}">
                                                            <i class="fa-solid fa-pen text-xs" aria-hidden="true"></i>
                                                        </a>
                                                    @else
                                                        <span class="inline-flex h-[27px] w-[27px] cursor-not-allowed items-center justify-center rounded border border-zinc-100 bg-zinc-50 text-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-600" title="{{ __('Edit') }}" aria-disabled="true"><i class="fa-solid fa-pen text-xs" aria-hidden="true"></i></span>
                                                    @endif
                                                @endif

                                                @if(in_array('delete', $actions))
                                                    @php
                                                        $modelName = class_basename($this->modelClass);
                                                    @endphp
                                                    <button
                                                        type="button"
                                                        class="inline-flex h-[27px] w-[27px] items-center justify-center rounded border border-zinc-200 bg-white text-zinc-600 hover:border-red-200 hover:bg-red-50 hover:text-red-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:border-red-900 dark:hover:bg-red-950/40 dark:hover:text-red-400"
                                                        title="{{ __('Delete') }}"
                                                        aria-label="{{ __('Delete') }}"
                                                        x-on:click="showDeleteModal{{ $item->id }} = true"
                                                    >
                                                        <i class="fa-solid fa-trash text-xs" aria-hidden="true"></i>
                                                    </button>

                                                    <x-ui.modal modal-id="delete-modal-{{ $item->id }}" alpine-show="showDeleteModal{{ $item->id }}" size="sm">
                                                        <x-slot:title>{{ __('Delete :model', ['model' => $modelName]) }}</x-slot:title>
                                                        <div class="space-y-4">
                                                            <p class="text-[13px] text-zinc-600 dark:text-zinc-400">
                                                                {{ __('Are you sure you want to delete this item? This cannot be undone.') }}
                                                            </p>

                                                            <div class="space-y-2 rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-600 dark:bg-zinc-900/50">
                                                                @foreach($columns as $index => $column)
                                                                    @php
                                                                        $field = is_array($column) ? ($column['key'] ?? $index) : $index;
                                                                        $label = is_array($column) ? ($column['label'] ?? ucfirst(str_replace('_', ' ', $field))) : ucfirst(str_replace('_', ' ', $field));
                                                                        $value = data_get($item, $field);
                                                                        $columnType = is_array($column) && isset($column['type']) ? $column['type'] : null;
                                                                        if ($columnType === 'toggle' || $columnType === 'custom' || $value === null || $value === '') {
                                                                            continue;
                                                                        }
                                                                        if ($value instanceof \Carbon\Carbon) {
                                                                            $value = $value->format('d.m.Y H:i');
                                                                        } elseif (is_array($column) && isset($column['format']) && $column['format'] === 'date' && $value) {
                                                                            $value = \Carbon\Carbon::parse($value)->format('d.m.Y');
                                                                        }
                                                                        if ($columnType === 'file-size' && $value) {
                                                                            $value = number_format($value / 1024, 2) . ' KB';
                                                                        }
                                                                    @endphp
                                                                    <div class="text-[12.5px]">
                                                                        <span class="font-medium text-zinc-500 dark:text-zinc-400">{{ $label }}:</span>
                                                                        <span class="ml-1 text-zinc-800 dark:text-zinc-200">{{ $value }}</span>
                                                                    </div>
                                                                @endforeach
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
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="{{ count($columns) + ($showCheckbox ? 1 : 0) + ($showActions ? 1 : 0) }}" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-zinc-500 dark:text-zinc-400">
                                    <i class="fa-solid fa-inbox mb-3 text-4xl opacity-40" aria-hidden="true"></i>
                                    <p class="text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $emptyTitle }}</p>
                                    @if(!empty($emptyCtaUrl) && !empty($emptyCtaLabel))
                                        <a href="{{ $emptyCtaUrl }}" class="mt-4 inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-3 py-2 text-sm font-medium text-[var(--color-accent-foreground)] hover:opacity-90">
                                            <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
                                            {{ $emptyCtaLabel }}
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($this->items->hasPages())
            <div class="border-t border-zinc-200 px-4 py-3 dark:border-zinc-700">
                {{ $this->items->links() }}
            </div>
        @endif
    </div>
</div>
