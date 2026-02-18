@php
    $role = $item->roles->first();
@endphp

@if($role)
    @php
        $roleDisplay = \App\Helpers\Variable::$fullRolesSelector[$role->name] ?? ucfirst($role->name);
        // Role'a göre variant belirle
        $variant = match($role->name) {
            'admin' => 'error',
            'editor' => 'warning',
            default => 'primary',
        };
    @endphp
    <x-badge variant="{{ $variant }}" size="sm">
        {{ $roleDisplay }}
    </x-badge>
@else
    <span class="text-sm text-zinc-500 dark:text-zinc-400">No role</span>
@endif
