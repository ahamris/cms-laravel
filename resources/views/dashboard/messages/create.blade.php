@extends('front.layouts.app')

@section('title', 'Create New Message')

@section('content')

    {{-- Hero Section --}}
    <section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
        <div class="absolute inset-0 bg-black/80"></div> {{-- Overlay for readability --}}
        <div class="relative container mx-auto px-6 flex justify-between items-center">
            <div class="flex flex-col items-start text-left">
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                    Create New Message
                </h1>
                {{-- Breadcrumbs --}}
                <div class="text-sm text-white text-shadow-amber-200">
                    <span>Dashboard</span>
                    <span class="mx-2">></span>
                    <a href="{{ route('dashboard.messages') }}" class="hover:text-white/80">Message Box</a>
                    <span class="mx-2">></span>
                    <span>Create New Message</span>
                </div>
            </div>
        </div>
    </section>

    <div class="bg-gray-50 font-sans">
        <div class="container mx-auto px-6 py-8">
            <div class="grid grid-cols-12 gap-8">

                {{-- Left Sidebar Navigation --}}
                <div class="col-span-12 lg:col-span-3 space-y-6">
                    {{-- Dashboard Sidebar Component --}}
                    @include('dashboard.partials.sidebar')
                    
                    {{-- Help Information --}}
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 p-6 rounded-lg">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-info text-xl mt-1"></i>
                            <div>
                                <h3 class="font-semibold mb-2">Need Help?</h3>
                                <ul class="text-sm space-y-1">
                                    <li>• Be as specific as possible in your description</li>
                                    <li>• Include relevant details like error messages or screenshots</li>
                                    <li>• Our team typically responds within 24 hours</li>
                                    <li>• You'll receive email notifications for updates</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Content --}}
                <main class="col-span-12 lg:col-span-9">
                    <div class="space-y-8">
                        {{-- Create Message Form --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-6 py-4">
                                <h1 class="text-2xl font-bold text-white">Create New Message</h1>
                                <p class="text-white/90 text-sm mt-1">Submit a message & support request to our team</p>
                            </div>

                            <div class="p-6">
                                <form action="{{ route('dashboard.messages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                    @csrf

                                    {{-- Subject --}}
                                    <div>
                                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fa-solid fa-heading mr-2"></i>
                                            Subject *
                                        </label>
                                        <input type="text" id="subject" name="subject" value="{{ old('subject') }}" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" 
                                               placeholder="Brief description of your issue" 
                                               required>
                                        @error('subject')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Priority --}}
                                    <div>
                                        <label for="ticket_priority_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fa-solid fa-flag mr-2"></i>
                                            Priority *
                                        </label>
                                        <select id="ticket_priority_id" name="ticket_priority_id" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" 
                                                required>
                                            <option value="">Select Priority</option>
                                            @foreach($priorities as $priority)
                                                <option value="{{ $priority->id }}" {{ old('ticket_priority_id') == $priority->id ? 'selected' : '' }}>
                                                    {{ $priority->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('ticket_priority_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Department (Optional) --}}
                                    <div>
                                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fa-solid fa-building mr-2"></i>
                                            Department (Optional)
                                        </label>
                                        <input type="text" id="department" name="department" value="{{ old('department') }}" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors" 
                                               placeholder="Which department should handle this?">
                                        @error('department')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Message --}}
                                    <div>
                                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fa-solid fa-message mr-2"></i>
                                            Message *
                                        </label>
                                        <textarea id="message" name="message" rows="6" 
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors resize-none" 
                                                  placeholder="Describe your issue in detail..." 
                                                  required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- File Attachments --}}
                                    <div>
                                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fa-solid fa-paperclip mr-2"></i>
                                            Attachments (Optional)
                                        </label>
                                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors cursor-pointer" onclick="document.getElementById('attachments').click()">
                                            <div class="space-y-1 text-center">
                                                <i class="fa-solid fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                                                <div class="flex text-sm text-gray-600">
                                                    <span class="font-medium text-primary hover:text-primary/80">Upload files</span>
                                                    <p class="pl-1">or drag them here</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PDF, DOC, DOCX, TXT, JPG, PNG up to 10MB per file</p>
                                            </div>
                                        </div>
                                        <input id="attachments" name="attachments[]" type="file" multiple 
                                               class="hidden" 
                                               accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif"
                                               onchange="handleFileSelection(this)">
                                        
                                        <!-- Selected Files Display -->
                                        <div id="selected-files" class="mt-3 hidden">
                                            <p class="text-sm font-medium text-gray-700 mb-2">Selected files:</p>
                                            <div id="file-list" class="space-y-2"></div>
                                        </div>
                                        
                                        @error('attachments.*')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Form Actions --}}
                                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                        <a href="{{ route('dashboard.messages') }}" 
                                           class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                            <i class="fa-solid fa-arrow-left"></i>
                                            Back to Messages
                                        </a>
                                        <button type="submit" 
                                                class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary/90 transition-colors font-semibold">
                                            <i class="fa-solid fa-paper-plane"></i>
                                            Create Message
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function handleFileSelection(input) {
    const selectedFilesDiv = document.getElementById('selected-files');
    const fileListDiv = document.getElementById('file-list');
    
    if (input.files && input.files.length > 0) {
        // Show the selected files section
        selectedFilesDiv.classList.remove('hidden');
        
        // Clear previous file list
        fileListDiv.innerHTML = '';
        
        // Add each selected file to the list
        Array.from(input.files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200';
            
            const fileInfo = document.createElement('div');
            fileInfo.className = 'flex items-center space-x-3';
            
            const fileIcon = document.createElement('i');
            fileIcon.className = 'fa-solid fa-file text-gray-500';
            
            const fileName = document.createElement('span');
            fileName.className = 'text-sm text-gray-700 font-medium';
            fileName.textContent = file.name;
            
            const fileSize = document.createElement('span');
            fileSize.className = 'text-xs text-gray-500';
            fileSize.textContent = `(${(file.size / 1024).toFixed(1)} KB)`;
            
            fileInfo.appendChild(fileIcon);
            fileInfo.appendChild(fileName);
            fileInfo.appendChild(fileSize);
            
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'text-red-500 hover:text-red-700 text-sm';
            removeButton.innerHTML = '<i class="fa-solid fa-times"></i>';
            removeButton.onclick = function() {
                removeFile(index);
            };
            
            fileItem.appendChild(fileInfo);
            fileItem.appendChild(removeButton);
            fileListDiv.appendChild(fileItem);
        });
    } else {
        // Hide the selected files section if no files
        selectedFilesDiv.classList.add('hidden');
    }
}

function removeFile(index) {
    const input = document.getElementById('attachments');
    const dt = new DataTransfer();
    
    // Add all files except the one to be removed
    Array.from(input.files).forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    // Update the input with the new file list
    input.files = dt.files;
    
    // Refresh the display
    handleFileSelection(input);
}

// Add drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.querySelector('[onclick="document.getElementById(\'attachments\').click()"]');
    
    if (dropZone) {
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('border-primary', 'bg-primary/5');
        });
        
        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-primary', 'bg-primary/5');
        });
        
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-primary', 'bg-primary/5');
            
            const files = e.dataTransfer.files;
            const input = document.getElementById('attachments');
            
            // Create a new FileList with the dropped files
            const dt = new DataTransfer();
            Array.from(files).forEach(file => {
                // Check file type
                const allowedTypes = ['.pdf', '.doc', '.docx', '.txt', '.jpg', '.jpeg', '.png', '.gif'];
                const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
                
                if (allowedTypes.includes(fileExtension)) {
                    dt.items.add(file);
                }
            });
            
            input.files = dt.files;
            handleFileSelection(input);
        });
    }
});
</script>
@endpush
