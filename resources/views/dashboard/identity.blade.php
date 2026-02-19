@extends('front.layouts.app')

@section('title', 'Identity')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative container mx-auto px-6 flex justify-between items-center">
            <div class="flex flex-col items-start text-left">
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                    Identity
                </h1>
                {{-- Breadcrumbs --}}
                <div class="text-sm text-white/80">
                    <span>Dashboard</span>
                    <span class="mx-2">></span>
                    <span>Identity</span>
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
                        {{-- Personal Data Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-6 py-4">
                                <h1 class="text-xl font-bold text-white">My Personal Data</h1>
                                <p class="text-white/90 text-sm mt-1">View and manage your identity information</p>
                            </div>

                            <div class="p-6">
                                {{-- Identity Data --}}
                                <div class="mb-8">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fa-solid fa-id-card text-primary mr-3"></i>
                                        Identity Data
                                    </h2>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @php
                                                $identityData = [
                                                    'First names' => 'Jan Pieter',
                                                    'Surname' => 'Jansen',
                                                    'Name use' => 'Eigen geslachtsnaam',
                                                    'Gender' => 'Man',
                                                    'Citizen service number' => '123456789',
                                                    'Date of birth' => '15 maart 1990',
                                                    'Place of birth' => 'Amsterdam',
                                                    'Country of birth' => 'Nederland',
                                                    'Municipal document' => 'Amsterdam',
                                                    'Date document' => '15 maart 1990',
                                                    'Document description' => 'Geboorteakte',
                                                    'Effective date of validity' => 'Onbekend',
                                                ];
                                            @endphp
                                            @foreach($identityData as $label => $value)
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                    <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">{{ $label }}</dt>
                                                    <dd class="flex items-center justify-between sm:justify-end">
                                                        <span class="text-sm font-semibold text-gray-800 mr-3">{{ $value }}</span>
                                                        <button class="text-primary hover:text-primary/80 transition-colors" title="More information">
                                                            <i class="fa-solid fa-info-circle text-sm"></i>
                                                        </button>
                                                    </dd>
                                                </div>
                                            @endforeach
                                        </dl>
                                    </div>
                                </div>

                                {{-- Address details --}}
                                <div class="mb-8">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fa-solid fa-map-marker-alt text-primary mr-3"></i>
                                        Address Details
                                    </h2>
                                    <div class="space-y-3">
                                        <details class="group" open>
                                            <summary class="flex cursor-pointer list-none items-center justify-between bg-primary/5 p-4 font-medium text-gray-900 hover:bg-primary/10 rounded-lg transition-colors duration-200 border border-primary/20">
                                                <span class="font-semibold text-primary">Current (valid as of July 5, 2015)</span>
                                                <i class="fa-solid fa-chevron-down transition-transform duration-200 group-open:rotate-180 text-primary"></i>
                                            </summary>
                                            <div class="p-4 bg-gray-50 rounded-lg mt-2">
                                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    @php
                                                        $addressData = [
                                                            'Street' => 'Hoofdstraat 123',
                                                            'House number' => '123',
                                                            'Postal code' => '1234 AB',
                                                            'City' => 'Amsterdam',
                                                            'Municipality' => 'Amsterdam',
                                                            'Country' => 'Netherlands',
                                                            'Valid from' => 'July 5, 2015',
                                                            'Valid until' => 'Present'
                                                        ];
                                                    @endphp
                                                    @foreach($addressData as $label => $value)
                                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                            <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">{{ $label }}</dt>
                                                            <dd class="flex items-center justify-between sm:justify-end">
                                                                <span class="text-sm font-semibold text-gray-800 mr-3">{{ $value }}</span>
                                                                <button class="text-primary hover:text-primary/80 transition-colors" title="More information">
                                                                    <i class="fa-solid fa-info-circle text-sm"></i>
                                                                </button>
                                                            </dd>
                                                        </div>
                                                    @endforeach
                                                </dl>
                                            </div>
                                        </details>
                                        
                                        @foreach(['Valid from October 7, 2011', 'Valid from January 19, 2010', 'Valid from December 27, 2007', 'Valid from August 26, 1986'] as $item)
                                        <details class="group">
                                            <summary class="flex cursor-pointer list-none items-center justify-between bg-gray-50 p-4 font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 border border-gray-200">
                                                <span class="font-medium">{{ $item }}</span>
                                                <i class="fa-solid fa-chevron-right transition-transform duration-200 group-open:rotate-90 text-gray-500"></i>
                                            </summary>
                                            <div class="p-4 bg-white rounded-lg mt-2 border border-gray-100">
                                                <p class="text-sm text-gray-600">Details for this period will be shown here.</p>
                                            </div>
                                        </details>
                                        @endforeach
                                    </div>
                                </div>
                                
                                {{-- Registration --}}
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fa-solid fa-clipboard-list text-primary mr-3"></i>
                                        Registration
                                    </h2>
                                    <div class="space-y-3">
                                        <details class="group">
                                            <summary class="flex cursor-pointer list-none items-center justify-between bg-gray-50 p-4 font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 border border-gray-200">
                                                <span class="font-medium">Current registration</span>
                                                <i class="fa-solid fa-chevron-right transition-transform duration-200 group-open:rotate-90 text-gray-500"></i>
                                            </summary>
                                            <div class="p-4 bg-white rounded-lg mt-2 border border-gray-100">
                                                <p class="text-sm text-gray-600">Registration details will be shown here.</p>
                                            </div>
                                        </details>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- FAQ Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-6 py-4">
                                <h2 class="text-xl font-bold text-white">Frequently Asked Questions</h2>
                                <p class="text-white/90 text-sm mt-1">Common questions about identity data</p>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    @php
                                        $faqs = [
                                            'How do I change data from the Municipal Personal Records Database (BRP)?',
                                            'What is the BRP?',
                                            'Why do I see data that I have registered as \'secret\'?',
                                            'Why am I missing some old BRP data in my overview?',
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
                                <a href="#" class="mt-4 inline-flex items-center text-sm font-semibold text-primary hover:text-primary/80 transition-colors">
                                    View all frequently asked questions 
                                    <i class="fa-solid fa-chevron-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
