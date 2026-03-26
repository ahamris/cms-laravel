@php $faqHub = $faqHub ?? false; @endphp
@if ($faqHub)
    <x-layouts.admin-faq-hub :title="'Create ' . $heading" active="elements">
        @include('admin.elements.partials.create-main')
    </x-layouts.admin-faq-hub>
@else
    <x-layouts.admin :title="'Create ' . $heading">
        @include('admin.elements.partials.create-main')
    </x-layouts.admin>
@endif
