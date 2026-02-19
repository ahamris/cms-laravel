@extends('front.layouts.app')

@section('title', 'Personal')

@section('content')


    {{-- Hero Section --}}
    <section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative container mx-auto px-6 flex justify-between items-center">
            <div class="flex flex-col items-start text-left">
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                    Business
                </h1>
                {{-- Breadcrumbs --}}
                <div class="text-sm text-white/80">
                    <span>Dashboard</span>
                    <span class="mx-2">></span>
                    <span>Business</span>
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
                    <div x-data="{ 
                        activeTab: 'business', 
                        showNewMessageForm: false,
                        selectedFiles: [],
                        handleFileUpload(event) {
                            const files = Array.from(event.target.files);
                            this.selectedFiles = [...this.selectedFiles, ...files];
                            event.target.value = ''; // Reset input
                        },
                        removeFile(index) {
                            this.selectedFiles.splice(index, 1);
                        },
                        formatFileSize(bytes) {
                            if (bytes === 0) return '0 Bytes';
                            const k = 1024;
                            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                            const i = Math.floor(Math.log(bytes) / Math.log(k));
                            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                        }
                    }" class="space-y-8">
                        {{-- Tab Navigation --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <nav class="flex space-x-8 px-6 py-4 border-b border-gray-200">
                                <button 
                                    @click="activeTab = 'business'"
                                    :class="{ 'border-primary text-primary': activeTab === 'business', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'business' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200">
                                    Business
                                </button>
                                <button 
                                    @click="activeTab = 'inform'"
                                    :class="{ 'border-primary text-primary': activeTab === 'inform', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'inform' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200">
                                    To inform
                                </button>
                                <button 
                                    @click="activeTab = 'personal'"
                                    :class="{ 'border-primary text-primary': activeTab === 'personal', 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': activeTab !== 'personal' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200">
                                    Personal data
                                </button>
                                <div class="flex-1 flex justify-end">
                                    <button 
                                        @click="activeTab = 'logout'"
                                        :class="{ 'text-red-600': activeTab === 'logout', 'text-gray-500 hover:text-gray-700': activeTab !== 'logout' }"
                                        class="whitespace-nowrap py-4 px-1 font-medium text-sm flex items-center focus:outline-none transition-colors duration-200">
                                        Log out
                                        <i class="fa-solid fa-right-from-bracket ml-1 text-base"></i>
                                    </button>
                                </div>
                            </nav>
                        </div>
                        
                        {{-- Tab Content Container --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            {{-- Business Tab Content --}}
                            <div x-show="activeTab === 'business'" class="space-y-6">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-800">Business</h1>
                                    <p class="mt-2 text-gray-600">Welcome, <span class="font-semibold">Jan Pieter</span>. Here you'll find an overview of your business. Click on the title for more information.</p>

                                    <div class="mt-6">
                                        <label for="search-business" class="block text-sm font-medium text-gray-700 mb-2">Find your business</label>
                                        <div class="relative inline-block w-full md:w-1/2">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fa-solid fa-search text-gray-400"></i>
                                            </div>
                                            <input type="search" id="search-business" placeholder="To search..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm transition-colors duration-200">
                                        </div>
                                    </div>

                                    <div class="mt-8">
                                        <h2 class="text-xl font-bold text-gray-800">Completed cases ( 6 )</h2>
                                        <div class="mt-4 border-t border-gray-200">
                                            <ul class="divide-y divide-gray-200">
                                                @php
                                                    $cases = [
                                                        ['id' => '1507336', 'title' => 'Extract from the BRP', 'details' => 'web form', 'result' => 'delivered'],
                                                        ['id' => '1276797', 'title' => 'Right to privacy', 'details' => 'Right to rectification | Post', 'result' => 'granted'],
                                                        ['id' => '1192304', 'title' => 'Objection', 'details' => '1142462', 'result' => 'case dismissed'],
                                                        ['id' => '1191012', 'title' => 'Penalty for late decision', 'details' => '', 'result' => 'rejected'],
                                                        ['id' => '1142462', 'title' => 'Right to privacy', 'details' => 'Right to access, Right to a human review of a decision | Digital (email and MyGouda)', 'result' => 'granted'],
                                                        ['id' => '242626', 'title' => 'Temporary bridging scheme for self-employed persons (TOZO 5)', 'details' => 'web form', 'result' => 'provided'],
                                                    ];
                                                @endphp

                                                @foreach ($cases as $case)
                                                <li class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4 items-center hover:bg-gray-50 transition-colors duration-200 rounded-sm px-2">
                                                    <div class="md:col-span-1">
                                                        <a href="{{ route('dashboard.case', ['id' => $case['id']]) }}" class="text-sm font-medium text-red-600 hover:text-red-700 hover:underline transition-colors duration-200">{{ $case['id'] }}: {{ $case['title'] }}</a>
                                                    </div>
                                                    <div class="md:col-span-1 text-sm text-gray-500">{{ $case['details'] ?: '—' }}</div>
                                                    <div class="md:col-span-1 text-sm text-gray-500 md:text-right">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                            @if($case['result'] === 'granted' || $case['result'] === 'delivered' || $case['result'] === 'provided') bg-green-100 text-green-800
                                                            @elseif($case['result'] === 'rejected' || $case['result'] === 'case dismissed') bg-red-100 text-red-800
                                                            @else bg-gray-100 text-gray-800
                                                            @endif">
                                                            {{ ucfirst($case['result']) }}
                                                        </span>
                                                    </div>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- To Inform Tab Content --}}
                            <div x-show="activeTab === 'inform'" class="space-y-6">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-800">Messages</h1>
                                    
                                    {{-- Search and New Message Section --}}
                                    <div class="mt-6 flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                                        {{-- Search Bar --}}
                                        <div class="relative flex-1 max-w-md">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fa-solid fa-search text-gray-400"></i>
                                            </div>
                                            <input type="search" placeholder="Search for a message..." 
                                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                        </div>
                                        
                                        {{-- New Message Button --}}
                                        <button @click="showNewMessageForm = true" 
                                                class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-sm hover:bg-primary/90 transition-colors duration-200">
                                            <i class="fa-solid fa-plus mr-2"></i>
                                            New
                                        </button>
                                    </div>

                                    {{-- Empty State Message --}}
                                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-sm">
                                        <p class="text-blue-800 text-sm">There is no communication to display.</p>
                                    </div>

                                    {{-- New Message Form Modal --}}
                                    <div x-show="showNewMessageForm" 
                                         x-cloak
                                         class="fixed inset-0 flex items-center justify-center z-50"
                                         @click.self="showNewMessageForm = false">
                                        
                                        {{-- Backdrop Overlay --}}
                                        <div class="absolute inset-0 bg-black opacity-30"></div>
                                        
                                        {{-- Modal Content --}}
                                        <div class="relative bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                                            {{-- Modal Header --}}
                                            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                                                <h2 class="text-xl font-bold text-gray-800">New Message</h2>
                                                <button @click="showNewMessageForm = false" 
                                                        class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                                    <i class="fa-solid fa-xmark text-xl"></i>
                                                </button>
                                            </div>

                                            {{-- Modal Content --}}
                                            <div class="p-6 space-y-6">
                                                {{-- Case Selection --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select a case...</label>
                                                    <div class="relative">
                                                        <select class="block w-full px-3 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary appearance-none">
                                                            <option value="">Choose a case...</option>
                                                            <option value="1507336">1507336: Extract from the BRP</option>
                                                            <option value="1276797">1276797: Right to privacy</option>
                                                            <option value="1192304">1192304: Objection</option>
                                                            <option value="1191012">1191012: Penalty for late decision</option>
                                                            <option value="1142462">1142462: Right to privacy</option>
                                                            <option value="242626">242626: TOZO 5 scheme</option>
                                                        </select>
                                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                            <i class="fa-solid fa-chevron-down text-gray-400"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Subject Field --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Type your subject here</label>
                                                    <input type="text" 
                                                           placeholder="Enter message subject..." 
                                                           class="block w-full px-3 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                                </div>

                                                {{-- Message Body --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Type your message here</label>
                                                    <textarea rows="6" 
                                                              placeholder="Enter your message content..." 
                                                              class="block w-full px-3 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary resize-none"></textarea>
                                                </div>

                                                {{-- File Upload --}}
                                                <div>
                                                    <input type="file" 
                                                           x-ref="fileInput"
                                                           multiple 
                                                           class="hidden" 
                                                           @change="handleFileUpload($event)">
                                                    <button type="button" 
                                                            @click="$refs.fileInput.click()"
                                                            class="inline-flex items-center px-4 py-2 border border-primary text-primary rounded-sm hover:bg-primary hover:text-white transition-colors duration-200">
                                                        <i class="fa-solid fa-paperclip mr-2"></i>
                                                        Add files
                                                    </button>
                                                </div>

                                                {{-- File List --}}
                                                <div x-show="selectedFiles.length > 0" class="space-y-2">
                                                    <h4 class="text-sm font-medium text-gray-700">Selected Files:</h4>
                                                    <div class="space-y-2">
                                                        <template x-for="(file, index) in selectedFiles" :key="index">
                                                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                                <div class="flex items-center space-x-3">
                                                                    <i class="fa-solid fa-file-lines text-gray-500"></i>
                                                                    <div>
                                                                        <p class="text-sm font-medium text-gray-800" x-text="file.name"></p>
                                                                        <p class="text-xs text-gray-500" x-text="formatFileSize(file.size)"></p>
                                                                    </div>
                                                                </div>
                                                                <button @click="removeFile(index)" 
                                                                        class="text-red-500 hover:text-red-700 transition-colors duration-200"
                                                                        title="Remove file">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Modal Footer --}}
                                            <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200">
                                                <button @click="showNewMessageForm = false" 
                                                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-sm hover:bg-gray-50 transition-colors duration-200">
                                                    Cancel
                                                </button>
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-sm hover:bg-gray-400 transition-colors duration-200">
                                                    Send
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Personal Data Tab Content --}}
                            <div x-show="activeTab === 'personal'" class="space-y-6">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-800">Personal data</h1>
                                    
                                    <div class="mt-6 space-y-8">
                                        {{-- Personal Data Section --}}
                                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                            <h2 class="text-xl font-bold text-gray-800 mb-4">Personal data</h2>
                                            
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">BSN</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">123456789</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">First names</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">John</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Middle names</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">-</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Surname</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">Doe</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Name usage</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">Doe</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">January 15, 1990</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">Male</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Intra-municipal</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">Yes</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">The Netherlands</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Postal address</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">No</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Residence Address Section --}}
                                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                            <h2 class="text-xl font-bold text-gray-800 mb-4">Residence address</h2>
                                            
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Street name</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">Sample Street</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">House number</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">123</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">House letter</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">-</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">House number addition</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">-</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">1234AB</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Place of residence</label>
                                                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-sm">
                                                        <span class="text-gray-800">Amsterdam</span>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone number</label>
                                                    <input type="tel" 
                                                           placeholder="Enter phone number" 
                                                           class="block w-full p-3 border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone number (mobile)</label>
                                                    <input type="tel" 
                                                           value="0612345678"
                                                           class="block w-full p-3 border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                                                    <input type="email" 
                                                           value="john.doe@example.com"
                                                           class="block w-full p-3 border border-gray-300 rounded-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Save Button --}}
                                        <div class="flex justify-start">
                                            <button type="button" 
                                                    class="px-6 py-3 bg-red-600 text-white font-medium rounded-sm hover:bg-red-700 transition-colors duration-200">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Logout Confirmation --}}
                            <div x-show="activeTab === 'logout'" class="space-y-6">
                                <div class="text-center">
                                    <h1 class="text-3xl font-bold text-gray-800">Log Out</h1>
                                    <p class="mt-4 text-gray-600">Are you sure you want to log out?</p>
                                    <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                                        <a href="#" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-sm shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                                            Yes, log me out
                                        </a>
                                        <button @click="activeTab = 'business'" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                                            Cancel
                                        </button>
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
