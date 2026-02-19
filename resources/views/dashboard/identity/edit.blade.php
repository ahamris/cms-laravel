@extends('front.layouts.app')

@section('title', 'Edit Identity')

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative container mx-auto px-6 flex justify-between items-center">
            <div class="flex flex-col items-start text-left">
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                    Edit Identity
                </h1>
                {{-- Breadcrumbs --}}
                <div class="text-sm text-white/80">
                    <span>Dashboard</span>
                    <span class="mx-2">></span>
                    <a href="{{ route('dashboard.identity.index') }}" class="hover:text-white/80">Identity</a>
                    <span class="mx-2">></span>
                    <span>Edit</span>
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
                    <form action="{{ route('dashboard.identity.update') }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')
                        
                        {{-- Error Messages --}}
                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fa-solid fa-exclamation-circle text-red-600 mr-3"></i>
                                    <h3 class="text-red-800 font-medium">Please fix the following errors:</h3>
                                </div>
                                <ul class="list-disc list-inside text-red-700 text-sm">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Personal Information Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-4 py-2">
                                <h2 class="text-xl font-bold text-white">Personal Information</h2>
                                <p class="text-white/90 text-sm mt-1">Update your personal details</p>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label for="telefoon" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                        <input type="text" id="telefoon" name="telefoon" value="{{ old('telefoon', $user->telefoon) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                                        <input type="text" id="type" name="type" value="{{ old('type', $user->type) }}" 
                                               placeholder="e.g., Individual, Business"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                                        <input type="url" id="website" name="website" value="{{ old('website', $user->website) }}" 
                                               placeholder="https://example.com"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Address Information Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2">
                                <h2 class="text-xl font-bold text-white">Address Information</h2>
                                <p class="text-white/90 text-sm mt-1">Update your current address</p>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label for="adres" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                        <input type="text" id="adres" name="adres" value="{{ old('adres', $user->adres) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label for="postcode" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                        <input type="text" id="postcode" name="postcode" value="{{ old('postcode', $user->postcode) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label for="plaats" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                        <input type="text" id="plaats" name="plaats" value="{{ old('plaats', $user->plaats) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label for="land" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                        <input type="text" id="land" name="land" value="{{ old('land', $user->land) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Business Information Section --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 py-2">
                                <h2 class="text-xl font-bold text-white">Business Information</h2>
                                <p class="text-white/90 text-sm mt-1">Update your business details</p>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label for="bedrijfsnaam" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                                        <input type="text" id="bedrijfsnaam" name="bedrijfsnaam" value="{{ old('bedrijfsnaam', $user->bedrijfsnaam) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label for="kvk_nummer" class="block text-sm font-medium text-gray-700 mb-2">KvK Number</label>
                                        <input type="text" id="kvk_nummer" name="kvk_nummer" value="{{ old('kvk_nummer', $user->kvk_nummer) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                    
                                    <div>
                                        <label for="btw_nummer" class="block text-sm font-medium text-gray-700 mb-2">BTW Number</label>
                                        <input type="text" id="btw_nummer" name="btw_nummer" value="{{ old('btw_nummer', $user->btw_nummer) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label for="iban" class="block text-sm font-medium text-gray-700 mb-2">IBAN</label>
                                        <input type="text" id="iban" name="iban" value="{{ old('iban', $user->iban) }}"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="flex items-center justify-between bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <a href="{{ route('dashboard.identity.index') }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                <i class="fa-solid fa-arrow-left"></i>
                                Cancel
                            </a>
                            
                            <button type="submit" 
                                    class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-primary/90 transition-colors flex items-center space-x-2 font-semibold">
                                <i class="fa-solid fa-save"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </main>
            </div>
        </div>
    </div>
@endsection
