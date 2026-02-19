@extends('front.layouts.app')

@section('title', 'Message Box')

@section('content')

    {{-- Hero Section --}}
    <section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
        <div class="absolute inset-0 bg-black/80"></div> {{-- Overlay for readability --}}
        <div class="relative container mx-auto px-6 flex justify-between items-center">
            <div class="flex flex-col items-start text-left">
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                    Message Box
                </h1>
                {{-- Breadcrumbs --}}
                <div class="text-sm text-white text-shadow-amber-200">
                    <span>Dashboard</span>
                    <span class="mx-2">></span>
                    <span>Message Box</span>
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
                            {{-- Support Messages Section --}}
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="bg-primary px-4 py-2 flex justify-between items-center">
                                    <div>
                                        <h1 class="text-2xl font-bold text-white">Message Box</h1>
                                        <p class="text-white/90 text-sm mt-1">Manage your message box & support requests</p>
                                    </div>
                                    <a href="{{ route('dashboard.messages.create') }}" class="bg-white text-primary px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                                        <i class="fa-solid fa-plus mr-2"></i>
                                        Create New Message
                                    </a>
                                </div>

                                <div>
                                    {{-- Messages Table --}}
                                    @if($messages->count() > 0)
                                        <div class="overflow-x-auto">
                                            <table class="w-full">
                                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                                    <tr>
                                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Message</th>
                                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subject</th>
                                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Priority</th>
                                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                                                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-100">
                                                    @foreach ($messages as $message)
                                                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 group cursor-pointer" onclick="window.location.href='{{ route('dashboard.messages.show', $message->id) }}'">
                                                        {{-- Message ID --}}
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="flex items-center gap-2">
                                                                <i class="fa-solid fa-ticket text-primary"></i>
                                                                <span class="text-sm font-mono text-gray-900">#{{ $message->ticket_id }}</span>
                                                            </div>
                                                        </td>
                                                        
                                                        {{-- Subject --}}
                                                        <td class="px-6 py-4">
                                                            <div class="text-sm font-semibold text-gray-900 group-hover:text-primary transition-colors">
                                                                {{ Str::limit($message->subject, 50) }}
                                                            </div>
                                                            @if($message->message)
                                                                <div class="text-xs text-gray-500 mt-1">
                                                                    {{ Str::limit($message->message, 80) }}
                                                                </div>
                                                            @endif
                                                        </td>
                                                        
                                                        {{-- Status --}}
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            @if($message->status)
                                                                @php
                                                                    $statusColors = [
                                                                        'Open' => 'bg-green-100 text-green-800 border-green-200',
                                                                        'In Progress' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                                        'Pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                                        'Resolved' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                                        'Closed' => 'bg-gray-100 text-gray-800 border-gray-200',
                                                                        'On Hold' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                                    ];
                                                                    $statusClass = $statusColors[$message->status->name] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                                                @endphp
                                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                                                    <span class="w-2 h-2 rounded-full mr-2" style="background-color: {{ $message->status->color }};"></span>
                                                                    {{ $message->status->name }}
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                    Unknown
                                                                </span>
                                                            @endif
                                                        </td>
                                                        
                                                        {{-- Priority --}}
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            @if($message->priority)
                                                                @php
                                                                    $priorityColors = [
                                                                        'Low' => 'bg-green-100 text-green-800 border-green-200',
                                                                        'Medium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                                        'High' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                                        'Urgent' => 'bg-red-100 text-red-800 border-red-200',
                                                                    ];
                                                                    $priorityClass = $priorityColors[$message->priority->name] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                                                @endphp
                                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $priorityClass }}">
                                                                    @if($message->priority->name === 'Urgent')
                                                                        <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                                                                    @elseif($message->priority->name === 'High')
                                                                        <i class="fa-solid fa-flag mr-1"></i>
                                                                    @elseif($message->priority->name === 'Medium')
                                                                        <i class="fa-solid fa-minus mr-1"></i>
                                                                    @else
                                                                        <i class="fa-solid fa-arrow-down mr-1"></i>
                                                                    @endif
                                                                    {{ $message->priority->name }}
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                    Normal
                                                                </span>
                                                            @endif
                                                        </td>
                                                        
                                                        {{-- Created Date --}}
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">{{ $message->created_at->format('M d, Y') }}</div>
                                                            <div class="text-xs text-gray-500">{{ $message->created_at->format('H:i') }}</div>
                                                        </td>
                                                        
                                                        {{-- Action --}}
                                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                                            <div class="flex items-center justify-end gap-2">
                                                                <span class="text-xs text-gray-500">View</span>
                                                                <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:translate-x-1 group-hover:text-primary transition-all"></i>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        {{-- Pagination - Only show if more than 10 messages --}}
                                        @if($messages->hasPages())
                                            <div class="px-6 py-4 border-t border-gray-200">
                                                {{ $messages->links() }}
                                            </div>
                                        @endif
                                    @else
                                        {{-- Empty State --}}
                                        <div class="text-center py-12">
                                            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-primary/10 to-primary/20 rounded-full flex items-center justify-center">
                                                <i class="fa-solid fa-ticket text-primary text-3xl"></i>
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-3">No Messages Yet</h3>
                                            <p class="text-gray-600 text-sm mb-6 max-w-md mx-auto">You haven't created any messages yet. Create your first message to get help from our support team.</p>
                                            <a href="{{ route('dashboard.messages.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary/90 transition-all duration-200 font-semibold shadow-sm hover:shadow-md">
                                                <i class="fa-solid fa-plus"></i>
                                                Create Your First Message
                                            </a>
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
// No additional JavaScript needed - using direct links to create page
</script>
@endpush
