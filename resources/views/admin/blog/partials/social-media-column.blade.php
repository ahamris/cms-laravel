@php
    $parentComponent = $this;
@endphp
<div class="flex items-center gap-2" x-data>
    <x-button 
        variant="sky" 
        size="sm" 
        icon="share-nodes" 
        title="Post to Social Media"
        x-on:click="$dispatch('open-social-media-modal', { id: {{ $item->id }}, title: '{{ addslashes($item->title) }}' })"
    ></x-button>
    <x-button 
        variant="success" 
        size="sm" 
        icon="eye" 
        title="View Social Media Posts"
        x-on:click="$dispatch('view-social-media-posts', { id: {{ $item->id }} })"
    ></x-button>
</div>

