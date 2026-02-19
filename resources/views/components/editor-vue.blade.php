@props([
    'name',
    'value' => '',
    'placeholder' => 'Start typing...',
    'label' => null,
    'required' => false,
    'id' => null,
])

@php
    $editorId = $id ?? 'editor-vue-' . uniqid();
    $inputName = $name;
    $inputId = 'input-' . $editorId;
    $editorElementId = 'editor-element-' . $editorId;
@endphp

{{-- Use tiptap-editor-wrapper class so setupEditor's onUpdate callback can find hidden input --}}
<div class="tiptap-editor-wrapper tiptap-editor-vue-wrapper" data-editor-id="{{ $editorId }}">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-600 dark:text-red-400">*</span>
            @endif
        </label>
    @endif

    {{-- Tab Butonları --}}
    <div class="flex items-center border border-zinc-200 dark:border-zinc-700 rounded-t-md bg-zinc-50 dark:bg-zinc-800/50" id="tabs-{{ $editorId }}">
        <button
            type="button"
            data-tab="editor"
            data-editor-id="{{ $editorId }}"
            class="editor-tab-btn px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 rounded-tl-md transition-colors bg-white dark:bg-zinc-800 border-b-2 border-zinc-900 dark:border-zinc-100"
        >
            <i class="fas fa-edit mr-2"></i>Editor
        </button>
        <button
            type="button"
            data-tab="html"
            data-editor-id="{{ $editorId }}"
            class="editor-tab-btn px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700/50"
        >
            <i class="fab fa-html5 mr-2"></i>HTML
        </button>
    </div>

    {{-- Editor Tab Content --}}
    <div data-tab-content="editor" data-editor-id="{{ $editorId }}" class="border-l border-r border-b border-zinc-200 dark:border-zinc-700 rounded-b-md">
        {{-- Toolbar (sadece editor tabında) --}}
        <div class="flex flex-wrap items-center gap-1 p-2 border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50" id="toolbar-{{ $editorId }}">
            {{-- Heading Dropdown --}}
            <div class="relative group">
                <button type="button" class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Headings">
                    <i class="fas fa-heading"></i>
                </button>
                <div class="absolute top-full left-0 mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded shadow-lg z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                    <button type="button" data-action="toggleHeading" data-level="1" data-editor-id="{{ $editorId }}" class="toolbar-btn w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        H1
                    </button>
                    <button type="button" data-action="toggleHeading" data-level="2" data-editor-id="{{ $editorId }}" class="toolbar-btn w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        H2
                    </button>
                    <button type="button" data-action="toggleHeading" data-level="3" data-editor-id="{{ $editorId }}" class="toolbar-btn w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        H3
                    </button>
                </div>
            </div>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            {{-- Text Formatting --}}
            <button type="button" data-action="toggleBold" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Bold (Ctrl+B)">
                <i class="fas fa-bold"></i>
            </button>
            <button type="button" data-action="toggleItalic" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Italic (Ctrl+I)">
                <i class="fas fa-italic"></i>
            </button>
            <button type="button" data-action="toggleUnderline" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Underline (Ctrl+U)">
                <i class="fas fa-underline"></i>
            </button>
            <button type="button" data-action="toggleStrike" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Strikethrough">
                <i class="fas fa-strikethrough"></i>
            </button>
            <button type="button" data-action="toggleCode" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Inline Code">
                <i class="fas fa-code"></i>
            </button>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            {{-- Subscript & Superscript --}}
            <button type="button" data-action="toggleSubscript" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Subscript">
                <i class="fas fa-subscript"></i>
            </button>
            <button type="button" data-action="toggleSuperscript" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Superscript">
                <i class="fas fa-superscript"></i>
            </button>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            {{-- Lists --}}
            <button type="button" data-action="toggleBulletList" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Bullet List">
                <i class="fas fa-list-ul"></i>
            </button>
            <button type="button" data-action="toggleOrderedList" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Numbered List">
                <i class="fas fa-list-ol"></i>
            </button>
            <button type="button" data-action="toggleTaskList" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Task List">
                <i class="fas fa-tasks"></i>
            </button>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            {{-- Alignment Dropdown --}}
            <div class="relative group">
                <button type="button" class="p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Text Alignment">
                    <i class="fas fa-align-left"></i>
                </button>
                <div class="absolute top-full left-0 mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded shadow-lg z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                    <button type="button" data-action="setTextAlign" data-align="left" data-editor-id="{{ $editorId }}" class="toolbar-btn w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        <i class="fas fa-align-left mr-2"></i>Left
                    </button>
                    <button type="button" data-action="setTextAlign" data-align="center" data-editor-id="{{ $editorId }}" class="toolbar-btn w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        <i class="fas fa-align-center mr-2"></i>Center
                    </button>
                    <button type="button" data-action="setTextAlign" data-align="right" data-editor-id="{{ $editorId }}" class="toolbar-btn w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        <i class="fas fa-align-right mr-2"></i>Right
                    </button>
                    <button type="button" data-action="setTextAlign" data-align="justify" data-editor-id="{{ $editorId }}" class="toolbar-btn w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        <i class="fas fa-align-justify mr-2"></i>Justify
                    </button>
                </div>
            </div>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            {{-- Block Elements --}}
            <button type="button" data-action="toggleBlockquote" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Blockquote">
                <i class="fas fa-quote-right"></i>
            </button>
            <button type="button" data-action="setCodeBlock" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Code Block">
                <i class="fas fa-file-code"></i>
            </button>
            <button type="button" data-action="setHorizontalRule" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Horizontal Rule">
                <i class="fas fa-minus"></i>
            </button>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            {{-- Link --}}
            <button type="button" data-action="toggleLink" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Link">
                <i class="fas fa-link"></i>
            </button>

            <div class="w-px h-6 bg-zinc-300 dark:bg-zinc-600"></div>

            {{-- Undo/Redo --}}
            <button type="button" data-action="undo" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Undo (Ctrl+Z)">
                <i class="fas fa-undo"></i>
            </button>
            <button type="button" data-action="redo" data-editor-id="{{ $editorId }}" class="toolbar-btn p-2 rounded hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors" title="Redo (Ctrl+Y)">
                <i class="fas fa-redo"></i>
            </button>
        </div>

        {{-- Editor Area --}}
        <div 
            id="{{ $editorElementId }}"
            data-placeholder="{{ $placeholder }}"
            class="prose prose-zinc dark:prose-invert max-w-none focus:outline-none min-h-[300px] p-4 bg-white dark:bg-zinc-800"
        ></div>
    </div>

    {{-- HTML Tab Content --}}
    <div data-tab-content="html" data-editor-id="{{ $editorId }}" class="border-l border-r border-b border-zinc-200 dark:border-zinc-700 rounded-b-md hidden">
        <textarea
            id="{{ $editorId }}-html-editor"
            class="w-full min-h-[300px] p-4 font-mono text-sm bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 border-0 rounded-b-md focus:outline-none resize-none"
            placeholder="HTML source code will appear here..."
        ></textarea>
    </div>

    {{-- Hidden input for form submission --}}
    <input
        type="hidden"
        name="{{ $inputName }}"
        id="{{ $inputId }}"
        value="{{ $value }}"
        @if($required) required @endif
    />
</div>

@push('scripts')
<script>
(function() {
    'use strict';
    
    const editorId = '{{ $editorId }}';
    const inputId = '{{ $inputId }}';
    const editorElementId = '{{ $editorElementId }}';
    const initialValue = @js($value);
    const placeholder = @js($placeholder);

    let editorData = null;
    let editorInstance = null;
    let activeTab = 'editor';
    let sourceCode = '';
    let updateToolbarInterval = null;

    // Initialize editor when DOM is ready
    function initEditor() {
        if (typeof window.setupEditor === 'undefined') {
            setTimeout(initEditor, 100);
            return;
        }

        const editorElement = document.getElementById(editorElementId);
        if (!editorElement) {
            setTimeout(initEditor, 100);
            return;
        }

        // Initialize TipTap editor using setupEditor (same as original)
        editorData = window.setupEditor(initialValue || '', placeholder);
        editorInstance = editorData.init(editorElement);

        // Store editor reference globally
        if (!window.vueTipTapEditors) {
            window.vueTipTapEditors = {};
        }
        window.vueTipTapEditors[editorId] = {
            editor: editorInstance,
            editorData: editorData,
            getHTML: () => editorData ? editorData.getHTML() : '',
            setHTML: (html) => {
                if (editorData) {
                    editorData.setHTML(html);
                    updateHiddenInput(html);
                }
            },
            // When form/page calls this before submit: use HTML tab content if user is on HTML tab
            updateHiddenInput: () => {
                const htmlEditorEl = document.getElementById(editorId + '-html-editor');
                if (activeTab === 'html' && htmlEditorEl) {
                    updateHiddenInput((htmlEditorEl.value || '').trim());
                } else if (editorData) {
                    updateHiddenInput(editorData.getHTML());
                }
            }
        };

        // Update hidden input initially
        updateHiddenInput(editorData.getHTML());

        // Setup toolbar buttons (after a small delay to ensure DOM is ready)
        setTimeout(() => {
            setupToolbar();
        }, 50);

        // Setup tab switching (after a small delay to ensure DOM is ready)
        setTimeout(() => {
            setupTabs();
        }, 50);

        // Update toolbar button states periodically (like Alpine.js reactivity)
        updateToolbarInterval = setInterval(() => {
            updateToolbarStates();
        }, 200);
    }

    function setupToolbar() {
        const wrapper = document.querySelector(`[data-editor-id="${editorId}"]`);
        if (!wrapper) {
            return;
        }

        const buttons = wrapper.querySelectorAll('.toolbar-btn[data-action]');
        if (buttons.length === 0) {
            return;
        }

        buttons.forEach(button => {
            // Check if already has listener (prevent duplicates)
            if (button.hasAttribute('data-listener-attached')) {
                return;
            }
            
            button.setAttribute('data-listener-attached', 'true');
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                const action = this.getAttribute('data-action');
                const btnEditorId = this.getAttribute('data-editor-id');
                if (action && btnEditorId === editorId) {
                    handleToolbarAction(this);
                }
            }, true); // Use capture phase
        });
    }

    function handleToolbarAction(button) {
        if (!editorData || !editorInstance) {
            return;
        }

        const action = button.getAttribute('data-action');
        const level = button.getAttribute('data-level');
        const align = button.getAttribute('data-align');

        try {
            switch(action) {
                case 'toggleHeading':
                    if (level) {
                        editorData.toggleHeading(parseInt(level));
                    }
                    break;
                case 'toggleBold':
                    editorData.toggleBold();
                    break;
                case 'toggleItalic':
                    editorData.toggleItalic();
                    break;
                case 'toggleUnderline':
                    editorData.toggleUnderline();
                    break;
                case 'toggleStrike':
                    editorData.toggleStrike();
                    break;
                case 'toggleCode':
                    editorData.toggleCode();
                    break;
                case 'toggleSubscript':
                    editorData.toggleSubscript();
                    break;
                case 'toggleSuperscript':
                    editorData.toggleSuperscript();
                    break;
                case 'toggleBulletList':
                    editorData.toggleBulletList();
                    break;
                case 'toggleOrderedList':
                    editorData.toggleOrderedList();
                    break;
                case 'toggleTaskList':
                    editorData.toggleTaskList();
                    break;
                case 'setTextAlign':
                    if (align) {
                        editorData.setTextAlign(align);
                    }
                    break;
                case 'toggleBlockquote':
                    editorData.toggleBlockquote();
                    break;
                case 'setCodeBlock':
                    editorData.setCodeBlock();
                    break;
                case 'setHorizontalRule':
                    editorData.setHorizontalRule();
                    break;
                case 'toggleLink':
                    // Check if link is active
                    if (editorData.isActive('link')) {
                        editorData.unsetLink();
                    } else {
                        editorData.setLink();
                    }
                    break;
                case 'undo':
                    editorData.undo();
                    break;
                case 'redo':
                    editorData.redo();
                    break;
            }
        } catch (error) {
            console.error('Toolbar action error:', error);
        }
    }

    function updateToolbarStates() {
        if (!editorData || !editorInstance) {
            return;
        }

        const wrapper = document.querySelector(`[data-editor-id="${editorId}"]`);
        if (!wrapper) {
            return;
        }

        const buttons = wrapper.querySelectorAll('.toolbar-btn[data-action]');
        buttons.forEach(button => {
            const action = button.getAttribute('data-action');
            const level = button.getAttribute('data-level');
            const align = button.getAttribute('data-align');
            let isActive = false;

            try {
                switch(action) {
                    case 'toggleHeading':
                        if (level) {
                            isActive = editorData.isActive('heading', { level: parseInt(level) });
                        }
                        break;
                    case 'toggleBold':
                        isActive = editorData.isActive('bold');
                        break;
                    case 'toggleItalic':
                        isActive = editorData.isActive('italic');
                        break;
                    case 'toggleUnderline':
                        isActive = editorData.isActive('underline');
                        break;
                    case 'toggleStrike':
                        isActive = editorData.isActive('strike');
                        break;
                    case 'toggleCode':
                        isActive = editorData.isActive('code');
                        break;
                    case 'toggleSubscript':
                        isActive = editorData.isActive('subscript');
                        break;
                    case 'toggleSuperscript':
                        isActive = editorData.isActive('superscript');
                        break;
                    case 'toggleBulletList':
                        isActive = editorData.isActive('bulletList');
                        break;
                    case 'toggleOrderedList':
                        isActive = editorData.isActive('orderedList');
                        break;
                    case 'toggleTaskList':
                        isActive = editorData.isActive('taskList');
                        break;
                    case 'toggleBlockquote':
                        isActive = editorData.isActive('blockquote');
                        break;
                    case 'setCodeBlock':
                        isActive = editorData.isActive('codeBlock');
                        break;
                    case 'toggleLink':
                        isActive = editorData.isActive('link');
                        break;
                }

                // Update button active state
                if (isActive) {
                    button.classList.add('bg-blue-100', 'dark:bg-blue-900');
                } else {
                    button.classList.remove('bg-blue-100', 'dark:bg-blue-900');
                }
            } catch (error) {
                // Silently fail
            }
        });
    }

    function setupTabs() {
        const wrapper = document.querySelector(`[data-editor-id="${editorId}"]`);
        if (!wrapper) {
            return;
        }

        // Tab button clicks
        const tabButtons = wrapper.querySelectorAll('.editor-tab-btn');
        if (tabButtons.length === 0) {
            return;
        }

        tabButtons.forEach(btn => {
            // Check if already has listener (prevent duplicates)
            if (btn.hasAttribute('data-listener-attached')) {
                return;
            }
            
            btn.setAttribute('data-listener-attached', 'true');
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                const tab = this.getAttribute('data-tab');
                const btnEditorId = this.getAttribute('data-editor-id');
                if (tab && btnEditorId === editorId) {
                    switchTab(tab);
                }
            }, true); // Use capture phase
        });

        // HTML editor input
        const htmlEditor = document.getElementById(editorId + '-html-editor');
        if (htmlEditor) {
            htmlEditor.addEventListener('input', function() {
                sourceCode = this.value;
                updateHiddenInput(sourceCode);
            });
        }
    }

    function switchTab(tab) {
        const wrapper = document.querySelector(`[data-editor-id="${editorId}"]`);
        if (!wrapper) {
            return;
        }

        const editorTab = wrapper.querySelector('[data-tab="editor"]');
        const htmlTab = wrapper.querySelector('[data-tab="html"]');
        const editorContent = wrapper.querySelector('[data-tab-content="editor"]');
        const htmlContent = wrapper.querySelector('[data-tab-content="html"]');
        const htmlEditor = document.getElementById(editorId + '-html-editor');

        if (!editorTab || !htmlTab || !editorContent || !htmlContent) {
            return;
        }

        if (tab === 'html' && activeTab === 'editor') {
            // Switching to HTML tab
            if (editorData) {
                const html = editorData.getHTML();
                sourceCode = editorData.formatHTML(html);
                if (htmlEditor) {
                    htmlEditor.value = sourceCode;
                }
            }

            // Update tab styles
            editorTab.className = 'editor-tab-btn px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 rounded-tl-md transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700/50';
            htmlTab.className = 'editor-tab-btn px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 transition-colors bg-white dark:bg-zinc-800 border-b-2 border-zinc-900 dark:border-zinc-100';

            // Show/hide content
            editorContent.classList.add('hidden');
            htmlContent.classList.remove('hidden');

            activeTab = 'html';
        } else if (tab === 'editor' && activeTab === 'html') {
            // Switching to editor tab
            if (htmlEditor && editorData) {
                const unformatted = htmlEditor.value.replace(/\n\s*/g, ' ').trim();
                editorData.setHTML(unformatted);
                updateHiddenInput(unformatted);
            }

            // Update tab styles
            editorTab.className = 'editor-tab-btn px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 rounded-tl-md transition-colors bg-white dark:bg-zinc-800 border-b-2 border-zinc-900 dark:border-zinc-100';
            htmlTab.className = 'editor-tab-btn px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 transition-colors hover:bg-zinc-100 dark:hover:bg-zinc-700/50';

            // Show/hide content
            editorContent.classList.remove('hidden');
            htmlContent.classList.add('hidden');

            activeTab = 'editor';
        }
    }

    function updateHiddenInput(html) {
        const hiddenInput = document.getElementById(inputId);
        if (hiddenInput) {
            hiddenInput.value = html || '';
            hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
            hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    // Sync content to hidden input whenever it changes (like Alpine.js $watch)
    let lastContent = '';
    function watchContent() {
        if (editorData) {
            const currentContent = editorData.getHTML();
            if (currentContent !== lastContent) {
                lastContent = currentContent;
                updateHiddenInput(currentContent);
            }
        }
    }
    setInterval(watchContent, 300);

    // Ensure content is synced before form submit (including when user is on HTML tab)
    const wrapper = document.querySelector(`[data-editor-id="${editorId}"]`);
    if (wrapper) {
        const form = wrapper.closest('form');
        if (form) {
            form.addEventListener('submit', (e) => {
                const hiddenInput = document.getElementById(inputId);
                const htmlEditorEl = document.getElementById(editorId + '-html-editor');
                if (!hiddenInput) return;
                // If user is on HTML tab, use textarea content; otherwise use TipTap HTML
                if (activeTab === 'html' && htmlEditorEl) {
                    hiddenInput.value = (htmlEditorEl.value || '').trim();
                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                } else if (editorData) {
                    const currentContent = editorData.getHTML();
                    hiddenInput.value = currentContent || '';
                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                } else {
                    hiddenInput.value = lastContent || '';
                }
            });
        }
    }

    // Initialize when ready - wait for Vue.js to mount
    let initRetryCount = 0;
    const maxInitRetries = 50; // 50 * 100ms = 5 seconds max wait
    
    function waitForVueAndInit() {
        initRetryCount++;
        
        // Give up after max retries
        if (initRetryCount > maxInitRetries) {
            console.warn(`[TipTap Vue Editor] Could not initialize editor "${editorId}" after ${maxInitRetries} retries`);
            return;
        }
        
        // Check if setupEditor is available (from tiptap.js via Vite)
        if (typeof window.setupEditor === 'undefined') {
            setTimeout(waitForVueAndInit, 100);
            return;
        }
        
        const wrapper = document.querySelector(`[data-editor-id="${editorId}"]`);
        const editorElement = document.getElementById(editorElementId);
        
        // Check if elements exist in DOM
        // Note: We don't check visibility because TipTap can initialize in hidden containers (v-show)
        // The editor will work correctly even when the container becomes visible later
        if (wrapper && editorElement && document.contains(wrapper) && document.contains(editorElement)) {
            initEditor();
        } else {
            setTimeout(waitForVueAndInit, 100);
        }
    }

    // Cleanup interval on page unload
    window.addEventListener('beforeunload', () => {
        if (updateToolbarInterval) {
            clearInterval(updateToolbarInterval);
        }
    });

    // Start initialization
    // For Vue.js pages, we need to wait a bit longer for Vue to mount
    // Vue.js 3 via CDN loads asynchronously, so we need to be patient
    const initDelay = 500; // 500ms initial delay for Vue.js to mount
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(waitForVueAndInit, initDelay);
        });
    } else {
        setTimeout(waitForVueAndInit, initDelay);
    }
})();
</script>
@endpush

