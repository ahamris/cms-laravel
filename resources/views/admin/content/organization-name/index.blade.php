<x-layouts.admin title="Organization Names">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Organization Names</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage all organization names in your system</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="outline-primary" icon="download" icon-position="left" x-data
                    x-on:click="toastManager.show('info', 'Exporting organization names...')">Export</x-button>
                <x-button variant="primary" icon="plus" icon-position="left"
                    href="{{ route('admin.content.organization-name.create') }}">Add New Organization</x-button>
            </div>
        </div>

        {{-- Organization Names Table --}}
        <livewire:admin.table resource="organization-name" :columns="[
        'id',
        'name',
        'abbreviation',
        'email',
        ['key' => 'is_active', 'type' => 'toggle'],
        ['key' => 'sort_order'],
        ['key' => 'created_at', 'format' => 'date'],
    ]"
            route-prefix="admin.content.organization-name" search-placeholder="Search organization names..."
            :paginate="15" custom-actions-view="admin.content.organization-name.partials.table-actions" />

        {{-- View Drawer --}}
        <x-ui.drawer drawer-id="organization-view-drawer" max-width="2xl">
            <div
                class="relative flex h-full flex-col overflow-y-auto bg-white shadow-xl dark:bg-gray-800 dark:after:absolute dark:after:inset-y-0 dark:after:left-0 dark:after:w-px dark:after:bg-white/10">
                {{-- Header --}}
                <div class="px-4 py-6 sm:px-6">
                    <div class="flex items-start justify-between">
                        <h2 id="organization-view-drawer-title"
                            class="text-base font-semibold text-gray-900 dark:text-white">Organization Name Details</h2>
                        <div class="ml-3 flex h-7 items-center">
                            <button type="button" command="close" commandfor="organization-view-drawer"
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
                    <div id="organization-view-content" class="space-y-6">
                        {{-- Loading state --}}
                        <div class="flex items-center justify-center py-12">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Loading...</div>
                        </div>
                    </div>
                </div>
            </div>
        </x-ui.drawer>

        {{-- Edit Drawer --}}
        <x-ui.drawer drawer-id="organization-drawer" max-width="2xl">
            <form id="organization-form" method="POST" action="{{ route('admin.content.organization-name.store') }}"
                class="relative flex h-full flex-col overflow-y-auto bg-white shadow-xl dark:bg-zinc-800">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="">

                <div class="flex-1">
                    {{-- Header --}}
                    <div class="bg-zinc-50 px-6 py-5 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 id="drawer-title" class="text-lg font-semibold text-zinc-900 dark:text-white">Edit
                                    Organization</h2>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Update the organization details
                                    below.</p>
                            </div>
                            <button type="button" command="close" commandfor="organization-drawer"
                                class="rounded-md p-1 text-zinc-400 hover:text-zinc-500 hover:bg-zinc-100 dark:hover:text-white dark:hover:bg-zinc-700 transition-colors">
                                <span class="sr-only">Close panel</span>
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Form Content --}}
                    <div class="px-6 py-6 space-y-5">
                        {{-- Organization Name --}}
                        <x-ui.input label="Organization Name" name="name" id="name" :value="old('name')"
                            placeholder="e.g., United Nations Educational, Scientific and Cultural Organization"
                            required />

                        {{-- Abbreviation & Email Row --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-ui.input label="Abbreviation" name="abbreviation" id="abbreviation"
                                :value="old('abbreviation')" placeholder="e.g., UNESCO" maxlength="10"
                                hint="Max 10 characters" />

                            <x-ui.input label="Email" name="email" id="email" type="email" :value="old('email')"
                                placeholder="contact@organization.org" icon="envelope" />
                        </div>

                        {{-- Address --}}
                        <x-ui.textarea 
                            label="Address" 
                            name="address" 
                            id="address" 
                            :value="old('address')"
                            placeholder="Enter the full address of the organization"
                            rows="3"
                            hint="Max 1000 characters" 
                        />

                        {{-- Sort Order --}}
                        <x-ui.input 
                            label="Sort Order" 
                            name="sort_order" 
                            id="sort_order" 
                            type="number"
                            :value="old('sort_order', 0)" 
                            min="0"
                            hint="Lower numbers appear first" 
                        />

                        {{-- Active Status --}}
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-zinc-900 dark:text-white">Active Status</span>
                                <span class="text-xs text-zinc-500">Visible in lists and searches</span>
                            </div>
                            <x-ui.toggle name="is_active" id="is_active" :checked="old('is_active', true)" />
                        </div>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div
                    class="shrink-0 border-t border-zinc-200 dark:border-zinc-700 px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50">
                    <div class="flex justify-end gap-3">
                        <x-ui.button type="button" variant="secondary" command="close" commandfor="organization-drawer">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary" id="submit-button">
                            Save Changes
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </x-ui.drawer>
    </div>

    @push('scripts')
        <script>
            let currentEditId = null;

            // Drawer açıldığında formu resetle
            function resetDrawerForm() {
                currentEditId = null;
                const form = document.getElementById('organization-form');
                const drawerTitle = document.getElementById('drawer-title');
                const drawerDescription = drawerTitle.nextElementSibling;
                const submitButton = document.getElementById('submit-button');
                const methodInput = document.getElementById('form-method');

                form.action = '{{ route('admin.content.organization-name.store') }}';
                methodInput.value = '';
                drawerTitle.textContent = 'New Organization Name';
                drawerDescription.textContent = 'Get started by filling in the information below to create your new organization name.';
                submitButton.textContent = 'Create';

                // Clear all error messages and styles
                form.querySelectorAll('p.text-red-600, p.text-red-400').forEach(el => {
                    if (el.textContent.trim() !== '' && el.previousElementSibling && (el.previousElementSibling.tagName === 'INPUT' || el.previousElementSibling.tagName === 'TEXTAREA')) {
                        el.remove();
                    }
                });
                form.querySelectorAll('input, textarea').forEach(input => {
                    input.classList.remove('outline-red-600', 'dark:outline-red-500', 'border-red-600', 'border-red-500');
                });

                // Form alanlarını temizle
                form.reset();
                form.querySelector('#is_active').checked = true;
            }

            // Edit drawer'ı aç
            function openEditDrawer(id) {
                currentEditId = id;
                const form = document.getElementById('organization-form');
                const drawerTitle = document.getElementById('drawer-title');
                const drawerDescription = drawerTitle.nextElementSibling;
                const submitButton = document.getElementById('submit-button');
                const methodInput = document.getElementById('form-method');

                // Drawer'ı aç
                const drawer = document.getElementById('organization-drawer');
                drawer.showModal();

                // Form action'ını güncelle
                form.action = '{{ route('admin.content.organization-name.update', ':id') }}'.replace(':id', id);
                methodInput.value = 'PUT';
                drawerTitle.textContent = 'Edit Organization Name';
                drawerDescription.textContent = 'Update the information below to edit this organization name.';
                submitButton.textContent = 'Update';

                // Veriyi yükle
                window.axios.get(`{{ route('admin.content.organization-name.json', ':id') }}`.replace(':id', id))
                    .then(response => {
                        const data = response.data;
                        form.querySelector('#name').value = data.name || '';
                        form.querySelector('#abbreviation').value = data.abbreviation || '';
                        form.querySelector('#email').value = data.email || '';
                        form.querySelector('#address').value = data.address || '';
                        form.querySelector('#sort_order').value = data.sort_order || 0;
                        form.querySelector('#is_active').checked = data.is_active || false;
                    })
                    .catch(error => {
                        console.error('Error loading organization data:', error);
                    });
            }

            // Event listener for edit drawer
            window.addEventListener('open-edit-drawer', (event) => {
                openEditDrawer(event.detail.id);
            });

            // View drawer'ı aç ve veriyi yükle
            function openViewDrawer(id) {
                const drawer = document.getElementById('organization-view-drawer');
                const content = document.getElementById('organization-view-content');
                const title = document.getElementById('organization-view-drawer-title');

                // Drawer'ı aç
                drawer.showModal();

                // Loading state
                title.textContent = 'Organization Name Details';
                content.innerHTML = '<div class="flex items-center justify-center py-12"><div class="text-sm text-gray-500 dark:text-gray-400">Loading...</div></div>';

                // Veriyi yükle
                window.axios.get(`{{ route('admin.content.organization-name.json', ':id') }}`.replace(':id', id))
                    .then(response => {
                        const data = response.data;
                        // Title'ı güncelle
                        title.textContent = data.name || 'Organization Name Details';
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
                                                <dt class="text-gray-500 dark:text-gray-400">Organization Name</dt>
                                                <dd class="text-gray-900 dark:text-white">${data.name || 'N/A'}</dd>
                                            </div>
                                            ${data.abbreviation ? `
                                            <div class="flex justify-between py-3 text-sm font-medium">
                                                <dt class="text-gray-500 dark:text-gray-400">Abbreviation</dt>
                                                <dd class="text-gray-900 dark:text-white">${data.abbreviation}</dd>
                                            </div>
                                            ` : ''}
                                            ${data.email ? `
                                            <div class="flex justify-between py-3 text-sm font-medium">
                                                <dt class="text-gray-500 dark:text-gray-400">Email</dt>
                                                <dd class="text-gray-900 dark:text-white">
                                                    <a href="mailto:${data.email}" class="text-[var(--color-accent)] dark:text-[var(--color-accent-content)] hover:opacity-80">${data.email}</a>
                                                </dd>
                                            </div>
                                            ` : ''}
                                            ${data.address ? `
                                            <div class="flex justify-between py-3 text-sm font-medium">
                                                <dt class="text-gray-500 dark:text-gray-400">Address</dt>
                                                <dd class="text-gray-900 dark:text-white text-right max-w-xs">${data.address.replace(/\\n/g, '<br>')}</dd>
                                            </div>
                                            ` : ''}
                                        </dl>
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
                                            <div class="flex justify-between py-3 text-sm font-medium">
                                                <dt class="text-gray-500 dark:text-gray-400">Sort Order</dt>
                                                <dd class="text-gray-900 dark:text-white font-mono">${data.sort_order || 0}</dd>
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
                                            onclick="openEditDrawer(${data.id}); document.getElementById('organization-view-drawer').close();"
                                            class="flex-1 rounded-md bg-[var(--color-accent)] px-3 py-2 text-sm font-semibold text-[var(--color-accent-foreground)] shadow-xs hover:bg-[color-mix(in_oklab,var(--color-accent)_90%,transparent)] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)] dark:shadow-none"
                                        >
                                            Edit
                                        </button>
                                        <button 
                                            type="button"
                                            command="close"
                                            commandfor="organization-view-drawer"
                                            class="flex-1 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-50 dark:bg-white/10 dark:text-gray-100 dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20"
                                        >
                                            Close
                                        </button>
                                    </div>
                                </div>
                            `;
                    })
                    .catch(error => {
                        console.error('Error loading organization data:', error);
                        content.innerHTML = '<div class="flex items-center justify-center py-12"><div class="text-sm text-red-600 dark:text-red-400">Error loading data. Please try again.</div></div>';
                    });
            }

            // Event listener for view drawer
            window.addEventListener('open-view-drawer', (event) => {
                openViewDrawer(event.detail.id);
            });

            // Form submit handler - AJAX ile submit et
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('organization-form');
                const drawer = document.getElementById('organization-drawer');

                if (form) {
                    form.addEventListener('submit', async function (e) {
                        e.preventDefault();

                        const submitButton = document.getElementById('submit-button');
                        const originalButtonText = submitButton.textContent;
                        submitButton.disabled = true;
                        submitButton.textContent = 'Saving...';

                        // Get form action and method
                        const action = form.action;
                        const method = form.querySelector('#form-method').value || 'POST';

                        try {
                            // Prepare form data
                            const formData = new FormData(form);
                            if (method === 'PUT') {
                                formData.append('_method', 'PUT');
                            }

                            // Use axios for the request
                            const response = await window.axios({
                                method: method === 'PUT' ? 'post' : 'post',
                                url: action,
                                data: formData,
                                headers: {
                                    'Content-Type': 'multipart/form-data',
                                },
                            });

                            // Success - close drawer and reload page
                            drawer.close();
                            window.location.reload();
                        } catch (error) {
                            // Handle validation errors
                            if (error.response && error.response.status === 422) {
                                const data = error.response.data;

                                // Clear previous errors
                                form.querySelectorAll('p.text-red-600, p.text-red-400').forEach(el => {
                                    if (el.textContent.trim() !== '' && el.previousElementSibling && (el.previousElementSibling.tagName === 'INPUT' || el.previousElementSibling.tagName === 'TEXTAREA')) {
                                        el.remove();
                                    }
                                });
                                form.querySelectorAll('input, textarea').forEach(input => {
                                    input.classList.remove('outline-red-600', 'dark:outline-red-500', 'border-red-600', 'border-red-500');
                                });

                                // Show new errors
                                if (data.errors) {
                                    Object.keys(data.errors).forEach(field => {
                                        const input = form.querySelector(`[name="${field}"]`);
                                        if (input) {
                                            input.classList.add('outline-red-600', 'dark:outline-red-500');

                                            // Find the parent container (sm:col-span-2 div)
                                            let parentContainer = input.parentElement;
                                            while (parentContainer && !parentContainer.classList.contains('sm:col-span-2')) {
                                                parentContainer = parentContainer.parentElement;
                                            }

                                            if (parentContainer) {
                                                // Remove existing error message in this container
                                                const existingError = parentContainer.querySelector('p.text-red-600, p.text-red-400');
                                                if (existingError) {
                                                    existingError.remove();
                                                }

                                                // Add error message
                                                const errorDiv = document.createElement('p');
                                                errorDiv.className = 'mt-2 text-sm/6 text-red-600 dark:text-red-400';
                                                errorDiv.textContent = data.errors[field][0];
                                                parentContainer.appendChild(errorDiv);
                                            }
                                        }
                                    });
                                }

                                // Show general error if exists
                                if (data.message && !data.errors) {
                                    alert(data.message);
                                }
                            } else {
                                // Other errors
                                console.error('Error submitting form:', error);
                                alert(error.response?.data?.message || 'An error occurred while saving. Please try again.');
                            }
                        } finally {
                            submitButton.disabled = false;
                            submitButton.textContent = originalButtonText;
                        }
                    });
                }
            });
        </script>
    @endpush

</x-layouts.admin>