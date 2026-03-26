@php
    $faqHub = $faqHub ?? false;
    $faqHubContext = $faqHubContext ?? false;
@endphp
@if ($faqHub)
    <x-layouts.admin-faq-hub :title="$heading" active="elements">
        @include('admin.elements.partials.index-main')
    </x-layouts.admin-faq-hub>
@else
    <x-layouts.admin :title="$heading">
        @include('admin.elements.partials.index-main')
    </x-layouts.admin>
@endif
