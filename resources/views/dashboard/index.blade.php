@extends('front.layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative container mx-auto px-6 flex justify-between items-center">
            <div class="flex flex-col items-start text-left">
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                    Welcome, {{ $user->name }}!
                </h1>
                {{-- Breadcrumbs --}}
                <div class="text-sm text-white/80">
                    <span>Dashboard</span>
                    <span class="mx-2">></span>
                    <span>Home</span>
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
                    <div class="space-y-4">

                        {{-- Recent Messages --}}
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <div class="bg-primary px-4 py-2">
                                <h2 class="text-xl font-bold text-white">Recent Messages</h2>
                                <p class="text-white/90 text-sm mt-1">Your latest support requests</p>
                            </div>
                            <div>
                                @if($recentMessages->count() > 0)
                                    {{-- Table Header --}}
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
                                                @foreach($recentMessages as $message)
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
                                                            {{ Str::limit($message->subject, 40) }}
                                                        </div>
                                                        @if($message->message)
                                                            <div class="text-xs text-gray-500 mt-1">
                                                                {{ Str::limit($message->message, 60) }}
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
                                @else
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
                                
                                {{-- View All Button --}}
                                @if($recentMessages->count() > 0)
                                    <div class="px-4 py-4 border-t border-gray-200 text-right">
                                        <a href="{{ route('dashboard.messages') }}" class="inline-flex items-center gap-2 bg-primary text-white font-semibold py-2 px-3 rounded-lg hover:from-primary/90 hover:to-primary/70 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <i class="fa-solid fa-list"></i>
                                            View All Messages
                                            <i class="fa-solid fa-chevron-right ml-1 group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- What can I find where?

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-6 py-4">
                                <h2 class="text-xl font-bold text-white">Wat kan ik waar vinden?</h2>
                                <p class="text-white/90 text-sm mt-1">What can you find where?</p>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    @foreach($sections as $index => $section)
                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-100 hover:bg-gray-100 transition-colors duration-200">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="bg-primary/10 text-primary w-12 h-12 flex items-center justify-center rounded-full">
                                                <i class="{{ $section['icon'] }} text-lg"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-primary mb-1">{{ $section['title'] }}</p>
                                            <p class="text-sm text-gray-600">{{ $section['desc'] }}</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <i class="fa-solid fa-chevron-right text-primary"></i>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        --}}



                        {{-- FAQ --}}
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <div class="bg-primary px-4 py-2">
                                <h2 class="text-xl font-bold text-white">Veelgestelde vragen</h2>
                                <p class="text-white/90 text-sm mt-1">Frequently asked questions</p>
                            </div>
                            <div class="px-4 pt-4">
                                <div class="space-y-3">
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
                                <div class="py-4 text-right">
                                    <a href="#" class="inline-flex items-center gap-2 text-primary font-semibold">
                                        <span class="underline hover:no-underline">Bekijk alle veelgestelde vragen</span>
                                        <i class="fa-solid fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
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
