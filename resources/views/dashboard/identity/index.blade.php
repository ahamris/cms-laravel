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
                    <div class="space-y-8">
                        {{-- Personal Data Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-4 py-2">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h1 class="text-2xl font-bold text-white">My Personal Data</h1>
                                        <p class="text-white/90 text-sm mt-1">View and manage your identity information</p>
                                    </div>
                                    <a href="{{ route('dashboard.identity.edit') }}" 
                                       class="bg-white text-primary px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                                        <i class="fa-solid fa-edit mr-2"></i>
                                        Edit Information
                                    </a>
                                </div>
                            </div>

                            <div class="p-6">
                                {{-- Success Message --}}
                                @if(session('success'))
                                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <i class="fa-solid fa-check-circle text-green-600 mr-3"></i>
                                            <p class="text-green-800">{{ session('success') }}</p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Personal Information --}}
                                <div class="mb-8">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fa-solid fa-user text-primary mr-3"></i>
                                        Personal Information
                                    </h2>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">Full Name</dt>
                                                <dd class="text-sm font-semibold text-gray-800">{{ $user->name ?: 'Not specified' }}</dd>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">Email</dt>
                                                <dd class="text-sm font-semibold text-gray-800">{{ $user->email }}</dd>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">Phone</dt>
                                                <dd class="text-sm font-semibold text-gray-800">{{ $user->telefoon ?: 'Not specified' }}</dd>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">Type</dt>
                                                <dd class="text-sm font-semibold text-gray-800">{{ $user->type ?: 'Not specified' }}</dd>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">Website</dt>
                                                <dd class="text-sm font-semibold text-gray-800">
                                                    @if($user->website)
                                                        <a href="{{ $user->website }}" target="_blank" class="text-primary hover:underline">{{ $user->website }}</a>
                                                    @else
                                                        Not specified
                                                    @endif
                                                </dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>

                                {{-- Address Information --}}
                                <div class="mb-8">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fa-solid fa-map-marker-alt text-primary mr-3"></i>
                                        Address Information
                                    </h2>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">Address</dt>
                                                <dd class="text-sm font-semibold text-gray-800">{{ $user->adres ?: 'Not specified' }}</dd>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">Postal Code</dt>
                                                <dd class="text-sm font-semibold text-gray-800">{{ $user->postcode ?: 'Not specified' }}</dd>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">City</dt>
                                                <dd class="text-sm font-semibold text-gray-800">{{ $user->plaats ?: 'Not specified' }}</dd>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">Country</dt>
                                                <dd class="text-sm font-semibold text-gray-800">{{ $user->land ?: 'Not specified' }}</dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                                
                                {{-- Business Information --}}
                                <div class="mb-8">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fa-solid fa-building text-primary mr-3"></i>
                                        Business Information
                                    </h2>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">Company Name</dt>
                                                <dd class="text-sm font-semibold text-gray-800">{{ $user->bedrijfsnaam ?: 'Not specified' }}</dd>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">KvK Number</dt>
                                                <dd class="text-sm font-semibold text-gray-800">{{ $user->kvk_nummer ?: 'Not specified' }}</dd>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">BTW Number</dt>
                                                <dd class="text-sm font-semibold text-gray-800">{{ $user->btw_nummer ?: 'Not specified' }}</dd>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                                                <dt class="text-sm font-medium text-gray-600 mb-1 sm:mb-0">IBAN</dt>
                                                <dd class="text-sm font-semibold text-gray-800">{{ $user->iban ?: 'Not specified' }}</dd>
                                            </div>
                                        </dl>
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
