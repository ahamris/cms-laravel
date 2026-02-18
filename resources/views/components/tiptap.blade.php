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

<div>
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-600 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    <div
        x-data="{
            ...setupEditor(@js($value), @js($placeholder)),
            activeTab: 'editor',
            sourceCode: '',
            switchTab(tab) {
                if (tab === 'html' && this.activeTab === 'editor') {
                    const html = this.getHTML();
                    this.sourceCode = this.formatHTML(html);
                } else if (tab === 'editor' && this.activeTab === 'html') {
                    const unformatted = this.sourceCode.replace(/\n\s*/g, ' ').trim();
                    this.setHTML(unformatted);
                }
                this.activeTab = tab;
            }
        }"
        x-init="() => init($refs.editor)"
        class="tiptap-editor-wrapper"
    >
        <!-- Tab Butonları -->
        <div x-show="isLoaded()" class="flex items-center border border-zinc-200 dark:border-zinc-700 rounded-t-md bg-zinc-50 dark:bg-zinc-800/50">
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

        <!-- Toolbar (sadece editor tabında) -->
        <div x-show="isLoaded() && activeTab === 'editor'" class="flex flex-wrap items-center gap-1 p-2 border-l border-r border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
            <!-- Undo/Redo -->
            <div class="flex items-center gap-0.5 pr-2 border-r border-zinc-200 dark:border-zinc-700">
                <button type="button" @click="undo()" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Undo">
                    <i class="fas fa-undo text-sm"></i>
                </button>
                <button type="button" @click="redo()" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Redo">
                    <i class="fas fa-redo text-sm"></i>
                </button>
            </div>

            <!-- Başlıklar -->
            <div class="flex items-center gap-0.5 px-2 border-r border-zinc-200 dark:border-zinc-700">
                <button type="button" @click="toggleHeading(1)" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('heading', { level: 1 }) }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400 font-bold text-sm" title="Heading 1">
                    H1
                </button>
                <button type="button" @click="toggleHeading(2)" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('heading', { level: 2 }) }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400 font-bold text-sm" title="Heading 2">
                    H2
                </button>
                <button type="button" @click="toggleHeading(3)" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('heading', { level: 3 }) }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400 font-bold text-sm" title="Heading 3">
                    H3
                </button>
            </div>

            <!-- Metin Formatlama -->
            <div class="flex items-center gap-0.5 px-2 border-r border-zinc-200 dark:border-zinc-700">
                <button type="button" @click="toggleBold()" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('bold') }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Bold">
                    <i class="fas fa-bold text-sm"></i>
                </button>
                <button type="button" @click="toggleItalic()" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('italic') }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Italic">
                    <i class="fas fa-italic text-sm"></i>
                </button>
                <button type="button" @click="toggleUnderline()" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('underline') }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Underline">
                    <i class="fas fa-underline text-sm"></i>
                </button>
                <button type="button" @click="toggleStrike()" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('strike') }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Strikethrough">
                    <i class="fas fa-strikethrough text-sm"></i>
                </button>
                <button type="button" @click="toggleCode()" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('code') }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Inline Code">
                    <i class="fas fa-code text-sm"></i>
                </button>
            </div>

            <!-- Üst/Alt Simge -->
            <div class="flex items-center gap-0.5 px-2 border-r border-zinc-200 dark:border-zinc-700">
                <button type="button" @click="toggleSubscript()" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('subscript') }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Subscript">
                    <i class="fas fa-subscript text-sm"></i>
                </button>
                <button type="button" @click="toggleSuperscript()" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('superscript') }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Superscript">
                    <i class="fas fa-superscript text-sm"></i>
                </button>
            </div>

            <!-- Listeler -->
            <div class="flex items-center gap-0.5 px-2 border-r border-zinc-200 dark:border-zinc-700">
                <button type="button" @click="toggleBulletList()" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('bulletList') }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Bullet List">
                    <i class="fas fa-list-ul text-sm"></i>
                </button>
                <button type="button" @click="toggleOrderedList()" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('orderedList') }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Numbered List">
                    <i class="fas fa-list-ol text-sm"></i>
                </button>
                <button type="button" @click="toggleTaskList()" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('taskList') }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Task List">
                    <i class="fas fa-tasks text-sm"></i>
                </button>
            </div>

            <!-- Blok Elementler -->
            <div class="flex items-center gap-0.5 px-2 border-r border-zinc-200 dark:border-zinc-700">
                <button type="button" @click="toggleBlockquote()" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('blockquote') }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Blockquote">
                    <i class="fas fa-quote-left text-sm"></i>
                </button>
                <button type="button" @click="setCodeBlock()" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('codeBlock') }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Code Block">
                    <i class="fas fa-file-code text-sm"></i>
                </button>
                <button type="button" @click="setHorizontalRule()" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Horizontal Rule">
                    <i class="fas fa-minus text-sm"></i>
                </button>
            </div>

            <!-- Link -->
            <div class="flex items-center gap-0.5 px-2 border-r border-zinc-200 dark:border-zinc-700">
                <button type="button" @click="setLink()" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive('link') }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Add Link">
                    <i class="fas fa-link text-sm"></i>
                </button>
                <button type="button" @click="unsetLink()" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Remove Link">
                    <i class="fas fa-unlink text-sm"></i>
                </button>
            </div>

            <!-- Hizalama -->
            <div class="flex items-center gap-0.5 pl-2">
                <button type="button" @click="setTextAlign('left')" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive({ textAlign: 'left' }) }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Align Left">
                    <i class="fas fa-align-left text-sm"></i>
                </button>
                <button type="button" @click="setTextAlign('center')" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive({ textAlign: 'center' }) }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Align Center">
                    <i class="fas fa-align-center text-sm"></i>
                </button>
                <button type="button" @click="setTextAlign('right')" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive({ textAlign: 'right' }) }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Align Right">
                    <i class="fas fa-align-right text-sm"></i>
                </button>
                <button type="button" @click="setTextAlign('justify')" :class="{ 'bg-zinc-200 dark:bg-zinc-700': isActive({ textAlign: 'justify' }) }" class="p-1.5 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-400" title="Justify">
                    <i class="fas fa-align-justify text-sm"></i>
                </button>
            </div>
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

