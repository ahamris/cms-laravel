@php
use Illuminate\Support\Facades\Storage;
@endphp
<x-layouts.admin title="Module Details">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Module Details</h1>
            <p class="text-zinc-600 dark:text-zinc-400">View the module information</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.module.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Modules
            </a>
            <a href="{{ route('admin.module.edit', $module) }}" class="inline-flex items-center gap-2 rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-gray-500">
                <i class="fa-solid fa-edit"></i>
                Edit
            </a>
        </div>
    </div>

    @php
        $fontSans = str_replace(['"', ', sans-serif'], '', get_setting('theme_font_sans', 'Inter'));
        $fontOutfit = str_replace(['"', ', sans-serif'], '', get_setting('theme_font_outfit', 'Outfit'));
    @endphp
    <style>
    .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6,
    .prose p, .prose blockquote, .prose pre, .prose ul, .prose ol,
    .prose li, .prose a, .prose strong, .prose em, .prose span {
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
    .prose h1 { font-size: {{ get_setting('theme_font_size_h1', '3rem') }}; font-family: "{{ $fontOutfit }}", sans-serif; font-weight: 700; line-height: 1.25; }
    .prose h2 { font-size: {{ get_setting('theme_font_size_h2', '2.25rem') }}; font-family: "{{ $fontOutfit }}", sans-serif; font-weight: 700; }
    .prose h3 { font-size: {{ get_setting('theme_font_size_h3', '1.5rem') }}; font-family: "{{ $fontOutfit }}", sans-serif; font-weight: 700; }
    .prose h4 { font-size: {{ get_setting('theme_font_size_h4', '1.125rem') }}; font-family: "{{ $fontOutfit }}", sans-serif; font-weight: 600; }
    .prose h5 { font-size: {{ get_setting('theme_font_size_h5', '1rem') }}; font-family: "{{ $fontOutfit }}", sans-serif; font-weight: 600; }
    .prose h6 { font-size: {{ get_setting('theme_font_size_h6', '0.875rem') }}; font-family: "{{ $fontOutfit }}", sans-serif; font-weight: 600; }
    .prose p { font-size: {{ get_setting('theme_font_size_p', '1rem') }}; font-family: "{{ $fontSans }}", sans-serif; line-height: 1.625; }
    .prose a { font-size: {{ get_setting('theme_font_size_p', '1rem') }}; color: var(--color-accent); font-family: "{{ $fontSans }}", sans-serif; transition: color 0.2s; }
    .prose strong { font-weight: 700; font-family: "{{ $fontSans }}", sans-serif; }
    .prose em { font-style: italic; font-family: "{{ $fontSans }}", sans-serif; }
    .prose span { font-size: {{ get_setting('theme_font_size_p', '1rem') }}; font-family: "{{ $fontSans }}", sans-serif; }
    .prose ul { list-style-type: disc; list-style-position: outside; margin-left: 1rem; }
    .prose ol { list-style-type: decimal; list-style-position: outside; margin-left: 1rem; }
    </style>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column - 2/3 --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Module Details Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Module Details</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Basic information about the module.</p>
                </div>

                <div class="space-y-6">
                    {{-- Featured Image --}}
                    @if($module->image)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Featured Image</label>
                        <div class="mt-2">
                            <img src="{{ Storage::url($module->image) }}" 
                                 alt="{{ $module->title }}" 
                                 class="w-full max-h-80 object-cover rounded-lg border border-gray-200 dark:border-white/10">
                        </div>
                    </div>
                    @endif

                    {{-- Anchor --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Anchor</label>
                        <div class="mt-2">
                            <code class="inline-flex items-center rounded-md bg-gray-100 dark:bg-white/10 px-3 py-1.5 text-sm font-mono text-gray-800 dark:text-gray-200">#{{ $module->anchor }}</code>
                        </div>
                    </div>

                    {{-- Navigation Title --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Navigation Title</label>
                        <div class="mt-2">
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $module->nav_title }}</p>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Title</label>
                        <div class="mt-2">
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $module->title }}</p>
                        </div>
                    </div>

                    {{-- Slug --}}
                    @if($module->slug)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Slug</label>
                        <div class="mt-2">
                            <code class="inline-flex items-center rounded-md bg-gray-100 dark:bg-white/10 px-3 py-1.5 text-sm font-mono text-gray-800 dark:text-gray-200">{{ $module->slug }}</code>
                        </div>
                    </div>
                    @endif

                    {{-- Subtitle --}}
                    @if($module->subtitle)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Subtitle</label>
                        <div class="mt-2 rounded-lg bg-gray-50 dark:bg-white/5 p-4 border border-gray-200 dark:border-white/10">
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $module->subtitle }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Short Body --}}
                    @if($module->short_body)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Short Body</label>
                        <div class="mt-2 rounded-lg bg-gray-50 dark:bg-white/5 p-4 border border-gray-200 dark:border-white/10">
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $module->short_body }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Long Body --}}
                    @if($module->long_body)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Long Body</label>
                        <div class="mt-2 rounded-lg bg-gray-50 dark:bg-white/5 p-6 border border-gray-200 dark:border-white/10">
                            <div class="prose prose-sm max-w-none dark:prose-invert">
                                {!! $module->long_body !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- List Items --}}
                    @if($module->list_items && count($module->list_items) > 0)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">List Items</label>
                        <div class="mt-2 rounded-lg bg-gray-50 dark:bg-white/5 p-4 border border-gray-200 dark:border-white/10">
                            <ul class="space-y-2">
                                @foreach($module->list_items as $item)
                                    <li class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                                        <i class="fa-solid fa-check text-[var(--color-accent)] mt-0.5"></i>
                                        <span>{!! $item !!}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    {{-- Link Text --}}
                    @if($module->link_text)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Link Text</label>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $module->link_text }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Testimonial --}}
                    @if($module->testimonial_quote)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Testimonial</label>
                        <div class="mt-2 rounded-lg bg-blue-50 dark:bg-blue-500/10 border-l-4 border-blue-400 dark:border-blue-500 p-4">
                            <blockquote class="text-gray-900 dark:text-gray-100 italic mb-2">
                                "{{ $module->testimonial_quote }}"
                            </blockquote>
                            @if($module->testimonial_author || $module->testimonial_company)
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    @if($module->testimonial_author)
                                        <strong>{{ $module->testimonial_author }}</strong>
                                    @endif
                                    @if($module->testimonial_company)
                                        @if($module->testimonial_author), @endif
                                        {{ $module->testimonial_company }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Associated Solutions --}}
                    @if($module->solutions->count() > 0)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Associated Solutions</label>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($module->solutions as $solution)
                                <div class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-[var(--color-accent)]/10 flex items-center justify-center">
                                        <i class="fas fa-puzzle-piece text-[var(--color-accent)] text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $solution->nav_title }}</div>
                                        @if($solution->short_body)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($solution->short_body, 60) }}</div>
                                        @endif
                                    </div>
                                    <a href="{{ route('admin.solution.show', $solution) }}" 
                                       class="flex-shrink-0 text-[var(--color-accent)] hover:text-[var(--color-accent)]/80 text-sm">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Associated Features --}}
                    @if($module->features->count() > 0)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Associated Features</label>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($module->features as $feature)
                                <div class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-[var(--color-accent)]/10 flex items-center justify-center">
                                        @if($feature->icon)
                                            <i class="{{ $feature->icon }} text-[var(--color-accent)] text-sm"></i>
                                        @else
                                            <i class="fas fa-star text-[var(--color-accent)] text-sm"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $feature->title }}</div>
                                        @if($feature->description)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($feature->description, 60) }}</div>
                                        @endif
                                    </div>
                                    <a href="{{ route('admin.feature.show', $feature) }}" 
                                       class="flex-shrink-0 text-[var(--color-accent)] hover:text-[var(--color-accent)]/80 text-sm">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column - 1/3 --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Status & Settings Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Status & Settings</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Module status and display options.</p>
                </div>

                <div class="space-y-6">
                    {{-- Status --}}
                    <div class="flex items-center justify-between">
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Status</label>
                        @if($module->is_active)
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-green-100 dark:bg-green-500/10 px-3 py-1.5 text-sm font-medium text-green-700 dark:text-green-400">
                                <svg class="size-2 fill-green-500" viewBox="0 0 6 6"><circle cx="3" cy="3" r="3" /></svg>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-red-100 dark:bg-red-500/10 px-3 py-1.5 text-sm font-medium text-red-700 dark:text-red-400">
                                <svg class="size-2 fill-red-500" viewBox="0 0 6 6"><circle cx="3" cy="3" r="3" /></svg>
                                Inactive
                            </span>
                        @endif
                    </div>

                    {{-- Image Position --}}
                    @if($module->image_position)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Image Position</label>
                        <div class="mt-2">
                            <span class="inline-flex items-center gap-x-1.5 rounded-full px-3 py-1.5 text-sm font-medium {{ $module->image_position === 'left' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' }}">
                                <i class="fa-solid fa-image text-xs"></i>
                                {{ ucfirst($module->image_position) }}
                            </span>
                        </div>
                    </div>
                    @endif

                    {{-- Sort Order --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Sort Order</label>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $module->sort_order ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEO Settings Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">SEO Settings</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Search engine optimization metadata.</p>
                </div>

                <div class="space-y-6">
                    {{-- Meta Title --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Meta Title</label>
                        <div class="mt-2">
                            @if($module->meta_title)
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $module->meta_title }}</p>
                            @else
                                <p class="text-sm text-gray-400 dark:text-gray-500 italic">Not set</p>
                            @endif
                        </div>
                    </div>

                    {{-- Meta Description --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Meta Description</label>
                        <div class="mt-2">
                            @if($module->meta_description)
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $module->meta_description }}</p>
                            @else
                                <p class="text-sm text-gray-400 dark:text-gray-500 italic">Not set</p>
                            @endif
                        </div>
                    </div>

                    {{-- Meta Keywords --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Meta Keywords</label>
                        <div class="mt-2">
                            @if($module->meta_keywords)
                                <div class="flex flex-wrap gap-2">
                                    @foreach(explode(',', $module->meta_keywords) as $keyword)
                                        <span class="inline-flex items-center rounded-md bg-gray-100 dark:bg-white/10 px-2 py-1 text-xs font-medium text-gray-600 dark:text-gray-300">{{ trim($keyword) }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-400 dark:text-gray-500 italic">Not set</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Timestamps Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Timestamps</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Creation and modification dates.</p>
                </div>

                <div class="space-y-6">
                    {{-- Created At --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Created At</label>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $module->created_at->format('M d, Y H:i:s') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $module->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    {{-- Updated At --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Updated At</label>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $module->updated_at->format('M d, Y H:i:s') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $module->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>
