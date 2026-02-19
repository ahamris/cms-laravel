@extends('front.layouts.app')

@section('title', 'Settings')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative container mx-auto px-6 flex justify-between items-center">
            <div class="flex flex-col items-start text-left">
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                    Settings
                </h1>
                {{-- Breadcrumbs --}}
                <div class="text-sm text-white/80">
                    <span>Dashboard</span>
                    <span class="mx-2">></span>
                    <span>Settings</span>
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
                        {{-- Email Settings --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-6 py-4">
                                <h2 class="text-xl font-bold text-white">E-mailadres</h2>
                                <p class="text-white/90 text-sm mt-1">Update your email address</p>
                            </div>
                            <div class="p-6">
                                <div class="mt-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 sr-only">E-mailadres</label>
                                    <div class="mt-1 flex items-center gap-4">
                                        <input type="email" name="email" id="email" class="py-3 px-4 block w-full md:w-1/2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary sm:text-sm transition-colors" placeholder="uwemail@email.com">
                                    </div>
                                </div>
                                <div class="mt-6">
                                     <button type="button" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                                        <i class="fa-solid fa-save mr-2"></i>
                                        Opslaan
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Notifications Settings --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-6 py-4">
                                <h2 class="text-xl font-bold text-white">Meldingen</h2>
                                <p class="text-white/90 text-sm mt-1">Configure your notification preferences</p>
                            </div>
                            <div class="p-6">
                                <div class="divide-y divide-gray-200">
                                    <div class="py-4 flex items-center justify-between">
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Berichtenbox</h3>
                                            <p class="text-sm text-gray-600">Melding als er nieuwe post is.</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-primary/20 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                        </label>
                                    </div>
                                    <div class="py-4 flex items-center justify-between">
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Herinnering als u post niet heeft geopend</h3>
                                            <p class="text-sm text-gray-600">U krijgt een herinnering als u een ongelezen bericht in uw Berichtenbox heeft.</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-primary/20 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                        </label>
                                    </div>
                                    <div class="py-4 flex items-center justify-between">
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Lopende zaken</h3>
                                            <p class="text-sm text-gray-600">Melding bij een statuswijziging.</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-primary/20 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                        </label>
                                    </div>
                                </div>
                                 <div class="mt-6">
                                     <button type="button" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                                        <i class="fa-solid fa-save mr-2"></i>
                                        Opslaan
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Organizations Settings --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-6 py-4">
                                <h2 class="text-xl font-bold text-white">Organisaties Berichtenbox</h2>
                                <p class="text-white/90 text-sm mt-1">Select which organizations can send you digital messages</p>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-600 mb-6">Selecteer van welke organisaties u post digitaal wilt ontvangen.</p>
                                
                                <div class="space-y-3">
                                    @php
                                        $organizations = [
                                            'Landelijke organisaties' => ['Belastingdienst', 'CAK', 'CIZ', 'CJIB', 'DUO', 'Immigratie- en Naturalisatiedienst', 'Justis', 'Kadaster', 'Ministerie van Binnenlandse Zaken en Koninkrijksrelaties', 'Ministerie van Defensie', 'Ministerie van Infrastructuur en Waterstaat', 'RDW', 'Rijksdienst voor Ondernemend Nederland', 'Rijksvastgoedbedrijf', 'SVB', 'UWV'],
                                            'Provincies' => [],
                                            'Gemeenten' => [],
                                            'Samenwerkingsverbanden' => [],
                                            'Waterschappen' => [],
                                            'Pensioenfondsen' => []
                                        ];
                                    @endphp

                                    @foreach($organizations as $category => $orgs)
                                    <details class="group">
                                        <summary class="flex cursor-pointer list-none items-center justify-between bg-gray-50 p-4 font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 border border-gray-200">
                                            <span class="font-medium">{{ $category }}</span>
                                            <i class="fa-solid fa-chevron-right transition-transform duration-200 group-open:rotate-90 text-gray-500"></i>
                                        </summary>
                                        <div class="p-4 bg-white rounded-lg mt-2 border border-gray-100">
                                            @if(empty($orgs))
                                                <p class="text-sm text-gray-500 italic">Geen organisaties in deze categorie.</p>
                                            @else
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                @foreach($orgs as $org)
                                                <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                                    <input id="org-{{ Str::slug($org) }}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary focus:ring-2 focus:ring-offset-0" checked>
                                                    <label for="org-{{ Str::slug($org) }}" class="text-sm text-gray-700 cursor-pointer select-none">{{ $org }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </details>
                                    @endforeach
                                </div>
                                
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                     <button type="button" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                                        <i class="fa-solid fa-save mr-2"></i>
                                        Opslaan
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- History --}}
                         <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-6 py-4">
                                <h2 class="text-xl font-bold text-white">Geschiedenis</h2>
                                <p class="text-white/90 text-sm mt-1">View your account activity history</p>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-600 mb-6">Hier ziet u wanneer u instellingen heeft aangepast en wanneer u bent in- en uitgelogd op MijnOverheid.</p>
                                <div class="flow-root">
                                    <ul role="list" class="-mb-8">
                                        @php
                                            $history = [
                                                ['date' => 'ma 29 aug. 2025', 'time' => '18:41', 'action' => 'U logde in op de website van MijnOverheid.'],
                                                ['date' => 'do 15 jul. 2025', 'time' => '14:09', 'action' => 'U logde uit op de website van MijnOverheid.'],
                                                ['date' => 'wo 14 jul. 2025', 'time' => '11:00', 'action' => 'U gaf aan dat u van de Belastingdienst, de Sociale Verzekeringsbank en het UWV geen berichten meer wilt ontvangen in de Berichtenbox.'],
                                            ];
                                        @endphp
                                        @foreach($history as $item)
                                        <li>
                                            <div class="relative pb-8">
                                                @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center ring-8 ring-white">
                                                            <i class="fa-solid fa-clock-rotate-left text-gray-500"></i>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500">{{ $item['action'] }}</p>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            <time datetime="{{ $item['date'] }}">{{ $item['date'] }}</time>, <time>{{ $item['time'] }}</time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
