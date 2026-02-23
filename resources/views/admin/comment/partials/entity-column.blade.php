@if($item->entity)
    @php
        $type = strtolower(class_basename($item->entity_type));
        // Map types to route names if they don't follow the direct plural/resource pattern
        $routeMap = ['blog' => 'blog'];
        $baseRoute = $routeMap[$type] ?? $type;
    @endphp
    <a href="{{ route('admin.content.' . $baseRoute . '.show', $item->entity) }}"
        class="text-sm text-[var(--color-accent)] hover:underline">
        {{ $item->entity->title ?? $item->entity->name ?? 'View ' . class_basename($item->entity_type) }}
    </a>
@else
    <span class="text-sm text-gray-500 dark:text-gray-400">N/A</span>
@endif