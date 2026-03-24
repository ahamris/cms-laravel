<div class="bg-white dark:bg-white/5 rounded-md border border-gray-200 dark:border-white/10 p-4">
    @if (!empty($label))
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- Used only to decode frames; not shown --}}
    <video id="{{ $id }}_capture" muted playsinline preload="metadata"
        class="pointer-events-none fixed left-0 top-0 w-px h-px opacity-0 overflow-hidden"
        aria-hidden="true"></video>

    <div class="flex flex-col sm:flex-row items-start gap-4">
        <div id="{{ $id }}_dropzone"
            class="relative w-full sm:w-1/2 min-h-48 flex-shrink-0 border border-dashed border-gray-300 dark:border-white/10 rounded-lg transition-colors duration-200 cursor-pointer bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 flex items-center justify-center group overflow-hidden">
            <input type="file"
                id="{{ $id }}"
                name="{{ $name }}"
                accept="{{ $accept }}"
                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                style="font-size: 0;"
                {{ $required && !$currentVideoUrl ? 'required' : '' }}
                {{ $attributes }}>

            @if ($currentVideoUrl)
                <div id="{{ $id }}_current_preview"
                    class="absolute inset-0 w-full h-full p-2 flex items-center justify-center bg-black">
                    <img id="{{ $id }}_current_thumb" src="" alt=""
                        class="max-w-full max-h-full object-contain rounded hidden">
                    <div
                        class="absolute inset-0 bg-black/0 hover:bg-black/40 rounded-lg transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                        <button type="button"
                            onclick="event.stopPropagation(); removeCurrentVideoUpload('{{ $id }}');"
                            class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg z-20">
                            <i class="fa-solid fa-times text-sm"></i>
                        </button>
                    </div>
                </div>
            @endif

            <div id="{{ $id }}_default"
                class="absolute inset-0 w-full h-full {{ $currentVideoUrl ? 'hidden' : '' }}">
                <div
                    class="w-full h-full min-h-48 bg-gray-100 dark:bg-white/5 flex flex-col items-center justify-center rounded-lg p-4">
                    <div class="mb-2">
                        <i class="fa-solid fa-film text-gray-400 dark:text-gray-500 text-4xl"></i>
                    </div>
                    <p class="font-medium text-gray-500 dark:text-gray-400">No video</p>
                    <p class="text-gray-400 dark:text-gray-500 mt-1">Click or drop a file</p>
                </div>
            </div>

            <div id="{{ $id }}_preview"
                class="hidden absolute inset-0 w-full h-full p-2 flex items-center justify-center bg-black">
                <img id="{{ $id }}_preview_thumb" src="" alt=""
                    class="max-w-full max-h-full object-contain rounded hidden">
                <div
                    class="absolute inset-0 bg-black/0 hover:bg-black/40 rounded-lg transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                    <button type="button"
                        onclick="event.stopPropagation(); removeVideoUploadPreview('{{ $id }}');"
                        class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg z-20">
                        <i class="fa-solid fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        @if ($helpText)
            <div class="flex-1">
                <p class="text-[var(--color-accent)] dark:text-[var(--color-accent)] mb-1">{{ $helpText }}</p>
                <p class="text-gray-500 dark:text-gray-400">Drag a video onto the box or click to upload.</p>
            </div>
        @endif
    </div>

    @error($name)
        <p class="mt-1 text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

