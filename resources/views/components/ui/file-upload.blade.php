@props([
    'name',
    'value' => '',
    'deleteName' => null,
    'label' => 'Upload a file',
    'accept' => 'image/png, image/jpeg, image/jpg, image/gif',
    'maxSize' => '10MB',
])

<div 
    x-data="fileUpload('{{ $value }}')" 
    :class="{
        'border-orange-500 dark:border-pink-500': imageUrl,
        'border-primary bg-primary/5 dark:border-primary dark:bg-primary/10': isDragging,
        'border-gray-300 dark:border-zinc-700': !imageUrl && !fileName && !isDragging
    }"
    x-on:dragover.prevent="isDragging = true"
    x-on:dragleave.prevent="isDragging = false"
    x-on:drop.prevent="handleDrop($event)"
    class="flex justify-center items-center border-2 border-dashed rounded-md h-48 overflow-hidden relative transition-colors hover:border-gray-400 dark:hover:border-zinc-600"
>
    <template x-if="!imageUrl && !fileName">
        <div class="space-y-1 text-center px-6 pt-5 pb-6 w-full h-full flex flex-col justify-center items-center">
            <svg aria-hidden="true" class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            </svg>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <label class="relative cursor-pointer bg-transparent rounded-md font-medium text-orange-500 dark:text-pink-500 hover:underline focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500" for="{{ $name }}">
                    <span>{{ $label }}</span>
                </label>
                <span class="pl-1">or drag and drop</span>
            </div>
            <p class="text-xs text-gray-500">{{ $accept }} up to {{ $maxSize }}</p>
        </div>
    </template>

    <template x-if="imageUrl || fileName">
        <div class="w-full h-full relative group">
            <div 
                class="absolute inset-0 flex flex-col justify-center items-center gap-2 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10"
            >
                <div class="flex gap-2">
                    <button 
                        type="button"
                        x-on:click="$refs.fileInput.click()"
                        class="bg-white text-zinc-900 px-3 py-1.5 rounded-md text-sm font-medium hover:bg-zinc-100 transition-colors shadow-sm"
                    >
                        Change
                    </button>
                    <button 
                        type="button"
                        x-on:click="removeImage"
                        class="bg-red-600 text-white px-3 py-1.5 rounded-md text-sm font-medium hover:bg-red-700 transition-colors shadow-sm"
                    >
                        Remove
                    </button>
                </div>
                <p class="text-white text-xs mt-2 font-medium drop-shadow-md">Click to change or remove</p>
            </div>
            
            <template x-if="isImage && imageUrl">
                <img :src="imageUrl" class="w-full h-full object-contain bg-gray-50 dark:bg-zinc-800/50" alt="Preview">
            </template>

            <template x-if="!isImage">
                <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 dark:bg-zinc-800/50 p-4">
                    <i class="fa-solid fa-file-lines text-4xl text-gray-400 mb-2"></i>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 text-center break-all" x-text="fileName"></p>
                </div>
            </template>
        </div>
    </template>

    <input class="sr-only" id="{{ $name }}" name="{{ $name }}" type="file" accept="{{ $accept }}" x-ref="fileInput" x-on:change="selectFile">
    
    @if($deleteName)
        <input x-model="shouldDelete" id="{{ $deleteName }}" name="{{ $deleteName }}" type="hidden" value="0"/>
    @endif
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('fileUpload', (initialImage = '') => ({
                    imageUrl: initialImage,
                    fileName: '',
                    isImage: true,
                    shouldDelete: 0,
                    isDragging: false,

                    init() {
                        if (this.imageUrl) {
                            this.isImage = true; // Assuming initial value is an image URL for now
                        }
                    },

                    selectFile(event) {
                        const file = event.target.files[0];
                        this.processFile(file);
                    },

                    handleDrop(event) {
                        this.isDragging = false;
                        const file = event.dataTransfer.files[0];
                        this.processFile(file);
                    },

                    processFile(file) {
                        if (!file) return;

                        this.fileName = file.name;
                        this.isImage = file.type.startsWith('image/');
                        this.shouldDelete = 0;

                        if (this.isImage) {
                            const reader = new FileReader();
                            reader.readAsDataURL(file);
                            reader.onload = () => {
                                this.imageUrl = reader.result;
                            };
                        } else {
                            this.imageUrl = null; 
                        }

                        // Manually assign the file to the input element
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        this.$refs.fileInput.files = dataTransfer.files;
                    },

                    removeImage() {
                        this.imageUrl = '';
                        this.fileName = '';
                        this.shouldDelete = 1;
                        this.$refs.fileInput.value = null; 
                    }
                }))
            })
        </script>
    @endpush
@endonce
