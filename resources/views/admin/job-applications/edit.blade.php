<x-layouts.admin title="Edit Job Application">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-edit text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Edit Job Application</h2>
                <p>Update application information</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.job-applications.show', $jobApplication) }}" 
               class="px-5 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-eye"></i>
                <span>View</span>
            </a>
            <a href="{{ route('admin.job-applications.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Applications</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <form action="{{ route('admin.job-applications.update', $jobApplication) }}" method="POST" id="applicationForm">
            @csrf
            @method('PUT')
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Applicant Information Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fa-solid fa-user mr-2 text-blue-500"></i>
                                Applicant Information
                            </h3>
                            {{-- Vacancy --}}
                            <div>
                                <label for="vacancy_id" class="block text-xs font-medium text-gray-700 mb-1">Vacancy <span class="text-red-500">*</span></label>
                                <select id="vacancy_id" name="vacancy_id" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('vacancy_id') border-red-500 @enderror" required>
                                    <option value="">Select a vacancy</option>
                                    @foreach($vacancies as $vacancy)
                                        <option value="{{ $vacancy->id }}" {{ old('vacancy_id', $jobApplication->vacancy_id) == $vacancy->id ? 'selected' : '' }}>{{ $vacancy->title }}</option>
                                    @endforeach
                                </select>
                                @error('vacancy_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Name --}}
                            <div>
                                <label for="name" class="block text-xs font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name', $jobApplication->name) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('name') border-red-500 @enderror" placeholder="Enter applicant name" required>
                                @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email', $jobApplication->email) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('email') border-red-500 @enderror" placeholder="Enter email address" required>
                                @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $jobApplication->phone) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('phone') border-red-500 @enderror" placeholder="Enter phone number">
                                @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Cover Letter --}}
                            <div>
                                <label for="cover_letter" class="block text-sm font-medium text-gray-700 mb-2">Cover Letter</label>
                                <x-editor id="cover_letter" name="cover_letter" :value="$jobApplication->cover_letter" placeholder="Enter cover letter..." />
                                @error('cover_letter')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Resume Path --}}
                            <div>
                                <label for="resume_path" class="block text-xs font-medium text-gray-700 mb-1">Resume Path</label>
                                <input type="text" id="resume_path" name="resume_path" value="{{ old('resume_path', $jobApplication->resume_path) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('resume_path') border-red-500 @enderror" placeholder="Enter resume file path">
                                @error('resume_path')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- LinkedIn URL --}}
                            <div>
                                <label for="linkedin_url" class="block text-xs font-medium text-gray-700 mb-1">LinkedIn URL</label>
                                <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $jobApplication->linkedin_url) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('linkedin_url') border-red-500 @enderror" placeholder="https://linkedin.com/in/...">
                                @error('linkedin_url')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Portfolio URL --}}
                            <div>
                                <label for="portfolio_url" class="block text-xs font-medium text-gray-700 mb-1">Portfolio URL</label>
                                <input type="url" id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url', $jobApplication->portfolio_url) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('portfolio_url') border-red-500 @enderror" placeholder="https://portfolio.com/...">
                                @error('portfolio_url')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Repository URL --}}
                            <div>
                                <label for="repo_url" class="block text-xs font-medium text-gray-700 mb-1">Repository URL</label>
                                <input type="url" id="repo_url" name="repo_url" value="{{ old('repo_url', $jobApplication->repo_url) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('repo_url') border-red-500 @enderror" placeholder="https://github.com/...">
                                @error('repo_url')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="lg:col-span-1 space-y-6">
                        {{-- Status Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fa-solid fa-paper-plane mr-2 text-purple-500"></i>
                                Status & Notes
                            </h3>
                            {{-- Status --}}
                            <div>
                                <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                <select id="status" name="status" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('status') border-red-500 @enderror" required>
                                    <option value="">Select a status</option>
                                    <option value="pending" {{ old('status', $jobApplication->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="reviewed" {{ old('status', $jobApplication->status) == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                    <option value="shortlisted" {{ old('status', $jobApplication->status) == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                    <option value="rejected" {{ old('status', $jobApplication->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="hired" {{ old('status', $jobApplication->status) == 'hired' ? 'selected' : '' }}>Hired</option>
                                </select>
                                @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Processed --}}
                            <div>
                                <x-ui.toggle 
                                    name="is_processed"
                                    :checked="old('is_processed', $jobApplication->is_processed) == 1 || old('is_processed', $jobApplication->is_processed) == '1'"
                                    label="Processed"
                                    :required="false"
                                />
                                @error('is_processed')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label for="notes" class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                                <textarea id="notes" name="notes" rows="4" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('notes') border-red-500 @enderror" placeholder="Internal notes about this application...">{{ old('notes', $jobApplication->notes) }}</textarea>
                                @error('notes')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 rounded-b-md flex items-center justify-end space-x-3">
                <a href="{{ route('admin.job-applications.index') }}" 
                   class="px-5 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-5 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80 transition-colors duration-200">
                    Update Application
                </button>
            </div>
        </form>
    </div>
</div>
</x-layouts.admin>

