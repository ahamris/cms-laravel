<x-layouts.admin title="Organizations">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Organizations</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage organizations (name and logo)</p>
            </div>
            <div class="flex items-center gap-3">
                <form id="organization-import-form" action="{{ route('admin.organization.import') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="organization-import-file" name="file" accept=".json" required />
                </form>
                <button type="button" id="organization-import-btn"
                    class="inline-flex items-center gap-2 rounded-md bg-zinc-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-zinc-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-600 dark:bg-zinc-500 dark:hover:bg-zinc-400">
                    <i class="fa-solid fa-file-import"></i>
                    Import JSON
                </button>
                <a href="{{ route('admin.organization.create') }}"
                    class="inline-flex items-center gap-2 rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 transition-opacity focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)]">
                    <i class="fa-solid fa-plus"></i>
                    Add Organization
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-md bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 px-4 py-3 text-sm text-green-800 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-800 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

        {{-- Organizations Table --}}
        <livewire:admin.table resource="organization" :columns="[
            'id',
            'name',
            ['key' => 'logo', 'label' => 'Logo', 'type' => 'custom', 'view' => 'admin.organization.partials.logo-column'],
            ['key' => 'created_at', 'format' => 'date'],
        ]" route-prefix="admin.organization"
            search-placeholder="Search organizations..." :paginate="15"
            custom-actions-view="admin.organization.partials.table-actions"
            :search-fields="['name']" />

        {{-- View Drawer --}}
        <x-ui.drawer drawer-id="organization-view-drawer" max-width="2xl">
            <div
                class="relative flex h-full flex-col overflow-y-auto bg-white shadow-xl dark:bg-gray-800 dark:after:absolute dark:after:inset-y-0 dark:after:left-0 dark:after:w-px dark:after:bg-white/10">
                <div class="px-4 py-6 sm:px-6">
                    <div class="flex items-start justify-between">
                        <h2 id="organization-view-drawer-title"
                            class="text-base font-semibold text-gray-900 dark:text-white">Organization Details</h2>
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
                <div class="relative mt-6 flex-1 px-4 sm:px-6">
                    <div id="organization-view-content" class="space-y-6">
                        <div class="flex items-center justify-center py-12">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Loading...</div>
                        </div>
                    </div>
                </div>
            </div>
        </x-ui.drawer>

        {{-- Create/Edit Drawer --}}
        <x-ui.drawer drawer-id="organization-drawer" max-width="2xl">
            <form id="organization-form" method="POST" action="{{ route('admin.organization.store') }}" enctype="multipart/form-data"
                class="relative flex h-full flex-col overflow-y-auto bg-white shadow-xl dark:bg-gray-800 dark:after:absolute dark:after:inset-y-0 dark:after:left-0 dark:after:w-px dark:after:bg-white/10">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="">

                <div class="flex-1">
                    <div class="bg-gray-50 px-4 py-6 sm:px-6 dark:bg-gray-800/50">
                        <div class="flex items-start justify-between space-x-3">
                            <div class="space-y-1">
                                <h2 id="drawer-title" class="text-base font-semibold text-gray-900 dark:text-white">Edit Organization</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Update name and logo.</p>
                            </div>
                            <div class="flex h-7 items-center">
                                <button type="button" command="close" commandfor="organization-drawer"
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
                    <div class="space-y-6 px-4 py-6 sm:px-6">
                        <x-ui.input id="name" name="name" label="Name" placeholder="Organization name" required />
                        <div id="drawer-current-logo-wrap" class="hidden">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current logo</p>
                            <div id="drawer-current-logo" class="mb-4 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-4 inline-block"></div>
                        </div>
                        <x-ui.image-upload
                            id="logo"
                            name="logo"
                            label="Logo"
                            :required="false"
                            help-text="JPG, PNG, GIF, WebP or SVG. Max 2MB."
                            :max-size="2048"
                            size="medium"
                            current-image-alt="Organization logo"
                        />
                    </div>
                </div>
                <div class="mt-auto shrink-0 border-t border-gray-200 px-4 py-5 sm:px-6 dark:border-white/10">
                    <div class="flex justify-end space-x-3">
                        <button type="button" command="close" commandfor="organization-drawer"
                            class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 dark:bg-white/10 dark:text-gray-100 dark:shadow-none dark:ring-white/10 dark:hover:bg-white/20">
                            Cancel
                        </button>
                        <button type="submit" id="submit-button"
                            class="inline-flex justify-center rounded-md bg-[var(--color-accent)] px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 transition-opacity focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--color-accent)] dark:shadow-none">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </x-ui.drawer>
    </div>

    <script>
        function openEditDrawer(id) {
            const form = document.getElementById('organization-form');
            const drawer = document.getElementById('organization-drawer');
            const currentLogoWrap = document.getElementById('drawer-current-logo-wrap');
            const currentLogoEl = document.getElementById('drawer-current-logo');
            form.action = '{{ route('admin.organization.update', ':id') }}'.replace(':id', id);
            form.querySelector('#form-method').value = 'PUT';
            const logoInput = form.querySelector('#logo');
            if (logoInput) logoInput.removeAttribute('required');

            form.querySelectorAll('p.text-red-600, p.text-red-400').forEach(el => el.remove());
            form.querySelectorAll('input').forEach(input => input.classList.remove('outline-red-600', 'dark:outline-red-500'));
            if (currentLogoWrap && currentLogoEl) {
                currentLogoEl.innerHTML = '';
                currentLogoWrap.classList.add('hidden');
            }
            drawer.showModal();

            window.axios.get('{{ route('admin.organization.json', ':id') }}'.replace(':id', id))
                .then(response => {
                    const data = response.data;
                    form.querySelector('#name').value = data.name || '';
                    if (currentLogoWrap && currentLogoEl) {
                        if (data.logo_url) {
                            currentLogoEl.innerHTML = '<img src="' + data.logo_url + '" alt="" class="max-h-24 w-auto object-contain" />';
                            currentLogoWrap.classList.remove('hidden');
                        } else {
                            currentLogoEl.innerHTML = '';
                            currentLogoWrap.classList.add('hidden');
                        }
                    }
                })
                .catch(error => console.error('Error loading organization:', error));
        }

        window.addEventListener('open-edit-drawer', (event) => {
            openEditDrawer(event.detail.id);
        });

        function openViewDrawer(id) {
            const drawer = document.getElementById('organization-view-drawer');
            const content = document.getElementById('organization-view-content');
            const title = document.getElementById('organization-view-drawer-title');
            drawer.showModal();
            title.textContent = 'Organization Details';
            content.innerHTML = '<div class="flex items-center justify-center py-12"><div class="text-sm text-gray-500 dark:text-gray-400">Loading...</div></div>';

            window.axios.get('{{ route('admin.organization.json', ':id') }}'.replace(':id', id))
                .then(response => {
                    const data = response.data;
                    title.textContent = data.name || 'Organization Details';
                    const createdDate = data.created_at ? new Date(data.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
                    const updatedDate = data.updated_at ? new Date(data.updated_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'N/A';
                    const logoHtml = data.logo_url
                        ? `<img src="${data.logo_url}" alt="" class="h-16 w-16 rounded object-cover" />`
                        : '<span class="text-zinc-500 dark:text-zinc-400">No logo</span>';
                    content.innerHTML = `
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Details</h3>
                                <dl class="divide-y divide-gray-200 border-t border-b border-gray-200 dark:divide-white/10 dark:border-white/10">
                                    <div class="flex justify-between py-3 text-sm font-medium items-center">
                                        <dt class="text-gray-500 dark:text-gray-400">Name</dt>
                                        <dd class="text-gray-900 dark:text-white">${data.name || 'N/A'}</dd>
                                    </div>
                                    <div class="flex justify-between py-3 text-sm font-medium items-center">
                                        <dt class="text-gray-500 dark:text-gray-400">Logo</dt>
                                        <dd class="text-gray-900 dark:text-white">${logoHtml}</dd>
                                    </div>
                                    <div class="flex justify-between py-3 text-sm font-medium">
                                        <dt class="text-gray-500 dark:text-gray-400">Created</dt>
                                        <dd class="text-gray-900 dark:text-white">${createdDate}</dd>
                                    </div>
                                    <div class="flex justify-between py-3 text-sm font-medium">
                                        <dt class="text-gray-500 dark:text-gray-400">Updated</dt>
                                        <dd class="text-gray-900 dark:text-white">${updatedDate}</dd>
                                    </div>
                                </dl>
                            </div>
                            <div class="flex gap-3 pt-4">
                                <button type="button" onclick="openEditDrawer(${data.id}); document.getElementById('organization-view-drawer').close();"
                                    class="flex-1 rounded-md bg-[var(--color-accent)] px-3 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 transition-opacity">Edit</button>
                                <button type="button" command="close" commandfor="organization-view-drawer"
                                    class="flex-1 rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-xs ring-1 ring-zinc-300 ring-inset hover:bg-zinc-50 dark:bg-white/10 dark:text-zinc-100 dark:shadow-none dark:ring-white/10 dark:hover:bg-white/20">Close</button>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error loading organization:', error);
                    content.innerHTML = '<div class="flex items-center justify-center py-12"><div class="text-sm text-red-600 dark:text-red-400">Error loading data.</div></div>';
                });
        }

        window.addEventListener('open-view-drawer', (event) => {
            openViewDrawer(event.detail.id);
        });

        document.addEventListener('DOMContentLoaded', function () {
            var importBtn = document.getElementById('organization-import-btn');
            var importFile = document.getElementById('organization-import-file');
            var importForm = document.getElementById('organization-import-form');
            if (importBtn && importFile && importForm) {
                importBtn.addEventListener('click', function () { importFile.click(); });
                importFile.addEventListener('change', function () {
                    if (importFile.files && importFile.files.length) importForm.submit();
                });
            }

            const form = document.getElementById('organization-form');
            const drawer = document.getElementById('organization-drawer');
            if (form) {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const submitButton = document.getElementById('submit-button');
                    const originalText = submitButton.textContent;
                    submitButton.disabled = true;
                    submitButton.textContent = 'Saving...';
                    try {
                        const formData = new FormData(form);
                        await window.axios({ method: 'post', url: form.action, data: formData });
                        drawer.close();
                        window.location.reload();
                    } catch (error) {
                        if (error.response && error.response.status === 422 && error.response.data.errors) {
                            form.querySelectorAll('p.text-red-600, p.text-red-400').forEach(el => el.remove());
                            form.querySelectorAll('input').forEach(i => i.classList.remove('outline-red-600', 'dark:outline-red-500'));
                            Object.keys(error.response.data.errors).forEach(field => {
                                const input = form.querySelector('[name="' + field + '"]');
                                if (input) {
                                    input.classList.add('outline-red-600', 'dark:outline-red-500');
                                    const container = input.closest('div');
                                    if (container) {
                                        const p = document.createElement('p');
                                        p.className = 'mt-2 text-sm/6 text-red-600 dark:text-red-400';
                                        p.textContent = error.response.data.errors[field][0];
                                        container.appendChild(p);
                                    }
                                }
                            });
                        } else {
                            alert('An error occurred while saving.');
                        }
                    } finally {
                        submitButton.disabled = false;
                        submitButton.textContent = originalText;
                    }
                });
            }
        });
    </script>
</x-layouts.admin>