@push('styles')
<style>
    /* TipTap Editor Styles */
    .tiptap-editor-vue-wrapper .ProseMirror {
        outline: none;
    }

    .tiptap-editor-vue-wrapper .ProseMirror p.is-editor-empty:first-child::before {
        content: attr(data-placeholder);
        float: left;
        color: #9ca3af;
        pointer-events: none;
        height: 0;
    }

    /* Başlık stilleri */
    .tiptap-editor-vue-wrapper .ProseMirror h1 {
        font-size: 2em;
        font-weight: bold;
    }

    .tiptap-editor-vue-wrapper .ProseMirror h2 {
        font-size: 1.5em;
        font-weight: bold;
    }

    .tiptap-editor-vue-wrapper .ProseMirror h3 {
        font-size: 1.25em;
        font-weight: bold;
    }

    /* Liste stilleri */
    .tiptap-editor-vue-wrapper .ProseMirror ul,
    .tiptap-editor-vue-wrapper .ProseMirror ol {
        padding-left: 1.5em;
    }

    /* Kod stilleri */
    .tiptap-editor-vue-wrapper .ProseMirror code {
        background-color: rgba(97, 97, 97, 0.1);
        padding: 0.2em 0.4em;
        border-radius: 3px;
        font-family: monospace;
    }

    .tiptap-editor-vue-wrapper .ProseMirror pre {
        background-color: rgba(97, 97, 97, 0.1);
        padding: 1em;
        border-radius: 4px;
        overflow-x: auto;
    }

    .tiptap-editor-vue-wrapper .ProseMirror pre code {
        background-color: transparent;
        padding: 0;
    }

    /* Blockquote */
    .tiptap-editor-vue-wrapper .ProseMirror blockquote {
        border-left: 3px solid #d4d4d8;
        padding-left: 1em;
        font-style: italic;
        margin: 1em 0;
    }

    /* Link stilleri */
    .tiptap-editor-vue-wrapper .ProseMirror a {
        color: #3b82f6;
        text-decoration: underline;
    }

    /* Task list stilleri */
    .tiptap-editor-vue-wrapper .ProseMirror ul[data-type="taskList"] {
        list-style: none;
        padding-left: 0;
    }

    .tiptap-editor-vue-wrapper .ProseMirror ul[data-type="taskList"] li {
        display: flex;
        align-items: flex-start;
    }

    .tiptap-editor-vue-wrapper .ProseMirror ul[data-type="taskList"] li > label {
        margin-right: 0.5em;
    }

    /* Text align stilleri */
    .tiptap-editor-vue-wrapper .ProseMirror [style*="text-align: left"] {
        text-align: left;
    }

    .tiptap-editor-vue-wrapper .ProseMirror [style*="text-align: center"] {
        text-align: center;
    }

    .tiptap-editor-vue-wrapper .ProseMirror [style*="text-align: right"] {
        text-align: right;
    }

    .tiptap-editor-vue-wrapper .ProseMirror [style*="text-align: justify"] {
        text-align: justify;
    }
</style>
@endpush
