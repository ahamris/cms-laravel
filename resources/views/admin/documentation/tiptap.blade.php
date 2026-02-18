<x-layouts.admin title="TipTap Editor - Components">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Examples -->
        <div class="space-y-8">
            <!-- Basic Editor -->
            <div>
                @php
                    $code1 = '<x-tiptap name="content" />';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Basic Editor</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code1 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <x-tiptap name="demo1" />
                </div>
            </div>

            <!-- With Placeholder and Label -->
            <div>
                @php
                    $code2 = '<x-tiptap 
    name="content" 
    label="Content"
    placeholder="Start typing your content..."
    :required="true"
/>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Label & Placeholder</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code2 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <x-tiptap 
                        name="demo2" 
                        label="Content"
                        placeholder="Start typing your content..."
                        :required="true"
                    />
                </div>
            </div>

            <!-- With Initial Value -->
            <div>
                @php
                    $code3 = '<x-tiptap 
    name="content" 
    label="Pre-filled Content"
    :value="$post->content"
/>';
                    $demoContent = '<h2>Welcome to TipTap Editor</h2><p>This is a <strong>rich text editor</strong> with many features:</p><ul><li>Bold, italic, underline text</li><li>Headings (H1, H2, H3)</li><li>Lists and task lists</li><li>Links and code blocks</li></ul><blockquote>TipTap is a headless, framework-agnostic editor.</blockquote>';
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">With Initial Value</h3>
                    <button 
                        type="button"
                        class="p-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                        data-code="{{ $code3 }}"
                        x-data="{ copied: false }"
                        x-on:click="navigator.clipboard.writeText($el.dataset.code); copied = true; setTimeout(() => copied = false, 2000);"
                    >
                        <i class="fa-solid text-sm" x-bind:class="copied ? 'fa-check text-green-600 dark:text-green-400' : 'fa-copy'"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <x-tiptap 
                        name="demo3" 
                        label="Pre-filled Content"
                        :value="$demoContent"
                    />
                </div>
            </div>

        </div>

        <!-- Right Column: Documentation -->
        <div>
            <div>
                <h3 class="text-xl font-semibold mb-4">Documentation</h3>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-6">
                <div>
                    <h4 class="text-lg font-semibold mb-3">Basic Usage</h4>
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;x-tiptap name="content" /&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Props</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">name</code> - string (required, form field name)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">value</code> - string (initial HTML content)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">placeholder</code> - string (default: 'Start typing...')</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">label</code> - string|null (optional label)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">required</code> - boolean (default: false)</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">id</code> - string|null (custom editor ID)</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Form Integration</h4>
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;form action="{{ route('posts.store') }}" method="POST"&gt;
    @csrf
    
    &lt;x-tiptap 
        name="content" 
        label="Content"
        :value="old('content')"
        :required="true"
    /&gt;
    
    &lt;button type="submit"&gt;Save&lt;/button&gt;
&lt;/form&gt;</code></pre>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                        The component includes a hidden input that automatically syncs with the editor content.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Features</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li>Rich text formatting (bold, italic, underline, strikethrough)</li>
                        <li>Headings (H1, H2, H3)</li>
                        <li>Lists (ordered, unordered, task lists)</li>
                        <li>Links with URL prompt</li>
                        <li>Text alignment (left, center, right, justify)</li>
                        <li>Code blocks and inline code</li>
                        <li>Blockquotes and horizontal rules</li>
                        <li>Subscript/Superscript</li>
                        <li>Undo/Redo support</li>
                        <li>Editor/HTML tab switching</li>
                        <li>Dark mode support</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Examples</h4>
                    <pre class="bg-gray-50 dark:bg-gray-900 p-3 rounded text-xs overflow-x-auto"><code class="language-xml">&lt;!-- Basic --&gt;
&lt;x-tiptap name="content" /&gt;
 
&lt;!-- With label and placeholder --&gt;
&lt;x-tiptap 
    name="content" 
    label="Blog Content"
    placeholder="Write your blog post..."
/&gt;
 
&lt;!-- With initial value --&gt;
&lt;x-tiptap 
    name="content" 
    :value="$post->content"
/&gt;
 
&lt;!-- Required field --&gt;
&lt;x-tiptap 
    name="content" 
    label="Content"
    :required="true"
/&gt;
 
&lt;!-- With old() helper for form validation --&gt;
&lt;x-tiptap 
    name="content" 
    :value="old('content', $post->content ?? '')"
/&gt;</code></pre>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-3">Keyboard Shortcuts</h4>
                    <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-1 list-disc list-inside">
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">Ctrl+B</code> - Bold</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">Ctrl+I</code> - Italic</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">Ctrl+U</code> - Underline</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">Ctrl+Z</code> - Undo</li>
                        <li><code class="text-xs bg-gray-50 dark:bg-gray-900 px-1 py-0.5 rounded">Ctrl+Y</code> - Redo</li>
                    </ul>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-3">
                        See <a href="https://tiptap.dev/docs" target="_blank" class="text-blue-600 dark:text-blue-400 underline">TipTap Documentation</a> for more details.
                    </p>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>
