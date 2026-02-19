@extends('front.layouts.app')

@section('title', 'My Affairs')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative container mx-auto px-6 flex justify-between items-center">
            <div class="flex flex-col items-start text-left">
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                    My Affairs
                </h1>
                {{-- Breadcrumbs --}}
                <div class="text-sm text-white/80">
                    <span>Dashboard</span>
                    <span class="mx-2">></span>
                    <span>My Affairs</span>
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
                        {{-- Affairs Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-4 py-2">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h1 class="text-2xl font-bold text-white">My Affairs</h1>
                                        <p class="text-white/90 text-sm mt-1">Track your Woo requests and government matters</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="bg-white text-primary px-3 py-1 rounded-full text-sm font-semibold">
                                            {{ $wooRequests->total() }} Total
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                @if($wooRequests->count() > 0)
                                    {{-- Table Header --}}
                                    <div class="bg-gray-50 rounded-lg px-6 py-3 mb-4">
                                        <div class="grid grid-cols-12 gap-4 text-sm font-semibold text-gray-600">
                                            <div class="col-span-2">Tracking</div>
                                            <div class="col-span-3">Request Details</div>
                                            <div class="col-span-2">Status</div>
                                            <div class="col-span-2">Created</div>
                                            <div class="col-span-2">Reviewed</div>
                                            <div class="col-span-1 text-center">Action</div>
                                        </div>
                                    </div>

                                    {{-- Table Content --}}
                                    <div class="space-y-3">
                                        @foreach($wooRequests as $request)
                                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
                                                <div class="grid grid-cols-12 gap-4 items-center">
                                                    {{-- Tracking Number --}}
                                                    <div class="col-span-2">
                                                        <p class="text-sm font-bold text-gray-900">{{ $request->tracking_number }}</p>
                                                    </div>

                                                    {{-- Request Details --}}
                                                    <div class="col-span-3">
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ Str::limit($request->request_details, 60) }}
                                                        </p>
                                                        @if($request->reason_for_request)
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ Str::limit($request->reason_for_request, 50) }}
                                                            </p>
                                                        @endif
                                                    </div>

                                                    {{-- Status --}}
                                                    <div class="col-span-2">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $request->status_badge_class }}">
                                                            <i class="fa-solid fa-circle mr-1 text-xs"></i>
                                                            {{ $request->status_label }}
                                                        </span>
                                                    </div>

                                                    {{-- Created Date --}}
                                                    <div class="col-span-2">
                                                        <p class="text-sm text-gray-600">{{ $request->created_at->format('M d, Y') }}</p>
                                                        <p class="text-xs text-gray-500">{{ $request->created_at->format('H:i') }}</p>
                                                    </div>

                                                    {{-- Reviewed Date --}}
                                                    <div class="col-span-2">
                                                        @if($request->reviewed_at)
                                                            <p class="text-sm text-gray-600">{{ $request->reviewed_at->format('M d, Y') }}</p>
                                                            <p class="text-xs text-gray-500">{{ $request->reviewed_at->format('H:i') }}</p>
                                                        @else
                                                            <p class="text-sm text-gray-500">-</p>
                                                        @endif
                                                    </div>

                                                    {{-- Action --}}
                                                    <div class="col-span-1 text-center">
                                                        <a href="{{ route('dashboard.woo-requests.show', $request) }}" 
                                                           class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors">
                                                            <i class="fa-solid fa-eye text-sm"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Pagination --}}
                                    @if($wooRequests->hasPages())
                                        <div class="mt-6 flex justify-center">
                                            {{ $wooRequests->links() }}
                                        </div>
                                    @endif
                                @else
                                    {{-- Empty State --}}
                                    <div class="text-center py-12">
                                        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-primary/10 to-primary/20 rounded-full flex items-center justify-center">
                                            <i class="fa-solid fa-file-lines text-primary text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Affairs Yet</h3>
                                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                            You haven't submitted any Woo requests yet. Your government requests will appear here.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- FAQ Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-6 py-4">
                                <h2 class="text-xl font-bold text-white">Frequently Asked Questions</h2>
                                <p class="text-white/90 text-sm mt-1">Common questions about current affairs</p>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    @php
                                        $faqs = [
                                            'What is Current Affairs?',
                                            'For which organizations can I view my Current Affairs?',
                                            'I have a question about an ongoing case.',
                                        ];
                                    @endphp
                                    @foreach($faqs as $faq)
                                    <details class="group">
                                        <summary class="flex cursor-pointer list-none items-center justify-between bg-gray-50 p-4 font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 border border-gray-200">
                                            <span class="font-medium">{{ $faq }}</span>
                                            <i class="fa-solid fa-chevron-right transition-transform duration-200 group-open:rotate-90 text-gray-500"></i>
                                        </summary>
                                        <div class="p-4 bg-white rounded-lg mt-2 border border-gray-100">
                                            <p class="text-sm text-gray-600">Content for this question will be displayed here.</p>
                                        </div>
                                    </details>
                                    @endforeach
                                </div>
                                <div class="mt-6">
                                    <a href="#" class="inline-flex items-center text-sm font-semibold text-primary hover:text-primary/80 transition-colors">
                                        View all frequently asked questions 
                                        <i class="fa-solid fa-chevron-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
