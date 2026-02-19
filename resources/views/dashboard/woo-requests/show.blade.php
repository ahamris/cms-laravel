@extends('front.layouts.app')

@section('title', 'Request #' . $wooRequest->tracking_number)

@section('content')

    {{-- Hero Section --}}
    <section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative container mx-auto px-6 flex justify-between items-center">
            <div class="flex flex-col items-start text-left">
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                    Request #{{ $wooRequest->tracking_number }}
                </h1>
                {{-- Breadcrumbs --}}
                <div class="text-sm text-white text-shadow-amber-200">
                    <span>Dashboard</span>
                    <span class="mx-2">></span>
                    <a href="{{ route('dashboard.affairs') }}" class="hover:text-white/80">My Affairs</a>
                    <span class="mx-2">></span>
                    <span>Request #{{ $wooRequest->tracking_number }}</span>
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
                        {{-- Request Details --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-4 py-2">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h1 class="text-2xl font-bold text-white">{{ $wooRequest->tracking_number }}</h1>
                                        <p class="text-white/90 text-sm mt-1">Woo Request Details</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white {{ str_replace(['bg-', 'text-'], ['text-', 'bg-'], $wooRequest->status_badge_class) }}">
                                            <i class="fa-solid fa-circle mr-1 text-xs"></i>
                                            {{ $wooRequest->status_label }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                {{-- Request Info --}}
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pb-6 border-b border-gray-200">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500 mb-1">Submitted Date</h3>
                                        <p class="text-gray-900">{{ $wooRequest->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    @if($wooRequest->reviewed_at)
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-500 mb-1">Reviewed Date</h3>
                                            <p class="text-gray-900">{{ $wooRequest->reviewed_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    @endif
                                    @if($wooRequest->reviewer)
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-500 mb-1">Reviewed By</h3>
                                            <p class="text-gray-900">{{ $wooRequest->reviewer->name }}</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Request Details --}}
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Request Details</h3>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <p class="text-gray-700 whitespace-pre-line">{{ $wooRequest->request_details }}</p>
                                    </div>
                                </div>

                                {{-- Reason for Request --}}
                                @if($wooRequest->reason_for_request)
                                    <div class="mb-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Reason for Request</h3>
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <p class="text-gray-700 whitespace-pre-line">{{ $wooRequest->reason_for_request }}</p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Admin Notes --}}
                                @if($wooRequest->admin_notes)
                                    <div class="mb-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Admin Notes</h3>
                                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                            <p class="text-blue-800 whitespace-pre-line">{{ $wooRequest->admin_notes }}</p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Attachments --}}
                                @if($wooRequest->uploaded_files && count($wooRequest->uploaded_files) > 0)
                                    <div class="mb-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Attachments</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($wooRequest->uploaded_files as $file)
                                                <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
                                                    <div class="flex items-center gap-3">
                                                        <i class="fa-solid fa-file text-gray-500 text-xl"></i>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $file['original_name'] }}</p>
                                                            <p class="text-xs text-gray-500">{{ number_format($file['file_size'] / 1024, 1) }} KB</p>
                                                        </div>
                                                        <a href="{{ asset('storage/' . $file['file_path']) }}" 
                                                           target="_blank"
                                                           class="text-primary hover:text-primary/80 transition-colors">
                                                            <i class="fa-solid fa-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Contact Information --}}
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Contact Information</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <h4 class="text-sm font-medium text-gray-500 mb-2">Personal Details</h4>
                                            <p class="text-gray-900 font-medium">{{ $wooRequest->full_name }}</p>
                                            <p class="text-gray-600 text-sm">{{ $wooRequest->email }}</p>
                                            @if($wooRequest->phone)
                                                <p class="text-gray-600 text-sm">{{ $wooRequest->phone }}</p>
                                            @endif
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <h4 class="text-sm font-medium text-gray-500 mb-2">Address</h4>
                                            <p class="text-gray-900">{{ $wooRequest->address }}</p>
                                            <p class="text-gray-600 text-sm">{{ $wooRequest->postal_code }} {{ $wooRequest->residence }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Back Button --}}
                                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                    <a href="{{ route('dashboard.affairs') }}" 
                                       class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                        <i class="fa-solid fa-arrow-left"></i>
                                        Back to My Affairs
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
