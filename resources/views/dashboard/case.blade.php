@extends('front.layouts.app')

@section('title', 'Case Details')

@section('content')
    <div class="bg-gray-50 font-sans">
        <div class="container mx-auto px-6 py-8">
            <div class="grid grid-cols-12 gap-8">
                {{-- Left Sidebar Navigation --}}
                @include('dashboard.partials.sidebar')

                {{-- Main Content --}}
                <main class="col-span-12 lg:col-span-9">
                    <div class="space-y-8">
                        {{-- Case Header --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            {{-- Result Status Bar --}}
                            <div class="bg-primary text-white px-4 py-3 rounded-lg mb-4 flex items-center">
                                <i class="fa-solid fa-check text-green-400 mr-3"></i>
                                <span class="font-semibold">Result: granted</span>
                            </div>

                            {{-- Case Title and Type --}}
                            <div class="mb-6">
                                <h1 class="text-3xl font-bold text-gray-800 mb-2">1276797: Right to privacy</h1>
                                <p class="text-gray-600">Right to rectification | Post</p>
                            </div>

                            {{-- Case Handling Section --}}
                            <div class="mb-6">
                                <div class="bg-primary text-white px-4 py-3 rounded-lg flex items-center justify-between cursor-pointer" 
                                     x-data="{ open: true }" 
                                     @click="open = !open">
                                    <span class="font-semibold">Case handling</span>
                                    <i class="fa-solid fa-chevron-down transition-transform duration-200" 
                                       :class="{ 'rotate-180': open }"></i>
                                </div>
                                
                                <div x-show="open" x-transition class="mt-3">
                                    <div class="bg-primary text-white px-4 py-3 rounded-lg flex items-center justify-between">
                                        <span class="font-semibold">Register</span>
                                        <div class="flex items-center">
                                            <i class="fa-solid fa-check text-green-400 mr-2"></i>
                                            <span>January 15, 2025</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- GDPR Rights Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">As a citizen, you have the following rights under the GDPR.</h2>
                            
                            <div class="space-y-4">
                                <div class="border-l-4 border-blue-500 pl-4">
                                    <h3 class="font-semibold text-gray-800">I have the right to access information.</h3>
                                    <p class="text-gray-600 text-sm">What personal data does the municipality have about me?</p>
                                </div>
                                
                                <div class="border-l-4 border-green-500 pl-4">
                                    <h3 class="font-semibold text-gray-800">I have the right to rectification.</h3>
                                    <p class="text-gray-600 text-sm">Does the municipality want to change or supplement my personal data?</p>
                                </div>
                                
                                <div class="border-l-4 border-yellow-500 pl-4">
                                    <h3 class="font-semibold text-gray-800">I have the right to be forgotten.</h3>
                                    <p class="text-gray-600 text-sm">Does the municipality want to delete my personal data?</p>
                                </div>
                                
                                <div class="border-l-4 border-purple-500 pl-4">
                                    <h3 class="font-semibold text-gray-800">I have the right to restriction of processing.</h3>
                                    <p class="text-gray-600 text-sm">Does the municipality want to use my personal data less often or temporarily stop using my data?</p>
                                </div>
                                
                                <div class="border-l-4 border-indigo-500 pl-4">
                                    <h3 class="font-semibold text-gray-800">I have the right to data portability.</h3>
                                    <p class="text-gray-600 text-sm">Does the municipality want to send my personal data (digitally) or forward it to another organization that needs my data?</p>
                                </div>
                                
                                <div class="border-l-4 border-pink-500 pl-4">
                                    <h3 class="font-semibold text-gray-800">I have the right to a human perspective on a decision.</h3>
                                    <p class="text-gray-600 text-sm">Does the municipality want an automatically taken decision that concerns me and has consequences for me to be reviewed by a human being?</p>
                                </div>
                                
                                <div class="border-l-4 border-primary pl-4">
                                    <h3 class="font-semibold text-gray-800">I have the right to withdraw consent</h3>
                                    <p class="text-gray-600 text-sm">I have previously given consent for the storage of my personal data, but I want to cancel this.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Application Details --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Application Details</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Are you submitting this application on your own behalf or on behalf of someone else?</label>
                                    <div class="bg-green-50 border border-green-200 rounded-lg px-3 py-2 mt-auto">
                                        <span class="text-green-800 font-medium">Myself</span>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Which right(s) do you wish to exercise?</label>
                                    <div class="bg-primary/10 border border-primary/20 rounded-lg px-3 py-2 mt-auto">
                                        <span class="text-primary font-medium">Right to rectification</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Description Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Description of your request</h2>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-gray-700 leading-relaxed">
                                    This is a response to the final decision received on December 17, 2024, regarding case number 1142462. 
                                    I would like to point out that the decision was received late, despite being dated December 16, 2024, 
                                    which I consider misleading and careless. As a citizen, I expect the municipality to comply with 
                                    both GDPR regulations and administrative law. I request a prompt and careful review of my comments 
                                    and requests, including correction of any errors and a transparent overview of the information 
                                    used in making this decision.
                                </p>
                            </div>
                        </div>

                        {{-- Additional Sections --}}
                        <div class="space-y-4">
                            @php
                                $sections = ['Tests', 'To deal with', 'Decisions', 'To handle'];
                            @endphp
                            
                            @foreach ($sections as $section)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                                <details class="group">
                                    <summary class="flex cursor-pointer list-none items-center justify-between bg-primary text-white px-4 py-3 rounded-lg hover:bg-primary/90 transition-colors duration-200">
                                        <span class="font-semibold">{{ $section }}</span>
                                        <div class="flex items-center">
                                            <i class="fa-solid fa-check text-green-400 mr-2"></i>
                                            <span class="text-sm">April 22, 2025</span>
                                            <i class="fa-solid fa-chevron-down ml-2 transition-transform duration-200 group-open:rotate-180 text-white"></i>
                                        </div>
                                    </summary>
                                    <div class="p-4 bg-white border-t border-primary/20">
                                        <p class="text-gray-600">No additional information</p>
                                    </div>
                                </details>
                            </div>
                            @endforeach
                        </div>

                        {{-- To Inform Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">To Inform</h2>
                            <p class="text-gray-600">There are no messages.</p>
                        </div>

                        {{-- Documents Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Documents</h2>
                            
                            <div class="mb-4">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-search text-gray-400"></i>
                                    </div>
                                    <input type="search" placeholder="Q Find a file or folder...." 
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-2">Files</h3>
                                <p class="text-gray-600">There are no files or folders to display.</p>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
