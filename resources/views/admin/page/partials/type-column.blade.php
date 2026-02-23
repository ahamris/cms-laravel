@if(($item->page_type ?? 'static') === 'showcase')
    <x-ui.badge variant="primary" icon="puzzle-piece" size="sm">Showcase</x-ui.badge>
@else
    <x-ui.badge variant="sky" icon="file-lines" size="sm">Static</x-ui.badge>
@endif
