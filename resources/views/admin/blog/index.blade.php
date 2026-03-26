<x-layouts.admin title="Articles">
    <div class="space-y-6" x-data="blogPageData()" x-init="loadSocialMediaPlatforms()">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="mb-1 text-xl font-semibold text-zinc-900 dark:text-white">{{ __('Articles') }}</h1>
                <p class="text-[12.5px] text-zinc-600 dark:text-zinc-400">{{ __('Manage all articles in your system') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.blog.create') }}">
                    {{ __('Add article') }}
                </x-ui.button>
            </div>
        </div>

        <livewire:admin.table
            resource="blog"
            :columns="[
                ['key' => 'image', 'type' => 'custom', 'view' => 'admin.blog.partials.image-column'],
                'title',
                ['key' => 'blog_category.name', 'label' => 'Category', 'type' => 'custom', 'view' => 'admin.blog.partials.category-column'],
                ['key' => 'author.name', 'label' => 'Author', 'type' => 'custom', 'view' => 'admin.blog.partials.author-column'],
                ['key' => 'created_at', 'format' => 'date'],
                ['key' => 'is_active', 'type' => 'toggle'],
                ['key' => 'is_featured', 'type' => 'toggle'],
                ['key' => 'social_media', 'type' => 'custom', 'view' => 'admin.blog.partials.social-media-column'],
            ]"
            route-prefix="admin.blog"
            search-placeholder="{{ __('Search articles…') }}"
            :status-filter-options="['' => __('All statuses'), 'active' => __('Active'), 'inactive' => __('Inactive')]"
            :paginate="15"
            custom-actions-view="admin.blog.partials.table-actions"
            :search-fields="['title', 'slug', 'short_body']"
            entity-count-label="{{ __('articles') }}"
            :empty-state-title="__('No articles found')"
            :empty-cta-url="route('admin.blog.create')"
            :empty-cta-label="__('Add article')"
        />

        <x-ui.slide-over alpine-show="showSocialMediaModal" :title="__('Post to social media')" max-width="lg">
            <div class="space-y-4">
                <p class="text-[12.5px] text-zinc-600 dark:text-zinc-400">
                    {{ __('Article') }}: <span id="modalBlogTitle" class="font-medium text-zinc-900 dark:text-white"></span>
                </p>

                <form id="socialMediaForm">
                    <div class="space-y-4">
                        <div class="mb-4 rounded-lg border border-amber-200/60 bg-amber-50/30 p-3 dark:border-amber-500/20 dark:bg-amber-500/5">
                            <label class="flex cursor-pointer items-center gap-2">
                                <input type="checkbox" name="post_to_all" id="postToAllConfigured" value="1" class="rounded border-zinc-300 text-amber-600 focus:ring-amber-500 dark:border-zinc-600">
                                <span class="text-[12.5px] font-medium text-zinc-900 dark:text-white">{{ __('Post to all configured platforms') }}</span>
                                <span id="postToAllCount" class="text-[11px] text-zinc-500 dark:text-zinc-400">(0)</span>
                            </label>
                            <p class="ml-6 mt-1 text-[11px] text-zinc-600 dark:text-zinc-400">{{ __('Uses all active platforms that have API credentials. Text, image, or video is taken from the article.') }}</p>
                        </div>

                        <div id="platformSelectionSection">
                            <label class="mb-2 block text-[11.5px] font-medium text-zinc-700 dark:text-zinc-300">{{ __('Or select platforms') }}</label>
                            <div id="platformsList" class="space-y-2"></div>
                        </div>

                        <div>
                            <label class="mb-2 block text-[11.5px] font-medium text-zinc-700 dark:text-zinc-300">{{ __('Posting schedule') }}</label>
                            <div class="space-y-2">
                                <label class="flex items-center text-[12.5px] text-zinc-700 dark:text-zinc-300">
                                    <input type="radio" name="schedule_type" value="now" checked class="mr-2 focus:outline-none focus:ring-[var(--color-accent)]">
                                    <span>{{ __('Post immediately') }}</span>
                                </label>
                                <label class="flex items-center text-[12.5px] text-zinc-700 dark:text-zinc-300">
                                    <input type="radio" name="schedule_type" value="scheduled" class="mr-2 focus:outline-none focus:ring-[var(--color-accent)]">
                                    <span>{{ __('Schedule for later') }}</span>
                                </label>
                            </div>
                        </div>

                        <div id="scheduledDateContainer" class="hidden">
                            <label class="mb-2 block text-[11.5px] font-medium text-zinc-700 dark:text-zinc-300">{{ __('Schedule date and time') }}</label>
                            <input type="datetime-local" name="scheduled_at" class="block w-full rounded-md border border-zinc-200 bg-white px-2.5 py-2 text-[12.5px] text-zinc-900 focus:border-zinc-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                        </div>

                        <div>
                            <label class="mb-2 block text-[11.5px] font-medium text-zinc-700 dark:text-zinc-300">{{ __('Custom content (optional)') }}</label>
                            <textarea name="custom_content" rows="4" placeholder="{{ __('Leave empty to use content based on the article title and excerpt…') }}" class="block w-full rounded-md border border-zinc-200 bg-white px-2.5 py-2 text-[12.5px] text-zinc-900 placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:placeholder:text-zinc-500"></textarea>
                            <p class="mt-1 text-[11px] text-zinc-500 dark:text-zinc-400">{{ __('If empty, content is generated per platform.') }}</p>
                        </div>
                    </div>
                </form>
            </div>
            <x-slot name="footer">
                <x-ui.button variant="secondary" type="button" x-on:click="showSocialMediaModal = false">{{ __('Cancel') }}</x-ui.button>
                <x-ui.button variant="primary" type="button" id="socialMediaSubmitBtn" x-on:click="submitSocialMediaForm()">
                    <span id="submitButtonText">{{ __('Post now') }}</span>
                </x-ui.button>
            </x-slot>
        </x-ui.slide-over>

        <x-ui.slide-over alpine-show="showSocialMediaPostsModal" :title="__('Social media posts')" max-width="xl">
            <div id="socialMediaPostsList"></div>
        </x-ui.slide-over>
    </div>

    <script>
        function blogPageData() {
            return {
                currentBlogId: null,
                availablePlatforms: [],
                showSocialMediaModal: false,
                showSocialMediaPostsModal: false,

                async loadSocialMediaPlatforms() {
                    try {
                        const { data } = await axios.get('/admin/content/social-media-platforms');
                        this.availablePlatforms = data;
                        const configuredCount = (data || []).filter(p => p.is_configured).length;
                        const el = document.getElementById('postToAllCount');
                        if (el) el.textContent = '(' + configuredCount + ' configured)';
                    } catch (error) {
                        console.error('Error loading platforms:', error);
                    }
                },

                openSocialMediaModal(blogId, blogTitle) {
                    this.currentBlogId = blogId;
                    window.currentBlogId = blogId;
                    const titleEl = document.getElementById('modalBlogTitle');
                    if (titleEl) titleEl.textContent = blogTitle;

                    const platformsList = document.getElementById('platformsList');
                    if (!platformsList) return;
                    platformsList.innerHTML = '';

                    this.availablePlatforms.forEach(function(platform) {
                        const platformDiv = document.createElement('div');
                        platformDiv.className = 'flex items-center rounded-lg border border-zinc-200 p-3 hover:bg-zinc-50 dark:border-zinc-600 dark:hover:bg-zinc-800/50';

                        const charLimit = platform.settings && platform.settings.character_limit
                            ? 'Max ' + platform.settings.character_limit + ' chars'
                            : 'No limit';

                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.name = 'platforms[]';
                        checkbox.value = platform.id;
                        checkbox.id = 'platform_' + platform.id;
                        checkbox.className = 'mr-3 rounded border-zinc-300 text-[var(--color-accent)] focus:ring-[var(--color-accent)] dark:border-zinc-600';

                        const icon = document.createElement('i');
                        icon.className = platform.icon || '';
                        icon.style.color = platform.color || '';
                        icon.style.width = '20px';

                        const label = document.createElement('label');
                        label.htmlFor = 'platform_' + platform.id;
                        label.className = 'ml-2 flex-1 cursor-pointer';

                        const nameSpan = document.createElement('span');
                        nameSpan.className = 'font-medium text-zinc-900 dark:text-white';
                        nameSpan.textContent = platform.name || '';

                        const limitSpan = document.createElement('span');
                        limitSpan.className = 'mt-0.5 block text-[11px] text-zinc-500 dark:text-zinc-400';
                        limitSpan.textContent = charLimit;

                        label.appendChild(nameSpan);
                        label.appendChild(limitSpan);

                        platformDiv.appendChild(checkbox);
                        platformDiv.appendChild(icon);
                        platformDiv.appendChild(label);

                        platformsList.appendChild(platformDiv);
                    });

                    this.showSocialMediaModal = true;
                },

                async viewSocialMediaPosts(blogId) {
                    try {
                        const { data: result } = await axios.get('/admin/content/blog/' + blogId + '/social-media-posts');

                        if (result.success) {
                            const postsList = document.getElementById('socialMediaPostsList');
                            if (!postsList) return;

                            if (result.posts.length === 0) {
                                postsList.innerHTML = '<div class="py-8 text-center">' +
                                    '<i class="fa-solid fa-share-nodes mb-4 text-4xl text-zinc-300 dark:text-zinc-600" aria-hidden="true"></i>' +
                                    '<h3 class="mb-2 text-sm font-medium text-zinc-900 dark:text-white">{{ __('No social media posts yet') }}</h3>' +
                                    '<p class="text-[12.5px] text-zinc-600 dark:text-zinc-400">{{ __('This article has not been posted to social media yet.') }}</p>' +
                                    '</div>';
                            } else {
                                let html = '';
                                result.posts.forEach(function(post) {
                                    const contentPreview = post.content
                                        ? (post.content.length > 200 ? post.content.substring(0, 200) + '…' : post.content)
                                        : '';

                                    let statusText = '';
                                    if (post.status === 'scheduled') {
                                        statusText = '{{ __('Scheduled') }}: ' + (post.scheduled_at || '');
                                    } else if (post.status === 'posted') {
                                        statusText = '{{ __('Posted') }}: ' + (post.posted_at || '');
                                    } else if (post.status === 'failed') {
                                        statusText = '{{ __('Failed') }}: ' + (post.error_message || '');
                                    }

                                    const viewPostLink = post.external_post_url
                                        ? '<a href="' + post.external_post_url + '" target="_blank" rel="noopener noreferrer" class="text-[var(--color-accent)] hover:opacity-80">{{ __('View post') }}</a>'
                                        : '';

                                    html += '<div class="mb-4 rounded-lg border border-zinc-200 p-4 dark:border-zinc-600">' +
                                        '<div class="mb-3 flex items-center justify-between">' +
                                        '<div class="flex items-center">' +
                                        '<i class="' + (post.platform.icon || '') + '" style="color: ' + (post.platform.color || '') + '; width: 20px;"></i>' +
                                        '<span class="ml-2 text-sm font-medium text-zinc-900 dark:text-white">' + (post.platform.name || '') + '</span>' +
                                        '</div>' +
                                        (post.status_badge || '') +
                                        '</div>' +
                                        '<div class="mb-3 text-[12.5px] text-zinc-700 dark:text-zinc-300">' + contentPreview + '</div>' +
                                        '<div class="flex items-center justify-between text-[11px] text-zinc-500 dark:text-zinc-400">' +
                                        '<div>' + statusText + '</div>' +
                                        viewPostLink +
                                        '</div>' +
                                        '</div>';
                                });
                                postsList.innerHTML = html;
                            }

                            this.showSocialMediaPostsModal = true;
                        } else if (typeof toastManager !== 'undefined') {
                            toastManager.show('error', '{{ __('Failed to load social media posts') }}');
                        }
                    } catch (error) {
                        console.error('Error loading social media posts:', error);
                        if (typeof toastManager !== 'undefined') {
                            toastManager.show('error', '{{ __('An error occurred while loading social media posts') }}');
                        }
                    }
                }
            };
        }

        window.addEventListener('open-social-media-modal', function(event) {
            const el = document.querySelector('[x-data*="blogPageData"]');
            if (el && el._x_dataStack && el._x_dataStack[0]) {
                el._x_dataStack[0].openSocialMediaModal(event.detail.id, event.detail.title);
            }
        });

        window.addEventListener('view-social-media-posts', function(event) {
            const el = document.querySelector('[x-data*="blogPageData"]');
            if (el && el._x_dataStack && el._x_dataStack[0]) {
                el._x_dataStack[0].viewSocialMediaPosts(event.detail.id);
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.name === 'schedule_type') {
                const scheduledContainer = document.getElementById('scheduledDateContainer');
                const submitButton = document.getElementById('submitButtonText');

                if (e.target.value === 'scheduled') {
                    scheduledContainer && scheduledContainer.classList.remove('hidden');
                    if (submitButton) submitButton.textContent = '{{ __('Schedule post') }}';
                } else {
                    scheduledContainer && scheduledContainer.classList.add('hidden');
                    if (submitButton) submitButton.textContent = '{{ __('Post now') }}';
                }
            }
        });

        function submitSocialMediaForm() {
            const form = document.getElementById('socialMediaForm');
            if (!form) return;
            const formData = new FormData(form);
            const postToAll = document.getElementById('postToAllConfigured') && document.getElementById('postToAllConfigured').checked;
            const selectedPlatforms = Array.from(document.querySelectorAll('input[name="platforms[]"]:checked')).map(function(cb) { return cb.value; });

            if (!postToAll && selectedPlatforms.length === 0) {
                if (typeof toastManager !== 'undefined') {
                    toastManager.show('error', '{{ __('Select at least one platform or enable post to all configured platforms.') }}');
                }
                return;
            }

            const submitButton = document.getElementById('socialMediaSubmitBtn');
            const submitLabel = document.getElementById('submitButtonText');
            const originalLabelHtml = submitLabel ? submitLabel.innerHTML : '';
            if (submitButton) {
                submitButton.disabled = true;
            }
            if (submitLabel) {
                submitLabel.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2" aria-hidden="true"></i>{{ __('Working…') }}';
            }

            const currentBlogId = window.currentBlogId;

            if (!currentBlogId) {
                if (typeof toastManager !== 'undefined') {
                    toastManager.show('error', '{{ __('Article not found') }}');
                }
                if (submitButton) {
                    submitButton.disabled = false;
                }
                if (submitLabel) {
                    submitLabel.innerHTML = originalLabelHtml;
                }
                return;
            }

            const payload = {
                schedule_type: formData.get('schedule_type'),
                scheduled_at: formData.get('scheduled_at'),
                custom_content: formData.get('custom_content')
            };
            if (postToAll) {
                payload.post_to_all = true;
                payload.platforms = [];
            } else {
                payload.platforms = selectedPlatforms;
            }

            axios.post('/admin/content/blog/' + currentBlogId + '/social-media-post', payload)
                .then(function(response) {
                    const result = response.data;
                    if (result.success) {
                        if (typeof toastManager !== 'undefined') {
                            toastManager.show('success', result.message);
                        }
                        const el = document.querySelector('[x-data*="blogPageData"]');
                        if (el && el._x_dataStack && el._x_dataStack[0]) {
                            el._x_dataStack[0].showSocialMediaModal = false;
                        }
                        form.reset();
                    } else if (typeof toastManager !== 'undefined') {
                        toastManager.show('error', result.message);
                    }
                })
                .catch(function(error) {
                    console.error('Error posting to social media:', error);
                    const message = error.response?.data?.message || '{{ __('An error occurred while posting to social media') }}';
                    if (typeof toastManager !== 'undefined') {
                        toastManager.show('error', message);
                    }
                })
                .finally(function() {
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                    if (submitLabel) {
                        submitLabel.innerHTML = originalLabelHtml;
                    }
                });
        }
    </script>
</x-layouts.admin>
