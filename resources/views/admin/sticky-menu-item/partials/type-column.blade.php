<x-ui.badge :variant="$item->link_type === 'external' ? 'secondary' : 'primary'" :icon="$item->link_type === 'external' ? 'external-link-alt' : 'link'">
    {{ $item->link_type === 'external' ? 'External' : 'Internal' }}
</x-ui.badge>