{{-- Left sidebar nav: use partial instead of missing x-dashboard.sidebar component --}}
<aside class="col-span-12 lg:col-span-3">
    <nav class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" aria-label="Dashboard menu">
        <ul class="p-2 space-y-1">
            @if(Route::has('dashboard.index'))
                <li>
                    <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors {{ request()->routeIs('dashboard.index') ? 'bg-primary/10 text-primary font-semibold' : '' }}">
                        <i class="fa-solid fa-house w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            @endif
            @if(Route::has('dashboard.messages'))
                <li>
                    <a href="{{ route('dashboard.messages') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors {{ request()->routeIs('dashboard.messages*') ? 'bg-primary/10 text-primary font-semibold' : '' }}">
                        <i class="fa-solid fa-envelope w-5 text-center"></i>
                        <span>Messages</span>
                    </a>
                </li>
            @endif
            @if(Route::has('dashboard.personal'))
                <li>
                    <a href="{{ route('dashboard.personal') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors {{ request()->routeIs('dashboard.personal') ? 'bg-primary/10 text-primary font-semibold' : '' }}">
                        <i class="fa-solid fa-briefcase w-5 text-center"></i>
                        <span>Business</span>
                    </a>
                </li>
            @endif
            @if(Route::has('dashboard.identity.index'))
                <li>
                    <a href="{{ route('dashboard.identity.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors {{ request()->routeIs('dashboard.identity*') ? 'bg-primary/10 text-primary font-semibold' : '' }}">
                        <i class="fa-solid fa-user w-5 text-center"></i>
                        <span>Identity</span>
                    </a>
                </li>
            @endif
            @if(Route::has('dashboard.settings'))
                <li>
                    <a href="{{ route('dashboard.settings') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors {{ request()->routeIs('dashboard.settings') ? 'bg-primary/10 text-primary font-semibold' : '' }}">
                        <i class="fa-solid fa-gear w-5 text-center"></i>
                        <span>Settings</span>
                    </a>
                </li>
            @endif
            @if(Route::has('dashboard.subscriptions'))
                <li>
                    <a href="{{ route('dashboard.subscriptions') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors {{ request()->routeIs('dashboard.subscriptions') ? 'bg-primary/10 text-primary font-semibold' : '' }}">
                        <i class="fa-solid fa-credit-card w-5 text-center"></i>
                        <span>Subscriptions</span>
                    </a>
                </li>
            @endif
            @if(Route::has('dashboard.affairs'))
                <li>
                    <a href="{{ route('dashboard.affairs') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors {{ request()->routeIs('dashboard.affairs') ? 'bg-primary/10 text-primary font-semibold' : '' }}">
                        <i class="fa-solid fa-folder-open w-5 text-center"></i>
                        <span>My Affairs</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</aside>
