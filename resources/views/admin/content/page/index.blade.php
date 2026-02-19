<x-layouts.admin title="Pages">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Pages</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage website pages and content</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.content.page.create') }}">
                    Add Page
                </x-button>
            </div>
        </div>

        {{-- Pages Table --}}
        <livewire:admin.table
            resource="page"
            :columns="[
                ['key' => 'image', 'label' => 'Image', 'type' => 'custom', 'view' => 'admin.content.page.partials.image-column'],
                ['key' => 'title', 'label' => 'Title', 'type' => 'custom', 'view' => 'admin.content.page.partials.title-column'],
                ['key' => 'page_type', 'label' => 'Type', 'type' => 'custom', 'view' => 'admin.content.page.partials.type-column'],
                ['key' => 'slug', 'label' => 'Slug', 'type' => 'custom', 'view' => 'admin.content.page.partials.slug-column'],
                ['key' => 'created_at', 'label' => 'Created', 'format' => 'date'],
                ['key' => 'home_page', 'label' => 'Homepage', 'type' => 'custom', 'view' => 'admin.content.page.partials.homepage-column'],
                ['key' => 'is_active', 'label' => 'Status', 'type' => 'toggle'],
            ]"
            route-prefix="admin.content.page"
            search-placeholder="Search pages..."
            :paginate="15"
            custom-actions-view="admin.content.page.partials.table-actions"
            :search-fields="['title', 'slug']"
        />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-action="set-homepage"]').forEach(function (btn) {
                if (btn.disabled) return;
                btn.addEventListener('click', function () {
                    var id = this.getAttribute('data-page-id');
                    var title = this.getAttribute('data-page-title');
                    if (!confirm('Set "' + title + '" as the homepage? This will replace any existing homepage.')) return;
                    fetch('/admin/content/page/' + id + '/set-homepage', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(function (r) { return r.ok ? r.json() : r.json().then(function (d) { throw new Error(d.error || 'Error'); }); })
                    .then(function (d) {
                        if (d.success) {
                            if (typeof window.notify === 'function') window.notify('success', d.message || 'Homepage set.');
                            else if (typeof toastr !== 'undefined') toastr.success(d.message || 'Homepage set.');
                            setTimeout(function () { window.location.reload(); }, 800);
                        } else throw new Error(d.error);
                    })
                    .catch(function (e) {
                        if (typeof window.notify === 'function') window.notify('error', e.message);
                        else if (typeof toastr !== 'undefined') toastr.error(e.message);
                    });
                });
            });

            document.querySelectorAll('[data-action="remove-homepage"]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var id = this.getAttribute('data-page-id');
                    var title = this.getAttribute('data-page-title');
                    if (!confirm('Remove "' + title + '" as the homepage?')) return;
                    fetch('/admin/content/page/' + id + '/remove-homepage', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(function (r) { return r.ok ? r.json() : r.json().then(function (d) { throw new Error(d.error || 'Error'); }); })
                    .then(function (d) {
                        if (d.success) {
                            if (typeof window.notify === 'function') window.notify('success', d.message || 'Homepage removed.');
                            else if (typeof toastr !== 'undefined') toastr.success(d.message || 'Homepage removed.');
                            setTimeout(function () { window.location.reload(); }, 800);
                        } else throw new Error(d.error);
                    })
                    .catch(function (e) {
                        if (typeof window.notify === 'function') window.notify('error', e.message);
                        else if (typeof toastr !== 'undefined') toastr.error(e.message);
                    });
                });
            });
        });

        document.addEventListener('livewire:navigated', function () {
            document.querySelectorAll('[data-action="set-homepage"]').forEach(function (btn) {
                if (btn.disabled || btn.dataset.bound) return;
                btn.dataset.bound = '1';
                btn.addEventListener('click', function () {
                    var id = this.getAttribute('data-page-id');
                    var title = this.getAttribute('data-page-title');
                    if (!confirm('Set "' + title + '" as the homepage? This will replace any existing homepage.')) return;
                    fetch('/admin/content/page/' + id + '/set-homepage', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(function (r) { return r.ok ? r.json() : r.json().then(function (d) { throw new Error(d.error || 'Error'); }); })
                    .then(function (d) {
                        if (d.success) {
                            if (typeof window.notify === 'function') window.notify('success', d.message || 'Homepage set.');
                            else if (typeof toastr !== 'undefined') toastr.success(d.message || 'Homepage set.');
                            setTimeout(function () { window.location.reload(); }, 800);
                        } else throw new Error(d.error);
                    })
                    .catch(function (e) {
                        if (typeof window.notify === 'function') window.notify('error', e.message);
                        else if (typeof toastr !== 'undefined') toastr.error(e.message);
                    });
                });
            });
            document.querySelectorAll('[data-action="remove-homepage"]').forEach(function (btn) {
                if (btn.dataset.bound) return;
                btn.dataset.bound = '1';
                btn.addEventListener('click', function () {
                    var id = this.getAttribute('data-page-id');
                    var title = this.getAttribute('data-page-title');
                    if (!confirm('Remove "' + title + '" as the homepage?')) return;
                    fetch('/admin/content/page/' + id + '/remove-homepage', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(function (r) { return r.ok ? r.json() : r.json().then(function (d) { throw new Error(d.error || 'Error'); }); })
                    .then(function (d) {
                        if (d.success) {
                            if (typeof window.notify === 'function') window.notify('success', d.message || 'Homepage removed.');
                            else if (typeof toastr !== 'undefined') toastr.success(d.message || 'Homepage removed.');
                            setTimeout(function () { window.location.reload(); }, 800);
                        } else throw new Error(d.error);
                    })
                    .catch(function (e) {
                        if (typeof window.notify === 'function') window.notify('error', e.message);
                        else if (typeof toastr !== 'undefined') toastr.error(e.message);
                    });
                });
            });
        });
    </script>
</x-layouts.admin>