@push('styles')
    <style>
        #{{ $id }}::file-selector-button {
            display: none !important;
        }

        #{{ $id }}::-webkit-file-upload-button {
            display: none !important;
        }

        #{{ $id }}_dropzone.drag-over {
            border-color: rgb(var(--color-accent)) !important;
            background-color: rgb(var(--color-accent) / 0.1) !important;
        }

        #{{ $id }}_dropzone:hover {
            border-color: rgb(var(--color-accent) / 0.5) !important;
        }

        .dark #{{ $id }}_dropzone:hover {
            border-color: rgb(var(--color-accent) / 0.5) !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            'use strict';

            const inputId = '{{ $id }}';
            const dropzoneId = inputId + '_dropzone';
            const fileInput = document.getElementById(inputId);
            const dropzone = document.getElementById(dropzoneId);
            const defaultState = document.getElementById(inputId + '_default');
            const previewState = document.getElementById(inputId + '_preview');
            const previewThumb = document.getElementById(inputId + '_preview_thumb');
            const currentThumb = document.getElementById(inputId + '_current_thumb');
            const captureVideo = document.getElementById(inputId + '_capture');
            const currentPreview = document.getElementById(inputId + '_current_preview');
            const maxSize = {{ $maxSize }} * 1024;
            const removeInputName = @json($removeInputName);
            const allowedPrefixes = ['video/'];
            const currentVideoUrlFromServer = @json($currentVideoUrl);
            let activeBlobUrl = null;
            let thumbLoadToken = 0;

            if (!fileInput || !dropzone) return;

            const MAX_THUMB = 960;

            function isVideoFile(file) {
                if (!file || !file.type) return false;
                return allowedPrefixes.some(function(p) {
                    return file.type.startsWith(p);
                });
            }

            function revokeActiveBlob() {
                if (activeBlobUrl) {
                    URL.revokeObjectURL(activeBlobUrl);
                    activeBlobUrl = null;
                }
                if (captureVideo) {
                    captureVideo.removeAttribute('src');
                    captureVideo.load();
                }
            }

            function drawThumbnailFromVideo(videoEl, imgEl) {
                if (!imgEl || !videoEl.videoWidth || !videoEl.videoHeight) {
                    return false;
                }
                let w = videoEl.videoWidth;
                let h = videoEl.videoHeight;
                const scale = Math.min(1, MAX_THUMB / Math.max(w, h));
                w = Math.round(w * scale);
                h = Math.round(h * scale);
                const canvas = document.createElement('canvas');
                canvas.width = w;
                canvas.height = h;
                const ctx = canvas.getContext('2d');
                if (!ctx) return false;
                try {
                    ctx.drawImage(videoEl, 0, 0, w, h);
                    imgEl.src = canvas.toDataURL('image/jpeg', 0.82);
                    imgEl.classList.remove('hidden');
                    return true;
                } catch (e) {
                    return false;
                }
            }

            function captureThumbnailFromUrl(url, imgEl, isBlob) {
                if (!captureVideo || !imgEl || !url) return;

                thumbLoadToken += 1;
                const loadId = thumbLoadToken;

                revokeActiveBlob();
                if (isBlob) {
                    activeBlobUrl = url;
                }

                imgEl.classList.add('hidden');

                if (url.indexOf('blob:') === 0) {
                    captureVideo.removeAttribute('crossorigin');
                } else {
                    try {
                        const parsed = new URL(url, window.location.href);
                        if (parsed.origin !== window.location.origin) {
                            captureVideo.crossOrigin = 'anonymous';
                        } else {
                            captureVideo.removeAttribute('crossorigin');
                        }
                    } catch (e) {
                        captureVideo.removeAttribute('crossorigin');
                    }
                }

                captureVideo.src = url;

                const onError = function() {
                    if (loadId !== thumbLoadToken) return;
                    imgEl.classList.add('hidden');
                };

                const onSeeked = function() {
                    if (loadId !== thumbLoadToken) return;
                    const ok = drawThumbnailFromVideo(captureVideo, imgEl);
                    if (!ok) {
                        imgEl.classList.add('hidden');
                    }
                };

                const onLoadedData = function() {
                    if (loadId !== thumbLoadToken) return;
                    const d = captureVideo.duration;
                    const t = d && !isNaN(d) && isFinite(d) ?
                        Math.min(0.1, Math.max(0.001, d * 0.05)) :
                        0.1;
                    captureVideo.currentTime = t;
                    setTimeout(function() {
                        if (loadId !== thumbLoadToken) return;
                        if (!imgEl.classList.contains('hidden')) return;
                        if (captureVideo.readyState < 2) return;
                        const ok = drawThumbnailFromVideo(captureVideo, imgEl);
                        if (!ok) {
                            imgEl.classList.add('hidden');
                        }
                    }, 400);
                };

                captureVideo.addEventListener('error', onError, {
                    once: true
                });
                captureVideo.addEventListener('loadeddata', onLoadedData, {
                    once: true
                });
                captureVideo.addEventListener('seeked', onSeeked, {
                    once: true
                });
                captureVideo.load();
            }

            if (currentVideoUrlFromServer && currentThumb) {
                captureThumbnailFromUrl(currentVideoUrlFromServer, currentThumb, false);
            }

            fileInput.addEventListener('change', function(e) {
                handleFileSelect(e.target.files[0]);
            });

            dropzone.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('drag-over');
            });

            dropzone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('drag-over');
            });

            dropzone.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('drag-over');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    handleFileSelect(files[0]);
                }
            });

            function assignFileToInput(file) {
                try {
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    fileInput.files = dt.files;
                } catch (err) {
                    console.error(err);
                }
            }

            function clearRemoveFlags() {
                const form = fileInput.closest('form');
                if (!form) return;
                form.querySelectorAll('input[type="hidden"][name="' + removeInputName + '"]').forEach(function(el) {
                    el.remove();
                });
            }

            function clearPreviewThumb() {
                if (previewThumb) {
                    previewThumb.removeAttribute('src');
                    previewThumb.classList.add('hidden');
                }
                revokeActiveBlob();
            }

            function handleFileSelect(file) {
                if (!file) return;

                if (!isVideoFile(file)) {
                    alert('Please select a valid video file.');
                    fileInput.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    alert('File size exceeds {{ $maxSize }} KB limit.');
                    fileInput.value = '';
                    return;
                }

                assignFileToInput(file);
                clearRemoveFlags();
                clearPreviewThumb();

                const url = URL.createObjectURL(file);
                captureThumbnailFromUrl(url, previewThumb, true);

                if (defaultState) defaultState.classList.add('hidden');
                if (previewState) previewState.classList.remove('hidden');
                if (currentPreview) currentPreview.classList.add('hidden');
            }

            window['removeVideoUploadPreview_' + inputId] = function() {
                fileInput.value = '';
                clearPreviewThumb();
                if (previewState) previewState.classList.add('hidden');
                const form = fileInput.closest('form');
                const removeScheduled = form && form.querySelector('input[type="hidden"][name="' + removeInputName +
                    '"]');
                if (currentPreview && !removeScheduled) {
                    currentPreview.classList.remove('hidden');
                    if (defaultState) defaultState.classList.add('hidden');
                } else if (defaultState) {
                    defaultState.classList.remove('hidden');
                }
            };

            window['removeCurrentVideoUpload_' + inputId] = function() {
                if (!confirm('Remove the current video?')) return;

                if (currentThumb) {
                    currentThumb.removeAttribute('src');
                    currentThumb.classList.add('hidden');
                }
                revokeActiveBlob();

                if (currentPreview) currentPreview.classList.add('hidden');
                if (previewState.classList.contains('hidden')) {
                    if (defaultState) defaultState.classList.remove('hidden');
                }

                const form = fileInput.closest('form');
                if (form) {
                    form.querySelectorAll('input[type="hidden"][name="' + removeInputName + '"]').forEach(function(el) {
                        el.remove();
                    });
                    const removeInput = document.createElement('input');
                    removeInput.type = 'hidden';
                    removeInput.name = removeInputName;
                    removeInput.value = '1';
                    fileInput.parentElement.appendChild(removeInput);
                }
            };

            window.removeVideoUploadPreview = function(id) {
                if (window['removeVideoUploadPreview_' + id]) {
                    window['removeVideoUploadPreview_' + id]();
                }
            };

            window.removeCurrentVideoUpload = function(id) {
                if (window['removeCurrentVideoUpload_' + id]) {
                    window['removeCurrentVideoUpload_' + id]();
                }
            };
        })();
    </script>
@endpush
