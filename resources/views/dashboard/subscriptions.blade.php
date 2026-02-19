@extends('front.layouts.app')

@section('title', 'Subscriptions')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative container mx-auto px-6 flex justify-between items-center">
            <div class="flex flex-col items-start text-left">
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                    Subscriptions
                </h1>
                {{-- Breadcrumbs --}}
                <div class="text-sm text-white/80">
                    <span>Dashboard</span>
                    <span class="mx-2">></span>
                    <span>Subscriptions</span>
                </div>
            </div>
        </div>
    </section>

    <div class="bg-gray-50 font-sans">
        <div class="container mx-auto px-6 py-8">
            <div class="grid grid-cols-12 gap-8">

                {{-- Left Sidebar Navigation --}}
                @include('dashboard.partials.sidebar')

                {{-- Main Content --}}
                <main class="col-span-12 lg:col-span-9">
                    <div class="space-y-8">
                        
                        {{-- Themes Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-4 py-2">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h1 class="text-2xl font-bold text-white">Theme Subscriptions</h1>
                                        <p class="text-white/90 text-sm mt-1">Subscribe to themes to get notified about new topics</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="bg-white text-primary px-3 py-1 rounded-full text-sm font-semibold">
                                            {{ $themes->count() }} Available
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                @if($themes->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach($themes as $theme)
                                            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:border-gray-300 transition-colors">
                                                <div class="flex items-start justify-between mb-4">
                                                    <div class="flex-1">
                                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $theme->name }}</h3>
                                                        @if($theme->description)
                                                            <p class="text-sm text-gray-600 mb-3">
                                                                {{ Str::limit($theme->description, 100) }}
                                                            </p>
                                                        @endif
                                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                                            <i class="fa-solid fa-file-lines"></i>
                                                            <span>{{ $theme->theme_topics_count }} {{ Str::plural('topic', $theme->theme_topics_count) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center justify-between">
                                                    @if($subscribedThemes->contains($theme->id))
                                                        <button onclick="unsubscribeFromTheme({{ $theme->id }})" 
                                                                class="flex-1 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors text-sm font-medium">
                                                            <i class="fa-solid fa-bell-slash mr-2"></i>
                                                            Unsubscribe
                                                        </button>
                                                    @else
                                                        <button onclick="subscribeToTheme({{ $theme->id }})" 
                                                                class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors text-sm font-medium">
                                                            <i class="fa-solid fa-bell mr-2"></i>
                                                            Subscribe
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    {{-- Empty State --}}
                                    <div class="text-center py-12">
                                        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-primary/10 to-primary/20 rounded-full flex items-center justify-center">
                                            <i class="fa-solid fa-palette text-primary text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Themes Available</h3>
                                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                            There are currently no active themes available for subscription.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Topics Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h1 class="text-2xl font-bold text-white">Topic Subscriptions</h1>
                                        <p class="text-white/90 text-sm mt-1">Subscribe to specific topics to get targeted notifications</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="bg-white text-blue-600 px-3 py-1 rounded-full text-sm font-semibold">
                                            {{ $topics->count() }} Available
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                @if($topics->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($topics as $topic)
                                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-start gap-4">
                                                            @if($topic->featured_image)
                                                                <img src="{{ asset('storage/' . $topic->featured_image) }}" 
                                                                     alt="{{ $topic->title }}" 
                                                                     class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                                            @else
                                                                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center flex-shrink-0">
                                                                    <i class="fa-solid fa-file-text text-gray-400 text-xl"></i>
                                                                </div>
                                                            @endif
                                                            
                                                            <div class="flex-1">
                                                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $topic->title }}</h3>
                                                                @if($topic->excerpt)
                                                                    <p class="text-sm text-gray-600 mb-2">
                                                                        {{ Str::limit($topic->excerpt, 120) }}
                                                                    </p>
                                                                @endif
                                                                
                                                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                                                    @if($topic->theme_topics->count() > 0)
                                                                        <div class="flex items-center gap-1">
                                                                            <i class="fa-solid fa-tag"></i>
                                                                            <span>{{ $topic->theme_topics->pluck('name')->join(', ') }}</span>
                                                                        </div>
                                                                    @endif
                                                                    <div class="flex items-center gap-1">
                                                                        <i class="fa-solid fa-eye"></i>
                                                                        <span>{{ number_format($topic->views) }} views</span>
                                                                    </div>
                                                                    @if($topic->published_at)
                                                                        <div class="flex items-center gap-1">
                                                                            <i class="fa-solid fa-calendar"></i>
                                                                            <span>{{ $topic->published_at->format('M d, Y') }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="ml-4">
                                                        @if($subscribedTopics->contains($topic->id))
                                                            <button onclick="unsubscribeFromTopic({{ $topic->id }})" 
                                                                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors text-sm font-medium">
                                                                <i class="fa-solid fa-bell-slash mr-2"></i>
                                                                Unsubscribe
                                                            </button>
                                                        @else
                                                            <button onclick="subscribeToTopic({{ $topic->id }})" 
                                                                    class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors text-sm font-medium">
                                                                <i class="fa-solid fa-bell mr-2"></i>
                                                                Subscribe
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    {{-- Empty State --}}
                                    <div class="text-center py-12">
                                        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center">
                                            <i class="fa-solid fa-file-lines text-blue-500 text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Topics Available</h3>
                                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                            There are currently no published topics available for subscription.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Theme subscription functions
function subscribeToTheme(themeId) {
    fetch(`/user/subscriptions/themes/${themeId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message (you can implement a toast notification here)
            alert(data.message);
            location.reload(); // Reload to update button states
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while subscribing to the theme.');
    });
}

function unsubscribeFromTheme(themeId) {
    fetch(`/user/subscriptions/themes/${themeId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message (you can implement a toast notification here)
            alert(data.message);
            location.reload(); // Reload to update button states
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while unsubscribing from the theme.');
    });
}

// Topic subscription functions
function subscribeToTopic(topicId) {
    fetch(`/user/subscriptions/topics/${topicId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message (you can implement a toast notification here)
            alert(data.message);
            location.reload(); // Reload to update button states
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while subscribing to the topic.');
    });
}

function unsubscribeFromTopic(topicId) {
    fetch(`/user/subscriptions/topics/${topicId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message (you can implement a toast notification here)
            alert(data.message);
            location.reload(); // Reload to update button states
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while unsubscribing from the topic.');
    });
}
</script>
@endpush
