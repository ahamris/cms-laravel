<x-layouts.admin title="Subscriptions">
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Subscriptions</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Manage subscriptions from potential customers</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.administrator.subscriptions.export', request()->query()) }}"
                   class="inline-flex items-center gap-2 rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90">
                    <i class="fa-solid fa-download"></i>
                    Export CSV
                </a>
                <x-button variant="primary" icon="plus" icon-position="left" href="{{ route('admin.administrator.subscriptions.create') }}">Add New Subscription</x-button>
            </div>
        </div>

        {{-- Status Overview Cards --}}
        @php
            $statusCounts = [
                'new' => \App\Models\Subscription::byStatus('new')->count(),
                'contacted' => \App\Models\Subscription::byStatus('contacted')->count(),
                'demo_scheduled' => \App\Models\Subscription::byStatus('demo_scheduled')->count(),
                'demo_completed' => \App\Models\Subscription::byStatus('demo_completed')->count(),
                'converted' => \App\Models\Subscription::byStatus('converted')->count(),
                'rejected' => \App\Models\Subscription::byStatus('rejected')->count(),
            ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase mb-1">New</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $statusCounts['new'] }}</p>
                    </div>
                    <i class="fa-solid fa-star text-gray-300 dark:text-gray-600 text-xl"></i>
                </div>
            </div>
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-cyan-600 dark:text-cyan-400 uppercase mb-1">Contacted</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $statusCounts['contacted'] }}</p>
                    </div>
                    <i class="fa-solid fa-phone text-gray-300 dark:text-gray-600 text-xl"></i>
                </div>
            </div>
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-yellow-600 dark:text-yellow-400 uppercase mb-1">Demo Scheduled</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $statusCounts['demo_scheduled'] }}</p>
                    </div>
                    <i class="fa-solid fa-calendar text-gray-300 dark:text-gray-600 text-xl"></i>
                </div>
            </div>
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-green-600 dark:text-green-400 uppercase mb-1">Demo Completed</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $statusCounts['demo_completed'] }}</p>
                    </div>
                    <i class="fa-solid fa-check text-gray-300 dark:text-gray-600 text-xl"></i>
                </div>
            </div>
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase mb-1">Converted</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $statusCounts['converted'] }}</p>
                    </div>
                    <i class="fa-solid fa-trophy text-gray-300 dark:text-gray-600 text-xl"></i>
                </div>
            </div>
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase mb-1">Rejected</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $statusCounts['rejected'] }}</p>
                    </div>
                    <i class="fa-solid fa-times text-gray-300 dark:text-gray-600 text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Subscriptions Table --}}
        <livewire:admin.table
            resource="subscription"
            :columns="[
                ['key' => 'contact', 'label' => 'Contact', 'type' => 'custom', 'view' => 'admin.administrator.subscriptions.partials.contact-column'],
                ['key' => 'company', 'label' => 'Company', 'type' => 'custom', 'view' => 'admin.administrator.subscriptions.partials.company-column'],
                ['key' => 'product_interest', 'label' => 'Product Interest', 'type' => 'custom', 'view' => 'admin.administrator.subscriptions.partials.product-interest-column'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'custom', 'view' => 'admin.administrator.subscriptions.partials.status-column'],
                ['key' => 'created_at', 'format' => 'date'],
                ['key' => 'is_active', 'type' => 'toggle'],
            ]"
            route-prefix="admin.administrator.subscriptions"
            search-placeholder="Search subscriptions..."
            :paginate="15"
            custom-actions-view="admin.administrator.subscriptions.partials.table-actions"
            :search-fields="['first_name', 'last_name', 'email', 'company_name']"
        />
    </div>
</x-layouts.admin>
