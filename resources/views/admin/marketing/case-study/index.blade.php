<x-layouts.admin title="Case Studies">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Case Studies</h1>
            <p class="text-gray-600">Manage customer success stories and case studies</p>
        </div>
        <a href="{{ route('admin.marketing.case-study.create') }}"
           class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors duration-200">
            <i class="fa-solid fa-plus mr-2"></i>
            Create New Case Study
        </a>
    </div>

    {{-- Case Studies Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($caseStudies->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Case Study</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Features</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metrics</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($caseStudies as $caseStudy)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        <div class="h-10 w-10 rounded-lg bg-green-50 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="fa-solid fa-chart-line text-green-600"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $caseStudy->title }}</div>
                                            @if($caseStudy->challenge)
                                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($caseStudy->challenge, 80) }}</div>
                                            @endif
                                            <div class="text-xs text-gray-400 mt-1">{{ $caseStudy->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($caseStudy->client_logo)
                                            <img src="{{ asset('storage/' . $caseStudy->client_logo) }}" 
                                                 alt="{{ $caseStudy->client_name }}" 
                                                 class="h-8 w-8 rounded object-contain mr-3">
                                        @else
                                            <div class="h-8 w-8 rounded bg-gray-200 flex items-center justify-center mr-3">
                                                <i class="fa-solid fa-building text-gray-400 text-xs"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $caseStudy->client_name }}</div>
                                            @if($caseStudy->client_industry)
                                                <div class="text-xs text-gray-500">{{ $caseStudy->client_industry }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($caseStudy->productFeatures && $caseStudy->productFeatures->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($caseStudy->productFeatures->take(2) as $feature)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    @if($feature->icon)
                                                        <i class="fa-solid {{ $feature->icon }} mr-1"></i>
                                                    @endif
                                                    {{ $feature->name }}
                                                </span>
                                            @endforeach
                                            @if($caseStudy->productFeatures->count() > 2)
                                                <span class="text-xs text-gray-500">+{{ $caseStudy->productFeatures->count() - 2 }} more</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">No features</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($caseStudy->metrics && count($caseStudy->metrics) > 0)
                                        <div class="text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-chart-bar mr-1 text-green-600"></i>
                                                {{ count($caseStudy->metrics) }} metrics
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ Str::limit($caseStudy->metrics[0] ?? '', 30) }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">No metrics</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $caseStudy->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $caseStudy->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        @if($caseStudy->is_featured)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fa-solid fa-star mr-1"></i>Featured
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.marketing.case-study.show', $caseStudy) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.marketing.case-study.edit', $caseStudy) }}"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button onclick="deleteCaseStudy({{ $caseStudy->id }})"
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
                <i class="fa-solid fa-chart-line text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">No case studies found. Create your first case study!</p>
            </div>
        @endif
    </div>

    {{-- Statistics --}}
    @if($caseStudies->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-green-50 flex items-center justify-center">
                        <i class="fa-solid fa-chart-line text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Case Studies</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $caseStudies->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="fa-solid fa-check-circle text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Studies</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $caseStudies->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-yellow-50 flex items-center justify-center">
                        <i class="fa-solid fa-star text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Featured</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $caseStudies->where('is_featured', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-purple-50 flex items-center justify-center">
                        <i class="fa-solid fa-building text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Unique Clients</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $caseStudies->unique('client_name')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Delete Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center mb-4">
                <i class="fa-solid fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Delete Case Study</h3>
            </div>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete this case study? This action cannot be undone and will also delete any associated images.
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
function deleteCaseStudy(caseStudyId) {
    document.getElementById('deleteForm').action = `/admin/marketing/case-study/${caseStudyId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}
</script>
</x-layouts.admin>
