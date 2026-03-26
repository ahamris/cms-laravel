@php
    $sectionCategories = config('page_row_section_categories.categories', []);
    $initialRows = $initialRows ?? [[
        'id' => null,
        'row_kind' => \App\Enums\PageLayoutRowKind::Element->value,
        'label' => $sectionCategories['hero']['default_row_label'] ?? 'Hero',
        'section_category' => 'hero',
        'sort_order' => 0,
    ]];
@endphp

<div class="rounded-xl border border-zinc-200 bg-gradient-to-b from-zinc-50/80 to-white p-6 shadow-sm dark:border-zinc-700 dark:from-zinc-900/50 dark:to-zinc-900/30"
    x-data="{
        rows: {{ Js::from($initialRows) }},
        pickerOpen: false,
        sectionMeta: {{ Js::from($sectionCategories) }},
        openPicker() { this.pickerOpen = true; document.body.classList.add('overflow-hidden'); },
        closePicker() { this.pickerOpen = false; document.body.classList.remove('overflow-hidden'); },
        pickCategory(key) {
            const meta = this.sectionMeta[key];
            if (!meta) return;
            this.rows.push({
                id: null,
                row_kind: 'element',
                section_category: key,
                label: meta.default_row_label,
                sort_order: this.rows.length,
            });
            this.closePicker();
        },
        pickShortBody() {
            this.rows.push({
                id: null,
                row_kind: 'short_body',
                section_category: null,
                label: @js(__('Intro')),
                sort_order: this.rows.length,
            });
            this.closePicker();
        },
        pickLongBody() {
            this.rows.push({
                id: null,
                row_kind: 'long_body',
                section_category: null,
                label: @js(__('Body')),
                sort_order: this.rows.length,
            });
            this.closePicker();
        },
        removeRow(i) {
            if (this.rows.length > 1) this.rows.splice(i, 1);
        },
    }"
    @keydown.escape.window="closePicker()">
    <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
        <div>
            <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Rows') }}</h3>
            <p class="mt-1 max-w-2xl text-xs text-zinc-600 dark:text-zinc-400">{{ __('Order defines where each block appears on the page. Add page fields (short description / body) or component rows. Page fields use the same content as the page editor.') }}</p>
        </div>
    </div>

    <div class="space-y-4">
        <template x-for="(row, index) in rows" :key="row.id != null ? 'id-' + row.id : 'new-' + index">
            <div class="flex flex-col gap-4 rounded-xl border border-zinc-200 bg-white p-4 shadow-xs sm:flex-row sm:items-stretch dark:border-zinc-600 dark:bg-zinc-900/80">
                <input type="hidden" :name="'rows[' + index + '][id]'" :value="row.id ?? ''">
                <input type="hidden" :name="'rows[' + index + '][row_kind]'" :value="row.row_kind">
                <input type="hidden" :name="'rows[' + index + '][section_category]'" :value="row.section_category ?? ''">

                <div class="flex h-20 w-full shrink-0 items-center justify-center rounded-lg bg-zinc-100 p-2 sm:h-auto sm:w-36 dark:bg-zinc-800/90">
                    <div x-show="row.row_kind === 'short_body'" x-cloak class="flex h-full w-full flex-col items-center justify-center gap-1 text-zinc-500">
                        <i class="fa-solid fa-align-left text-2xl" aria-hidden="true"></i>
                        <span class="text-[0.65rem] font-medium uppercase tracking-wide">{{ __('Intro') }}</span>
                    </div>
                    <div x-show="row.row_kind === 'long_body'" x-cloak class="flex h-full w-full flex-col items-center justify-center gap-1 text-zinc-500">
                        <i class="fa-solid fa-file-lines text-2xl" aria-hidden="true"></i>
                        <span class="text-[0.65rem] font-medium uppercase tracking-wide">{{ __('Body') }}</span>
                    </div>
                    <div x-show="row.row_kind === 'element'" x-cloak class="h-full w-full">
                        @foreach (array_keys($sectionCategories) as $catKey)
                            <div x-show="row.section_category === @js($catKey)" x-cloak class="h-full w-full">
                                <x-page-row.section-graphic :category="$catKey" class="max-h-[4.5rem] w-full" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="min-w-0 flex flex-1 flex-col justify-center gap-3 sm:flex-row sm:items-end">
                    <div class="min-w-0 flex-1">
                        <label class="mb-1 block text-xs font-medium text-zinc-600 dark:text-zinc-400" x-text="'{{ __('Label') }} #' + (index + 1)"></label>
                        <input type="text" :name="'rows[' + index + '][label]'" x-model="row.label" required
                            class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-950"
                            placeholder="{{ __('Slot title (shown in admin)') }}">
                    </div>
                    <div class="w-full sm:w-24">
                        <label class="mb-1 block text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ __('Order') }}</label>
                        <input type="number" :name="'rows[' + index + '][sort_order]'" x-model.number="row.sort_order" min="0"
                            class="w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-950">
                    </div>
                    <button type="button" @click="removeRow(index)"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-200 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 dark:border-red-900/50 dark:text-red-400 dark:hover:bg-red-950/40 sm:shrink-0"
                        :disabled="rows.length <= 1">
                        <i class="fa-solid fa-trash-can text-[0.7rem]" aria-hidden="true"></i>
                        {{ __('Remove') }}
                    </button>
                </div>
            </div>
        </template>
    </div>

    @error('rows')
        <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <button type="button" @click="openPicker()"
        class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl border-2 border-dashed border-zinc-300 bg-white px-4 py-3 text-sm font-medium text-zinc-800 transition hover:border-primary hover:bg-primary/5 hover:text-primary dark:border-zinc-600 dark:bg-zinc-900/40 dark:text-zinc-200 dark:hover:border-primary dark:hover:bg-primary/10 sm:w-auto">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-900 text-white dark:bg-zinc-100 dark:text-zinc-900">
            <i class="fa-solid fa-plus text-xs" aria-hidden="true"></i>
        </span>
        {{ __('Add row') }}
    </button>

    <div x-show="pickerOpen" x-cloak class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-zinc-950/70 p-4 pt-10 pb-16 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        @click.self="closePicker()">
        <div class="relative w-full max-w-5xl rounded-2xl border border-zinc-200 bg-zinc-50 shadow-2xl dark:border-zinc-700 dark:bg-zinc-900" @click.stop>
            <div class="sticky top-0 z-10 flex items-center justify-between gap-4 border-b border-zinc-200 bg-zinc-50/95 px-5 py-4 backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/95">
                <div>
                    <h4 class="text-base font-semibold text-zinc-900 dark:text-white">{{ __('Add row') }}</h4>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Page fields or a component block.') }}</p>
                </div>
                <button type="button" @click="closePicker()" class="rounded-lg p-2 text-zinc-500 hover:bg-zinc-200 hover:text-zinc-800 dark:hover:bg-zinc-800 dark:hover:text-zinc-100" aria-label="{{ __('Close') }}">
                    <i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i>
                </button>
            </div>

            <div class="border-b border-zinc-200 px-4 pb-3 pt-4 dark:border-zinc-700">
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Page content') }}</p>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <button type="button" @click="pickShortBody()"
                        class="group flex flex-col rounded-xl border border-zinc-200 bg-white p-4 text-left shadow-sm transition hover:border-primary hover:ring-2 hover:ring-primary/20 dark:border-zinc-600 dark:bg-zinc-950">
                        <span class="mb-2 flex h-12 w-12 items-center justify-center rounded-lg bg-violet-100 text-violet-700 dark:bg-violet-950 dark:text-violet-300">
                            <i class="fa-solid fa-align-left text-xl" aria-hidden="true"></i>
                        </span>
                        <span class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Intro') }}</span>
                        <span class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Uses the page short body field at this position.') }}</span>
                    </button>
                    <button type="button" @click="pickLongBody()"
                        class="group flex flex-col rounded-xl border border-zinc-200 bg-white p-4 text-left shadow-sm transition hover:border-primary hover:ring-2 hover:ring-primary/20 dark:border-zinc-600 dark:bg-zinc-950">
                        <span class="mb-2 flex h-12 w-12 items-center justify-center rounded-lg bg-sky-100 text-sky-700 dark:bg-sky-950 dark:text-sky-300">
                            <i class="fa-solid fa-file-lines text-xl" aria-hidden="true"></i>
                        </span>
                        <span class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Body') }}</span>
                        <span class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Uses the page “long body” (main content) at this position.') }}</span>
                    </button>
                </div>
            </div>

            <div class="px-4 pb-4 pt-3">
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Components') }}</p>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($sectionCategories as $key => $meta)
                        <button type="button" @click="pickCategory(@js($key))"
                            class="group flex flex-col rounded-xl border border-zinc-200 bg-white p-3 text-left shadow-sm transition hover:border-primary hover:shadow-md hover:ring-2 hover:ring-primary/20 dark:border-zinc-600 dark:bg-zinc-950 dark:hover:border-primary">
                            <div class="mb-3 flex h-24 w-full items-center justify-center rounded-lg bg-zinc-100 p-2 ring-1 ring-zinc-200/80 group-hover:bg-zinc-50 dark:bg-zinc-800/80 dark:ring-zinc-700 group-hover:dark:bg-zinc-800">
                                <x-page-row.section-graphic :category="$key" class="h-full max-h-[5.5rem] w-full opacity-95 group-hover:opacity-100" />
                            </div>
                            <span class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __($meta['label']) }}</span>
                            <span class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $meta['component_count'] }} {{ __('components') }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
