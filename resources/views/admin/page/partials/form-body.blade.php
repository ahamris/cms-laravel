@php
    $p = $page ?? null;
    $publishedAtDisplay = $p?->published_at
        ? $p->published_at->timezone(config('app.timezone'))->locale(app()->getLocale())->translatedFormat('j M Y, H:i')
        : null;
    $pageSeoAssistConfig = [
        'metaTitleHasValue' => filled(old('meta_title', $p->meta_title ?? '')),
        'metaDescHasValue' => filled(old('meta_body', $p->meta_body ?? '')),
        'descriptionFieldId' => 'meta_body',
        'syncEventName' => 'seo-assist-page-sync',
    ];
@endphp

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div
        class="hidden"
        aria-hidden="true"
        x-data="seoAssistFromSummary({{ \Illuminate\Support\Js::from($pageSeoAssistConfig) }})"
        x-init="init()"
    ></div>
    <div class="space-y-4 lg:col-span-2">
        @php
            $mainCard = 'rounded-md border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-800/60';
        @endphp

        {{-- Title & URL --}}
        <div class="{{ $mainCard }}" data-section="page_info" x-show="visibleSections.includes('page_info')" x-transition>
            <h3 class="mb-4 flex items-center text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                <span class="mr-2.5 flex h-7 w-7 items-center justify-center rounded-md bg-primary/10">
                    <i class="fa-solid fa-heading text-xs text-primary"></i>
                </span>
                {{ __('Title & URL') }}
            </h3>
            <div class="space-y-4">
                <div>
                    <label for="title" class="mb-1 block text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ __('Title') }} <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $p->title ?? '') }}" required
                        class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-900 placeholder:text-zinc-400 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/20 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100 dark:placeholder:text-zinc-500 @error('title') border-red-500 @enderror"
                        placeholder="{{ __('Add title') }}">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($p)
                        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Public URL') }}: <span class="font-mono text-zinc-700 dark:text-zinc-300">{{ url('/pagina/'.($p->slug ?? '')) }}</span></p>
                    @endif
                </div>
                <div>
                    <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                        <label for="slug" class="block text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ __('URL slug') }} <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2">
                            <button type="button" class="text-xs font-medium text-sky-600 hover:underline dark:text-sky-400" x-show="!slugUnlocked" @click="slugUnlocked = true; $nextTick(() => $refs.slugField?.focus())">
                                {{ __('Edit slug') }}
                            </button>
                            <button type="button" class="text-xs font-medium text-zinc-500 hover:text-zinc-700 hover:underline dark:text-zinc-400 dark:hover:text-zinc-200" x-show="slugUnlocked" @click="slugUnlocked = false" x-cloak>
                                {{ __('Done') }}
                            </button>
                        </div>
                    </div>
                    <input
                        type="text"
                        id="slug"
                        name="slug"
                        x-ref="slugField"
                        value="{{ old('slug', $p->slug ?? '') }}"
                        required
                        data-slug-from="title"
                        :readonly="!slugUnlocked"
                        class="w-full rounded-md border px-3 py-2 text-sm focus:outline-none focus:ring-1 @error('slug') border-red-500 @enderror"
                        :class="slugUnlocked
                            ? 'border-zinc-200 bg-white text-zinc-900 focus:border-primary focus:ring-primary/20 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100'
                            : 'border-zinc-200 bg-zinc-100 text-zinc-600 cursor-default dark:border-zinc-600 dark:bg-zinc-800/90 dark:text-zinc-400'"
                        placeholder="url-slug"
                    >
                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400" x-show="!slugUnlocked" x-cloak>{{ __('Slug updates from the title while locked. Use “Edit slug” to change it manually.') }}</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="rounded-md border border-amber-200 bg-amber-50/90 px-3 py-2 text-xs text-amber-950 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-100" role="note" x-show="!visibleSections.includes('page_rows')" x-cloak>
                    {{ __('Row layout and hero selection are hidden for this page template. Choose Default, Landing, or Minimal under “Page template” in the sidebar to configure layout rows and hero blocks.') }}
                </div>
            </div>
        </div>

        @if(!empty($pageLayoutTemplatesData))
            <div class="{{ $mainCard }}" data-section="page_rows" x-show="visibleSections.includes('page_rows')" x-transition>
                <h3 class="mb-2 flex items-center text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                    <span class="mr-2.5 flex h-7 w-7 items-center justify-center rounded-md bg-primary/10">
                        <i class="fa-solid fa-table-columns text-xs text-primary"></i>
                    </span>
                    {{ __('Layout template') }}
                </h3>
                <p class="mb-4 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Pick a row layout, then expand each row. UI block rows list library items by section type (Hero Sections, FAQs, …). Intro (short body) and main content appear here too—either from the layout or as extra rows when the template omits them.') }}</p>
                <div>
                    <label for="page_layout_template_id" class="mb-1.5 block text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ __('Row layout') }}</label>
                    <select id="page_layout_template_id" name="page_layout_template_id" x-model="layoutTemplateId"
                        class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/20 dark:border-zinc-600 dark:bg-zinc-900 @error('page_layout_template_id') border-red-500 @enderror">
                        <option value="">{{ __('— None —') }}</option>
                        @foreach($pageLayoutTemplates ?? [] as $plt)
                            <option value="{{ $plt->id }}">{{ $plt->name }}</option>
                        @endforeach
                    </select>
                    @error('page_layout_template_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="selectedLayoutTemplate" x-cloak class="mt-5 space-y-3 border-t border-zinc-200 pt-5 dark:border-zinc-700">
                    <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Overview') }}</p>
                    <div>
                        <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100" x-text="selectedLayoutTemplate.name"></p>
                        <template x-if="selectedLayoutTemplate.description">
                            <p class="mt-1 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400" x-text="selectedLayoutTemplate.description"></p>
                        </template>
                    </div>
                    <div class="rounded-md border border-zinc-200 bg-zinc-50/90 p-3 text-xs dark:border-zinc-600 dark:bg-zinc-900/50">
                        <p class="font-semibold text-zinc-800 dark:text-zinc-200">{{ __('Top shell') }}</p>
                        <p class="mt-1 text-zinc-600 dark:text-zinc-400" x-show="selectedLayoutTemplate.use_header_section" x-cloak>{{ __('Site header navigation appears above the page content.') }}</p>
                        <div x-show="!selectedLayoutTemplate.use_header_section && selectedLayoutTemplate.use_hero_section" x-cloak>
                            <p class="mt-1 text-zinc-600 dark:text-zinc-400">{{ __('A hero area is reserved below the site header. Open the Hero row below and pick a Hero section or Hero video block—that choice is the design visitors see.') }}</p>
                            <template x-if="heroLayoutRows.length === 0">
                                <div class="mt-2 space-y-2 text-amber-800 dark:text-amber-200/90">
                                    <p>{{ __('This layout uses a hero area but has no row with category “hero”, so there is no hero dropdown yet. Add a row with section category “Hero Sections” in the layout template.') }}</p>
                                    <a
                                        x-show="layoutTemplateEditHref"
                                        x-cloak
                                        :href="layoutTemplateEditHref"
                                        class="inline-flex text-xs font-semibold text-amber-900 underline decoration-amber-900/50 underline-offset-2 hover:text-amber-950 dark:text-amber-100 dark:hover:text-white"
                                    >{{ __('Edit this layout template') }}</a>
                                </div>
                            </template>
                            <ul class="mt-2 list-disc space-y-1 pl-4 text-zinc-700 dark:text-zinc-300" x-show="heroLayoutRows.length > 0">
                                <template x-for="hrow in heroLayoutRows" :key="'hero-inline-' + hrow.id">
                                    <li>
                                        <span class="font-medium" x-text="hrow.label + ':'"></span>
                                        <span x-text="selectedElementTitle(hrow.id) || {{ Js::from(__('Not selected — expand the row below')) }}"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                        <p class="mt-1 text-zinc-600 dark:text-zinc-400" x-show="!selectedLayoutTemplate.use_header_section && !selectedLayoutTemplate.use_hero_section" x-cloak>{{ __('No extra top shell: content follows the row order below.') }}</p>
                    </div>
                </div>

                @error('layout_row_element')
                    <p class="mt-4 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="mt-5 space-y-3 border-t border-zinc-200 pt-5 dark:border-zinc-700">
                    <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Rows') }}</p>
                    <template x-for="row in displayLayoutRows" :key="'plt-row-' + row.id">
                        <div class="overflow-hidden rounded-md border border-zinc-200 dark:border-zinc-600">
                            <button
                                type="button"
                                class="flex w-full items-center gap-2 bg-zinc-50/90 px-3 py-2.5 text-left hover:bg-zinc-100 dark:bg-zinc-800/40 dark:hover:bg-zinc-800/70"
                                @click="toggleLayoutRowExpanded(row.id)"
                                :aria-expanded="isLayoutRowExpanded(row.id)"
                            >
                                <i class="fa-solid fa-chevron-down w-4 shrink-0 text-center text-[10px] text-zinc-500 transition-transform dark:text-zinc-400" :class="{ '-rotate-180': isLayoutRowExpanded(row.id) }" aria-hidden="true"></i>
                                <div class="min-w-0 flex-1">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-zinc-700 dark:text-zinc-300" x-text="row.label"></span>
                                    <span class="mt-0.5 block truncate text-xs text-zinc-500 dark:text-zinc-400 sm:mt-0 sm:ml-2 sm:inline" x-text="layoutRowSummary(row)"></span>
                                </div>
                            </button>
                            <div
                                class="border-t border-zinc-200 p-3 dark:border-zinc-600"
                                x-show="isLayoutRowExpanded(row.id)"
                                x-transition
                            >
                                <template x-if="row.row_kind === 'element'">
                                    <div>
                                        <p class="mb-2 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Choose a UI block. Options are grouped by section type (same names as in Page layout templates).') }}</p>
                                        <select class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900"
                                            x-model="layoutRowSelections[row.id]"
                                            :name="'layout_row_element[' + row.id + ']'">
                                            <option value="">{{ __('— None —') }}</option>
                                            <template x-for="group in elementsGroupedForRow(row)" :key="group.label + '-' + row.id">
                                                <optgroup :label="group.label">
                                                    <template x-for="el in group.items" :key="el.id">
                                                        <option :value="el.id" x-text="el.title + ' (' + el.type + ')'"></option>
                                                    </template>
                                                </optgroup>
                                            </template>
                                        </select>
                                        <p class="mt-2 text-xs text-amber-700 dark:text-amber-400/90" x-show="elementsForRow(row).length === 0" x-cloak>
                                            {{ __('No elements of this type yet. Create one under UI Blocks in the sidebar.') }}
                                        </p>
                                    </div>
                                </template>

                                <template x-if="row.row_kind === 'short_body' && isFirstRowKind(row, 'short_body')">
                                    <div>
                                        <label for="short_body" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('Intro (short body)') }} <span class="text-red-500">*</span></label>
                                        <textarea id="short_body" name="short_body" rows="4" required
                                            class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 @error('short_body') border-red-500 @enderror"
                                            placeholder="{{ __('Brief summary') }}">{{ old('short_body', $p->short_body ?? '') }}</textarea>
                                        @error('short_body')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </template>

                                <template x-if="row.row_kind === 'short_body' && !isFirstRowKind(row, 'short_body')">
                                    <p class="rounded-md border border-dashed border-zinc-300 bg-zinc-50 px-3 py-2 text-xs text-zinc-600 dark:border-zinc-600 dark:bg-zinc-900/40 dark:text-zinc-400">
                                        {{ __('This row repeats the same short body as the first intro slot above.') }}
                                    </p>
                                </template>

                                <template x-if="row.row_kind === 'long_body' && isFirstRowKind(row, 'long_body')">
                                    <div>
                                        <label for="input-long_body" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('Long body') }} <span class="text-red-500">*</span></label>
                                        <x-editor id="long_body" name="long_body" :value="old('long_body', $p->long_body ?? '')" placeholder="{{ __('Full page content') }}..." />
                                        @error('long_body')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </template>

                                <template x-if="row.row_kind === 'long_body' && !isFirstRowKind(row, 'long_body')">
                                    <p class="rounded-md border border-dashed border-zinc-300 bg-zinc-50 px-3 py-2 text-xs text-zinc-600 dark:border-zinc-600 dark:bg-zinc-900/40 dark:text-zinc-400">
                                        {{ __('This row repeats the same long body as the first body slot above.') }}
                                    </p>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        @endif

        {{-- Fallback only when page_rows section is off (legal template); otherwise intro/long live under Layout template rows --}}
        <template x-if="visibleSections.includes('body') && needsShortBodyFallback && !visibleSections.includes('page_rows')">
            <div class="{{ $mainCard }}" data-section="body" x-transition>
                <h4 class="mb-3 text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-400">{{ __('Intro (short body)') }}</h4>
                <div>
                    <label for="short_body" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('Intro (short body)') }} <span class="text-red-500">*</span></label>
                    <textarea id="short_body" name="short_body" rows="4" required
                        class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 @error('short_body') border-red-500 @enderror"
                        placeholder="{{ __('Brief summary') }}">{{ old('short_body', $p->short_body ?? '') }}</textarea>
                    @error('short_body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </template>

        <template x-if="visibleSections.includes('body') && needsLongBodyFallback && !visibleSections.includes('page_rows')">
            <div class="{{ $mainCard }}" data-section="body" x-transition>
                <h4 class="mb-3 text-xs font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-400">{{ __('Long body') }}</h4>
                <div>
                    <label for="input-long_body" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('Long body') }} <span class="text-red-500">*</span></label>
                    <x-editor id="long_body" name="long_body" :value="old('long_body', $p->long_body ?? '')" placeholder="{{ __('Full page content') }}..." />
                    @error('long_body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </template>
    </div>

    {{-- Sidebar (WordPress-style meta boxes) --}}
    <div class="space-y-4">
        <div id="publish-meta-box" class="overflow-hidden rounded-sm border border-zinc-300 bg-white shadow-sm dark:border-zinc-600 dark:bg-zinc-800/90" data-section="sidebar_settings" x-show="visibleSections.includes('sidebar_settings')" x-transition>
            <div class="flex items-center justify-between gap-2 border-b border-zinc-200 bg-zinc-100 px-3 py-2 dark:border-zinc-600 dark:bg-zinc-800">
                <span class="text-[11px] font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">{{ __('Publish') }}</span>
                @if($p && filled($p->slug))
                    <a href="{{ url('/pagina/'.$p->slug) }}" target="_blank" rel="noopener noreferrer" class="shrink-0 rounded border border-sky-600 bg-white px-2 py-0.5 text-[11px] font-medium text-sky-700 hover:bg-sky-50 dark:border-sky-500 dark:bg-zinc-800 dark:text-sky-400 dark:hover:bg-zinc-700">
                        {{ __('Preview changes') }}
                    </a>
                @else
                    <span class="text-[11px] text-zinc-400 dark:text-zinc-500" title="{{ __('Save the page with a slug to open a public preview.') }}">{{ __('Preview') }}</span>
                @endif
            </div>
            <div class="space-y-3 p-4 text-sm text-zinc-800 dark:text-zinc-200">
                <div class="flex items-start gap-2.5">
                    <i class="fa-solid fa-key mt-0.5 w-4 shrink-0 text-center text-zinc-400" aria-hidden="true"></i>
                    <div class="min-w-0 flex-1">
                        <span class="text-zinc-600 dark:text-zinc-400">{{ __('Status') }}:</span>
                        <span class="font-medium text-zinc-900 dark:text-zinc-100" x-text="isActiveLive ? {{ Js::from(__('Published')) }} : {{ Js::from(__('Draft')) }}"></span>
                        <a href="#publish-active-toggle" class="ml-1 text-xs font-medium text-sky-600 hover:underline dark:text-sky-400">{{ __('Edit') }}</a>
                    </div>
                </div>
                <div class="flex items-start gap-2.5">
                    <i class="fa-solid fa-eye mt-0.5 w-4 shrink-0 text-center text-zinc-400" aria-hidden="true"></i>
                    <div class="min-w-0 flex-1">
                        <span class="text-zinc-600 dark:text-zinc-400">{{ __('Visibility') }}:</span>
                        <span class="font-medium text-zinc-900 dark:text-zinc-100" x-text="isActiveLive ? {{ Js::from(__('Public')) }} : {{ Js::from(__('Hidden')) }}"></span>
                        <a href="#publish-active-toggle" class="ml-1 text-xs font-medium text-sky-600 hover:underline dark:text-sky-400">{{ __('Edit') }}</a>
                    </div>
                </div>
                @if($p)
                    <div class="flex items-start gap-2.5">
                        <i class="fa-regular fa-calendar mt-0.5 w-4 shrink-0 text-center text-zinc-400" aria-hidden="true"></i>
                        <div class="min-w-0 flex-1">
                            <span class="text-zinc-600 dark:text-zinc-400">{{ __('Published on') }}:</span>
                            <p class="mt-0.5 font-medium text-zinc-900 dark:text-zinc-100">{{ $publishedAtDisplay ?? __('Not set') }}</p>
                        </div>
                    </div>
                @endif
                <div id="publish-active-toggle" class="flex items-center justify-between gap-3 border-t border-zinc-100 pt-3 dark:border-zinc-700/80">
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Live on site') }}</span>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="peer sr-only" @checked(old('is_active', $p->is_active ?? true)) @change="isActiveLive = $event.target.checked">
                        <div class="peer h-6 w-11 rounded-full bg-zinc-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-zinc-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-[var(--color-accent)] peer-checked:after:translate-x-full peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:bg-zinc-600 dark:peer-focus:ring-primary/30"></div>
                    </label>
                </div>
                @if($p)
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Page URL') }}: <span class="break-all font-mono text-zinc-700 dark:text-zinc-300">{{ url('/pagina/'.($p->slug ?? '')) }}</span></p>
                @endif
                <div class="rounded-md px-2.5 py-2 text-xs font-medium leading-snug" :class="seoScoreToneClasses">
                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                        <i class="fa-solid fa-chart-simple shrink-0" aria-hidden="true"></i>
                        <span>{{ __('SEO') }}: <span x-text="seoScoreEstimate"></span> / 100</span>
                    </div>
                    <p class="mt-1 text-[10px] font-normal opacity-90">{{ __('Based on meta title length and meta description length.') }}</p>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-2 border-t border-zinc-100 pt-3 dark:border-zinc-700/80">
                    @if($p)
                        <button type="submit" form="admin-page-delete-form" class="text-sm text-red-600 underline decoration-red-600/60 underline-offset-2 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm({{ Js::from(__('Move this page to trash?')) }})">
                            {{ __('Move to trash') }}
                        </button>
                    @else
                        <span></span>
                    @endif
                    <x-ui.button type="submit" form="admin-page-edit-form" name="submit_action" value="edit" variant="primary" size="sm">
                        {{ $p ? __('Update') : __('Save') }}
                    </x-ui.button>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-sm border border-zinc-300 bg-white shadow-sm dark:border-zinc-600 dark:bg-zinc-800/90" data-section="page_info" x-show="visibleSections.includes('page_info')" x-transition>
            <div class="border-b border-zinc-200 bg-zinc-100 px-3 py-2 text-[11px] font-semibold uppercase tracking-wide text-zinc-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                {{ __('Page template') }}
            </div>
            <div class="p-4">
                <label for="template" class="mb-1.5 block text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ __('Template') }}</label>
                <select id="template" name="template" x-model="currentTemplate"
                    class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/20 dark:border-zinc-600 dark:bg-zinc-900">
                    @foreach($templates ?? [] as $key => $config)
                        <option value="{{ $key }}">{{ $config['label'] ?? $key }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="overflow-hidden rounded-sm border border-zinc-300 bg-white shadow-sm dark:border-zinc-600 dark:bg-zinc-800/90" data-section="sidebar_image" x-show="visibleSections.includes('sidebar_image')" x-transition>
            <div class="border-b border-zinc-200 bg-zinc-100 px-3 py-2 text-[11px] font-semibold uppercase tracking-wide text-zinc-600 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                {{ __('Featured image') }}
            </div>
            <div class="p-4">
            <x-image-upload
                id="image"
                name="image"
                :label="__('Page image')"
                :help-text="__('Optional featured image (max 20MB).')"
                :max-size="20480"
                :required="false"
                current-image="{{ $p?->image ? asset('storage/'.$p->image) : '' }}"
                current-image-alt="{{ $p?->title ?? '' }}"
            />
            </div>
        </div>

        <div
            class="overflow-hidden rounded-sm border border-zinc-300 bg-white shadow-sm dark:border-zinc-600 dark:bg-zinc-800/90"
            data-section="seo"
            x-show="visibleSections.includes('seo')"
            x-transition
        >
            <button
                type="button"
                class="flex w-full items-center justify-between gap-2 border-b border-zinc-200 bg-zinc-100 px-3 py-2 text-left text-[11px] font-semibold uppercase tracking-wide text-zinc-600 hover:bg-zinc-200/70 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700/50"
                @click="sidebarSeoOpen = !sidebarSeoOpen"
                :aria-expanded="sidebarSeoOpen"
            >
                <span>{{ __('SEO') }}</span>
                <i class="fa-solid fa-chevron-up text-[10px] text-zinc-500 transition-transform duration-200 dark:text-zinc-400" :class="{ 'rotate-180': !sidebarSeoOpen }" aria-hidden="true"></i>
            </button>
            <div class="space-y-4 p-4" x-show="sidebarSeoOpen" x-transition>
            <div class="flex flex-wrap items-start justify-between gap-2 border-b border-zinc-100 pb-3 dark:border-zinc-600/50">
                <p class="max-w-xl text-[11px] leading-relaxed text-zinc-500 dark:text-zinc-400">
                    {{ __('Meta title is suggested from the page title; meta description from the short body. Clear a field or use the button to re-apply.') }}
                </p>
                <button
                    type="button"
                    class="shrink-0 rounded border border-zinc-200 bg-white px-2.5 py-1 text-[11px] font-medium text-sky-700 shadow-sm hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-800 dark:text-sky-400 dark:hover:bg-zinc-700"
                    onclick="window.dispatchEvent(new CustomEvent('seo-assist-page-sync'))"
                >
                    {{ __('Sync SEO from title & short body') }}
                </button>
            </div>
            <div>
                <div class="mb-1 flex items-center justify-between gap-2">
                    <label for="meta_title" class="block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('Meta title') }}</label>
                    <span class="text-xs tabular-nums text-zinc-500 dark:text-zinc-400"><span x-text="metaTitleLen"></span> / 255</span>
                </div>
                <input type="text" id="meta_title" name="meta_title" x-ref="metaTitle"
                    @input="metaTitleLen = $refs.metaTitle.value.length"
                    value="{{ old('meta_title', $p->meta_title ?? '') }}"
                    maxlength="255"
                    class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900">
            </div>
            <div>
                <div class="mb-1 flex items-center justify-between gap-2">
                    <label for="meta_body" class="block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('Meta description') }}</label>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">
                        <span x-text="metaBodyLen"></span> {{ __('chars') }}
                        <span class="text-zinc-400">(~160 {{ __('recommended') }})</span>
                    </span>
                </div>
                <textarea id="meta_body" name="meta_body" rows="3" x-ref="metaBody"
                    @input="metaBodyLen = $refs.metaBody.value.length"
                    class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900">{{ old('meta_body', $p->meta_body ?? '') }}</textarea>
            </div>
            <div>
                <label for="meta_keywords" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('Meta keywords') }}</label>
                <textarea id="meta_keywords" name="meta_keywords" rows="3"
                    class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900">{{ old('meta_keywords', $p->meta_keywords ?? '') }}</textarea>
            </div>
            </div>
        </div>

        <div
            class="overflow-hidden rounded-sm border border-zinc-300 bg-white shadow-sm dark:border-zinc-600 dark:bg-zinc-800/90"
            data-section="marketing"
            x-show="visibleSections.includes('marketing')"
            x-transition
        >
            <button
                type="button"
                class="flex w-full items-center justify-between gap-2 border-b border-zinc-200 bg-zinc-100 px-3 py-2 text-left text-[11px] font-semibold uppercase tracking-wide text-zinc-600 hover:bg-zinc-200/70 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700/50"
                @click="sidebarMarketingOpen = !sidebarMarketingOpen"
                :aria-expanded="sidebarMarketingOpen"
            >
                <span>{{ __('Marketing automation') }}</span>
                <i class="fa-solid fa-chevron-up text-[10px] text-zinc-500 transition-transform duration-200 dark:text-zinc-400" :class="{ 'rotate-180': !sidebarMarketingOpen }" aria-hidden="true"></i>
            </button>
            <div class="space-y-4 p-4" x-show="sidebarMarketingOpen" x-transition>
                <div class="grid grid-cols-1 gap-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="funnel_fase" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('Funnel phase') }}</label>
                            <select id="funnel_fase" name="funnel_fase"
                                class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900">
                                <option value="">{{ __('Select funnel phase') }}</option>
                                <option value="interesseer" @selected(old('funnel_fase', $p->funnel_fase ?? null) === 'interesseer')>Interesseer</option>
                                <option value="overtuig" @selected(old('funnel_fase', $p->funnel_fase ?? null) === 'overtuig')>Overtuig</option>
                                <option value="activeer" @selected(old('funnel_fase', $p->funnel_fase ?? null) === 'activeer')>Activeer</option>
                                <option value="inspireer" @selected(old('funnel_fase', $p->funnel_fase ?? null) === 'inspireer')>Inspireer</option>
                            </select>
                        </div>
                        <div>
                            <label for="marketing_persona_id" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('Target persona') }}</label>
                            <select id="marketing_persona_id" name="marketing_persona_id"
                                class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900">
                                <option value="">{{ __('Select persona') }}</option>
                                @foreach($marketingPersonas as $persona)
                                    <option value="{{ $persona->id }}" @selected(old('marketing_persona_id', $p->marketing_persona_id ?? null) == $persona->id)>{{ $persona->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="content_type_id" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('Content type') }}</label>
                            <select id="content_type_id" name="content_type_id"
                                class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900">
                                <option value="">{{ __('Select content type') }}</option>
                                @foreach($contentTypes as $type)
                                    <option value="{{ $type->id }}" @selected(old('content_type_id', $p->content_type_id ?? null) == $type->id)>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="primary_keyword" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('Primary keyword') }}</label>
                            <input type="text" id="primary_keyword" name="primary_keyword" value="{{ old('primary_keyword', $p->primary_keyword ?? '') }}"
                                class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900">
                        </div>
                    </div>
                    <div>
                        <label for="ai_briefing" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('AI briefing') }}</label>
                        <textarea id="ai_briefing" name="ai_briefing" rows="3"
                            class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900">{{ old('ai_briefing', $p->ai_briefing ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
