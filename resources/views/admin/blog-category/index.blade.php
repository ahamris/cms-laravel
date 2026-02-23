<x-layouts.admin title="Blog Categories">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Blog Categories</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage all blog categories in your system</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.blog-category.create') }}"
                    class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 transition-opacity focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)]">
                    <i class="fa-solid fa-plus"></i>
                    Add New Category
                </a>
            </div>
        </div>

        {{-- Blog Categories Table --}}
        <livewire:admin.table resource="blog-category" :columns="[
        'id',
        'name',
        'slug',
        ['key' => 'description', 'type' => 'text', 'limit' => 60],
        ['key' => 'color', 'type' => 'color'],
        ['key' => 'is_active', 'type' => 'toggle'],
        ['key' => 'created_at', 'format' => 'date'],
    ]" route-prefix="admin.content.blog-category"
            search-placeholder="Search blog categories..." :paginate="15"
            custom-actions-view="admin.blog-category.partials.table-actions"
            :search-fields="['name', 'slug', 'description']" />

        {{-- View Drawer --}}
        <x-ui.drawer drawer-id="blog-category-view-drawer" max-width="2xl">
            <div
                class="relative flex h-full flex-col overflow-y-auto bg-white shadow-xl dark:bg-gray-800 dark:after:absolute dark:after:inset-y-0 dark:after:left-0 dark:after:w-px dark:after:bg-white/10">
                {{-- Header --}}
                <div class="px-4 py-6 sm:px-6">
                    <div class="flex items-start justify-between">
                        <h2 id="blog-category-view-drawer-title"
                            class="text-base font-semibold text-gray-900 dark:text-white">Blog Category Details</h2>
                        <div class="ml-3 flex h-7 items-center">
                            <button type="button" command="close" commandfor="blog-category-view-drawer"
                                class="relative rounded-md text-gray-400 hover:text-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)] dark:hover:text-white dark:focus-visible:outline-[var(--color-accent)]">
                                <span class="absolute -inset-2.5"></span>
                                <span class="sr-only">Close panel</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                    data-slot="icon" aria-hidden="true" class="size-6">
                                    <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="relative mt-6 flex-1 px-4 sm:px-6">
                    <div id="blog-category-view-content" class="space-y-6">
                        {{-- Loading state --}}
                        <div class="flex items-center justify-center py-12">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Loading...</div>
                        </div>
                    </div>
                </div>
            </div>
        </x-ui.drawer>

        {{-- Create/Edit Drawer --}}
        <x-ui.drawer drawer-id="blog-category-drawer" max-width="2xl">
            <form id="blog-category-form" method="POST" action="{{ route('admin.blog-category.store') }}"
                class="relative flex h-full flex-col overflow-y-auto bg-white shadow-xl dark:bg-gray-800 dark:after:absolute dark:after:inset-y-0 dark:after:left-0 dark:after:w-px dark:after:bg-white/10">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="">

                <div class="flex-1">
                    {{-- Header --}}
                    <div class="bg-gray-50 px-4 py-6 sm:px-6 dark:bg-gray-800/50">
                        <div class="flex items-start justify-between space-x-3">
                            <div class="space-y-1">
                                <h2 id="drawer-title" class="text-base font-semibold text-gray-900 dark:text-white">Edit
                                    Blog Category</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Update the information below to edit
                                    this blog category.</p>
                            </div>
                            <div class="flex h-7 items-center">
                                <button type="button" command="close" commandfor="blog-category-drawer"
                                    class="relative rounded-md text-gray-400 hover:text-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)] dark:hover:text-white dark:focus-visible:outline-[var(--color-accent)]">
                                    <span class="absolute -inset-2.5"></span>
                                    <span class="sr-only">Close panel</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        data-slot="icon" aria-hidden="true" class="size-6">
                                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Form Content --}}
                    <div class="space-y-6 px-4 py-6 sm:px-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Name --}}
                            <x-ui.input id="name" name="name" label="Name" placeholder="e.g., Technology" required />

                            {{-- Slug --}}
                            <x-ui.input id="slug" name="slug" label="Slug" placeholder="e.g., technology" required
                                slug-from="name"
                                hint="URL-friendly version of the name" />
                        </div>

                        {{-- Description --}}
                        <div>
                            <div class="mb-4">
                                <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Description</h2>
                            </div>
                            <x-ui.textarea id="description" name="description" rows="4"
                                placeholder="Describe the blog category" required />
                        </div>

                        {{-- Color & Status --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                            {{-- Color --}}
                            <div>
                                <label for="color"
                                    class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">Color</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" id="color" name="color"
                                        class="h-10 w-12 rounded-md cursor-pointer dark:bg-white/5 dark:outline-white/10" />
                                    <x-ui.input type="text" id="colorText" name="" placeholder="#3B82F6"
                                        pattern="^#[0-9A-Fa-f]{6}$" />
                                </div>
                            </div>

                            {{-- Active Status --}}
                            <div class="flex items-center justify-between py-2">
                                <div>
                                    <label
                                        class="block text-sm/6 font-medium text-gray-900 dark:text-white">Active</label>
                                    <p class="text-sm/6 text-gray-600 dark:text-gray-400">Category is visible</p>
                                </div>
                                <x-ui.toggle id="is_active" name="is_active" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="mt-auto shrink-0 border-t border-gray-200 px-4 py-5 sm:px-6 dark:border-white/10">
                    <div class="flex justify-end space-x-3">
                        <button type="button" command="close" commandfor="blog-category-drawer"
                            class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 dark:bg-white/10 dark:text-gray-100 dark:shadow-none dark:ring-white/10 dark:hover:bg-white/20">
                            Cancel
                        </button>
                        <button type="submit" id="submit-button"
                            class="inline-flex justify-center rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 transition-opacity focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)] dark:shadow-none">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </x-ui.drawer>
    </div>

    <script>
        // Edit drawer'ı aç
        function openEditDrawer(id) {
            const form = document.getElementById('blog-category-form');
            const submitButton = document.getElementById('submit-button');
            const drawer = document.getElementById('blog-category-drawer');

            // Drawer'ı aç
            drawer.showModal();

            // Form action'ını güncelle
            form.action = '{{ route('admin.blog-category.update', ':id') }}'.replace(':id', id);
            form.querySelector('#form-method').value = 'PUT';

            // Clear previous errors
            form.querySelectorAll('p.text-red-600, p.text-red-400').forEach(el => el.remove());
            form.querySelectorAll('input, textarea').forEach(input => {
                input.classList.remove('outline-red-600', 'dark:outline-red-500');
            });

            // Veriyi yükle
            window.axios.get(`{{ route('admin.blog-category.json', ':id') }}`.replace(':id', id))
                .then(response => {
                    const data = response.data;
                    form.querySelector('#name').value = data.name || '';
                    form.querySelector('#slug').value = data.slug || '';
                    form.querySelector('#description').value = data.description || '';
                    form.querySelector('#color').value = data.color || '#3B82F6';
                    form.querySelector('#colorText').value = data.color || '#3B82F6';

                    const toggleInput = form.querySelector('#is_active');
                    if (toggleInput) {
                        toggleInput.checked = !!data.is_active;
                        toggleInput.dispatchEvent(new Event('change'));
                    }
                })
                .catch(error => {
                    console.error('Error loading blog category data:', error);
                });
        }

        // Event listener for edit drawer
        window.addEventListener('open-edit-drawer', (event) => {
            openEditDrawer(event.detail.id);
        });

        // View drawer'ı aç ve veriyi yükle
        function openViewDrawer(id) {
            const drawer = document.getElementById('blog-category-view-drawer');
            const content = document.getElementById('blog-category-view-content');
            const title = document.getElementById('blog-category-view-drawer-title');

            // Drawer'ı aç
            drawer.showModal();

            // Loading state
            title.textContent = 'Blog Category Details';
            content.innerHTML = '<div class="flex items-center justify-center py-12"><div class="text-sm text-gray-500 dark:text-gray-400">Loading...</div></div>';

            // Veriyi yükle
            window.axios.get(`{{ route('admin.blog-category.json', ':id') }}`.replace(':id', id))
                .then(response => {
                    const data = response.data;
                    // Title'ı güncelle
                    title.textContent = data.name || 'Blog Category Details';
                    // Format dates
                    const createdDate = data.created_at ? new Date(data.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
                    const updatedDate = data.updated_at ? new Date(data.updated_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';

                    content.innerHTML = `
                        <div class="space-y-6">
                            {{-- Basic Information --}}
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Basic Information</h3>
                                <dl class="divide-y divide-gray-200 border-t border-b border-gray-200 dark:divide-white/10 dark:border-white/10">
                                    <div class="flex justify-between py-3 text-sm font-medium">
                                        <dt class="text-gray-500 dark:text-gray-400">Name</dt>
                                        <dd class="text-gray-900 dark:text-white">${data.name || 'N/A'}</dd>
                                    </div>
                                    <div class="flex justify-between py-3 text-sm font-medium">
                                        <dt class="text-gray-500 dark:text-gray-400">Slug</dt>
                                        <dd class="text-gray-900 dark:text-white font-mono text-xs">${data.slug || 'N/A'}</dd>
                                    </div>
                                    <div class="flex justify-between py-3 text-sm font-medium">
                                        <dt class="text-gray-500 dark:text-gray-400">Color</dt>
                                        <dd class="text-gray-900 dark:text-white flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full border border-gray-300 dark:border-white/10" style="background-color: ${data.color || '#3B82F6'}"></div>
                                            <span class="font-mono text-xs">${data.color || 'N/A'}</span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            {{-- Description --}}
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Description</h3>
                                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-md p-4 border border-gray-200 dark:border-white/10">
                                    <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">${data.description || 'No description provided.'}</p>
                                </div>
                            </div>

                            {{-- Status Information --}}
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Status</h3>
                                <dl class="divide-y divide-gray-200 border-t border-b border-gray-200 dark:divide-white/10 dark:border-white/10">
                                    <div class="flex justify-between py-3 text-sm font-medium">
                                        <dt class="text-gray-500 dark:text-gray-400">Active Status</dt>
                                        <dd class="text-gray-900 dark:text-white">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${data.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'}">
                                                ${data.is_active ? 'Active' : 'Inactive'}
                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            {{-- Metadata --}}
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Metadata</h3>
                                <dl class="divide-y divide-gray-200 border-t border-b border-gray-200 dark:divide-white/10 dark:border-white/10">
                                    <div class="flex justify-between py-3 text-sm font-medium">
                                        <dt class="text-gray-500 dark:text-gray-400">ID</dt>
                                        <dd class="text-gray-900 dark:text-white font-mono">${data.id}</dd>
                                    </div>
                                    <div class="flex justify-between py-3 text-sm font-medium">
                                        <dt class="text-gray-500 dark:text-gray-400">Created</dt>
                                        <dd class="text-gray-900 dark:text-white">${createdDate}</dd>
                                    </div>
                                    <div class="flex justify-between py-3 text-sm font-medium">
                                        <dt class="text-gray-500 dark:text-gray-400">Last Updated</dt>
                                        <dd class="text-gray-900 dark:text-white">${updatedDate}</dd>
                                    </div>
                                </dl>
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-3 pt-4">
                                <button 
                                    type="button"
                                    onclick="openEditDrawer(${data.id}); document.getElementById('blog-category-view-drawer').close();"
                                    class="flex-1 rounded-md bg-[var(--color-accent)] px-3 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 transition-opacity focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)] dark:shadow-none"
                                >
                                    Edit
                                </button>
                                <button 
                                    type="button"
                                    command="close"
                                    commandfor="blog-category-view-drawer"
                                    class="flex-1 rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-xs ring-1 ring-zinc-300 ring-inset hover:bg-zinc-50 dark:bg-white/10 dark:text-zinc-100 dark:shadow-none dark:ring-white/10 dark:hover:bg-white/20"
                                >
                                    Close
                                </button>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error loading blog category data:', error);
                    content.innerHTML = '<div class="flex items-center justify-center py-12"><div class="text-sm text-red-600 dark:text-red-400">Error loading data. Please try again.</div></div>';
                });
        }

        // Event listener for view drawer
        window.addEventListener('open-view-drawer', (event) => {
            openViewDrawer(event.detail.id);
        });

        // Form submit handler - AJAX ile submit et
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('blog-category-form');
            const drawer = document.getElementById('blog-category-drawer');

            if (form) {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const submitButton = document.getElementById('submit-button');
                    const originalButtonText = submitButton.textContent;
                    submitButton.disabled = true;
                    submitButton.textContent = 'Saving...';

                    // Get form action and method
                    const action = form.action;

                    try {
                        const formData = new FormData(form);

                        // Use axios for the request
                        const response = await window.axios({
                            method: 'post',
                            url: action,
                            data: formData,
                        });

                        // Success - close drawer and reload page
                        drawer.close();
                        window.location.reload();
                    } catch (error) {
                        // Handle validation errors
                        if (error.response && error.response.status === 422) {
                            const data = error.response.data;

                            // Clear previous errors
                            form.querySelectorAll('p.text-red-600, p.text-red-400').forEach(el => el.remove());
                            form.querySelectorAll('input, textarea').forEach(input => {
                                input.classList.remove('outline-red-600', 'dark:outline-red-500');
                            });

                            // Show new errors
                            if (data.errors) {
                                Object.keys(data.errors).forEach(field => {
                                    const input = form.querySelector(`[name="${field}"]`);
                                    if (input) {
                                        input.classList.add('outline-red-600', 'dark:outline-red-500');

                                        const container = input.closest('div');
                                        if (container) {
                                            const errorDiv = document.createElement('p');
                                            errorDiv.className = 'mt-2 text-sm/6 text-red-600 dark:text-red-400';
                                            errorDiv.textContent = data.errors[field][0];
                                            container.appendChild(errorDiv);
                                        }
                                    }
                                });
                            }
                        } else {
                            console.error('Error submitting form:', error);
                            alert('An error occurred while saving. Please try again.');
                        }
                    } finally {
                        submitButton.disabled = false;
                        submitButton.textContent = originalButtonText;
                    }
                });
            }

            // Color picker synchronization
            const colorInput = document.getElementById('color');
            const colorTextInput = document.getElementById('colorText');

            if (colorInput && colorTextInput) {
                colorInput.addEventListener('input', function () {
                    colorTextInput.value = this.value.toUpperCase();
                });

                colorTextInput.addEventListener('input', function () {
                    if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                        colorInput.value = this.value;
                    }
                });
            }
        });
    </script>
</x-layouts.admin>