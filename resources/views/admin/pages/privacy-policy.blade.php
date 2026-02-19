@extends('layouts.front')

@section('title', $privacyPolicy->title . ' - ' . __('ui.open_publications'))

@section('content')
<main class="bg-gray-50">
    <section class="bg-white py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">{{ $privacyPolicy->title }}</h1>
                
                <div class="prose max-w-none">
                    {!! $privacyPolicy->content !!}
                    
                    <p class="text-sm text-gray-500 mt-12">
                        {{ __('ui.privacy_policy_updated') }} {{ $privacyPolicy->updated_at->format('d F Y') }}
                        @if($privacyPolicy->version)
                            ({{ __('ui.version') }} {{ $privacyPolicy->version }})
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
