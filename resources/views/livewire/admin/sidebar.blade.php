@php
    $adminLogoUrl = get_setting('admin_logo') ? get_image(get_setting('admin_logo')) : null;
@endphp
<x-navigation.nav-sidebar 
    :title="$menu->name ?? 'Admin Panel'" 
    logo="cube"
    :logo-url="$adminLogoUrl"
    id="admin-sidebar"
    nav-class="min-h-0"
>
    @forelse($menuItems as $item)
        <x-partials.sidebar-menu-item :item="$item" />
    @empty
        <x-navigation.nav-section title="Navigation">
            <x-navigation.nav-item title="Dashboard" icon="chart-line" route="admin.index" :active="request()->routeIs('admin.index')" />
        </x-navigation.nav-section>
    @endforelse
</x-navigation.nav-sidebar>
