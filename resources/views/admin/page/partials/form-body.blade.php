@php
    $p = $page ?? null;
@endphp

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="space-y-4 lg:col-span-2">
        <div class="flex flex-wrap gap-2 border-b border-zinc-200 pb-3 dark:border-zinc-700" role="tablist">
            <button type="button" role="tab"
                :class="activeMainTab === 'content' ? 'bg-primary/10 text-primary border-primary/30' : 'bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-700'"
                class="rounded-md border px-3 py-1.5 text-sm font-medium transition-colors"
                @click="activeMainTab = 'content'">
                {{ __('Content') }}
            </button>
            <button type="button" role="tab"
                :class="activeMainTab === 'marketing' ? 'bg-primary/10 text-primary border-primary/30' : 'bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-700'"
                class="rounded-md border px-3 py-1.5 text-sm font-medium transition-colors"
                @click="activeMainTab = 'marketing'">
                {{ __('Marketing') }}
            </button>
        </div>

        <div class="space-y-6">
            <div x-show="activeMainTab === 'content'" x-transition class="space-y-6">
                {{-- Page Information --}}
                <div class="rounded-md border border-zinc-200 bg-zinc-50/50 p-6 dark:border-zinc-700 dark:bg-zinc-900/40" data-section="page_info" x-show="visibleSections.includes('page_info')" x-transition>
                    <h3 class="mb-4 flex items-center text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                        <span class="mr-2.5 flex h-7 w-7 items-center justify-center rounded-md bg-primary/10">
                            <i class="fa-solid fa-file-lines text-xs text-primary"></i>
                        </span>
                        {{ __('Page information') }}
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="template" class="mb-1.5 block text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ __('Template') }}</label>
                            <select id="template" name="template" x-model="currentTemplate"
                                class="mb-4 w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/20 dark:border-zinc-600 dark:bg-zinc-900">
                                @foreach($templates ?? [] as $key => $config)
                                    <option value="{{ $key }}">{{ $config['label'] ?? $key }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(!empty($pageLayoutTemplatesData))
                            <div class="mt-4 space-y-3 border-t border-zinc-200 pt-4 dark:border-zinc-700" data-section="page_rows" x-show="visibleSections.includes('page_rows')" x-transition>
                                <h4 class="text-xs font-semibold uppercase tracking-wide text-zinc-700 dark:text-zinc-300">{{ __('Layout template') }}</h4>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Pick a template: ordered rows can be page fields (intro / body) or components. Only component rows need an element from the library.') }}</p>
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
                                <div x-show="selectedLayoutTemplate" x-cloak class="flex flex-wrap items-center gap-2 rounded-md border border-zinc-200 bg-zinc-50/80 px-3 py-2 text-xs dark:border-zinc-600 dark:bg-zinc-900/50">
                                    <span class="font-medium text-zinc-600 dark:text-zinc-400">{{ __('Top shell') }}:</span>
                                    <span class="rounded-full bg-zinc-200 px-2 py-0.5 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-200" x-show="selectedLayoutTemplate.use_header_section">{{ __('Header') }}</span>
                                    <span class="rounded-full bg-zinc-200 px-2 py-0.5 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-200" x-show="!selectedLayoutTemplate.use_header_section && selectedLayoutTemplate.use_hero_section">{{ __('Hero') }}</span>
                                    <span class="rounded-full bg-zinc-200 px-2 py-0.5 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-200" x-show="!selectedLayoutTemplate.use_header_section && !selectedLayoutTemplate.use_hero_section">{{ __('None') }}</span>
                                </div>
                                <div x-show="layoutRowsForSelect.length" x-cloak class="space-y-3">
                                    <template x-for="row in layoutRowsForSelect" :key="row.id + '-' + row.row_kind">
                                        <div>
                                            <label class="mb-1 block text-xs font-medium text-zinc-600 dark:text-zinc-400" x-text="row.label"></label>
                                            <div x-show="row.row_kind === 'element'" x-cloak>
                                                <select class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900"
                                                    x-model="layoutRowSelections[row.id]"
                                                    :name="'layout_row_element[' + row.id + ']'">
                                                    <option value="">{{ __('— None —') }}</option>
                                                    <template x-for="el in elementsForRow(row)" :key="el.id">
                                                        <option :value="el.id" x-text="el.title + ' (' + el.type + ')'"></option>
                                                    </template>
                                                </select>
                                                <p class="mt-1 text-xs text-amber-700 dark:text-amber-400/90" x-show="elementsForRow(row).length === 0" x-cloak>
                                                    {{ __('No elements of this type yet. Create one under UI Blocks in the sidebar.') }}
                                                </p>
                                            </div>
                                            <p class="rounded-md border border-dashed border-zinc-300 bg-zinc-50 px-3 py-2 text-xs text-zinc-600 dark:border-zinc-600 dark:bg-zinc-900/40 dark:text-zinc-400" x-show="row.row_kind === 'short_body'" x-cloak>
                                                {{ __('This slot shows the page intro (short body field) in this position.') }}
                                            </p>
                                            <p class="rounded-md border border-dashed border-zinc-300 bg-zinc-50 px-3 py-2 text-xs text-zinc-600 dark:border-zinc-600 dark:bg-zinc-900/40 dark:text-zinc-400" x-show="row.row_kind === 'long_body'" x-cloak>
                                                {{ __('This slot shows the page body (long content) in this position.') }}
                                            </p>
                                        </div>
                                    </template>
                                </div>
                                @error('layout_row_element')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div>
                            <label for="title" class="mb-1.5 block text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ __('Title') }} <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title', $p->title ?? '') }}" required
                                class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/20 dark:border-zinc-600 dark:bg-zinc-900 @error('title') border-red-500 @enderror"
                                placeholder="{{ __('Page title') }}">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($p)
                                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Public URL') }}: <span class="font-mono text-zinc-700 dark:text-zinc-300">{{ url('/pagina/'.($p->slug ?? '')) }}</span></p>
                            @endif
                        </div>
                        <div>
                            <label for="slug" class="mb-1.5 block text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ __('URL slug') }} <span class="text-red-500">*</span></label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $p->slug ?? '') }}" required data-slug-from="title"
                                class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary/20 dark:border-zinc-600 dark:bg-zinc-900 @error('slug') border-red-500 @enderror"
                                placeholder="url-slug">
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Short Body & Long Body --}}
                <div class="space-y-6 rounded-md border border-zinc-200 bg-zinc-50/50 p-6 dark:border-zinc-700 dark:bg-zinc-900/40" data-section="body" x-show="visibleSections.includes('body')" x-transition>
                    <div>
                        <label for="short_body" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('Short body') }} <span class="text-red-500">*</span></label>
                        <textarea id="short_body" name="short_body" rows="4" required
                            class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 @error('short_body') border-red-500 @enderror"
                            placeholder="{{ __('Brief summary') }}">{{ old('short_body', $p->short_body ?? '') }}</textarea>
                        @error('short_body')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="long_body" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('Long body') }} <span class="text-red-500">*</span></label>
                        <x-editor id="long_body" name="long_body" :value="old('long_body', $p->long_body ?? '')" placeholder="{{ __('Full page content') }}..." />
                        @error('long_body')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div x-show="activeMainTab === 'marketing'" x-transition x-cloak>
                <div class="rounded-md border border-zinc-200 bg-zinc-50/50 p-6 dark:border-zinc-700 dark:bg-zinc-900/40" data-section="marketing" x-show="visibleSections.includes('marketing')" x-transition>
                    <h3 class="mb-4 flex items-center text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                        <span class="mr-2.5 flex h-7 w-7 items-center justify-center rounded-md bg-purple-50 dark:bg-purple-900/30">
                            <i class="fa-solid fa-bullhorn text-xs text-purple-500"></i>
                        </span>
                        {{ __('Marketing automation') }}
                    </h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
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
                        <div class="md:col-span-2">
                            <label for="ai_briefing" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('AI briefing') }}</label>
                            <textarea id="ai_briefing" name="ai_briefing" rows="3"
                                class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900">{{ old('ai_briefing', $p->ai_briefing ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="rounded-md border border-zinc-200 bg-zinc-50/50 p-6 dark:border-zinc-700 dark:bg-zinc-900/40" data-section="sidebar_settings" x-show="visibleSections.includes('sidebar_settings')" x-transition>
            <h3 class="mb-4 flex items-center text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                <span class="mr-2.5 flex h-7 w-7 items-center justify-center rounded-md bg-zinc-100 dark:bg-zinc-800">
                    <i class="fa-solid fa-cog text-xs text-zinc-500"></i>
                </span>
                {{ __('Page settings') }}
            </h3>
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ __('Status') }}</span>
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $p->is_active ?? true)) class="peer sr-only">
                    <div class="peer h-6 w-11 rounded-full bg-zinc-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-zinc-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-[var(--color-accent)] peer-checked:after:translate-x-full peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:bg-zinc-600 dark:peer-focus:ring-primary/30"></div>
                </label>
            </div>
            @if($p)
                <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Page URL') }}: {{ url('/pagina/'.($p->slug ?? '')) }}</p>
            @endif
        </div>

        <div class="rounded-md border border-zinc-200 bg-zinc-50/50 p-6 dark:border-zinc-700 dark:bg-zinc-900/40" data-section="sidebar_image" x-show="visibleSections.includes('sidebar_image')" x-transition>
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

        @php
            $selectedFaqId = old('faq_element_id', $p ? $p->elements->firstWhere('type', \App\Enums\ElementType::Faq)?->id : null);
            $selectedCtaId = old('cta_element_id', $p ? $p->elements->firstWhere('type', \App\Enums\ElementType::Cta)?->id : null);
        @endphp
        <div class="space-y-4 rounded-md border border-zinc-200 bg-zinc-50/50 p-6 dark:border-zinc-700 dark:bg-zinc-900/40" data-section="sidebar_elements" x-show="visibleSections.includes('sidebar_elements')" x-transition>
            <h3 class="mb-4 flex items-center text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                <span class="mr-2.5 flex h-7 w-7 items-center justify-center rounded-md bg-amber-50 dark:bg-amber-900/20">
                    <i class="fa-solid fa-puzzle-piece text-xs text-amber-600"></i>
                </span>
                {{ __('Element selection') }}
            </h3>
            <div>
                <label for="faq_element_id" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('FAQ element') }}</label>
                <select id="faq_element_id" name="faq_element_id"
                    class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 @error('faq_element_id') border-red-500 @enderror">
                    <option value="">— {{ __('None') }} —</option>
                    @foreach($faqElements ?? [] as $el)
                        <option value="{{ $el->id }}" @selected((string) $selectedFaqId === (string) $el->id)>{{ $el->title ?: 'FAQ #'.$el->id }}</option>
                    @endforeach
                </select>
                @error('faq_element_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="cta_element_id" class="mb-1 block text-xs font-medium text-zinc-700 dark:text-zinc-300">{{ __('CTA element') }}</label>
                <select id="cta_element_id" name="cta_element_id"
                    class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm focus:outline-none dark:border-zinc-600 dark:bg-zinc-900 @error('cta_element_id') border-red-500 @enderror">
                    <option value="">— {{ __('None') }} —</option>
                    @foreach($ctaElements ?? [] as $el)
                        <option value="{{ $el->id }}" @selected((string) $selectedCtaId === (string) $el->id)>{{ $el->title ?: 'CTA #'.$el->id }}</option>
                    @endforeach
                </select>
                @error('cta_element_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="space-y-4 rounded-md border border-zinc-200 bg-zinc-50/50 p-6 dark:border-zinc-700 dark:bg-zinc-900/40" data-section="seo" x-show="visibleSections.includes('seo')" x-transition>
            <h3 class="mb-4 flex items-center text-sm font-semibold text-zinc-800 dark:text-zinc-100">
                <span class="mr-2.5 flex h-7 w-7 items-center justify-center rounded-md bg-sky-50 dark:bg-sky-900/20">
                    <i class="fa-solid fa-search text-xs text-sky-500"></i>
                </span>
                {{ __('SEO settings') }}
            </h3>
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
</div>
