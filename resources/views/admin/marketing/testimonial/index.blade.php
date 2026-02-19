<x-layouts.admin title="Marketing Testimonials">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Marketing Testimonials</h1>
            <p class="text-gray-600">Manage customer testimonials and social proof</p>
        </div>
        <a href="{{ route('admin.marketing.testimonial.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-plus mr-2"></i>
            Create New Testimonial
        </a>
    </div>

    {{-- Testimonials Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($testimonials->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quote</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($testimonials as $testimonial)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($testimonial->photo)
                                            <img class="h-10 w-10 rounded-full mr-3" src="{{ asset('storage/' . $testimonial->photo) }}" alt="{{ $testimonial->customer_name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                <i class="fa-solid fa-user text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $testimonial->customer_name }}</div>
                                            @if($testimonial->company)
                                                <div class="text-sm text-gray-500">{{ $testimonial->company }}</div>
                                            @endif
                                            @if($testimonial->position)
                                                <div class="text-xs text-gray-400">{{ $testimonial->position }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ Str::limit($testimonial->quote, 80) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($testimonial->rating)
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-solid fa-star {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600">({{ $testimonial->rating }})</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">No rating</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $testimonial->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $testimonial->featured ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $testimonial->featured ? 'Featured' : 'Regular' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.marketing.testimonial.show', $testimonial) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.marketing.testimonial.edit', $testimonial) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button onclick="toggleFeatured({{ $testimonial->id }})"
                                                class="text-yellow-600 hover:text-yellow-900"
                                                title="Toggle Featured">
                                            <i class="fa-solid fa-star"></i>
                                        </button>
                                        <button onclick="deleteTestimonial({{ $testimonial->id }})"
                                                class="text-red-600 hover:text-red-900"
                                                title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fa-solid fa-quote-right text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">No testimonials found. Create your first testimonial!</p>
            </div>
        @endif
    </div>
</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center mb-4">
                <i class="fa-solid fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Delete Testimonial</h3>
            </div>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete this testimonial? This action cannot be undone.
            </p>
            <div class="flex items-center justify-end space-x-4">
                <button type="button"
                        onclick="document.getElementById('deleteModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteTestimonial(testimonialId) {
    document.getElementById('deleteForm').action = `/admin/marketing/testimonial/${testimonialId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

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
</script>
</x-layouts.admin>
