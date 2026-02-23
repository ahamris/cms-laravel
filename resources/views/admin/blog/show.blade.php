<x-layouts.admin title="Blog Post Details">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Blog Post Details</h1>
            <p class="text-zinc-600 dark:text-zinc-400">View the blog post information</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Blogs
            </a>
            <a href="{{ route('admin.blog.edit', $blog) }}" class="inline-flex items-center gap-2 rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-gray-500">
                <i class="fa-solid fa-edit"></i>
                Edit
            </a>
            <a href="{{ url('/artikelen/' . $blog->slug) }}" target="_blank" class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90">
                <i class="fa-solid fa-external-link-alt"></i>
                View Article
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
            {{-- Post Details Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Post Details</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Basic information about the blog post.</p>
                </div>

                <div class="space-y-6">
                    {{-- Featured Image --}}
                    @if($blog->image)
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Featured Image</label>
                        <div class="mt-2">
                            <img src="{{ Storage::url($blog->image) }}" 
                                 alt="{{ $blog->title }}" 
                                 class="w-full max-h-80 object-cover rounded-lg border border-gray-200 dark:border-white/10">
                        </div>
                    </div>
                    @endif

                    {{-- Title --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Title</label>
                        <div class="mt-2">
                            <p class="text-lg font-medium text-gray-900 dark:text-white">{{ $blog->title }}</p>
                        </div>
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Slug</label>
                        <div class="mt-2">
                            <code class="inline-flex items-center rounded-md bg-gray-100 dark:bg-white/10 px-3 py-1.5 text-sm font-mono text-gray-800 dark:text-gray-200">{{ $blog->slug }}</code>
                        </div>
                    </div>

                    {{-- Short Description --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Short Description</label>
                        <div class="mt-2 rounded-lg bg-gray-50 dark:bg-white/5 p-4 border border-gray-200 dark:border-white/10">
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $blog->short_body }}</p>
                        </div>
                    </div>

                    {{-- Main Content --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Main Content</label>
                        <div class="mt-2 rounded-lg bg-gray-50 dark:bg-white/5 p-6 border border-gray-200 dark:border-white/10">
                            <div class="prose prose-sm max-w-none dark:prose-invert">
                                {!! $blog->long_body !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column - 1/3 --}}
        <div class="lg:col-span-1 space-y-8">
            {{-- Publishing Section --}}
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                <div class="mb-6">
                    <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Publishing</h2>
                    <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Category, author, and status.</p>
                </div>

                <div class="space-y-6">
                    {{-- Category --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Category</label>
                        <div class="mt-2">
                            @if($blog->blog_category)
                                <span class="inline-flex items-center gap-x-1.5 rounded-full px-3 py-1.5 text-sm font-medium" 
                                      style="background-color: {{ $blog->blog_category->color }}20; color: {{ $blog->blog_category->color }};">
                                    <span class="size-2 rounded-full" style="background-color: {{ $blog->blog_category->color }};"></span>
                                    {{ $blog->blog_category->name }}
                                </span>
                            @else
                                <span class="text-sm text-gray-500 dark:text-gray-400">No category assigned</span>
                            @endif
                        </div>
                    </div>

                    {{-- Author --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Author</label>
                        <div class="mt-2">
                            <div class="flex items-center gap-x-3">
                                <div class="size-10 rounded-full bg-[var(--color-accent)]/10 flex items-center justify-center">
                                    <span class="text-sm font-medium text-[var(--color-accent)]">{{ substr($blog->author->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $blog->author->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $blog->author->email ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center justify-between">
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Status</label>
                        @if($blog->is_active)
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

                    {{-- Featured --}}
                    <div class="flex items-center justify-between">
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Featured</label>
                        @if($blog->is_featured)
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-yellow-100 dark:bg-yellow-500/10 px-3 py-1.5 text-sm font-medium text-yellow-700 dark:text-yellow-400">
                                <i class="fa-solid fa-star text-xs"></i>
                                Featured
                            </span>
                        @else
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-gray-100 dark:bg-gray-500/10 px-3 py-1.5 text-sm font-medium text-gray-600 dark:text-gray-400">
                                <i class="fa-regular fa-star text-xs"></i>
                                Not Featured
                            </span>
                        @endif
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
                            @if($blog->meta_title)
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $blog->meta_title }}</p>
                            @else
                                <p class="text-sm text-gray-400 dark:text-gray-500 italic">Not set</p>
                            @endif
                        </div>
                    </div>

                    {{-- Meta Description --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Meta Description</label>
                        <div class="mt-2">
                            @if($blog->meta_description)
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $blog->meta_description }}</p>
                            @else
                                <p class="text-sm text-gray-400 dark:text-gray-500 italic">Not set</p>
                            @endif
                        </div>
                    </div>

                    {{-- Meta Keywords --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Meta Keywords</label>
                        <div class="mt-2">
                            @if($blog->meta_keywords)
                                <div class="flex flex-wrap gap-2">
                                    @foreach(explode(',', $blog->meta_keywords) as $keyword)
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
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $blog->created_at->format('M d, Y H:i:s') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $blog->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    {{-- Updated At --}}
                    <div>
                        <label class="block text-sm/6 font-medium text-gray-900 dark:text-white">Updated At</label>
                        <div class="mt-2">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $blog->updated_at->format('M d, Y H:i:s') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $blog->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>
