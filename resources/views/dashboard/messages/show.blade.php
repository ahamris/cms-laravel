@extends('front.layouts.app')

@section('title', 'Message #' . $message->ticket_id)

@section('content')

    {{-- Hero Section --}}
    <section class="relative bg-cover bg-center h-[200px] flex items-center text-white" style="background-image: url('{{ asset('frontend/images/dashboard.jpg') }}')">
        <div class="absolute inset-0 bg-black/80"></div> {{-- Overlay for readability --}}
        <div class="relative container mx-auto px-6 flex justify-between items-center">
            <div class="flex flex-col items-start text-left">
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight max-w-4xl pb-4">
                    Message #{{ $message->ticket_id }}
                </h1>
                {{-- Breadcrumbs --}}
                <div class="text-sm text-white text-shadow-amber-200">
                    <span>Dashboard</span>
                    <span class="mx-2">></span>
                    <a href="{{ route('dashboard.messages') }}" class="hover:text-white/80">Message Box</a>
                    <span class="mx-2">></span>
                    <span>Message #{{ $message->ticket_id }}</span>
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
                        {{-- Message Details --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-primary px-4 py-2">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h1 class="text-2xl font-bold text-white">{{ $message->subject }}</h1>
                                        <p class="text-white/90 text-sm mt-1">Message #{{ $message->ticket_id }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if($message->status)
                                            <span class="bg-white text-{{ $message->status->color }} px-3 py-1 rounded-full text-sm font-semibold">
                                                {{ $message->status->name }}
                                            </span>
                                        @endif
                                        @if($message->priority)
                                            <span class="bg-white text-{{ $message->priority->color }} px-3 py-1 rounded-full text-sm font-semibold">
                                                {{ $message->priority->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                {{-- Message Info --}}
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pb-6 border-b border-gray-200">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500 mb-1">Created Date</h3>
                                        <p class="text-gray-900">{{ $message->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    @if($message->due_date)
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-500 mb-1">Due Date</h3>
                                            <p class="text-gray-900">{{ $message->due_date->format('M d, Y H:i') }}</p>
                                        </div>
                                    @endif
                                    @if($message->department)
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-500 mb-1">Department</h3>
                                            <p class="text-gray-900">{{ $message->department }}</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Original Message --}}
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Original Message</h3>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <p class="text-gray-700 whitespace-pre-line">{{ $message->message }}</p>
                                    </div>
                                </div>

                                {{-- Attachments --}}
                                @if($message->attachments && count($message->attachments) > 0)
                                    <div class="mb-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Attachments</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($message->attachments as $attachment)
                                                <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors">
                                                    <div class="flex items-center gap-3">
                                                        <i class="fa-solid fa-file text-gray-500 text-xl"></i>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment['original_name'] }}</p>
                                                            <p class="text-xs text-gray-500">{{ number_format($attachment['file_size'] / 1024, 1) }} KB</p>
                                                        </div>
                                                        <a href="{{ asset('storage/' . $attachment['file_path']) }}" 
                                                           target="_blank"
                                                           class="text-primary hover:text-primary/80 transition-colors">
                                                            <i class="fa-solid fa-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Replies --}}
                                @if($message->replies && $message->replies->count() > 0)
                                    <div class="mb-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Replies</h3>
                                        <div class="space-y-4">
                                            @foreach($message->replies->sortBy('created_at') as $reply)
                                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <div class="flex items-center gap-2">
                                                            @if($reply->user_id)
                                                                <i class="fa-solid fa-user-tie text-blue-600"></i>
                                                                <span class="text-sm font-medium text-blue-900">
                                                                    {{ $reply->user->name }} (Support Team)
                                                                </span>
                                                            @else
                                                                <i class="fa-solid fa-user text-green-600"></i>
                                                                <span class="text-sm font-medium text-green-900">
                                                                    {{ $message->user->name }} (You)
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <span class="text-xs text-gray-500">{{ $reply->created_at->format('M d, Y H:i') }}</span>
                                                    </div>
                                                    <p class="text-gray-700 whitespace-pre-line">{{ $reply->message }}</p>
                                                    
                                                    @if($reply->attachments && count($reply->attachments) > 0)
                                                        <div class="mt-3 pt-3 border-t border-gray-300">
                                                            <p class="text-sm font-medium text-gray-700 mb-2">Attachments:</p>
                                                            <div class="space-y-2">
                                                                @foreach($reply->attachments as $attachment)
                                                                    <div class="flex items-center gap-2 p-2 bg-gray-100 rounded-lg">
                                                                        <i class="fa-solid fa-paperclip text-gray-600"></i>
                                                                        <a href="{{ asset('storage/' . $attachment['file_path']) }}" 
                                                                           target="_blank" 
                                                                           class="text-sm text-gray-800 hover:text-gray-900 font-medium">
                                                                            {{ $attachment['original_name'] }}
                                                                        </a>
                                                                        <span class="text-xs text-gray-600">
                                                                            ({{ number_format($attachment['file_size'] / 1024, 1) }} KB)
                                                                        </span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Reply Form - Only show if message is not closed --}}
                                @if($message->status && $message->status->name !== 'Closed')
                                <div class="pt-6 border-t border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Reply</h3>
                                    <form action="{{ route('dashboard.messages.reply', $message) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        
                                        <div>
                                            <label for="reply_message" class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fa-solid fa-reply mr-2"></i>
                                                Your Reply
                                            </label>
                                            <textarea id="reply_message" name="message" rows="4" 
                                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-colors resize-none"
                                                      placeholder="Type your reply here..." 
                                                      required></textarea>
                                            @error('message')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="reply_attachments" class="block text-sm font-medium text-gray-700 mb-2">Attachments (optional)</label>
                                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors cursor-pointer" onclick="document.getElementById('reply_attachments').click()">
                                                <div class="space-y-1 text-center">
                                                    <i class="fa-solid fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                                                    <div class="flex text-sm text-gray-600">
                                                        <span class="font-medium text-primary hover:text-primary/80">Upload files</span>
                                                        <p class="pl-1">or drag them here</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX, TXT, JPG, PNG up to 10MB per file</p>
                                                </div>
                                            </div>
                                            <input id="reply_attachments" name="attachments[]" type="file" multiple 
                                                   class="hidden" 
                                                   accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif"
                                                   onchange="handleReplyFileSelection(this)">
                                            
                                            <!-- Selected Files Display -->
                                            <div id="reply-selected-files" class="mt-3 hidden">
                                                <p class="text-sm font-medium text-gray-700 mb-2">Selected files:</p>
                                                <div id="reply-file-list" class="space-y-2"></div>
                                            </div>
                                            
                                            @error('attachments.*')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="flex items-center justify-between">
                                            <a href="{{ route('dashboard.messages') }}" 
                                               class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                                <i class="fa-solid fa-arrow-left"></i>
                                                Back to Message Box
                                            </a>
                                            <button type="submit" 
                                                    class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary/90 transition-colors flex items-center space-x-2 font-semibold">
                                                <i class="fa-solid fa-paper-plane"></i>
                                                <span>Send Reply</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                @else
                                {{-- Closed Message --}}
                                <div class="pt-6 border-t border-gray-200">
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-300 rounded-lg p-4">
                                        {{-- Header Section --}}
                                        <div class="text-center mb-4">
                                            <div class="w-12 h-12 mx-auto mb-2 bg-gray-200 rounded-full flex items-center justify-center">
                                                <i class="fa-solid fa-lock text-gray-500 text-lg"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-800 mb-1">Message Closed</h3>
                                            <p class="text-sm text-gray-600">This message has been closed and no longer accepts new replies.</p>
                                        </div>
                                        
                                        @php
                                            $canBeReopened = $message->canBeReopened();
                                        @endphp
                                        
                                        {{-- Content Section - 2 Columns --}}
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                            {{-- Left Column - Reopen Section --}}
                                            <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                                                @if($canBeReopened)
                                                    @php
                                                        $lastReply = $message->replies->sortByDesc('created_at')->first();
                                                        $lastReplyDate = $lastReply ? $lastReply->created_at : $message->created_at;
                                                        $reopenDeadline = $lastReplyDate->copy()->addDays(14);
                                                    @endphp
                                                    <div class="text-center">
                                                        <div class="w-8 h-8 mx-auto mb-2 bg-green-100 rounded-full flex items-center justify-center">
                                                            <i class="fa-solid fa-clock text-green-600 text-sm"></i>
                                                        </div>
                                                        <h4 class="text-sm font-semibold text-gray-800 mb-2">Still Time to Reopen</h4>
                                                        <div class="bg-gray-50 rounded p-2 mb-3">
                                                            <p class="text-sm font-bold text-primary">
                                                                {{ $reopenDeadline->format('M d, Y \a\t H:i') }}
                                                            </p>
                                                        </div>
                                                        <form action="{{ route('dashboard.messages.reopen', $message) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="w-full inline-flex items-center justify-center gap-1 px-4 py-2 border border-transparent rounded text-white bg-primary hover:bg-primary/90 transition-colors text-sm font-medium">
                                                                <i class="fa-solid fa-unlock"></i>
                                                                Reopen Message
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="flex flex-col items-center justify-center h-full min-h-[120px]">
                                                        <div class="w-8 h-8 mx-auto mb-2 bg-red-100 rounded-full flex items-center justify-center">
                                                            <i class="fa-solid fa-hourglass-end text-red-600 text-sm"></i>
                                                        </div>
                                                        <h4 class="text-sm font-semibold text-gray-800 mb-2 text-center">Reopen Period Expired</h4>
                                                        <p class="text-xs text-gray-500 text-center">
                                                            The 14-day reopen period has ended.
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            {{-- Right Column - Actions Section --}}
                                            <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                                                <div class="text-center">
                                                    <div class="w-8 h-8 mx-auto mb-2 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <i class="fa-solid fa-list text-blue-600 text-sm"></i>
                                                    </div>
                                                    <h4 class="text-sm font-semibold text-gray-800 mb-2">What's Next?</h4>
                                                    <div class="space-y-2">
                                                        <a href="{{ route('dashboard.messages.create') }}" 
                                                           class="block w-full inline-flex items-center justify-center gap-1 px-3 py-2 border border-primary text-primary rounded hover:bg-primary hover:text-white transition-colors text-sm font-medium">
                                                            <i class="fa-solid fa-plus"></i>
                                                            New Message
                                                        </a>
                                                        <a href="{{ route('dashboard.messages') }}" 
                                                           class="block w-full inline-flex items-center justify-center gap-1 px-3 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition-colors text-sm font-medium">
                                                            <i class="fa-solid fa-arrow-left"></i>
                                                            Back to Messages
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
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
function handleReplyFileSelection(input) {
    const selectedFilesDiv = document.getElementById('reply-selected-files');
    const fileListDiv = document.getElementById('reply-file-list');
    
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
                removeReplyFile(index);
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

function removeReplyFile(index) {
    const input = document.getElementById('reply_attachments');
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
    handleReplyFileSelection(input);
}

// Add drag and drop functionality for reply
document.addEventListener('DOMContentLoaded', function() {
    const replyDropZone = document.querySelector('[onclick="document.getElementById(\'reply_attachments\').click()"]');
    
    if (replyDropZone) {
        replyDropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            replyDropZone.classList.add('border-primary', 'bg-primary/5');
        });
        
        replyDropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            replyDropZone.classList.remove('border-primary', 'bg-primary/5');
        });
        
        replyDropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            replyDropZone.classList.remove('border-primary', 'bg-primary/5');
            
            const files = e.dataTransfer.files;
            const input = document.getElementById('reply_attachments');
            
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
            handleReplyFileSelection(input);
        });
    }
});
</script>
@endpush
