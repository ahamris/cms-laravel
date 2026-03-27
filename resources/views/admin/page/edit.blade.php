@php
    $__metaTitle = old('meta_title', $page->meta_title ?? '');
    $__metaBody = old('meta_body', $page->meta_body ?? '');
@endphp

<x-layouts.admin title="{{ __('Edit page') }}">
    <div class="space-y-6">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex flex-col gap-1">
                <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">{{ __('Edit page') }}</h1>
                <p class="text-[12.5px] text-zinc-600 dark:text-zinc-400">{{ $page->title }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <x-ui.button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.page.index') }}">
                    {{ __('Back to pages') }}
                </x-ui.button>
                <x-ui.button variant="secondary" icon="eye" icon-position="left" href="{{ route('admin.page.show', $page) }}">
                    {{ __('View') }}
                </x-ui.button>
            </div>
        </div>

        <form id="admin-page-edit-form" action="{{ route('admin.page.update', $page) }}" method="POST" enctype="multipart/form-data" class="space-y-6 rounded-lg border border-zinc-200 bg-zinc-100/90 p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900/50"
            x-data="{
                templates: {{ Js::from($templates ?? []) }},
                currentTemplate: {{ Js::from($currentTemplate ?? 'default') }},
                dirty: false,
                isActiveLive: {{ Js::from((bool) old('is_active', $page->is_active ?? true)) }},
                slugUnlocked: false,
                layoutRowExpanded: {},
                sidebarSeoOpen: true,
                sidebarMarketingOpen: true,
                metaTitleLen: {{ strlen($__metaTitle) }},
                metaBodyLen: {{ strlen($__metaBody) }},
                get visibleSections() {
                    const t = this.templates[this.currentTemplate];
                    const def = this.templates['default'];
                    const fallback = ['page_info', 'page_rows', 'body', 'marketing', 'sidebar_settings', 'sidebar_image', 'seo'];
                    return (t && t.sections) ? t.sections : (def && def.sections) ? def.sections : fallback;
                },
                pageLayoutTemplatesData: {{ Js::from($pageLayoutTemplatesData ?? []) }},
                elementsForLayout: {{ Js::from($elementsForLayout ?? []) }},
                sectionElementTypes: {{ Js::from($sectionElementTypes ?? (object) []) }},
                elementTypeCategoryLabels: {{ Js::from($elementTypeCategoryLabels ?? []) }},
                sectionCategoryLabelsOrdered: {{ Js::from($sectionCategoryLabelsOrdered ?? []) }},
                layoutTemplateEditUrlTemplate: {{ Js::from($layoutTemplateEditUrlTemplate ?? '') }},
                layoutTemplateId: {{ Js::from(old('page_layout_template_id', $page->page_layout_template_id)) }},
                layoutRowSelections: {{ Js::from($layoutRowSelections ?? new \stdClass) }},
                get layoutRowsForSelect() {
                    const id = this.layoutTemplateId;
                    if (id === null || id === '' || id === undefined) return [];
                    const t = this.pageLayoutTemplatesData.find(x => String(x.id) === String(id));
                    return t && t.rows ? t.rows : [];
                },
                get displayLayoutRows() {
                    const base = [...this.layoutRowsForSelect];
                    if (!this.visibleSections.includes('page_rows') || !this.visibleSections.includes('body')) {
                        return base;
                    }
                    const out = [];
                    if (this.needsShortBodyFallback) {
                        out.push({
                            id: '__fallback_short_body',
                            row_kind: 'short_body',
                            label: {{ Js::from(__('Intro (short body)')) }},
                            section_category: null,
                            isSyntheticFallback: true,
                        });
                    }
                    out.push(...base);
                    if (this.needsLongBodyFallback) {
                        out.push({
                            id: '__fallback_long_body',
                            row_kind: 'long_body',
                            label: {{ Js::from(__('Main content')) }},
                            section_category: null,
                            isSyntheticFallback: true,
                        });
                    }
                    return out;
                },
                get layoutTemplateEditHref() {
                    if (!this.selectedLayoutTemplate || !this.layoutTemplateEditUrlTemplate) return '';
                    return this.layoutTemplateEditUrlTemplate.replace('__ID__', String(this.selectedLayoutTemplate.id));
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
                elementsGroupedForRow(row) {
                    const els = this.elementsForRow(row);
                    if (!els.length) return [];
                    const other = {{ Js::from(__('Other')) }};
                    const labelFor = (type) => this.elementTypeCategoryLabels[type] || other;
                    const buckets = new Map();
                    for (const el of els) {
                        const L = labelFor(el.type);
                        if (!buckets.has(L)) buckets.set(L, []);
                        buckets.get(L).push(el);
                    }
                    const byTitle = (a, b) => String(a.title || '').localeCompare(String(b.title || ''), undefined, { sensitivity: 'base' });
                    const out = [];
                    for (const L of this.sectionCategoryLabelsOrdered) {
                        if (buckets.has(L)) {
                            out.push({ label: L, items: [...buckets.get(L)].sort(byTitle) });
                            buckets.delete(L);
                        }
                    }
                    for (const [L, items] of buckets) {
                        out.push({ label: L, items: [...items].sort(byTitle) });
                    }
                    return out;
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
                isFirstRowKind(row, kind) {
                    const rows = this.displayLayoutRows;
                    const first = rows.find(r => r.row_kind === kind);
                    return Boolean(first && String(first.id) === String(row.id));
                },
                get needsShortBodyFallback() {
                    if (!this.visibleSections.includes('body')) return false;
                    if (!this.visibleSections.includes('page_rows')) return true;
                    if (this.layoutTemplateId === null || this.layoutTemplateId === '' || this.layoutTemplateId === undefined) return true;
                    return !this.layoutRowsForSelect.some(r => r.row_kind === 'short_body');
                },
                get needsLongBodyFallback() {
                    if (!this.visibleSections.includes('body')) return false;
                    if (!this.visibleSections.includes('page_rows')) return true;
                    if (this.layoutTemplateId === null || this.layoutTemplateId === '' || this.layoutTemplateId === undefined) return true;
                    return !this.layoutRowsForSelect.some(r => r.row_kind === 'long_body');
                },
                get heroLayoutRows() {
                    return this.layoutRowsForSelect.filter(r => r.row_kind === 'element' && r.section_category === 'hero');
                },
                selectedElementTitle(rowId) {
                    const id = this.layoutRowSelections[rowId];
                    if (id === null || id === '' || id === undefined) return '';
                    const el = this.elementsForLayout.find(e => String(e.id) === String(id));
                    return el ? (el.title + ' (' + el.type + ')') : '';
                },
                get seoScoreEstimate() {
                    let score = 0;
                    const title = this.metaTitleLen ?? 0;
                    const desc = this.metaBodyLen ?? 0;
                    if (title >= 10) score += 10;
                    if (title >= 30 && title <= 60) score += 25;
                    else if (title > 60) score += 15;
                    else if (title > 0) score += 5;
                    if (desc >= 50) score += 10;
                    if (desc >= 120 && desc <= 180) score += 35;
                    else if (desc > 0) score += 15;
                    if (title > 0 && desc > 0) score += 10;
                    return Math.min(100, score);
                },
                get seoScoreToneClasses() {
                    const s = this.seoScoreEstimate;
                    if (s >= 70) return 'bg-emerald-100 text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-200';
                    if (s >= 40) return 'bg-amber-100 text-amber-900 dark:bg-amber-900/35 dark:text-amber-200';
                    return 'bg-rose-100 text-rose-900 dark:bg-rose-900/40 dark:text-rose-200';
                },
                isLayoutRowExpanded(rowId) {
                    return this.layoutRowExpanded[rowId] !== false;
                },
                toggleLayoutRowExpanded(rowId) {
                    this.layoutRowExpanded[rowId] = !this.isLayoutRowExpanded(rowId);
                },
                layoutRowSummary(row) {
                    if (!row) return '';
                    if (row.row_kind === 'element') {
                        const t = this.selectedElementTitle(row.id);
                        return t || {{ Js::from(__('Not selected')) }};
                    }
                    if (row.row_kind === 'short_body') return {{ Js::from(__('Page intro')) }};
                    if (row.row_kind === 'long_body') return {{ Js::from(__('Main content')) }};
                    return '';
                },
                resetLayoutRowExpansion() {
                    this.layoutRowExpanded = {};
                },
            }"
            x-init="
                window.addEventListener('beforeunload', (e) => { if (dirty) { e.preventDefault(); e.returnValue = ''; } });
                $el.addEventListener('submit', () => { dirty = false });
                $nextTick(() => { syncLayoutRowSelections(); });
                $watch('layoutTemplateId', () => { resetLayoutRowExpansion(); $nextTick(() => syncLayoutRowSelections()); });
            "
            @input.debounce.400ms="dirty = true"
            @change="dirty = true">
            @csrf
            @method('PUT')

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

        <form id="admin-page-delete-form" action="{{ route('admin.page.destroy', $page) }}" method="POST" class="hidden" aria-hidden="true">
            @csrf
            @method('DELETE')
        </form>
    </div>
    @push('scripts')
        <x-ui.slug-script />
    @endpush
</x-layouts.admin>
