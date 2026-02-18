<header class="h-16 border-b bg-zinc-50 border-zinc-300 dark:bg-zinc-900 dark:border-zinc-700">
    <div class="flex h-full items-center gap-2 md:gap-4 px-4 md:px-8">
        <!-- Left: Mobile toggle + Breadcrumbs -->
        <div class="flex items-center gap-2 md:gap-4 min-w-0 flex-1 md:flex-initial overflow-hidden">
            <button
                @click="$store.sidebar.toggle()"
                class="inline-flex w-8 h-8 items-center justify-center text-zinc-700 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-zinc-100 focus:outline-none lg:hidden flex-shrink-0"
                aria-label="Toggle sidebar"
            >
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <div class="min-w-0 flex-1 md:flex-initial overflow-hidden">
            <x-navigation.breadcrumbs />
            </div>
        </div>

        <!-- Center: Search Input -->
        <div class="hidden md:flex items-center justify-center flex-1 max-w-xl mx-auto">
            @livewire('admin.search', ['dropdownMode' => true])
        </div>

        <!-- Mobile: Search Button -->
        <div class="md:hidden flex items-center flex-shrink-0">
            <button
                @click="$dispatch('open-search')"
                type="button"
                class="inline-flex w-8 h-8 items-center justify-center text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 focus:outline-none"
                aria-label="Search"
            >
                <i class="fa-solid fa-search"></i>
            </button>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2 md:gap-3 flex-shrink-0">
            <!-- Dark Mode Toggle -->
            <div
                x-data="{
                    get darkModePreference() {
                        return $store.darkMode.mode || 'system';
                    },
                    setDarkMode(value) {
                        $store.darkMode.set(value);
                    }
                }"
                class="relative"
            >
                <div class="inline-flex rounded-full bg-zinc-100/75 dark:bg-zinc-950/50 p-1 ring-1 ring-zinc-200/90 dark:ring-zinc-700/50">
                    <div class="relative inline-flex items-center">
                        <!-- Toggle Indicator -->
                        <div
                            x-cloak
                            class="toggle-indicator absolute inset-y-0 left-0 w-1/3 rounded-full bg-white dark:bg-zinc-700/75 shadow-sm transition-transform duration-150 ease-out"
                            x-bind:class="{
                                'translate-x-0': darkModePreference === 'light',
                                'translate-x-full': darkModePreference === 'system',
                                'translate-x-[200%]': darkModePreference === 'dark',
                            }"
                        ></div>
                        
                        <!-- Light Mode -->
                        <label class="group relative flex">
                            <input
                                class="peer absolute start-0 top-0 appearance-none opacity-0"
                                id="dark-mode-off"
                                name="dark-mode-switch"
                                type="radio"
                                value="light"
                                x-bind:checked="darkModePreference === 'light'"
                                x-on:change="setDarkMode('light')"
                            />
                            <span
                                class="relative flex cursor-pointer items-center justify-center rounded-lg p-2 text-zinc-500 transition-transform duration-150 ease-out peer-checked:text-zinc-900 peer-focus-visible:ring-3 peer-focus-visible:ring-zinc-200 hover:text-zinc-900 active:scale-97 dark:text-zinc-400 dark:peer-checked:text-white dark:peer-focus-visible:ring-zinc-500/50 dark:hover:text-white"
                            >
                                <i class="fa-solid fa-sun text-sm"></i>
                                <span class="sr-only">Light mode</span>
                            </span>
                        </label>
                        
                        <!-- System Mode -->
                        <label class="group relative flex">
                            <input
                                class="peer absolute start-0 top-0 appearance-none opacity-0"
                                id="dark-mode-system"
                                name="dark-mode-switch"
                                type="radio"
                                value="system"
                                x-bind:checked="darkModePreference === 'system'"
                                x-on:change="setDarkMode('system')"
                            />
                            <span
                                class="relative flex cursor-pointer items-center justify-center rounded-lg p-2 text-zinc-500 transition-transform duration-150 ease-out peer-checked:text-zinc-900 peer-focus-visible:ring-3 peer-focus-visible:ring-zinc-200 hover:text-zinc-900 active:scale-97 dark:text-zinc-400 dark:peer-checked:text-white dark:peer-focus-visible:ring-zinc-500/50 dark:hover:text-white"
                            >
                                <i class="fa-solid fa-desktop text-sm"></i>
                                <span class="sr-only">System preference</span>
                            </span>
                        </label>
                        
                        <!-- Dark Mode -->
                        <label class="group relative flex">
                            <input
                                class="peer absolute start-0 top-0 appearance-none opacity-0"
                                id="dark-mode-on"
                                name="dark-mode-switch"
                                type="radio"
                                value="dark"
                                x-bind:checked="darkModePreference === 'dark'"
                                x-on:change="setDarkMode('dark')"
                            />
                            <span
                                class="relative flex cursor-pointer items-center justify-center rounded-lg p-2 text-zinc-500 transition-transform duration-150 ease-out peer-checked:text-zinc-900 peer-focus-visible:ring-3 peer-focus-visible:ring-zinc-200 hover:text-zinc-900 active:scale-97 dark:text-zinc-400 dark:peer-checked:text-white dark:peer-focus-visible:ring-zinc-500/50 dark:hover:text-white"
                            >
                                <i class="fa-solid fa-moon text-sm"></i>
                                <span class="sr-only">Dark mode</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- User Dropdown -->
            <x-ui.dropdown>
                <x-slot name="trigger">
                    <button class="flex items-center gap-2 text-zinc-900 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white">
                        <div class="w-8 h-8 bg-[var(--color-accent)] rounded flex items-center justify-center text-[var(--color-accent-foreground)] font-semibold text-xs">
                            @if(auth()->check() && auth()->user()->name)
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            @else
                                <span class="text-xs">Ad</span>
                            @endif
                        </div>
                        <span class="hidden md:block">{{ auth()->check() ? auth()->user()->name : 'Admin' }}</span>
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <!-- User Info Section -->
                    <div class="px-4 py-3 border-b border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[var(--color-accent)] rounded flex items-center justify-center text-[var(--color-accent-foreground)] font-semibold text-sm flex-shrink-0">
                                @if(auth()->check() && auth()->user()->name)
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                @else
                                    <span class="text-sm">Ad</span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="font-semibold text-sm text-zinc-900 dark:text-white truncate">
                                    {{ auth()->check() ? auth()->user()->name : 'Admin' }}
                                </div>
                                <div class="text-xs text-zinc-600 dark:text-zinc-400 truncate">
                                    {{ auth()->check() && auth()->user()->email ? auth()->user()->email : 'admin@example.com' }}
                                </div>
                                <div class="text-xs font-medium text-zinc-700 dark:text-zinc-300 mt-0.5">
                                    @php
                                        $user = auth()->user();
                                        $roleName = $user && $user->roles->isNotEmpty() 
                                            ? $user->roles->first()->name 
                                            : 'User';
                                        $roleDisplay = \App\Helpers\Variable::$fullRolesSelector[$roleName] ?? ucfirst($roleName);
                                    @endphp
                                    {{ $roleDisplay }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="py-1">
                        <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 hover:bg-[var(--color-accent)]/10 dark:hover:bg-[var(--color-accent)]/20 transition-colors">
                            <i class="fa-regular fa-user w-5 text-center"></i>
                            <span>My Profile</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-900 dark:text-zinc-100 hover:bg-[var(--color-accent)]/10 dark:hover:bg-[var(--color-accent)]/20 transition-colors">
                            <i class="fa-regular fa-gear w-5 text-center"></i>
                            <span>Settings</span>
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-zinc-200 dark:border-zinc-700"></div>

                    <!-- Sign Out -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </x-slot>
            </x-ui.dropdown>
        </div>
    </div>
</header>
