<x-layouts.admin title="Edit Vacancy">
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-md flex items-center justify-center">
                <i class="fa-solid fa-edit text-white text-base"></i>
            </div>
            <div class="flex flex-col gap-1">
                <h2>Edit Vacancy</h2>
                <p>Update vacancy information</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.vacancies.show', $vacancy) }}" 
               class="px-5 py-2 rounded-md bg-blue-600 text-white text-sm hover:bg-blue-700 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-eye"></i>
                <span>View</span>
            </a>
            <a href="{{ route('admin.vacancies.index') }}" 
               class="px-5 py-2 rounded-md bg-white border border-gray-200 text-gray-700 text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Vacancies</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-50/50 rounded-md border border-gray-200">
        <form action="{{ route('admin.vacancies.update', $vacancy) }}" method="POST" id="vacancyForm">
            @csrf
            @method('PUT')
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Basic Information Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fa-solid fa-info-circle mr-2 text-blue-500"></i>
                                Basic Information
                            </h3>
                            {{-- Title --}}
                            <div>
                                <label for="title" class="block text-xs font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title', $vacancy->title) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('title') border-red-500 @enderror" placeholder="Enter job title" required>
                                @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Slug --}}
                            <x-ui.input
                                id="slug"
                                name="slug"
                                :value="old('slug', $vacancy->slug)"
                                slug-from="title"
                                label="Slug"
                                placeholder="url-friendly-slug"
                                hint="URL-friendly version of the title. Auto-generated from title if left blank."
                            />

                            {{-- Location --}}
                            <div>
                                <label for="location" class="block text-xs font-medium text-gray-700 mb-1">Location <span class="text-red-500">*</span></label>
                                <input type="text" id="location" name="location" value="{{ old('location', $vacancy->location) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('location') border-red-500 @enderror" placeholder="e.g., Amsterdam, Netherlands" required>
                                @error('location')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Department --}}
                            <div>
                                <label for="department" class="block text-xs font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                                <input type="text" id="department" name="department" value="{{ old('department', $vacancy->department) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('department') border-red-500 @enderror" placeholder="e.g., Engineering, Marketing" required>
                                @error('department')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Short Code --}}
                            <div>
                                <label for="short_code" class="block text-xs font-medium text-gray-700 mb-1">Short Code <span class="text-red-500">*</span></label>
                                <select id="short_code" name="short_code" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('short_code') border-red-500 @enderror" required>
                                    <option value="">Select a code</option>
                                    <option value="BE" {{ old('short_code', $vacancy->short_code) == 'BE' ? 'selected' : '' }}>BE - Backend</option>
                                    <option value="FE" {{ old('short_code', $vacancy->short_code) == 'FE' ? 'selected' : '' }}>FE - Frontend</option>
                                    <option value="MM" {{ old('short_code', $vacancy->short_code) == 'MM' ? 'selected' : '' }}>MM - Marketing</option>
                                    <option value="DO" {{ old('short_code', $vacancy->short_code) == 'DO' ? 'selected' : '' }}>DO - DevOps</option>
                                    <option value="QA" {{ old('short_code', $vacancy->short_code) == 'QA' ? 'selected' : '' }}>QA - Quality Assurance</option>
                                    <option value="AI" {{ old('short_code', $vacancy->short_code) == 'AI' ? 'selected' : '' }}>AI - AI/ML</option>
                                    <option value="HR" {{ old('short_code', $vacancy->short_code) == 'HR' ? 'selected' : '' }}>HR - Human Resources</option>
                                    <option value="IT" {{ old('short_code', $vacancy->short_code) == 'IT' ? 'selected' : '' }}>IT - Information Technology</option>
                                    <option value="PM" {{ old('short_code', $vacancy->short_code) == 'PM' ? 'selected' : '' }}>PM - Project Management</option>
                                </select>
                                @error('short_code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Type --}}
                            <div>
                                <label for="type" class="block text-xs font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                                <select id="type" name="type" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('type') border-red-500 @enderror" required>
                                    <option value="">Select a type</option>
                                    <option value="full-time" {{ old('type', $vacancy->type) == 'full-time' ? 'selected' : '' }}>Full-time</option>
                                    <option value="part-time" {{ old('type', $vacancy->type) == 'part-time' ? 'selected' : '' }}>Part-time</option>
                                    <option value="contract" {{ old('type', $vacancy->type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="remote" {{ old('type', $vacancy->type) == 'remote' ? 'selected' : '' }}>Remote</option>
                                    <option value="project-based" {{ old('type', $vacancy->type) == 'project-based' ? 'selected' : '' }}>Project-based</option>
                                </select>
                                @error('type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Hours Per Week --}}
                            <div>
                                <label for="hours_per_week" class="block text-xs font-medium text-gray-700 mb-1">Hours Per Week</label>
                                <input type="text" id="hours_per_week" name="hours_per_week" value="{{ old('hours_per_week', $vacancy->hours_per_week) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('hours_per_week') border-red-500 @enderror" placeholder="e.g., 32 - 40 uur p/w">
                                @error('hours_per_week')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Experience Level --}}
                            <div>
                                <label for="experience_level" class="block text-xs font-medium text-gray-700 mb-1">Experience Level</label>
                                <input type="text" id="experience_level" name="experience_level" value="{{ old('experience_level', $vacancy->experience_level) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('experience_level') border-red-500 @enderror" placeholder="e.g., Geen maximum, 3+ jaar">
                                @error('experience_level')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Category --}}
                            <div>
                                <label for="category" class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                                <input type="text" id="category" name="category" value="{{ old('category', $vacancy->category) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('category') border-red-500 @enderror" placeholder="e.g., ICT, Financieel, Communicatie">
                                @error('category')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Salary Range --}}
                            <div>
                                <label for="salary_range" class="block text-xs font-medium text-gray-700 mb-1">Salary Range <span class="text-red-500">*</span></label>
                                <input type="text" id="salary_range" name="salary_range" value="{{ old('salary_range', $vacancy->salary_range) }}" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none @error('salary_range') border-red-500 @enderror" placeholder="e.g., €50,000 - €70,000" required>
                                @error('salary_range')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            {{-- Closing Date --}}
                            <div>
                                <label for="closing_date" class="block text-xs font-medium text-gray-700 mb-1">Closing Date <span class="text-red-500">*</span></label>
                                <x-ui.date-picker
                                    id="closing_date"
                                    name="closing_date"
                                    :value="old('closing_date', $vacancy->closing_date?->format('Y-m-d'))"
                                    placeholder="Select closing date"
                                    :required="true"
                                />
                                @error('closing_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Description Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fa-solid fa-file-alt mr-2 text-green-500"></i>
                                Description
                            </h3>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                                <x-editor id="description" name="description" :value="$vacancy->description" placeholder="Enter job description..." />
                                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Requirements Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fa-solid fa-list-check mr-2 text-purple-500"></i>
                                Requirements
                            </h3>
                            <div>
                                <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">Requirements <span class="text-red-500">*</span></label>
                                <x-editor id="requirements" name="requirements" :value="$vacancy->requirements" placeholder="Enter job requirements..." />
                                @error('requirements')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Responsibilities Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fa-solid fa-tasks mr-2 text-orange-500"></i>
                                Responsibilities
                            </h3>
                            <div>
                                <label for="responsibilities" class="block text-sm font-medium text-gray-700 mb-2">Responsibilities <span class="text-red-500">*</span></label>
                                <x-editor id="responsibilities" name="responsibilities" :value="$vacancy->responsibilities" placeholder="Enter job responsibilities..." />
                                @error('responsibilities')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="lg:col-span-1 space-y-6">
                        {{-- Publishing Card --}}
                        <div class="bg-gray-50/50 rounded-md border border-gray-200 p-6 space-y-6">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fa-solid fa-paper-plane mr-2 text-purple-500"></i>
                                Publishing
                            </h3>
                            {{-- Status --}}
                            <div>
                                <x-ui.toggle 
                                    name="is_active"
                                    :checked="old('is_active', $vacancy->is_active) == 1 || old('is_active', $vacancy->is_active) == '1'"
                                    label="Status"
                                    :required="true"
                                />
                                @error('is_active')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 rounded-b-md flex items-center justify-end space-x-3">
                <a href="{{ route('admin.vacancies.index') }}" 
                   class="px-5 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-5 py-2 text-sm text-white bg-primary rounded-md hover:bg-primary/80 transition-colors duration-200">
                    Update Vacancy
                </button>
            </div>
        </form>
    </div>
</div>
</x-layouts.admin>

