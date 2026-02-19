<x-layouts.admin title="Contact Forms">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Contact Forms</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage contact form submissions from potential customers</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="secondary" icon="download" x-data
                    x-on:click="toastManager.show('info', 'Exporting contact forms...')">Export</x-ui.button>
            </div>
        </div>

        {{-- Contact Forms Table --}}
        <livewire:admin.table resource="App\Models\ContactForm" :columns="[
        'id',
        ['key' => 'full_name', 'label' => 'Contact'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'phone', 'label' => 'Phone'],
        [
            'key' => 'reden',
            'label' => 'Reason',
            'type' => 'badge',
            'badge_map' => [
                'ondersteuning' => ['label' => 'Ondersteuning', 'color' => 'blue'],
                'kennismaking' => ['label' => 'Kennismaking', 'color' => 'purple'],
                'demo' => ['label' => 'Demo', 'color' => 'green'],
            ]
        ],
        [
            'key' => 'status',
            'type' => 'badge',
            'badge_map' => [
                'new' => ['label' => 'New', 'color' => 'blue'],
                'contacted' => ['label' => 'Contacted', 'color' => 'yellow'],
                'resolved' => ['label' => 'Resolved', 'color' => 'green'],
                'closed' => ['label' => 'Closed', 'color' => 'gray'],
            ]
        ],
        ['key' => 'created_at', 'format' => 'date'],
    ]" route-prefix="admin.administrator.contact-forms"
            search-placeholder="Search contact forms..." :paginate="15"
            custom-actions-view="admin.contact-forms.partials.table-actions" />
    </div>
</x-layouts.admin>