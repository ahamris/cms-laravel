<x-layouts.admin title="Edit Blog Post">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Edit Blog Post</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Update blog post information</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.blog.show', $blog) }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20 transition-all">
                <i class="fa-solid fa-eye text-blue-500"></i>
                View
            </a>
            <a href="{{ route('admin.blog.index') }}"
                class="inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-xs ring-1 ring-gray-300 ring-inset dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20 transition-all">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Blogs
            </a>
        </div>
    </div>

    <form action="{{ route('admin.blog.update', $blog) }}" method="POST" enctype="multipart/form-data"
        id="blogForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - 2/3 --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Post Details Section --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Post Details</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Basic information about your blog post.
                        </p>
                    </div>

                    <div class="space-y-6">
                        {{-- Title --}}
                        <x-ui.input id="title" name="title" :value="old('title', $blog->title)" label="Blog Title"
                            placeholder="e.g. 10 Tips for Better SEO" required />

                        {{-- Slug --}}
                        <x-ui.input id="slug" name="slug" label="URL Slug" :value="old('slug', $blog->slug)"
                            placeholder="url-friendly-slug" required
                            slug-from="title"
                            hint="URL-friendly version of the title. Auto-generated if left blank."
                            :error="$errors->has('slug')" :errorMessage="$errors->first('slug')" />

                        {{-- Short Description --}}
                        <x-ui.textarea id="short_body" name="short_body" label="Short Description" rows="3"
                            :value="old('short_body', $blog->short_body)" placeholder="Brief summary of the post..."
                            required :maxLength="150" :showCharacterCount="true"
                            hint="Summary length is important for SEO snippets." />

                        {{-- Main Content --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Main Content <span
                                    class="text-red-500">*</span></label>
                            <div class="prose-container">
                                <x-editor id="long_body" name="long_body" :value="$blog->long_body"
                                    placeholder="Write your amazing content here..." />
                            </div>
                            @error('long_body')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- SEO Settings Section --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">SEO & Metadata</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Control how your post appears in search
                            engines.</p>
                    </div>

                    @if(isset($seoRecommendations) && count($seoRecommendations) > 0)
                        <div
                            class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800 rounded-md">
                            <h3 class="text-sm font-bold text-amber-900 dark:text-amber-200 mb-2">
                                <i class="fa-solid fa-lightbulb mr-2"></i>SEO Recommendations
                            </h3>
                            <ul class="space-y-2 text-xs text-amber-800 dark:text-amber-300">
                                @foreach($seoRecommendations as $recommendation)
                                    <li class="flex items-start gap-2">
                                        <i class="fa-solid fa-circle-check mt-0.5 text-amber-500"></i>
                                        <span>{{ $recommendation['message'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <x-ui.input id="meta_title" name="meta_title" :value="old('meta_title', $blog->meta_title)"
                            label="Meta Title" placeholder="Search engine title (50-60 chars recommended)" />

                        <x-ui.textarea id="meta_description" name="meta_description" :value="old('meta_description', $blog->meta_description)" label="Meta Description" rows="3"
                            placeholder="Brief description for search engine results (150-160 chars recommended)" />

                        <x-ui.tag-input id="meta_keywords" name="meta_keywords" :tags="array_filter(explode(',', old('meta_keywords', $blog->meta_keywords ?? '')))" label="Meta Keywords"
                            placeholder="Add keyword and press enter..."
                            hint="Important keywords that describe your content." />
                    </div>
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- AI Content Generator Section --}}
                @if ($aiServiceConfigured ?? false)
                    <div
                        class="rounded-md border border-purple-200 dark:border-purple-500/20 bg-gradient-to-br from-purple-50 to-white dark:from-purple-900/10 dark:to-zinc-900/10 p-5 shadow-sm overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-md bg-purple-600 text-white shadow-sm">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                </div>
                                <h2 class="text-base font-semibold text-gray-900 dark:text-white">AI Content</h2>
                            </div>
                            <x-ui.button type="button" x-data="{ open: false }"
                                @click="open = !open; $dispatch('toggle-ai-panel')" variant="default" size="sm"
                                class="!px-2 !py-1">
                                <span x-text="open ? 'Hide' : 'Open'">Open</span>
                                <i class="fa-solid" :class="open ? 'fa-chevron-up' : 'fa-chevron-down' ? 'ml-1' : ''"></i>
                            </x-ui.button>
                        </div>

                        <div id="aiGeneratorPanel"
                            class="hidden mt-6 space-y-4 pt-6 border-t border-purple-200 dark:border-purple-500/20">
                            <div
                                class="p-3 rounded-md bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-xs text-amber-700 dark:text-amber-300">
                                <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                                <strong>Note:</strong> Re-generating content will overwrite your current edits.
                            </div>

                            <x-ui.input id="ai_topic" label="Topic" required :value="$blog->title"
                                placeholder="Topic or idea..." />

                            <x-ui.input id="ai_keywords" label="Target Keywords" :value="$blog->meta_keywords ?? ''"
                                placeholder="Keywords..." />

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <x-ui.select id="ai_tone" label="Tone" size="sm">
                                        <option value="professional">Professional</option>
                                        <option value="casual">Casual</option>
                                        <option value="expert">Expert</option>
                                        <option value="persuasive">Persuasive</option>
                                        <option value="neutral">Neutral</option>
                                    </x-ui.select>
                                </div>

                                <div>
                                    <x-ui.select id="ai_length" label="Length" size="sm">
                                        <option value="short">Short</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="long">Long</option>
                                    </x-ui.select>
                                </div>
                            </div>

                            <x-ui.button type="button" onclick="generateWithAI()" id="aiGenerateBtn" variant="primary"
                                color="purple" class="w-full mt-2" icon="wand-magic-sparkles">
                                <span id="aiGenerateBtnText">Regenerate Now</span>
                            </x-ui.button>

                            <div id="aiErrorMessage"
                                class="hidden p-3 rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-xs text-red-700 dark:text-red-300">
                                <span id="aiErrorText"></span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- SEO Score Card --}}
                @if(isset($blog->seo_status) && $blog->seo_status)
                    <div
                        class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                        <div class="mb-4">
                            <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">SEO Health</h2>
                            <div class="flex flex-col items-center justify-center py-4">
                                <div
                                    class="relative inline-flex items-center justify-center p-1 rounded-full border-4 {{ $blog->seo_score > 80 ? 'border-green-500' : ($blog->seo_score > 50 ? 'border-amber-500' : 'border-red-500') }} mb-4">
                                    <div
                                        class="w-16 h-16 rounded-full flex items-center justify-center bg-gray-50 dark:bg-zinc-800">
                                        <span
                                            class="text-xl font-bold {{ $blog->seo_score > 80 ? 'text-green-600' : ($blog->seo_score > 50 ? 'text-amber-600' : 'text-red-600') }}">
                                            {{ $blog->seo_score ?? '0' }}
                                        </span>
                                    </div>
                                </div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white capitalize">
                                    {{ str_replace('-', ' ', $blog->seo_status) }}
                                </p>
                            </div>
                        </div>
                        <button type="button" onclick="analyzeSEO({{ $blog->id }})" id="analyzeBtn"
                            class="w-full flex items-center justify-center gap-2 rounded-md bg-zinc-100 dark:bg-zinc-800 px-4 py-2 text-sm font-bold text-gray-900 dark:text-white hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-all">
                            <i class="fa-solid fa-arrows-rotate" id="analyzeIcon"></i>
                            Re-analyze SEO
                        </button>
                    </div>
                @endif

                {{-- Publishing Settings Section --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Publishing</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Post settings and visibility.</p>
                    </div>

                    <div class="space-y-6">
                        {{-- Blog Category --}}
                        <x-ui.select id="blog_category_id" name="blog_category_id" label="Category" required
                            placeholder="Select a category" :value="old('blog_category_id', $blog->blog_category_id)">
                            @foreach ($blogCategories as $category)
                                <option value="{{ $category->id }}" {{ old('blog_category_id', $blog->blog_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-ui.select>

                        {{-- Blog Type --}}
                        <x-ui.select id="blog_type_id" name="blog_type_id" label="Blog Type"
                            placeholder="Select a type (optional)" :value="old('blog_type_id', $blog->blog_type_id)">
                            <option value="">— None —</option>
                            @foreach ($blogTypes as $type)
                                <option value="{{ $type->id }}" {{ old('blog_type_id', $blog->blog_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </x-ui.select>

                        {{-- Author --}}
                        <x-ui.select id="author_id" name="author_id" label="Author" required
                            placeholder="Select an author" :value="old('author_id', $blog->author_id)">
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" {{ old('author_id', $blog->author_id) == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </x-ui.select>

                        {{-- Featured Status --}}
                        <div
                            class="flex items-center justify-between py-4 border-y border-gray-100 dark:border-white/5">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white">Featured
                                    Post</label>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Show on homepage</p>
                            </div>
                            <x-ui.toggle name="is_featured" :checked="old('is_featured', $blog->is_featured) == 1" />
                        </div>

                        {{-- Status Toggle --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white">Post
                                    Status</label>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Active and visible to public</p>
                            </div>
                            <x-ui.toggle name="is_active" :checked="old('is_active', $blog->is_active) == 1" />
                        </div>
                    </div>
                </div>

                {{-- Featured Image Section --}}
                <div
                    class="rounded-md border border-zinc-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-sm">
                    <div class="mb-4">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Featured Image</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update visual for sharing.</p>
                    </div>

                    <div class="space-y-4">
                        <x-image-upload id="image" name="image" label="" :required="false"
                            help-text="Optimal: 1200x630px (Max 20MB)" :max-size="20480"
                            :current-image="$blog->image ? Storage::disk('public')->url($blog->image) : null"
                            :current-image-alt="$blog->title" />
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="sticky top-24 space-y-4">
                    <x-ui.button type="submit" name="action" value="save" variant="primary"
                        class="w-full !py-3 font-bold" icon="floppy-disk">
                        Update Post
                    </x-ui.button>

                    <x-ui.button type="submit" name="action" value="save_and_stay" variant="default"
                        class="w-full !py-3 font-bold" icon="keyboard">
                        Save & Keep Editing
                    </x-ui.button>

                    <a href="{{ route('admin.blog.index') }}"
                        class="block w-full text-center text-sm font-semibold text-gray-500 dark:text-zinc-400 hover:text-gray-700 dark:hover:text-zinc-200 transition-all">
                        Cancel Changes
                    </a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            // AI Panel Toggle Logic
            window.addEventListener('toggle-ai-panel', () => {
                const panel = document.getElementById('aiGeneratorPanel');
                panel.classList.toggle('hidden');
            });

            function analyzeSEO(blogId) {
                const btn = document.getElementById('analyzeBtn');
                const icon = document.getElementById('analyzeIcon');

                btn.disabled = true;
                icon.classList.add('fa-spin');

                fetch(`/admin/content/blog/${blogId}/analyze-seo`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Failed to analyze SEO: ' + (data.error || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        alert('Error analyzing SEO: ' + error.message);
                    })
                    .finally(() => {
                        btn.disabled = false;
                        icon.classList.remove('fa-spin');
                    });
            }

            function generateWithAI() {
                const topic = document.getElementById('ai_topic').value.trim();
                const keywords = document.getElementById('ai_keywords').value.trim();
                const tone = document.getElementById('ai_tone').value;
                const length = document.getElementById('ai_length').value;

                const btn = document.getElementById('aiGenerateBtn');
                const btnText = document.getElementById('aiGenerateBtnText');
                const btnIcon = document.getElementById('aiGenerateIcon');
                const errorDiv = document.getElementById('aiErrorMessage');
                const errorText = document.getElementById('aiErrorText');

                if (!topic) {
                    errorDiv.classList.remove('hidden');
                    errorText.textContent = 'Please enter a topic or title idea.';
                    return;
                }

                if (!confirm('This will replace existing content. Continue?')) return;

                errorDiv.classList.add('hidden');
                btn.disabled = true;
                btnText.textContent = 'Generating...';
                btnIcon.classList.remove('fa-wand-magic-sparkles');
                btnIcon.classList.add('fa-spinner', 'fa-spin');

                fetch('{{ route('admin.blog.generate-with-ai') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ topic, keywords, tone, length }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.data.title) {
                                document.getElementById('title').value = data.data.title;
                                document.getElementById('title').dispatchEvent(new Event('input'));
                            }
                            if (data.data.short_body) {
                                document.getElementById('short_body').value = data.data.short_body.substring(0, 150);
                                document.getElementById('short_body').dispatchEvent(new Event('input'));
                            }
                            if (data.data.long_body) {
                                const longBodyInput = document.getElementById('input-long_body');
                                if (longBodyInput) {
                                    longBodyInput.value = data.data.long_body;
                                    longBodyInput.dispatchEvent(new Event('input', { bubbles: true }));
                                    const editorWrapper = longBodyInput.closest('[x-data]');
                                    if (editorWrapper && editorWrapper._x_dataStack) {
                                        const alpineData = editorWrapper._x_dataStack[0];
                                        if (alpineData && alpineData.setHTML) {
                                            alpineData.setHTML(data.data.long_body);
                                        }
                                    }
                                }
                            }
                            if (data.data.meta_title) document.getElementById('meta_title').value = data.data.meta_title;
                            if (data.data.meta_description) document.getElementById('meta_description').value = data.data.meta_description;

                            // Handle tag-input for keywords
                            if (data.data.meta_keywords) {
                                const tagInputContainer = document.getElementById('meta_keywords').closest('[x-data]');
                                if (tagInputContainer && tagInputContainer._x_dataStack) {
                                    const tagData = tagInputContainer._x_dataStack[0];
                                    const keywordsArray = data.data.meta_keywords.split(',').map(k => k.trim()).filter(k => k);
                                    tagData.tags = keywordsArray;
                                }
                            }

                            alert('✓ Content generated successfully!');
                        } else {
                            errorDiv.classList.remove('hidden');
                            errorText.textContent = data.error || 'Failed to generate content.';
                        }
                    })
                    .catch(error => {
                        errorDiv.classList.remove('hidden');
                        errorText.textContent = 'An error occurred during generation.';
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btnText.textContent = 'Regenerate';
                        btnIcon.classList.remove('fa-spinner', 'fa-spin');
                        btnIcon.classList.add('fa-wand-magic-sparkles');
                    });
            }
        </script>
    @endpush
</x-layouts.admin>