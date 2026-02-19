<x-layouts.admin title="View Testimonial">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $testimonial->customer_name }}</h1>
            <p class="text-gray-600">Testimonial Details</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.marketing.testimonial.edit', $testimonial) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit Testimonial
            </a>
            <a href="{{ route('admin.marketing.testimonial.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Testimonial Quote --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-8">
                    <div class="text-center">
                        <i class="fa-solid fa-quote-left text-4xl text-primary/20 mb-4"></i>
                        <blockquote class="text-xl text-gray-900 italic leading-relaxed mb-6">
                            "{{ $testimonial->quote }}"
                        </blockquote>
                        
                        {{-- Rating --}}
                        @if($testimonial->rating)
                            <div class="flex items-center justify-center mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }} text-lg"></i>
                                @endfor
                                <span class="ml-2 text-gray-600">({{ $testimonial->rating }}/5)</span>
                            </div>
                        @endif

                        {{-- Customer Info --}}
                        <div class="border-t border-gray-200 pt-6">
                            <p class="text-lg font-semibold text-gray-900">{{ $testimonial->customer_name }}</p>
                            @if($testimonial->position && $testimonial->company)
                                <p class="text-gray-600">{{ $testimonial->position }} at {{ $testimonial->company }}</p>
                            @elseif($testimonial->position)
                                <p class="text-gray-600">{{ $testimonial->position }}</p>
                            @elseif($testimonial->company)
                                <p class="text-gray-600">{{ $testimonial->company }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                            <p class="text-gray-900">{{ $testimonial->customer_name }}</p>
                        </div>
                        @if($testimonial->company)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                                <p class="text-gray-900">{{ $testimonial->company }}</p>
                            </div>
                        @endif
                    </div>
                    
                    @if($testimonial->position)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Position/Title</label>
                            <p class="text-gray-900">{{ $testimonial->position }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tags --}}
            @if($testimonial->tags && count($testimonial->tags) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Tags</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2">
                            @foreach($testimonial->tags as $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fa-solid fa-tag mr-1"></i>
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Images --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Images</h3>
                </div>
                <div class="p-6 space-y-4">
                    {{-- Customer Photo --}}
                    <div class="text-center">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Customer Photo</label>
                        @if($testimonial->photo)
                            <img src="{{ asset('storage/' . $testimonial->photo) }}" 
                                 alt="{{ $testimonial->customer_name }}" 
                                 class="w-24 h-24 rounded-full object-cover mx-auto">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mx-auto">
                                <i class="fa-solid fa-user text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm mt-2">No photo uploaded</p>
                        @endif
                    </div>

                    {{-- Company Logo --}}
                    <div class="text-center">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Company Logo</label>
                        @if($testimonial->company_logo)
                            <img src="{{ asset('storage/' . $testimonial->company_logo) }}" 
                                 alt="{{ $testimonial->company }}" 
                                 class="w-24 h-16 object-contain mx-auto bg-gray-50 rounded">
                        @else
                            <div class="w-24 h-16 bg-gray-200 flex items-center justify-center mx-auto rounded">
                                <i class="fa-solid fa-building text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 text-sm mt-2">No logo uploaded</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Status & Settings --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Status & Settings</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $testimonial->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fa-solid fa-{{ $testimonial->is_active ? 'check' : 'times' }} mr-1"></i>
                            {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Featured</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $testimonial->featured ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            <i class="fa-solid fa-{{ $testimonial->featured ? 'star' : 'star-o' }} mr-1"></i>
                            {{ $testimonial->featured ? 'Featured' : 'Regular' }}
                        </span>
                    </div>

                    @if($testimonial->rating)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">({{ $testimonial->rating }}/5)</span>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <p class="text-gray-900">{{ $testimonial->sort_order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-gray-900 text-sm">{{ $testimonial->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>

                    @if($testimonial->updated_at != $testimonial->created_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-900 text-sm">{{ $testimonial->updated_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <button onclick="toggleFeatured({{ $testimonial->id }})"
                            class="w-full bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                        <i class="fa-solid fa-star mr-2"></i>
                        {{ $testimonial->featured ? 'Remove from Featured' : 'Mark as Featured' }}
                    </button>
                    
                    <button onclick="copyQuote()"
                            class="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        <i class="fa-solid fa-copy mr-2"></i>
                        Copy Quote
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFeatured(testimonialId) {
    fetch(`/admin/marketing/testimonial/${testimonialId}/toggle-featured`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function copyQuote() {
    const quote = `"{{ $testimonial->quote }}" - {{ $testimonial->customer_name }}{{ $testimonial->company ? ', ' . $testimonial->company : '' }}`;
    navigator.clipboard.writeText(quote).then(function() {
        // Show a temporary success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fa-solid fa-check mr-2"></i>Copied!';
        button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
        button.classList.add('bg-green-600');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-gray-600', 'hover:bg-gray-700');
        }, 2000);
    });
}
</script>
</x-layouts.admin>
