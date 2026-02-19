<x-layouts.admin title="Job Application Details">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-info-circle text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Job Application Details</h2>
                <p>View application information</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.job-applications.edit', $jobApplication) }}" 
               class="px-5 py-2 rounded-md bg-yellow-600 text-white text-sm hover:bg-yellow-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-edit"></i>
                <span>Edit</span>
            </a>
            @if($jobApplication->vacancy)
                <a href="{{ route('admin.vacancies.show', $jobApplication->vacancy) }}" 
                   class="px-5 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 transition-colors duration-200 flex items-center space-x-2">
                    <i class="fa-solid fa-briefcase"></i>
                    <span>View Vacancy</span>
                </a>
            @endif
            <a href="{{ route('admin.job-applications.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Applications</span>
            </a>
        </div>
    </div>

    {{-- Application Details --}}
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
                        <label class="block text-xs font-medium text-gray-700 mb-1">Name</label>
                        <p class="text-sm text-gray-900 font-medium">{{ $jobApplication->name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-sm text-gray-900">{{ $jobApplication->email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                        <p class="text-sm text-gray-900">{{ $jobApplication->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Vacancy</label>
                        @if($jobApplication->vacancy)
                            <a href="{{ route('admin.vacancies.show', $jobApplication->vacancy) }}" 
                               class="text-sm text-primary hover:text-primary/80 transition-colors duration-200">
                                {{ $jobApplication->vacancy->title }}
                            </a>
                        @else
                            <span class="text-sm text-gray-500">N/A</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Status and Processed --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'reviewed' => 'bg-blue-100 text-blue-800',
                            'shortlisted' => 'bg-purple-100 text-purple-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'hired' => 'bg-green-100 text-green-800',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$jobApplication->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($jobApplication->status) }}
                    </span>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Processed</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jobApplication->is_processed ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        <i class="fa-solid {{ $jobApplication->is_processed ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                        {{ $jobApplication->is_processed ? 'Processed' : 'Not Processed' }}
                    </span>
                </div>
            </div>

            {{-- Cover Letter --}}
            @if($jobApplication->cover_letter)
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cover Letter</label>
                    <div class="bg-white rounded-md p-4 border border-gray-200">
                        <div class="prose max-w-none">
                            {!! $jobApplication->cover_letter !!}
                        </div>
                    </div>
                </div>
            @endif

            {{-- Links --}}
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-link mr-2 text-green-500"></i>
                    Links & Resources
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($jobApplication->resume_path)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Resume</label>
                            <a href="{{ Storage::url($jobApplication->resume_path) }}" 
                               target="_blank"
                               class="text-sm text-primary hover:text-primary/80 transition-colors duration-200 flex items-center space-x-2">
                                <i class="fa-solid fa-file-pdf"></i>
                                <span>View Resume</span>
                            </a>
                        </div>
                    @endif
                    @if($jobApplication->linkedin_url)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">LinkedIn</label>
                            <a href="{{ $jobApplication->linkedin_url }}" 
                               target="_blank"
                               class="text-sm text-primary hover:text-primary/80 transition-colors duration-200 flex items-center space-x-2">
                                <i class="fa-brands fa-linkedin"></i>
                                <span>View Profile</span>
                            </a>
                        </div>
                    @endif
                    @if($jobApplication->portfolio_url)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Portfolio</label>
                            <a href="{{ $jobApplication->portfolio_url }}" 
                               target="_blank"
                               class="text-sm text-primary hover:text-primary/80 transition-colors duration-200 flex items-center space-x-2">
                                <i class="fa-solid fa-globe"></i>
                                <span>View Portfolio</span>
                            </a>
                        </div>
                    @endif
                    @if($jobApplication->repo_url)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Repository</label>
                            <a href="{{ $jobApplication->repo_url }}" 
                               target="_blank"
                               class="text-sm text-primary hover:text-primary/80 transition-colors duration-200 flex items-center space-x-2">
                                <i class="fa-brands fa-github"></i>
                                <span>View Repository</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Notes --}}
            @if($jobApplication->notes)
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                    <div class="bg-white rounded-md p-4 border border-gray-200">
                        <p class="text-xs text-gray-900 whitespace-pre-wrap">{{ $jobApplication->notes }}</p>
                    </div>
                </div>
            @endif

            {{-- Timestamps --}}
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-clock mr-2 text-gray-500"></i>
                    Timestamps
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Applied At</label>
                        <p class="text-xs text-gray-900">{{ $jobApplication->created_at->format('M d, Y H:i:s') }}</p>
                        <p class="text-xs text-gray-500">{{ $jobApplication->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Updated At</label>
                        <p class="text-xs text-gray-900">{{ $jobApplication->updated_at->format('M d, Y H:i:s') }}</p>
                        <p class="text-xs text-gray-500">{{ $jobApplication->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 rounded-b-md flex items-center justify-end space-x-3">
            <a href="{{ route('admin.job-applications.index') }}" 
               class="px-5 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                Back to List
            </a>
            <a href="{{ route('admin.job-applications.edit', $jobApplication) }}" 
               class="px-5 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80 transition-colors duration-200">
                Edit Application
            </a>
        </div>
    </div>
</div>
</x-layouts.admin>

