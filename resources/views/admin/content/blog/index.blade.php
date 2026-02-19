<x-layouts.admin title="Blogs">
    <div class="space-y-6" x-data="blogPageData()" x-init="loadSocialMediaPlatforms()">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Blogs</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage all blog posts in your system</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.blog.create') }}">Add New Blog Post</x-button>
            </div>
        </div>

        {{-- Blogs Table --}}
        <livewire:admin.table
            resource="blog"
            :columns="[
                ['key' => 'image', 'type' => 'custom', 'view' => 'admin.content.blog.partials.image-column'],
                'title',
                ['key' => 'blog_category.name', 'label' => 'Category', 'type' => 'custom', 'view' => 'admin.content.blog.partials.category-column'],
                ['key' => 'author.name', 'label' => 'Author', 'type' => 'custom', 'view' => 'admin.content.blog.partials.author-column'],
                ['key' => 'created_at', 'format' => 'date'],
                ['key' => 'is_active', 'type' => 'toggle'],
                ['key' => 'is_featured', 'type' => 'toggle'],
                ['key' => 'social_media', 'type' => 'custom', 'view' => 'admin.content.blog.partials.social-media-column'],
            ]"
            route-prefix="admin.content.blog"
            search-placeholder="Search blogs..."
            :paginate="15"
            custom-actions-view="admin.content.blog.partials.table-actions"
            :search-fields="['title', 'slug', 'short_body']"
        />

        {{-- Social Media Posting Modal --}}
        <x-ui.modal modal-id="socialMediaModal" size="lg" alpine-show="showSocialMediaModal">
            <x-slot:title>Post to Social Media</x-slot:title>
            <div class="space-y-4">
                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Blog: <span id="modalBlogTitle" class="font-medium text-gray-900 dark:text-white"></span></p>
                </div>

                <form id="socialMediaForm">
                    <div class="space-y-4">
                        {{-- Platform Selection --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Platforms</label>
                            <div id="platformsList" class="space-y-2">
                                {{-- Platforms will be loaded dynamically --}}
                            </div>
                        </div>

                        {{-- Schedule Options --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Posting Schedule</label>
                            <div class="space-y-2">
                                <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="schedule_type" value="now" checked class="mr-2 focus:outline-none focus:ring-[var(--color-accent)]">
                                    <span>Post immediately</span>
                                </label>
                                <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="schedule_type" value="scheduled" class="mr-2 focus:outline-none focus:ring-[var(--color-accent)]">
                                    <span>Schedule for later</span>
                                </label>
                            </div>
                        </div>

                        {{-- Scheduled Date/Time --}}
                        <div id="scheduledDateContainer" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Schedule Date & Time</label>
                            <input type="datetime-local" 
                                   name="scheduled_at" 
                                   class="block w-full rounded-md bg-white dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white outline-1 -outline-offset-1 outline-gray-300 dark:outline-white/10 focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-[var(--color-accent)]">
                        </div>

                        {{-- Custom Content (Optional) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Custom Content (Optional)</label>
                            <textarea name="custom_content" 
                                      rows="4" 
                                      placeholder="Leave empty to use auto-generated content based on blog title and excerpt..."
                                      class="block w-full rounded-md bg-white dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white outline-1 -outline-offset-1 outline-gray-300 dark:outline-white/10 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-[var(--color-accent)]"></textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">If left empty, content will be automatically generated for each platform</p>
                        </div>
                    </div>
                </form>
            </div>
            <x-slot:footer>
                <x-button variant="secondary" x-on:click="showSocialMediaModal = false">Cancel</x-button>
                <x-button 
                    variant="primary" 
                    type="button"
                    x-on:click="submitSocialMediaForm()"
                >
                    <span id="submitButtonText">Post Now</span>
                </x-button>
            </x-slot:footer>
        </x-ui.modal>

        {{-- Social Media Posts View Modal --}}
        <x-ui.modal modal-id="socialMediaPostsModal" size="xl" alpine-show="showSocialMediaPostsModal">
            <x-slot:title>Social Media Posts</x-slot:title>
            <div id="socialMediaPostsList">
                {{-- Posts will be loaded dynamically --}}
            </div>
        </x-ui.modal>
    </div>

    <script>
        // Alpine.js data function
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
                    } catch (error) {
                        console.error('Error loading platforms:', error);
                    }
                },
                
                openSocialMediaModal(blogId, blogTitle) {
                    this.currentBlogId = blogId;
                    window.currentBlogId = blogId;
                    document.getElementById('modalBlogTitle').textContent = blogTitle;
                    
                    // Populate platforms
                    const platformsList = document.getElementById('platformsList');
                    platformsList.innerHTML = '';
                    
                    this.availablePlatforms.forEach(function(platform) {
                        const platformDiv = document.createElement('div');
                        platformDiv.className = 'flex items-center p-3 border border-gray-200 dark:border-white/10 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5';
                        
                        const charLimit = platform.settings && platform.settings.character_limit 
                            ? 'Max ' + platform.settings.character_limit + ' chars' 
                            : 'No limit';
                        
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.name = 'platforms[]';
                        checkbox.value = platform.id;
                        checkbox.id = 'platform_' + platform.id;
                        checkbox.className = 'mr-3 rounded border-gray-300 dark:border-white/10 text-[var(--color-accent)] focus:ring-[var(--color-accent)]';
                        
                        const icon = document.createElement('i');
                        icon.className = platform.icon || '';
                        icon.style.color = platform.color || '';
                        icon.style.width = '20px';
                        
                        const label = document.createElement('label');
                        label.htmlFor = 'platform_' + platform.id;
                        label.className = 'ml-2 flex-1 cursor-pointer';
                        
                        const nameSpan = document.createElement('span');
                        nameSpan.className = 'font-medium text-gray-900 dark:text-white';
                        nameSpan.textContent = platform.name || '';
                        
                        const limitSpan = document.createElement('span');
                        limitSpan.className = 'text-sm text-gray-500 dark:text-gray-400 block';
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
                            
                            if (result.posts.length === 0) {
                                postsList.innerHTML = '<div class="text-center py-8">' +
                                    '<i class="fa-solid fa-share-nodes text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>' +
                                    '<h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No social media posts yet</h3>' +
                                    '<p class="text-gray-600 dark:text-gray-400">This blog has not been posted to social media yet.</p>' +
                                    '</div>';
                            } else {
                                let html = '';
                                result.posts.forEach(function(post) {
                                    const contentPreview = post.content 
                                        ? (post.content.length > 200 ? post.content.substring(0, 200) + '...' : post.content) 
                                        : '';
                                    
                                    let statusText = '';
                                    if (post.status === 'scheduled') {
                                        statusText = 'Scheduled: ' + (post.scheduled_at || '');
                                    } else if (post.status === 'posted') {
                                        statusText = 'Posted: ' + (post.posted_at || '');
                                    } else if (post.status === 'failed') {
                                        statusText = 'Failed: ' + (post.error_message || '');
                                    }
                                    
                                    const viewPostLink = post.external_post_url 
                                        ? '<a href="' + post.external_post_url + '" target="_blank" class="text-[var(--color-accent)] hover:opacity-80">View Post</a>' 
                                        : '';
                                    
                                    html += '<div class="border border-gray-200 dark:border-white/10 rounded-lg p-4 mb-4">' +
                                        '<div class="flex items-center justify-between mb-3">' +
                                        '<div class="flex items-center">' +
                                        '<i class="' + (post.platform.icon || '') + '" style="color: ' + (post.platform.color || '') + '; width: 20px;"></i>' +
                                        '<span class="ml-2 font-medium text-gray-900 dark:text-white">' + (post.platform.name || '') + '</span>' +
                                        '</div>' +
                                        (post.status_badge || '') +
                                        '</div>' +
                                        '<div class="text-sm text-gray-700 dark:text-gray-300 mb-3">' + contentPreview + '</div>' +
                                        '<div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">' +
                                        '<div>' + statusText + '</div>' +
                                        viewPostLink +
                                        '</div>' +
                                        '</div>';
                                });
                                postsList.innerHTML = html;
                            }
                            
                            this.showSocialMediaPostsModal = true;
                        } else {
                            if (typeof toastManager !== 'undefined') {
                                toastManager.show('error', 'Failed to load social media posts');
                            }
                        }
                    } catch (error) {
                        console.error('Error loading social media posts:', error);
                        if (typeof toastManager !== 'undefined') {
                            toastManager.show('error', 'An error occurred while loading social media posts');
                        }
                    }
                }
            };
        }

        // Event listeners for custom events from Livewire table
        window.addEventListener('open-social-media-modal', function(event) {
            const el = document.querySelector('[x-data="blogPageData()"]');
            if (el && el._x_dataStack && el._x_dataStack[0]) {
                el._x_dataStack[0].openSocialMediaModal(event.detail.id, event.detail.title);
            }
        });

        window.addEventListener('view-social-media-posts', function(event) {
            const el = document.querySelector('[x-data="blogPageData()"]');
            if (el && el._x_dataStack && el._x_dataStack[0]) {
                el._x_dataStack[0].viewSocialMediaPosts(event.detail.id);
            }
        });

        // Handle schedule type change
        document.addEventListener('change', function(e) {
            if (e.target.name === 'schedule_type') {
                const scheduledContainer = document.getElementById('scheduledDateContainer');
                const submitButton = document.getElementById('submitButtonText');
                
                if (e.target.value === 'scheduled') {
                    scheduledContainer.classList.remove('hidden');
                    submitButton.textContent = 'Schedule Post';
                } else {
                    scheduledContainer.classList.add('hidden');
                    submitButton.textContent = 'Post Now';
                }
            }
        });

        // Handle form submission
        function submitSocialMediaForm() {
            const form = document.getElementById('socialMediaForm');
            const formData = new FormData(form);
            const selectedPlatforms = Array.from(document.querySelectorAll('input[name="platforms[]"]:checked')).map(function(cb) { return cb.value; });
            
            if (selectedPlatforms.length === 0) {
                if (typeof toastManager !== 'undefined') {
                    toastManager.show('error', 'Please select at least one platform');
                }
                return;
            }
            
            const submitButton = document.querySelector('#socialMediaModal button[type="button"]:last-child');
            const originalText = submitButton ? submitButton.innerHTML : '';
            if (submitButton) {
                submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Processing...';
                submitButton.disabled = true;
            }
            
            const currentBlogId = window.currentBlogId;
            
            if (!currentBlogId) {
                if (typeof toastManager !== 'undefined') {
                    toastManager.show('error', 'Blog ID not found');
                }
                if (submitButton) {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }
                return;
            }
            
            axios.post('/admin/content/blog/' + currentBlogId + '/social-media-post', {
                platforms: selectedPlatforms,
                schedule_type: formData.get('schedule_type'),
                scheduled_at: formData.get('scheduled_at'),
                custom_content: formData.get('custom_content')
            })
            .then(function(response) {
                const result = response.data;
                if (result.success) {
                    if (typeof toastManager !== 'undefined') {
                        toastManager.show('success', result.message);
                    }
                    // Close modal via Alpine.js
                    const el = document.querySelector('[x-data="blogPageData()"]');
                    if (el && el._x_dataStack && el._x_dataStack[0]) {
                        el._x_dataStack[0].showSocialMediaModal = false;
                    }
                    form.reset();
                } else {
                    if (typeof toastManager !== 'undefined') {
                        toastManager.show('error', result.message);
                    }
                }
            })
            .catch(function(error) {
                console.error('Error posting to social media:', error);
                const message = error.response?.data?.message || 'An error occurred while posting to social media';
                if (typeof toastManager !== 'undefined') {
                    toastManager.show('error', message);
                }
            })
            .finally(function() {
                if (submitButton) {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }
            });
        }
    </script>
</x-layouts.admin>
