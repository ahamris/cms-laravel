@extends('front.layouts.app')

@section('title', 'Message - ' . $message['subject'])

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative container mx-auto px-6 flex justify-between items-center">
            <div class="flex flex-col items-start text-left">
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                    Message Details
                </h1>
                {{-- Breadcrumbs --}}
                <div class="text-sm text-white text-shadow-amber-200">
                    <a href="{{ route('dashboard.index') }}" class="hover:underline">Dashboard</a>
                    <span class="mx-2">></span>
                    <a href="{{ route('dashboard.messages') }}" class="hover:underline">Messages</a>
                    <span class="mx-2">></span>
                    <span>Message Details</span>
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
                    <div class="space-y-6">
                        {{-- Back Button --}}
                        <div class="flex items-center gap-3">
                            <a href="{{ route('dashboard.messages') }}" class="flex items-center gap-2 text-primary hover:text-primary/80 transition-colors">
                                <i class="fa-solid fa-arrow-left"></i>
                                <span class="font-medium">Back to Messages</span>
                            </a>
                        </div>

                        {{-- Message Card --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            {{-- Message Header --}}
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h1 class="text-2xl font-bold text-gray-900">{{ $message['subject'] }}</h1>
                                            @if($message['priority'] === 'high')
                                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">High Priority</span>
                                            @elseif($message['priority'] === 'medium')
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Medium Priority</span>
                                            @else
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Low Priority</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-user text-gray-400"></i>
                                                <span class="font-medium">{{ $message['sender'] }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-calendar text-gray-400"></i>
                                                <span>{{ $message['date'] }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-tag text-gray-400"></i>
                                                <span>{{ $message['category'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors" title="Archive">
                                            <i class="fa-solid fa-box-archive"></i>
                                        </button>
                                        <button class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                        <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors" title="Print">
                                            <i class="fa-solid fa-print"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Message Content --}}
                            <div class="p-6">
                                <div class="prose max-w-none">
                                    <div class="whitespace-pre-line text-gray-700 leading-relaxed">
                                        {{ $message['content'] }}
                                    </div>
                                </div>
                            </div>

                            {{-- Attachments --}}
                            @if(!empty($message['attachments']))
                                <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                        <i class="fa-solid fa-paperclip"></i>
                                        Attachments ({{ count($message['attachments']) }})
                                    </h3>
                                    <div class="space-y-3">
                                        @foreach($message['attachments'] as $attachment)
                                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:border-primary/30 transition-colors">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                                        <i class="fa-solid fa-file-pdf text-red-600"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $attachment['name'] }}</p>
                                                        <p class="text-sm text-gray-500">{{ $attachment['size'] }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button class="p-2 text-gray-400 hover:text-primary transition-colors" title="Download">
                                                        <i class="fa-solid fa-download"></i>
                                                    </button>
                                                    <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors" title="Preview">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Message Actions --}}
                            <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <button class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-medium">
                                            <i class="fa-solid fa-reply mr-2"></i>
                                            Reply
                                        </button>
                                        <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                            <i class="fa-solid fa-share mr-2"></i>
                                            Forward
                                        </button>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Message ID: #{{ $message['id'] }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .prose {
        color: #374151;
        max-width: none;
    }
    .prose p {
        margin-top: 0;
        margin-bottom: 1rem;
    }
    .prose ul {
        margin-top: 0;
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }
    .prose li {
        margin-top: 0.25rem;
        margin-bottom: 0.25rem;
    }
</style>
@endpush