@once
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

            /* Başlık stilleri */
            .tiptap-editor-wrapper .ProseMirror h1 { font-size: 2em; font-weight: bold; margin: 0.67em 0; }
            .tiptap-editor-wrapper .ProseMirror h2 { font-size: 1.5em; font-weight: bold; margin: 0.75em 0; }
            .tiptap-editor-wrapper .ProseMirror h3 { font-size: 1.25em; font-weight: bold; margin: 0.83em 0; }

            /* Liste stilleri */
            .tiptap-editor-wrapper .ProseMirror ul,
            .tiptap-editor-wrapper .ProseMirror ol {
                padding-left: 1.5em;
            }

            .tiptap-editor-wrapper .ProseMirror ul { list-style-type: disc; }
            .tiptap-editor-wrapper .ProseMirror ol { list-style-type: decimal; }

            /* Task list stilleri */
            .tiptap-editor-wrapper .ProseMirror ul[data-type="taskList"] {
                list-style: none;
                padding-left: 0;
            }

            .tiptap-editor-wrapper .ProseMirror ul[data-type="taskList"] li {
                display: flex;
                align-items: flex-start;
                gap: 0.5em;
            }

            .tiptap-editor-wrapper .ProseMirror ul[data-type="taskList"] li > label {
                flex: 0 0 auto;
                margin-top: 0.25em;
            }

            .tiptap-editor-wrapper .ProseMirror ul[data-type="taskList"] li > div {
                flex: 1 1 auto;
            }

            /* Kod stilleri */
            .tiptap-editor-wrapper .ProseMirror code {
                background-color: rgba(97, 97, 97, 0.1);
                padding: 0.2em 0.4em;
                border-radius: 3px;
                font-family: monospace;
            }

            .tiptap-editor-wrapper .ProseMirror pre {
                background-color: #1e1e1e;
                color: #d4d4d4;
                padding: 1em;
                border-radius: 0.5em;
                overflow-x: auto;
            }

            .tiptap-editor-wrapper .ProseMirror pre code {
                background: none;
                padding: 0;
                color: inherit;
            }

            /* Blockquote */
            .tiptap-editor-wrapper .ProseMirror blockquote {
                border-left: 3px solid #d4d4d8;
                padding-left: 1em;
                margin-left: 0;
                font-style: italic;
            }

            .dark .tiptap-editor-wrapper .ProseMirror blockquote {
                border-left-color: #52525b;
            }

            /* Horizontal rule */
            .tiptap-editor-wrapper .ProseMirror hr {
                border: none;
                border-top: 2px solid #e4e4e7;
                margin: 1em 0;
            }

            .dark .tiptap-editor-wrapper .ProseMirror hr {
                border-top-color: #3f3f46;
            }

            /* Link stilleri */
            .tiptap-editor-wrapper .ProseMirror a {
                color: #3b82f6;
                text-decoration: underline;
            }

            .dark .tiptap-editor-wrapper .ProseMirror a {
                color: #60a5fa;
            }
        </style>
    @endpush
@endonce
