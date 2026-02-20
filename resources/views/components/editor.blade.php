@props([
    'name',
    'value' => '',
    'placeholder' => 'Start typing...',
    'label' => null,
    'required' => false,
    'id' => null,
])

@php
    $editorId = $id ?? 'editor-' . uniqid();
    $inputName = $name;
    $inputId = 'input-' . $editorId;
@endphp

<div v-pre>
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-600 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    <div
        x-data="tipTapLazy(@js($value), @js($placeholder))"
        x-init="
            // Sync content to hidden input whenever it changes
            $watch('content', () => {
                const hiddenInput = document.getElementById('{{ $inputId }}');
                if (hiddenInput) {
                    hiddenInput.value = content || '';
                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });

            // Ensure content is synced before form submit
            const form = $el.closest('form');
            if (form) {
                const wrapper = $el;
                const inputId = '{{ $inputId }}';
                form.addEventListener('submit', (e) => {
                    const hiddenInput = document.getElementById(inputId);
                    if (!hiddenInput) return;
                    const data = (typeof Alpine !== 'undefined' && Alpine.$data && Alpine.$data(wrapper)) || (wrapper._x_dataStack && wrapper._x_dataStack[0]) || null;
                    let content;
                    if (data?.activeTab === 'html' && (data.sourceCode !== undefined && data.sourceCode !== null)) {
                        content = (typeof data.sourceCode === 'string' ? data.sourceCode : '').replace(/\n\s*/g, ' ').trim();
                        if (data.editorInstance && typeof data.editorInstance.setHTML === 'function') {
                            data.editorInstance.setHTML(content);
                        }
                    } else {
                        content = data?.editorInstance ? (data.editorInstance.getHTML() || '') : (data?.content ?? '');
                    }
                    hiddenInput.value = content || '';
                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                });
            }
        "
        class="tiptap-editor-wrapper relative"
    >
        <!-- Loading Overlay -->
        <div x-show="isLoading" class="absolute inset-0 z-50 flex items-center justify-center bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm rounded-md transition-opacity">
            <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400">
                <i class="fas fa-circle-notch fa-spin text-xl"></i>
                <span class="text-sm font-medium">Loading editor...</span>
            </div>
        </div>
        <!-- Tab Butonları (always visible so toolbar is not hidden when init is deferred) -->
        <div class="flex items-center border border-zinc-200 dark:border-zinc-700 rounded-t-md bg-zinc-50 dark:bg-zinc-800/50">
            <button
                type="button"
                @click="switchTab('editor')"
                :class="{
                    'bg-white dark:bg-zinc-800 border-b-2 border-zinc-900 dark:border-zinc-100': activeTab === 'editor',
                    'hover:bg-zinc-100 dark:hover:bg-zinc-700/50': activeTab !== 'editor'
                }"
                class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 rounded-tl-md transition-colors"
            >
                <i class="fas fa-edit mr-2"></i>Editor
            </button>
            <button
                type="button"
                @click="switchTab('html')"
                :class="{
                    'bg-white dark:bg-zinc-800 border-b-2 border-zinc-900 dark:border-zinc-100': activeTab === 'html',
                    'hover:bg-zinc-100 dark:hover:bg-zinc-700/50': activeTab !== 'html'
                }"
                class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 transition-colors"
            >
                <i class="fab fa-html5 mr-2"></i>HTML
            </button>
        </div>

        <!-- Toolbar (sadece editor tabında; shown regardless of isLoaded so it does not disappear when init is deferred) -->
        <div x-show="activeTab === 'editor'" class="flex flex-wrap items-center gap-1 p-2 border-l border-r border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
            <!-- Heading Dropdown -->
            <div class="relative group">
                <button
                    type="button"
                    class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Headings"
                >
                    <i class="fas fa-heading"></i>
                </button>
                <div class="absolute top-full left-0 mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded shadow-lg z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                    <button type="button" @click="toggleHeading(1)" :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('heading', { level: 1 }) }" class="w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        H1
                    </button>
                    <button type="button" @click="toggleHeading(2)" :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('heading', { level: 2 }) }" class="w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        H2
                    </button>
                    <button type="button" @click="toggleHeading(3)" :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('heading', { level: 3 }) }" class="w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        H3
                    </button>
                </div>
            </div>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            <!-- Text Formatting -->
            <button
                type="button"
                @click="toggleBold()"
                :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('bold') }"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Bold (Ctrl+B)"
            >
                <i class="fas fa-bold"></i>
            </button>
            <button
                type="button"
                @click="toggleItalic()"
                :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('italic') }"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Italic (Ctrl+I)"
            >
                <i class="fas fa-italic"></i>
            </button>
            <button
                type="button"
                @click="toggleUnderline()"
                :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('underline') }"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Underline (Ctrl+U)"
            >
                <i class="fas fa-underline"></i>
            </button>
            <button
                type="button"
                @click="toggleStrike()"
                :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('strike') }"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Strikethrough"
            >
                <i class="fas fa-strikethrough"></i>
            </button>
            <button
                type="button"
                @click="toggleCode()"
                :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('code') }"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Inline Code"
            >
                <i class="fas fa-code"></i>
            </button>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            <!-- Subscript & Superscript -->
            <button
                type="button"
                @click="toggleSubscript()"
                :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('subscript') }"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Subscript"
            >
                <i class="fas fa-subscript"></i>
            </button>
            <button
                type="button"
                @click="toggleSuperscript()"
                :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('superscript') }"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Superscript"
            >
                <i class="fas fa-superscript"></i>
            </button>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            <!-- Lists -->
            <button
                type="button"
                @click="toggleBulletList()"
                :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('bulletList') }"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Bullet List"
            >
                <i class="fas fa-list-ul"></i>
            </button>
            <button
                type="button"
                @click="toggleOrderedList()"
                :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('orderedList') }"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Numbered List"
            >
                <i class="fas fa-list-ol"></i>
            </button>
            <button
                type="button"
                @click="toggleTaskList()"
                :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('taskList') }"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Task List"
            >
                <i class="fas fa-tasks"></i>
            </button>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            <!-- Alignment Dropdown -->
            <div class="relative group">
                <button
                    type="button"
                    class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                    title="Text Alignment"
                >
                    <i class="fas fa-align-left"></i>
                </button>
                <div class="absolute top-full left-0 mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded shadow-lg z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                    <button type="button" @click="setTextAlign('left')" class="w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        <i class="fas fa-align-left mr-2"></i>Left
                    </button>
                    <button type="button" @click="setTextAlign('center')" class="w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        <i class="fas fa-align-center mr-2"></i>Center
                    </button>
                    <button type="button" @click="setTextAlign('right')" class="w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        <i class="fas fa-align-right mr-2"></i>Right
                    </button>
                    <button type="button" @click="setTextAlign('justify')" class="w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        <i class="fas fa-align-justify mr-2"></i>Justify
                    </button>
                </div>
            </div>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            <!-- Block Elements -->
            <button
                type="button"
                @click="toggleBlockquote()"
                :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('blockquote') }"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Blockquote"
            >
                <i class="fas fa-quote-right"></i>
            </button>
            <button
                type="button"
                @click="setCodeBlock()"
                :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('codeBlock') }"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Code Block"
            >
                <i class="fas fa-file-code"></i>
            </button>
            <button
                type="button"
                @click="setHorizontalRule()"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Horizontal Rule"
            >
                <i class="fas fa-minus"></i>
            </button>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            <!-- Link -->
            <button
                type="button"
                @click="isActive('link') ? unsetLink() : setLink()"
                :class="{ 'bg-blue-100 dark:bg-blue-900': isActive('link') }"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Link"
            >
                <i class="fas fa-link"></i>
            </button>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            <!-- Undo/Redo -->
            <button
                type="button"
                @click="undo()"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Undo (Ctrl+Z)"
            >
                <i class="fas fa-undo"></i>
            </button>
            <button
                type="button"
                @click="redo()"
                class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                title="Redo (Ctrl+Y)"
            >
                <i class="fas fa-redo"></i>
            </button>
        </div>

        <!-- Editor Alanı -->
        <div x-show="activeTab === 'editor'">
            <div
                x-ref="editor"
                data-placeholder="{{ $placeholder }}"
                class="prose prose-zinc dark:prose-invert max-w-none focus:outline-none min-h-[300px] p-4 border-l border-r border-b border-zinc-200 dark:border-zinc-700 rounded-b-md bg-white dark:bg-zinc-800"
            ></div>
        </div>

        <!-- HTML Kaynak Kod Alanı -->
        <div x-show="activeTab === 'html'" class="border-l border-r border-b border-zinc-200 dark:border-zinc-700 rounded-b-md">
            <textarea
                x-model="sourceCode"
                class="w-full min-h-[300px] p-4 font-mono text-sm bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 border-0 rounded-b-md focus:outline-none resize-none"
                placeholder="HTML source code will appear here..."
            ></textarea>
        </div>

        <!-- Hidden input (form submit için) -->
        <input
            type="hidden"
            name="{{ $inputName }}"
            id="{{ $inputId }}"
            x-model="content"
            @if($required) required @endif
        />
    </div>

    @error($name)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>

