@php
    use App\Enums\ElementType;
    use App\Enums\PageLayoutRowKind;
    $templates = config('page_templates.templates', []);
    $templateLabel = $templates[$page->template ?? '']['label'] ?? ($page->template ?? '—');
    $linkedFaq = $page->elements->firstWhere('type', ElementType::Faq);
    $linkedCta = $page->elements->firstWhere('type', ElementType::Cta);
    $publicUrl = url('/pagina/'.$page->slug);
    $orderedLayoutAssignments = $page->layoutAssignments
        ->filter(fn ($a) => $a->templateRow !== null)
        ->sortBy(fn ($a) => [$a->templateRow->sort_order, $a->templateRow->id]);
@endphp

<x-layouts.admin title="View Page — {{ $page->title }}">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Page details</h1>
                <p class="mt-1 text-zinc-600 dark:text-zinc-400">Read-only overview; edit to change content.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <x-button variant="secondary" icon="arrow-left" icon-position="left" href="{{ route('admin.page.index') }}">
                    Back to pages
                </x-button>
                <x-button variant="primary" icon="edit" icon-position="left" href="{{ route('admin.page.edit', $page) }}">
                    Edit
                </x-button>
                <x-button variant="secondary" icon="up-right-from-square" icon-position="left" href="{{ $publicUrl }}" target="_blank" rel="noopener noreferrer">
                    View on site
                </x-button>
                <form action="{{ route('admin.page.duplicate', $page) }}" method="POST" class="inline">
                    @csrf
                    <x-button variant="secondary" icon="copy" icon-position="left" title="Duplicate this page" type="submit">
                        Duplicate
                    </x-button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900/80">
                    <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                        @if($page->image)
                            <div class="shrink-0">
                                <img src="{{ asset('storage/'.$page->image) }}" alt="{{ $page->title }}"
                                    class="h-40 w-40 rounded-lg border border-zinc-200 object-cover dark:border-zinc-600">
                            </div>
                        @endif
                        <div class="min-w-0 flex-1 space-y-4">
                            <div class="flex flex-wrap items-center gap-3">
                                @if($page->icon)
                                    <i class="{{ $page->icon }} text-primary text-2xl" aria-hidden="true"></i>
                                @endif
                                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $page->title }}</h2>
                                @if($page->is_active)
                                    <x-ui.badge variant="success" size="sm">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="error" size="sm">Inactive</x-ui.badge>
                                @endif
                                @if($page->is_homepage)
                                    <x-ui.badge variant="warning" icon="star" size="sm">Homepage</x-ui.badge>
                                @endif
                            </div>
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Slug</dt>
                                    <dd class="mt-1">
                                        <code class="rounded bg-zinc-100 px-2 py-1 text-sm text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200">{{ $page->slug }}</code>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Template</dt>
                                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $templateLabel }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Row layout template</dt>
                                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $page->pageLayoutTemplate?->name ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Layout</dt>
                                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $page->layout ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Published</dt>
                                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                                        {{ $page->published_at ? $page->published_at->format('M j, Y H:i') : '—' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Created</dt>
                                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $page->created_at->format('M j, Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Updated</dt>
                                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $page->updated_at->format('M j, Y H:i') }}</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">URL</dt>
                                    <dd class="mt-1">
                                        <a href="{{ $publicUrl }}" target="_blank" rel="noopener noreferrer"
                                            class="text-sm font-medium text-primary hover:underline">
                                            {{ $publicUrl }}
                                            <i class="fa-solid fa-arrow-up-right-from-square ml-1 text-xs opacity-70" aria-hidden="true"></i>
                                        </a>
                                    </dd>
                                </div>
                                @if($page->parent)
                                    <div class="sm:col-span-2">
                                        <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Parent page</dt>
                                        <dd class="mt-1">
                                            <a href="{{ route('admin.page.show', $page->parent) }}" class="text-sm text-primary hover:underline">
                                                {{ $page->parent->title }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                                @if($page->icon)
                                    <div class="sm:col-span-2">
                                        <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Icon class</dt>
                                        <dd class="mt-1 font-mono text-sm text-zinc-800 dark:text-zinc-200">{{ $page->icon }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900/80">
                    <h3 class="mb-3 text-lg font-semibold text-zinc-900 dark:text-white">{{ __('Intro (short body)') }}</h3>
                    <div class="prose prose-zinc dark:prose-invert max-w-none text-sm">
                        <p class="whitespace-pre-wrap text-zinc-700 dark:text-zinc-300">{{ $page->short_body }}</p>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900/80">
                    <h3 class="mb-3 text-lg font-semibold text-zinc-900 dark:text-white">Full content</h3>
                    <div class="prose prose-zinc dark:prose-invert max-w-none">
                        {!! $page->long_body !!}
                    </div>
                </div>

                @if($page->pageLayoutTemplate && $orderedLayoutAssignments->isNotEmpty())
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900/80">
                        <h3 class="mb-3 text-lg font-semibold text-zinc-900 dark:text-white">Row builder</h3>
                        <p class="mb-1 text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $page->pageLayoutTemplate->name }}</p>
                        <p class="mb-4 flex flex-wrap gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                            <span>{{ $page->pageLayoutTemplate->use_header_section ? __('Header: on') : __('Header: off') }}</span>
                            <span>·</span>
                            <span>{{ $page->pageLayoutTemplate->use_hero_section ? __('Hero: on') : __('Hero: off') }}</span>
                        </p>
                        <ol class="list-decimal space-y-2 pl-5 text-sm text-zinc-800 dark:text-zinc-200">
                            @foreach($orderedLayoutAssignments as $a)
                                @php
                                    $tr = $a->templateRow;
                                    $kind = $tr->row_kind instanceof \BackedEnum ? $tr->row_kind : PageLayoutRowKind::tryFrom((string) $tr->row_kind) ?? PageLayoutRowKind::Element;
                                    $catLabel = $tr->section_category ? config('page_row_section_categories.categories.'.$tr->section_category.'.label') : null;
                                @endphp
                                <li>
                                    <span class="font-medium">{{ $tr->label }}</span>
                                    @if($kind === PageLayoutRowKind::ShortBody)
                                        <span class="ml-1 rounded bg-violet-100 px-1.5 py-0.5 text-xs text-violet-800 dark:bg-violet-950 dark:text-violet-200">{{ __('Intro') }}</span>
                                    @elseif($kind === PageLayoutRowKind::LongBody)
                                        <span class="ml-1 rounded bg-sky-100 px-1.5 py-0.5 text-xs text-sky-800 dark:bg-sky-950 dark:text-sky-200">{{ __('Body') }}</span>
                                    @else
                                        @if($catLabel)
                                            <span class="ml-1 rounded bg-zinc-100 px-1.5 py-0.5 text-xs font-normal text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">{{ __($catLabel) }}</span>
                                        @endif
                                        @if($a->element)
                                            <span class="text-zinc-600 dark:text-zinc-400"> — {{ $a->element->title ?: ('Element #'.$a->element->id) }}</span>
                                            <span class="ml-1 rounded bg-zinc-100 px-1.5 py-0.5 text-xs text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">{{ $a->element->type->value }}</span>
                                        @else
                                            <span class="text-zinc-400"> — {{ __('No element selected') }}</span>
                                        @endif
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                @if($linkedFaq || $linkedCta)
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900/80">
                        <h3 class="mb-3 text-lg font-semibold text-zinc-900 dark:text-white">Linked elements</h3>
                        <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            @if($linkedFaq)
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">FAQ</dt>
                                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $linkedFaq->title ?: 'FAQ #'.$linkedFaq->id }}</dd>
                                </div>
                            @endif
                            @if($linkedCta)
                                <div>
                                    <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">CTA</dt>
                                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $linkedCta->title ?: 'CTA #'.$linkedCta->id }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif

                <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900/80">
                    <h3 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Marketing</h3>
                    <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Funnel phase</dt>
                            <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $page->funnel_fase ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Persona</dt>
                            <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $page->marketingPersona->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Content type</dt>
                            <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $page->contentType->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Primary keyword</dt>
                            <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $page->primary_keyword ?? '—' }}</dd>
                        </div>
                        @if($page->secondary_keywords && count($page->secondary_keywords))
                            <div class="md:col-span-2">
                                <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Secondary keywords</dt>
                                <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ implode(', ', $page->secondary_keywords) }}</dd>
                            </div>
                        @endif
                        @if($page->ai_briefing)
                            <div class="md:col-span-2">
                                <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">AI briefing</dt>
                                <dd class="mt-1 whitespace-pre-wrap text-sm text-zinc-700 dark:text-zinc-300">{{ $page->ai_briefing }}</dd>
                            </div>
                        @endif
                        @if($page->seo_analysis && count($page->seo_analysis))
                            <div class="md:col-span-2">
                                <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">SEO analysis</dt>
                                <dd class="mt-1">
                                    <pre class="max-h-48 overflow-auto rounded-lg bg-zinc-50 p-3 text-xs text-zinc-800 dark:bg-zinc-950 dark:text-zinc-200">{{ json_encode($page->seo_analysis, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900/80">
                    <h3 class="mb-3 text-sm font-semibold text-zinc-900 dark:text-white">SEO</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Meta title</dt>
                            <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $page->meta_title ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Meta description</dt>
                            <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300">{{ $page->meta_body ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Meta keywords</dt>
                            <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300">{{ $page->meta_keywords ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>

                @if($page->ogImage)
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900/80">
                        <h3 class="mb-3 text-sm font-semibold text-zinc-900 dark:text-white">Open Graph image</h3>
                        <p class="text-sm text-zinc-700 dark:text-zinc-300">{{ $page->ogImage->title ?? $page->ogImage->original_filename ?? 'Media #'.$page->og_image_id }}</p>
                    </div>
                @elseif($page->og_image_id)
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900/80">
                        <h3 class="mb-3 text-sm font-semibold text-zinc-900 dark:text-white">Open Graph</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Media ID {{ $page->og_image_id }} (file not loaded)</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
