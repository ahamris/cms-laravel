@php $faqHub = $faqHub ?? false; @endphp
@if ($faqHub)
    <x-layouts.admin-faq-hub :title="'Edit ' . $heading" active="elements">
        @include('admin.elements.partials.edit-main')
    </x-layouts.admin-faq-hub>
@else
    <x-layouts.admin :title="'Edit ' . $heading">
        @include('admin.elements.partials.edit-main')
    </x-layouts.admin>
@endif
