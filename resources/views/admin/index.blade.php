<x-layouts.admin title="Dashboard">

    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Dashboard</h1>
                <p class="text-zinc-600 dark:text-zinc-400">Welcome back! Here's what's happening with your site today.</p>
            </div>
            <div>
                <x-ui.card variant="default" :hover="false">
                    <x-slot:body>
                        @livewire('admin.online-guest-component', key('online-guests'))
                    </x-slot:body>
                </x-ui.card>
            </div>
        </div>

        {{-- Quick Stats Grid --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Total Users --}}
            <x-ui.card
                icon="users"
                icon-color="primary"
                title="Total Users"
                :value="number_format($userStats['total'] ?? 0)"
                variant="default"
                :hover="false"
                :action-url="route('admin.administrator.users.index')"
                action-text="View all"
            />

            {{-- Blogs --}}
            <x-ui.card
                icon="newspaper"
                icon-color="blue"
                title="Blogs"
                :value="number_format($contentStats['blogs'] ?? 0)"
                variant="default"
                :hover="false"
                :action-url="route('admin.content.blog.index')"
                action-text="View all"
            />

            {{-- Pages --}}
            <x-ui.card
                icon="file-text"
                icon-color="emerald"
                title="Pages"
                :value="number_format($contentStats['pages'] ?? 0)"
                variant="default"
                :hover="false"
                :action-url="route('admin.content.page.index')"
                action-text="View all"
            />

            {{-- Contacts --}}
            <x-ui.card
                icon="envelope"
                icon-color="amber"
                title="Contacts"
                :value="number_format($contentStats['contacts'] ?? 0)"
                variant="default"
                :hover="false"
                :action-url="route('admin.administrator.contacts.index')"
                action-text="View all"
            />
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Quick Actions --}}
            <div class="lg:col-span-2 space-y-6">
                <x-ui.card variant="default" :hover="false">
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-zinc-900 dark:text-white flex items-center">
                            <i class="fa-solid fa-bolt mr-2 text-[var(--color-accent)]"></i>
                            Quick Actions
                        </h3>
                    </x-slot:header>
                    <x-slot:body>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <x-button variant="outline-primary" size="sm" icon="plus" :href="route('admin.content.blog.create')" class="justify-center">
                                New Blog
                            </x-button>
                            <x-button variant="outline-primary" size="sm" icon="plus" :href="route('admin.content.page.create')" class="justify-center">
                                New Page
                            </x-button>
                            <x-button variant="outline-primary" size="sm" icon="chart-line" :href="route('admin.analytics.index')" class="justify-center">
                                Analytics
                            </x-button>
                            <x-button variant="outline-primary" size="sm" icon="users" :href="route('admin.administrator.users.index')" class="justify-center">
                                Users
                            </x-button>
                            <x-button variant="outline-primary" size="sm" icon="cog" :href="route('admin.settings.general.index')" class="justify-center">
                                Settings
                            </x-button>
                        </div>
                    </x-slot:body>
                </x-ui.card>

                {{-- Recent Activity --}}
                <x-ui.card variant="default" :hover="false">
                    <x-slot:header>
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-zinc-900 dark:text-white flex items-center">
                                <i class="fa-solid fa-history mr-2 text-[var(--color-accent)]"></i>
                                Recent Activity
                            </h3>
                            <x-button variant="outline" size="sm" :href="route('admin.activity-log.index')">
                                View all
                            </x-button>
                        </div>
                    </x-slot:header>
                    <x-slot:body>
                        <div class="text-center py-8 text-zinc-500 dark:text-zinc-400">
                            <i class="fa-solid fa-inbox text-4xl mb-2"></i>
                            <p class="text-sm">No recent activity</p>
                        </div>
                    </x-slot:body>
                </x-ui.card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- System Status --}}
                <x-ui.card variant="default" :hover="false">
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-zinc-900 dark:text-white flex items-center">
                            <i class="fa-solid fa-server mr-2 text-[var(--color-accent)]"></i>
                            System Status
                        </h3>
                    </x-slot:header>
                    <x-slot:body>
                        <dl class="space-y-3 divide-y divide-zinc-200 dark:divide-zinc-700">
                            <div class="flex items-center justify-between py-2 first:pt-0 last:pb-0">
                                <dt class="text-sm text-zinc-600 dark:text-zinc-400">Admin Users</dt>
                                <dd class="text-sm font-semibold text-zinc-900 dark:text-white">{{ number_format($userStats['admins'] ?? 0) }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-2 first:pt-0 last:pb-0">
                                <dt class="text-sm text-zinc-600 dark:text-zinc-400">Solutions</dt>
                                <dd class="text-sm font-semibold text-zinc-900 dark:text-white">{{ number_format($contentStats['solutions'] ?? 0) }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-2 first:pt-0 last:pb-0">
                                <dt class="text-sm text-zinc-600 dark:text-zinc-400">Case Studies</dt>
                                <dd class="text-sm font-semibold text-zinc-900 dark:text-white">{{ number_format($contentStats['case_studies'] ?? 0) }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-2 first:pt-0 last:pb-0">
                                <dt class="text-sm text-zinc-600 dark:text-zinc-400">Help Articles</dt>
                                <dd class="text-sm font-semibold text-zinc-900 dark:text-white">{{ number_format($contentStats['help_articles'] ?? 0) }}</dd>
                            </div>
                        </dl>
                    </x-slot:body>
                </x-ui.card>

                {{-- Reports --}}
                <x-ui.card variant="default" :hover="false">
                    <x-slot:header>
                        <h3 class="text-base font-semibold text-zinc-900 dark:text-white flex items-center">
                            <i class="fa-solid fa-chart-bar mr-2 text-[var(--color-accent)]"></i>
                            Reports
                        </h3>
                    </x-slot:header>
                    <x-slot:body>
                        <div class="space-y-2">
                            <x-button variant="outline" size="sm" icon="chart-line" icon-position="left" :href="route('admin.analytics.index')" class="w-full justify-start">
                                Web Statistics
                            </x-button>
                            <x-button variant="outline" size="sm" icon="history" icon-position="left" :href="route('admin.activity-log.index')" class="w-full justify-start">
                                Activity Logs
                            </x-button>
                            <x-button variant="outline" size="sm" icon="envelope-open-text" icon-position="left" :href="route('admin.administrator.email-logs.index')" class="w-full justify-start">
                                Email Logs
                            </x-button>
                        </div>
                    </x-slot:body>
                </x-ui.card>
            </div>
        </div>
    </div>

</x-layouts.admin>