@push('styles')
    <style>
        /* TipTap Editor Styles */
        .tiptap-editor-wrapper .ProseMirror {
            outline: none;
        }

        .tiptap-editor-wrapper .ProseMirror p.is-editor-empty:first-child::before {
            content: attr(data-placeholder);
            float: left;
            color: #9ca3af;
            pointer-events: none;
            height: 0;
        }

        /* Heading weights: h1-h3 semibold, match front base */
        .tiptap-editor-wrapper .ProseMirror h1 {
            font-size: 2em;
            font-weight: 600;
        }

        .tiptap-editor-wrapper .ProseMirror h2 {
            font-size: 1.5em;
            font-weight: 600;
        }

        .tiptap-editor-wrapper .ProseMirror h3 {
            font-size: 1.25em;
            font-weight: 600;
        }

        /* Liste stilleri */
        .tiptap-editor-wrapper .ProseMirror ul,
        .tiptap-editor-wrapper .ProseMirror ol {
            padding-left: 1.5em;
        }

        /* Kod stilleri */
        .tiptap-editor-wrapper .ProseMirror code {
            background-color: rgba(97, 97, 97, 0.1);
            padding: 0.2em 0.4em;
            border-radius: 3px;
            font-family: monospace;
        }

        .tiptap-editor-wrapper .ProseMirror pre {
            background-color: rgba(97, 97, 97, 0.1);
            padding: 1em;
            border-radius: 4px;
            overflow-x: auto;
        }

        .tiptap-editor-wrapper .ProseMirror pre code {
            background-color: transparent;
            padding: 0;
        }

        /* Blockquote */
        .tiptap-editor-wrapper .ProseMirror blockquote {
            border-left: 3px solid #d4d4d8;
            padding-left: 1em;
            font-style: italic;
            margin: 1em 0;
        }

        /* Link stilleri */
        .tiptap-editor-wrapper .ProseMirror a {
            color: #3b82f6;
            text-decoration: underline;
        }

        /* Task list stilleri */
        .tiptap-editor-wrapper .ProseMirror ul[data-type="taskList"] {
            list-style: none;
            padding-left: 0;
        }

        .tiptap-editor-wrapper .ProseMirror ul[data-type="taskList"] li {
            display: flex;
            align-items: flex-start;
        }

        .tiptap-editor-wrapper .ProseMirror ul[data-type="taskList"] li > label {
            margin-right: 0.5em;
        }

        /* Text align stilleri */
        .tiptap-editor-wrapper .ProseMirror [style*="text-align: left"] {
            text-align: left;
        }

        .tiptap-editor-wrapper .ProseMirror [style*="text-align: center"] {
            text-align: center;
        }

        .tiptap-editor-wrapper .ProseMirror [style*="text-align: right"] {
            text-align: right;
        }

        .tiptap-editor-wrapper .ProseMirror [style*="text-align: justify"] {
            text-align: justify;
        }
    </style>
@endpush

