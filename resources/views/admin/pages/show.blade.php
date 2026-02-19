@extends('layouts.front')

@section('title', $page->title . ' - ' . __('ui.open_publications'))

@section('content')
<main class="bg-gray-50">
    <section class="bg-white py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">{{ $page->title }}</h1>

                <div class="prose max-w-none">
                    {!! $page->long_body !!}
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
