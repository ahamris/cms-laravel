@php
    $__metaTitle = old('meta_title', '');
    $__metaBody = old('meta_body', '');
@endphp

<x-layouts.admin title="{{ __('Create page') }}">
    <div class="space-y-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex flex-col gap-1">
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">{{ __('Create page') }}</h1>
                <p class="text-[12.5px] text-zinc-600 dark:text-zinc-400">{{ __('Add a new page') }}</p>
            </div>
            <x-ui.button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.page.index') }}">
                {{ __('Back to pages') }}
            </x-ui.button>
        </div>

        <form action="{{ route('admin.content.page.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6"
            x-data="{
                templates: {{ Js::from($templates ?? []) }},
                currentTemplate: {{ Js::from($currentTemplate ?? 'default') }},
                activeMainTab: 'content',
                dirty: false,
                metaTitleLen: {{ strlen($__metaTitle) }},
                metaBodyLen: {{ strlen($__metaBody) }},
                get visibleSections() {
                    const t = this.templates[this.currentTemplate];
                    const def = this.templates['default'];
                    const fallback = ['page_info', 'page_rows', 'body', 'marketing', 'sidebar_settings', 'sidebar_image', 'sidebar_elements', 'seo'];
                    return (t && t.sections) ? t.sections : (def && def.sections) ? def.sections : fallback;
                },
                pageLayoutTemplatesData: {{ Js::from($pageLayoutTemplatesData ?? []) }},
                elementsForLayout: {{ Js::from($elementsForLayout ?? []) }},
                sectionElementTypes: {{ Js::from($sectionElementTypes ?? (object) []) }},
                layoutTemplateId: {{ Js::from(old('page_layout_template_id')) }},
                layoutRowSelections: {{ Js::from($layoutRowSelections ?? new \stdClass) }},
                get layoutRowsForSelect() {
                    const id = this.layoutTemplateId;
                    if (id === null || id === '' || id === undefined) return [];
                    const t = this.pageLayoutTemplatesData.find(x => String(x.id) === String(id));
                    return t && t.rows ? t.rows : [];
                },
                get selectedLayoutTemplate() {
                    const id = this.layoutTemplateId;
                    if (id === null || id === '' || id === undefined) return null;
                    return this.pageLayoutTemplatesData.find(x => String(x.id) === String(id)) ?? null;
                },
                elementsForRow(row) {
                    if (!row || row.row_kind !== 'element') return [];
                    const cat = row.section_category;
                    const allowed = this.sectionElementTypes[cat];
                    if (!allowed || !allowed.length) return [];
                    return this.elementsForLayout.filter(e => allowed.includes(e.type));
                },
                syncLayoutRowSelections() {
                    for (const row of this.layoutRowsForSelect) {
                        if (row.row_kind !== 'element') {
                            this.layoutRowSelections[row.id] = '';
                            continue;
                        }
                        const cur = this.layoutRowSelections[row.id];
                        if (cur === null || cur === '' || cur === undefined) continue;
                        const ok = this.elementsForRow(row).some(e => String(e.id) === String(cur));
                        if (!ok) this.layoutRowSelections[row.id] = '';
                    }
                },
            }"
            x-init="
                window.addEventListener('beforeunload', (e) => { if (dirty) { e.preventDefault(); e.returnValue = ''; } });
                $el.addEventListener('submit', () => { dirty = false });
                $watch('layoutTemplateId', () => { $nextTick(() => syncLayoutRowSelections()); });
            "
            @input.debounce.400ms="dirty = true"
            @change="dirty = true">
            @csrf

            @include('admin.page.partials.form-body')

            <div class="flex flex-col-reverse gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-700 sm:flex-row sm:justify-end">
                <x-button variant="secondary" href="{{ route('admin.page.index') }}">{{ __('Cancel') }}</x-button>
                <x-button type="submit" name="submit_action" value="index" variant="secondary" icon="save" icon-position="left">
                    {{ __('Save & close') }}
                </x-button>
                <x-button type="submit" name="submit_action" value="edit" variant="primary" icon="save" icon-position="left">
                    {{ __('Save') }}
                </x-button>
            </div>
        </form>
    </div>
    @push('scripts')
        <x-ui.slug-script />
    @endpush
</x-layouts.admin>
