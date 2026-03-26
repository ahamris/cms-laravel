@php $faqHub = $faqHub ?? false; @endphp
@if ($faqHub)
    <x-layouts.admin-faq-hub :title="$heading . ' · preview'" active="elements">
        @include('admin.elements.partials.show-main')
    </x-layouts.admin-faq-hub>
@else
    <x-layouts.admin :title="$heading . ' Detail'">
        @include('admin.elements.partials.show-main')
    </x-layouts.admin>
@endif
