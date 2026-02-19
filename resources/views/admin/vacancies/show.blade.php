<x-layouts.admin title="Vacancy Details">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-info-circle text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Vacancy Details</h2>
                <p>View vacancy information</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('career.detail', $vacancy->slug) }}" 
               target="_blank"
               class="px-5 py-2 rounded-md bg-green-600 text-white text-sm hover:bg-green-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-external-link-alt"></i>
                <span>View on Site</span>
            </a>
            <a href="{{ route('admin.vacancies.edit', $vacancy) }}" 
               class="px-5 py-2 rounded-md bg-yellow-600 text-white text-sm hover:bg-yellow-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('admin.job-applications.index', ['vacancy_id' => $vacancy->id]) }}" 
               class="px-5 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-file-alt"></i>
                <span>View Applications ({{ $vacancy->applications_count }})</span>
            </a>
            <a href="{{ route('admin.vacancies.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Vacancies</span>
            </a>
        </div>
    </div>

    {{-- Vacancy Details --}}
    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <div class="p-6 space-y-6">
            {{-- Basic Information --}}
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                    Basic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Title</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $vacancy->title }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Slug</label>
                        <code class="bg-white text-gray-800 px-2 py-1 rounded text-xs border border-gray-200">{{ $vacancy->slug }}</code>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Location</label>
                        <p class="text-sm text-gray-900">{{ $vacancy->location }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Department</label>
                        <p class="text-sm text-gray-900">{{ $vacancy->department }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Short Code</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $vacancy->short_code }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Type</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ ucfirst(str_replace('-', ' ', $vacancy->type)) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Salary Range</label>
                        <p class="text-sm text-gray-900">{{ $vacancy->salary_range }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Closing Date</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $vacancy->closing_date < now() ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $vacancy->closing_date->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                <div class="bg-white rounded-md p-4 border border-gray-200">
                    <div class="prose max-w-none">
                        {!! $vacancy->description !!}
                    </div>
                </div>
            </div>

            {{-- Requirements --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Requirements</label>
                <div class="bg-white rounded-md p-4 border border-gray-200">
                    <div class="prose max-w-none">
                        {!! $vacancy->requirements !!}
                    </div>
                </div>
            </div>

            {{-- Responsibilities --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Responsibilities</label>
                <div class="bg-white rounded-md p-4 border border-gray-200">
                    <div class="prose max-w-none">
                        {!! $vacancy->responsibilities !!}
                    </div>
                </div>
            </div>

            {{-- Status and Applications --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $vacancy->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fa-solid {{ $vacancy->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                        {{ $vacancy->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Applications</label>
                    <a href="{{ route('admin.job-applications.index', ['vacancy_id' => $vacancy->id]) }}" 
                       class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors duration-200">
                        {{ $vacancy->applications_count }} application(s)
                    </a>
                </div>
            </div>

            {{-- Timestamps --}}
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-clock mr-2 text-gray-500"></i>
                    Timestamps
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Created At</label>
                        <p class="text-xs text-gray-900">{{ $vacancy->created_at->format('M d, Y H:i:s') }}</p>
                        <p class="text-xs text-gray-500">{{ $vacancy->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Updated At</label>
                        <p class="text-xs text-gray-900">{{ $vacancy->updated_at->format('M d, Y H:i:s') }}</p>
                        <p class="text-xs text-gray-500">{{ $vacancy->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 rounded-b-md flex items-center justify-end space-x-3">
            <a href="{{ route('admin.vacancies.index') }}" 
               class="px-5 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                Back to List
            </a>
            <a href="{{ route('admin.vacancies.edit', $vacancy) }}" 
               class="px-5 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80 transition-colors duration-200">
                Edit Vacancy
            </a>
        </div>
    </div>
</div>
</x-layouts.admin>

