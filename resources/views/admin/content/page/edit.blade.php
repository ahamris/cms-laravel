<x-layouts.admin title="Edit Page">
    <div id="page-builder-app" v-cloak x-ignore>
        {{--
        The Vue App will mount here.
        We will reconstruct the HTML structure inside the Vue template
        or use inline templates if we want to keep some Blade rendering.
        For "100% design fidelity", we will likely move the HTML into the Vue component
        or use x-template.

        For now, this is the wrapper.
        --}}
        <div class="flex justify-center items-center h-64" v-if="!isReady">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
        </div>

        {{-- Fixed Toolbox --}}
        <div v-if="toolbox.show && isEditingMode" class="ct-toolbox"
            :class="{ 'ct-toolbox-minimized': toolbox.isMinimized }"
            :style="{ top: toolbox.position.top + 'px', left: toolbox.position.left + 'px' }">

            {{-- Column 1: Drag Handle --}}
            <div class="ct-col-drag" @mousedown="startDrag">
                <i class="fas fa-grip-vertical"></i>
            </div>

            {{-- Column 2: Content --}}
            <div class="ct-col-content">
                {{-- Header: Toggle Button --}}
                <div class="ct-toolbox-header">
                    <button @click="toggleToolboxMinimize" class="ct-toolbox-toggle-btn" type="button"
                        :title="toolbox.isMinimized ? 'Open Toolbox' : 'Close Toolbox'">
                        <i class="fas" :class="toolbox.isMinimized ? 'fa-plus' : 'fa-minus'"></i>
                    </button>
                    <span v-if="!toolbox.isMinimized" class="ct-toolbox-title">Toolbox</span>

                    {{-- Tooltip Popup --}}
                    <div v-if="toolbox.showTooltip" class="ct-toolbox-tooltip">
                        <div class="ct-tooltip-arrow"></div>
                        <span>Open toolbox here</span>
                    </div>
                </div>

                {{-- Tools Body --}}
                <div class="ct-toolbox-body" v-show="!toolbox.isMinimized">
                    {{-- Colors Row (Top) --}}
                    <div class="ct-colors-row">
                        <div v-for="tool in getToolsByGroup('color')" :key="tool.name"
                            class="ct-tool-button ct-color-btn"
                            :class="{ active: toolbox.activeTools.includes(tool.name) }"
                            @mousedown.prevent="executeTool(tool)" :title="tool.name">
                            <i class="fas" :class="tool.icon" :style="tool.style"></i>
                        </div>
                    </div>

                    {{-- Main Tools Area (Vertical Columns) --}}
                    <div class="ct-tools-container">
                        <div class="ct-tool-group-column"
                            v-for="(group, index) in uniqueToolGroups.filter(g => g !== 'color')" :key="index">
                            <div v-for="tool in getToolsByGroup(group)" :key="tool.name" class="ct-tool-button"
                                :class="{ active: toolbox.activeTools.includes(tool.name) }"
                                @mousedown.prevent="executeTool(tool)" :title="tool.name">
                                <i class="fas" :class="tool.icon" :style="tool.style"></i>
                                <span v-if="tool.text" style="font-size: 10px; font-weight: bold;">@{{ tool.text
                                    }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="file" id="toolbox-image-upload" style="display: none" @change="handleImageUpload">
        </div>

        {{-- Toast Notifications --}}
        <div class="ct-toast-container">
            <div v-for="toast in toasts" :key="toast.id" class="ct-toast" :class="toast.type">
                <i class="fas ct-toast-icon" :class="toast.icon"></i>
                <span>@{{ toast.message }}</span>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-gray-50/50 rounded-lg p-4">
            <form action="{{ route('admin.content.page.update', $page) }}" method="POST" enctype="multipart/form-data"
                id="vue-page-form" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Hidden Inputs for Vue Data --}}
                <input type="hidden" name="title" :value="form.title">
                <input type="hidden" name="slug" :value="form.slug">
                <input type="hidden" name="page_type" :value="form.page_type">
                <input type="hidden" name="layout_type" :value="form.layout_type">
                <input type="hidden" name="design_type" :value="form.design_type">
                <input type="hidden" name="header_block" :value="form.header_block">
                <input type="hidden" name="footer_block" :value="form.footer_block">
                <input type="hidden" name="hide_header" :value="form.hide_header ? 1 : 0">
                <input type="hidden" name="hide_footer" :value="form.hide_footer ? 1 : 0">
                <input type="hidden" name="home_page" :value="form.home_page ? 1 : 0">
                <input type="hidden" name="is_active" :value="form.is_active ? 1 : 0">
                <input type="hidden" name="widget_config" :value="JSON.stringify(blocks)">

                {{-- Main Grid --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <div class="flex flex-col gap-4">
                            {{-- Title & Slug --}}
                            <div class="bg-white rounded-md border border-gray-200">
                                <div class="p-6">
                                    <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                                        <span
                                            class="flex items-center justify-center w-7 h-7 rounded-md bg-primary/10 mr-2.5">
                                            <i class="fa-solid fa-file-lines text-primary text-xs"></i>
                                        </span>
                                        Page Information
                                    </h3>
                                    <div class="space-y-4">
                                        {{-- Title --}}
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Title</label>
                                            <input type="text" v-model="form.title" @input="generateSlugFromTitle"
                                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors"
                                                placeholder="Enter page title">
                                        </div>
                                        {{-- Slug --}}
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1.5">URL
                                                Slug</label>
                                            <input type="text" v-model="form.slug"
                                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors"
                                                placeholder="page-slug">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Page Type --}}
                            <div class="bg-white rounded-md border border-gray-200">
                                <div class="p-6">
                                    <label class="block text-xs font-medium text-gray-700 mb-4">
                                        Page Type <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 gap-4">
                                        {{-- Standard Page --}}
                                        <label
                                            class="group relative flex rounded-lg border border-gray-300 bg-white p-4 cursor-pointer"
                                            :class="{'ring-2 ring-indigo-600 ring-offset-2': form.page_type === 'static'}">
                                            <input type="radio" value="static" v-model="form.page_type" class="sr-only">
                                            <div class="flex-1">
                                                <span class="block text-sm font-medium text-gray-900">Standard</span>
                                                <span class="mt-1 block text-sm text-gray-500">Traditional content with
                                                    text and images</span>
                                            </div>
                                            <svg v-if="form.page_type === 'static'" viewBox="0 0 20 20"
                                                fill="currentColor" class="size-5 text-indigo-600">
                                                <path
                                                    d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                                    clip-rule="evenodd" fill-rule="evenodd" />
                                            </svg>
                                        </label>

                                        {{-- Showcase Page --}}
                                        <label
                                            class="group relative flex rounded-lg border border-gray-300 bg-white p-4 cursor-pointer"
                                            :class="{'ring-2 ring-indigo-600 ring-offset-2': form.page_type === 'showcase'}">
                                            <input type="radio" value="showcase" v-model="form.page_type"
                                                class="sr-only">
                                            <div class="flex-1">
                                                <span class="block text-sm font-medium text-gray-900">Showcase</span>
                                                <span class="mt-1 block text-sm text-gray-500">Dynamic widgets and
                                                    sections</span>
                                            </div>
                                            <svg v-if="form.page_type === 'showcase'" viewBox="0 0 20 20"
                                                fill="currentColor" class="size-5 text-indigo-600">
                                                <path
                                                    d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                                    clip-rule="evenodd" fill-rule="evenodd" />
                                            </svg>
                                        </label>
                                    </div>
                                    <p v-if="errors.page_type" class="mt-2 text-xs text-red-600"
                                        v-text="errors.page_type[0]"></p>

                                    {{-- Layout Settings --}}
                                    <div class="mt-6 transition-all duration-300">
                                        <label class="block text-xs font-medium text-gray-700 mb-4">Layout
                                            Settings</label>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-2">Layout
                                                Type</label>
                                            <select v-model="form.layout_type"
                                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                                <option value="">Default (Keep component's original width)</option>
                                                <option value="full-width">Full Width</option>
                                                <option value="container">Container (mx-auto)</option>
                                                <option value="max-w-2xl">Max Width: 2xl</option>
                                                <option value="max-w-4xl">Max Width: 4xl</option>
                                                <option value="max-w-6xl">Max Width: 6xl</option>
                                                <option value="max-w-7xl">Max Width: 7xl</option>
                                            </select>
                                            <p class="mt-1 text-xs text-gray-500">This setting will override container
                                                and max-width classes in all components</p>
                                            <p v-if="errors.layout_type" class="mt-1 text-xs text-red-600"
                                                v-text="errors.layout_type[0]"></p>
                                        </div>
                                    </div>

                                    {{-- Design Type (Static) --}}
                                    <div v-show="isStatic" class="mt-6 transition-all duration-300">
                                        <label class="block text-xs font-medium text-gray-700 mb-4">Design Type</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            <label
                                                class="group relative flex rounded-lg border border-gray-300 bg-white p-4 cursor-pointer"
                                                :class="{'ring-2 ring-indigo-600 ring-offset-2': form.design_type === 'general'}">
                                                <input type="radio" value="general" v-model="form.design_type"
                                                    class="sr-only">
                                                <div class="flex-1">
                                                    <span class="block text-sm font-medium text-gray-900">General</span>
                                                </div>
                                                <svg v-if="form.design_type === 'general'" viewBox="0 0 20 20"
                                                    fill="currentColor" class="size-5 text-indigo-600">
                                                    <path
                                                        d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                                        clip-rule="evenodd" fill-rule="evenodd" />
                                                </svg>
                                            </label>

                                            <label
                                                class="group relative flex rounded-lg border border-gray-300 bg-white p-4 cursor-pointer"
                                                :class="{'ring-2 ring-indigo-600 ring-offset-2': form.design_type === 'custom'}">
                                                <input type="radio" value="custom" v-model="form.design_type"
                                                    class="sr-only">
                                                <div class="flex-1">
                                                    <span class="block text-sm font-medium text-gray-900">Custom</span>
                                                </div>
                                                <svg v-if="form.design_type === 'custom'" viewBox="0 0 20 20"
                                                    fill="currentColor" class="size-5 text-indigo-600">
                                                    <path
                                                        d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                                        clip-rule="evenodd" fill-rule="evenodd" />
                                                </svg>
                                            </label>
                                        </div>

                                        {{-- Custom Header/Footer --}}
                                        <div v-show="isCustomDesign" class="mt-6 space-y-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-2">Header
                                                    Block</label>
                                                <select v-model="form.header_block"
                                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                                    <option value="">Select header block...</option>
                                                    <option v-for="block in resources.headerBlocks" :value="block.path"
                                                        :key="block.path" v-text="block.name"></option>
                                                </select>
                                                <p v-if="errors.header_block" class="mt-1 text-xs text-red-600"
                                                    v-text="errors.header_block[0]"></p>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-2">Footer
                                                    Block</label>
                                                <select v-model="form.footer_block"
                                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                                    <option value="">Select footer block...</option>
                                                    <option v-for="block in resources.footerBlocks" :value="block.path"
                                                        :key="block.path" v-text="block.name"></option>
                                                </select>
                                                <p v-if="errors.footer_block" class="mt-1 text-xs text-red-600"
                                                    v-text="errors.footer_block[0]"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Static Fields (Short/Long Body) --}}
                            <div v-show="isStatic" class="space-y-6">
                                {{-- Page Image (Standard pages only) --}}
                                <div class="bg-white rounded-md border border-gray-200">
                                    <div class="p-6">
                                        <label class="block text-xs font-medium text-gray-700 mb-2">Page Image</label>
                                        <p class="text-xs text-gray-500 mb-3">Optional featured image shown above the
                                            main content on the front.</p>
                                        @if($page->image ?? false)
                                            <div class="mb-3 flex items-center gap-4">
                                                <img src="{{ asset('storage/' . $page->image) }}" alt=""
                                                    class="h-24 w-32 rounded object-cover border border-gray-200">
                                                <label class="flex items-center gap-2 text-sm text-gray-600">
                                                    <input type="checkbox" name="remove_image" value="1"
                                                        class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                                    <span>Remove image</span>
                                                </label>
                                            </div>
                                        @endif
                                        <input type="file" name="image"
                                            accept="image/jpeg,image/png,image/gif,image/webp"
                                            class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                                        @if($page->image ?? false)
                                            <p class="mt-1 text-xs text-gray-500">Choose a new file to replace the current
                                                image.</p>
                                        @endif
                                        <p v-if="errors.image" class="mt-1 text-xs text-red-600"
                                            v-text="errors.image[0]"></p>
                                    </div>
                                </div>

                                <div class="bg-white rounded-md border border-gray-200">
                                    <div class="p-6 space-y-6">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Short Body <span
                                                    class="text-red-500">*</span></label>
                                            <textarea v-model="form.short_body" name="short_body" rows="4"
                                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none"
                                                :class="{'border-red-500': errors.short_body}"></textarea>
                                            <p v-if="errors.short_body" class="mt-1 text-sm text-red-600"
                                                v-text="errors.short_body[0]"></p>
                                        </div>
                                        <div>
                                            {{-- TipTap Editor for Vue.js --}}
                                            <x-editor-vue id="long_body" name="long_body"
                                                placeholder="Write the full content here..." :value="old('long_body', $page->long_body ?? '')" />

                                            @error('long_body')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Marketing Automation --}}
                                <div class="bg-white rounded-md border border-gray-200">
                                    <div class="p-6 space-y-6">
                                        <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                                            <span
                                                class="flex items-center justify-center w-7 h-7 rounded-md bg-purple-50 mr-2.5">
                                                <i class="fa-solid fa-bullhorn text-purple-500 text-xs"></i>
                                            </span>
                                            Marketing Automation
                                        </h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Funnel
                                                    Phase</label>
                                                <select v-model="form.funnel_fase" name="funnel_fase"
                                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                                    <option value="">Select funnel phase</option>
                                                    <option value="interesseer">Interesseer (Interest)</option>
                                                    <option value="overtuig">Overtuig (Convince)</option>
                                                    <option value="activeer">Activeer (Activate)</option>
                                                    <option value="inspireer">Inspireer (Inspire)</option>
                                                </select>
                                                <p v-if="errors.funnel_fase" class="mt-1 text-sm text-red-600"
                                                    v-text="errors.funnel_fase[0]"></p>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Target
                                                    Persona</label>
                                                <select v-model="form.marketing_persona_id" name="marketing_persona_id"
                                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                                    <option value="">Select target persona</option>
                                                    <option v-for="persona in resources.marketingPersonas"
                                                        :value="persona.id" :key="persona.id" v-text="persona.name">
                                                    </option>
                                                </select>
                                                <p v-if="errors.marketing_persona_id" class="mt-1 text-sm text-red-600"
                                                    v-text="errors.marketing_persona_id[0]"></p>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Content
                                                    Type</label>
                                                <select v-model="form.content_type_id" name="content_type_id"
                                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                                    <option value="">Select content type</option>
                                                    <option v-for="type in resources.contentTypes" :value="type.id"
                                                        :key="type.id" v-text="type.name"></option>
                                                </select>
                                                <p v-if="errors.content_type_id" class="mt-1 text-sm text-red-600"
                                                    v-text="errors.content_type_id[0]"></p>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Primary
                                                    Keyword</label>
                                                <input type="text" v-model="form.primary_keyword" name="primary_keyword"
                                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                                <p v-if="errors.primary_keyword" class="mt-1 text-sm text-red-600"
                                                    v-text="errors.primary_keyword[0]"></p>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Secondary
                                                    Keywords</label>
                                                <div
                                                    class="flex flex-wrap gap-2 p-2 bg-white border border-gray-200 rounded-md min-h-[38px]">
                                                    <div v-for="(keyword, index) in form.secondary_keywords"
                                                        :key="index"
                                                        class="flex items-center bg-gray-100 rounded px-2 py-1 text-xs">
                                                        <span v-text="keyword"></span>
                                                        <button type="button" @click="removeSecondaryKeyword(index)"
                                                            class="ml-2 text-gray-500 hover:text-red-500 focus:outline-none">
                                                            <i class="fa-solid fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" @keydown.enter.prevent="addSecondaryKeyword"
                                                        @keydown.comma.prevent="addSecondaryKeyword"
                                                        placeholder="Add keyword..."
                                                        class="flex-1 bg-transparent border-none focus:ring-0 text-xs p-0 min-w-[80px]">
                                                </div>
                                                <p class="mt-1 text-[10px] text-gray-500">Press Enter or Comma to add
                                                    tags</p>
                                                <p v-if="errors.secondary_keywords" class="mt-1 text-sm text-red-600"
                                                    v-text="errors.secondary_keywords[0]"></p>
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-xs font-medium text-gray-700 mb-1">AI
                                                    Briefing</label>
                                                <textarea v-model="form.ai_briefing" name="ai_briefing" rows="4"
                                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none"></textarea>
                                                <p v-if="errors.ai_briefing" class="mt-1 text-sm text-red-600"
                                                    v-text="errors.ai_briefing[0]"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Showcase Widget Selector --}}
                            <div v-show="isShowcase" class="space-y-6">
                                {{-- Header Section --}}
                                <div class="bg-white rounded-md border border-gray-200">
                                    <div class="p-6 space-y-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">Header (Header/Hero)
                                                </h3>
                                                <p class="text-sm text-gray-500 mt-1">Header section and hero sections
                                                </p>
                                            </div>
                                            <button type="button" @click="openBlockSelector('header')"
                                                class="px-4 py-2 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/80 transition-colors duration-200">
                                                Add Block
                                            </button>
                                        </div>

                                        {{-- Header Blocks List --}}
                                        <div v-show="headerBlocks.length > 0" class="space-y-3">
                                            <div v-for="(block, index) in headerBlocks" :key="block.id" draggable="true"
                                                @dragstart="handleDragStart(index, 'header', $event)"
                                                @dragover="handleDragOver(index, 'header', $event)"
                                                @drop="handleDrop(index, 'header', $event)" @dragend="handleDragEnd"
                                                :class="{'opacity-50': draggedBlockIndex === index && draggedBlockRegion === 'header'}"
                                                class="rounded-md border border-gray-200 bg-gray-50 px-4 py-3 cursor-move transition-opacity hover:border-primary">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-shrink-0 text-gray-400">
                                                        <i class="fa-solid fa-grip-vertical"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900"
                                                            v-text="block.name"></p>
                                                        <p class="text-xs text-gray-500"
                                                            v-text="formatCategoryName(block.category || '') + ' > ' + (block.section || '')">
                                                        </p>
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        {{-- View Button --}}
                                                        <button type="button" @click="viewBlock(block.id)"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                                            title="Preview">
                                                            <i class="fa-solid fa-eye text-sm"></i>
                                                        </button>

                                                        {{-- Visual Editor Button --}}
                                                        <button type="button" @click="editBlock(block.id)"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                                            title="Visual Editor">
                                                            <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                                                        </button>

                                                        {{-- Edit HTML Button --}}
                                                        <button type="button" @click="editBlockHtml(block.id)"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-500 hover:text-green-600 hover:bg-green-50 transition-colors"
                                                            title="Edit as HTML">
                                                            <i class="fa-solid fa-code text-sm"></i>
                                                        </button>

                                                        {{-- Delete Button --}}
                                                        <button type="button" @click="removeBlock(block.id)"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                                            title="Delete">
                                                            <i class="fa-solid fa-trash text-sm"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-show="headerBlocks.length === 0"
                                            class="rounded-md border-2 border-dashed border-gray-300 p-8 text-center">
                                            <p class="text-sm text-gray-500">No header blocks selected. Click 'Add
                                                Block' to add header/hero sections.</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Body Section --}}
                                <div class="bg-white rounded-md border border-gray-200">
                                    <div class="p-6 space-y-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">Body</h3>
                                                <p class="text-sm text-gray-500 mt-1">All sections except header and
                                                    hero</p>
                                            </div>
                                            <button type="button" @click="openBlockSelector('body')"
                                                class="px-4 py-2 rounded-md bg-primary text-white text-sm font-medium hover:bg-primary/80 transition-colors duration-200">
                                                Add Block
                                            </button>
                                        </div>

                                        {{-- Body Blocks List --}}
                                        <div v-show="bodyBlocks.length > 0" class="space-y-3">
                                            <div v-for="(block, index) in bodyBlocks" :key="block.id" draggable="true"
                                                @dragstart="handleDragStart(index, 'body', $event)"
                                                @dragover="handleDragOver(index, 'body', $event)"
                                                @drop="handleDrop(index, 'body', $event)" @dragend="handleDragEnd"
                                                :class="{'opacity-50': draggedBlockIndex === index && draggedBlockRegion === 'body'}"
                                                class="rounded-md border border-gray-200 bg-gray-50 px-4 py-3 cursor-move transition-opacity hover:border-primary">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-shrink-0 text-gray-400">
                                                        <i class="fa-solid fa-grip-vertical"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900"
                                                            v-text="block.name"></p>
                                                        <p class="text-xs text-gray-500"
                                                            v-text="formatCategoryName(block.category || '') + ' > ' + (block.section || '')">
                                                        </p>
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        {{-- View Button --}}
                                                        <button type="button" @click="viewBlock(block.id)"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                                            title="Preview">
                                                            <i class="fa-solid fa-eye text-sm"></i>
                                                        </button>

                                                        {{-- Visual Editor Button --}}
                                                        <button type="button" @click="editBlock(block.id)"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                                            title="Visual Editor">
                                                            <i class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                                                        </button>

                                                        {{-- Edit HTML Button --}}
                                                        <button type="button" @click="editBlockHtml(block.id)"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-500 hover:text-green-600 hover:bg-green-50 transition-colors"
                                                            title="Edit as HTML">
                                                            <i class="fa-solid fa-code text-sm"></i>
                                                        </button>

                                                        {{-- Delete Button --}}
                                                        <button type="button" @click="removeBlock(block.id)"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                                            title="Delete">
                                                            <i class="fa-solid fa-trash text-sm"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-show="bodyBlocks.length === 0"
                                            class="rounded-md border-2 border-dashed border-gray-300 p-8 text-center">
                                            <p class="text-sm text-gray-500">No body blocks selected. Click 'Add Block'
                                                to add content sections.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Block Selection Modal --}}
                            <div v-if="showBlockSelector"
                                class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4"
                                @click.self="showBlockSelector = false">
                                <div
                                    class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
                                    {{-- Modal Header --}}
                                    <div class="flex items-center justify-between p-6 border-b border-gray-200">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">Select UI Block</h3>
                                            <p class="text-sm text-gray-500 mt-1"
                                                v-text="blockSelectorType === 'header' ? 'Select Header or Hero sections' : 'Select Body sections (excluding Header/Hero)'">
                                            </p>
                                        </div>
                                        <button type="button" @click="showBlockSelector = false"
                                            class="text-gray-400 hover:text-gray-600">
                                            <i class="fa-solid fa-times text-xl"></i>
                                        </button>
                                    </div>

                                    {{-- Tabs --}}
                                    <div class="flex border-b border-gray-200">
                                        <button type="button" @click="activeSelectorTab = 'select'"
                                            :class="activeSelectorTab === 'select' ? 'border-b-2 border-primary text-primary' : 'text-gray-500'"
                                            class="px-6 py-3 font-medium text-sm transition-colors">Select
                                            Block</button>
                                        <button type="button" @click="activeSelectorTab = 'paste'"
                                            :class="activeSelectorTab === 'paste' ? 'border-b-2 border-primary text-primary' : 'text-gray-500'"
                                            class="px-6 py-3 font-medium text-sm transition-colors">Paste HTML</button>
                                        <button type="button"
                                            @click="activeSelectorTab = 'saved'; loadPresets(selectedPresetType)"
                                            :class="activeSelectorTab === 'saved' ? 'border-b-2 border-primary text-primary' : 'text-gray-500'"
                                            class="px-6 py-3 font-medium text-sm transition-colors">Saved
                                            Predefined</button>
                                    </div>

                                    {{-- Tab Content --}}
                                    <div class="flex-1 overflow-hidden flex flex-col">
                                        {{-- Select Block Tab --}}
                                        <div v-show="activeSelectorTab === 'select'"
                                            class="flex-1 flex flex-col overflow-hidden">
                                            {{-- Fixed Search --}}
                                            <div class="p-6 pb-4 border-b border-gray-200 bg-white flex-shrink-0">
                                                <label class="block text-sm font-semibold text-gray-900 mb-2">Search
                                                    Elements</label>
                                                <div class="relative">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class="fa-solid fa-search text-gray-400"></i>
                                                    </div>
                                                    <input type="text" v-model="componentSearchQuery"
                                                        placeholder="Type to search for elements..."
                                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-primary">
                                                </div>
                                            </div>

                                            {{-- Category Tabs --}}
                                            <div
                                                class="flex border-b border-gray-200 bg-white flex-shrink-0 overflow-x-auto">
                                                <button v-for="category in Object.keys(filteredComponents)"
                                                    :key="category" type="button" @click="activeCategoryTab = category"
                                                    :class="activeCategoryTab === category ? 'border-b-2 border-primary text-primary font-semibold' : 'text-gray-500 hover:text-gray-700'"
                                                    class="px-6 py-3 font-medium text-sm transition-colors whitespace-nowrap"
                                                    v-text="formatCategoryName(category)">
                                                </button>
                                            </div>

                                            {{-- Scrollable Content --}}
                                            <div class="flex-1 overflow-y-auto p-6">
                                                <div class="space-y-4">
                                                    <div v-for="(sections, category) in filteredComponents"
                                                        :key="category" v-show="activeCategoryTab === category">
                                                        <div class="space-y-2 ml-4">
                                                            <div v-for="(section, sectionName) in sections"
                                                                :key="sectionName">
                                                                <h6 class="text-sm text-gray-600 mb-1"
                                                                    v-text="section.name || sectionName"></h6>
                                                                <div class="space-y-2">
                                                                    <div v-for="component in section.components"
                                                                        :key="component.id"
                                                                        class="flex items-center justify-between p-2 hover:bg-gray-50 rounded border border-gray-200">
                                                                        <span
                                                                            class="text-sm text-gray-900 flex-1 truncate"
                                                                            v-text="component.name"></span>
                                                                        <div class="flex gap-2 ml-2">
                                                                            <button type="button"
                                                                                @click="previewComponent(component)"
                                                                                class="text-xs text-blue-600 hover:underline whitespace-nowrap">Preview</button>
                                                                            <button type="button"
                                                                                @click="addComponent(category, sectionName, component)"
                                                                                class="text-xs text-green-600 hover:underline whitespace-nowrap">Add</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-show="Object.keys(filteredComponents).length === 0"
                                                        class="text-center py-8 text-gray-500">
                                                        <p>No elements found matching your search.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Paste HTML Tab --}}
                                        <div v-show="activeSelectorTab === 'paste'" class="p-6 space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Component
                                                    Name</label>
                                                <input type="text" v-model="customBlockName"
                                                    class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                                    placeholder="Enter component name...">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">HTML
                                                    Code</label>
                                                <textarea v-model="customBlockHtml" rows="12"
                                                    class="w-full px-3 py-2 text-sm font-mono bg-white border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                                    placeholder="Paste your HTML code here..."></textarea>
                                            </div>
                                            <div class="flex justify-end">
                                                <button type="button" @click="addCustomBlock"
                                                    class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 transition-colors font-medium">
                                                    <i class="fa-solid fa-plus mr-2"></i> Add Block
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Saved Presets Tab --}}
                                        <div v-show="activeSelectorTab === 'saved'" class="p-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <h4 class="text-sm font-semibold text-gray-900">Saved Presets</h4>
                                                <button type="button" @click="openSavePresetModal(blockSelectorType)"
                                                    class="px-3 py-1.5 text-xs bg-primary text-white rounded-md hover:bg-primary/80 transition-colors">
                                                    <i class="fa-solid fa-save mr-1"></i> Save Current
                                                </button>
                                            </div>

                                            {{-- Preset Type Filter --}}
                                            <div class="flex gap-2 mb-4">
                                                <button type="button"
                                                    @click="selectedPresetType = 'header'; loadPresets('header')"
                                                    :class="selectedPresetType === 'header' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700'"
                                                    class="px-3 py-1.5 text-xs rounded-md transition-colors">Header</button>
                                                <button type="button"
                                                    @click="selectedPresetType = 'body'; loadPresets('body')"
                                                    :class="selectedPresetType === 'body' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-700'"
                                                    class="px-3 py-1.5 text-xs rounded-md transition-colors">Body</button>
                                            </div>

                                            {{-- Presets List --}}
                                            <div class="space-y-2 max-h-96 overflow-y-auto">
                                                <div v-for="preset in presets.filter(p => p.type === selectedPresetType)"
                                                    :key="preset.id"
                                                    class="flex items-center justify-between p-3 border border-gray-200 rounded-md hover:bg-gray-50">
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900"
                                                            v-text="preset.name"></p>
                                                        <p v-if="preset.description" class="text-xs text-gray-500"
                                                            v-text="preset.description"></p>
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <button type="button" @click="usePreset(preset)"
                                                            class="px-2 py-1 text-xs text-green-600 hover:bg-green-50 rounded">Use</button>
                                                        <button type="button" @click="deletePreset(preset)"
                                                            class="px-2 py-1 text-xs text-red-600 hover:bg-red-50 rounded">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div v-if="presets.filter(p => p.type === selectedPresetType).length === 0"
                                                    class="text-center py-8 text-gray-500">
                                                    <i class="fa-solid fa-folder-open text-2xl mb-2"></i>
                                                    <p class="text-sm">No saved presets yet.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Image Edit Modal --}}
                            <div v-if="modals.imageEdit.show"
                                class="fixed inset-0 z-[70] flex items-center justify-center p-4"
                                @click.self="closeImageEditModal" v-cloak>
                                <div class="fixed inset-0 bg-black/50"></div>
                                <div
                                    class="relative bg-white rounded-lg shadow-2xl w-full max-w-xl p-8 z-10 max-h-[85vh] overflow-y-auto">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Edit Image</h3>
                                        <button type="button" @click="closeImageEditModal"
                                            class="text-gray-500 hover:text-gray-700 transition-colors">
                                            <i class="fa-solid fa-times text-lg"></i>
                                        </button>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Upload
                                                Image:</label>
                                            <input type="file" @change="handleImageFileSelect" accept="image/*"
                                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                            <p class="mt-1 text-xs text-gray-500">Or enter URL below</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Image
                                                URL:</label>
                                            <input type="text" v-model="imageEditData.url"
                                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none"
                                                placeholder="https://example.com/image.jpg">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Alt
                                                Text:</label>
                                            <input type="text" v-model="imageEditData.alt"
                                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none"
                                                placeholder="Image description">
                                        </div>

                                        <div class="flex items-center gap-3 pt-4">
                                            <button type="button" @click="deleteImage"
                                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors font-medium">
                                                <i class="fa-solid fa-trash mr-2"></i> Delete Image
                                            </button>
                                            <button type="button" @click="toggleImageVisibility"
                                                class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-900 transition-colors font-medium"
                                                v-text="imageEditData.disabled ? 'Enable Image' : 'Disable Image'"></button>
                                            <button type="button" @click="saveImageEdit"
                                                class="flex-1 px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 transition-colors font-medium">Save</button>
                                            <button type="button" @click="closeImageEditModal"
                                                class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Link Edit Modal --}}
                            <div v-if="modals.linkEdit.show"
                                class="fixed inset-0 z-[70] flex items-center justify-center p-4"
                                @click.self="closeLinkEditModal" v-cloak>
                                <div class="fixed inset-0 bg-black/50"></div>
                                <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-md p-6 z-10">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Edit Link</h3>
                                        <button type="button" @click="closeLinkEditModal"
                                            class="text-gray-500 hover:text-gray-700 transition-colors">
                                            <i class="fa-solid fa-times text-lg"></i>
                                        </button>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Link
                                                URL:</label>
                                            <input type="text" v-model="linkEditData.href"
                                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none"
                                                placeholder="https://example.com or #">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Link
                                                Text:</label>
                                            <input type="text" v-model="linkEditData.text"
                                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none"
                                                placeholder="Link text">
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Background
                                                Color</label>
                                            <div class="flex items-center gap-3">
                                                <input type="color" v-model="linkEditData.backgroundColor"
                                                    class="h-10 w-20 rounded-lg border border-gray-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                                                    title="Select background color">
                                                <input type="text" v-model="linkEditData.backgroundColor"
                                                    class="flex-1 px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                                    placeholder="#3b82f6 or rgba(59, 130, 246, 1)">
                                                <button type="button" @click="linkEditData.backgroundColor = ''"
                                                    class="px-3 py-2.5 text-sm text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg border border-gray-200 transition-colors"
                                                    title="Clear color">
                                                    <i class="fa-solid fa-times"></i>
                                                </button>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-400">Color will be applied as inline style
                                            </p>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Text
                                                Color</label>
                                            <div class="flex items-center gap-3">
                                                <input type="color" v-model="linkEditData.color"
                                                    class="h-10 w-20 rounded-lg border border-gray-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                                                    title="Select text color">
                                                <input type="text" v-model="linkEditData.color"
                                                    class="flex-1 px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                                    placeholder="#ffffff or rgba(255, 255, 255, 1)">
                                                <button type="button" @click="linkEditData.color = ''"
                                                    class="px-3 py-2.5 text-sm text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg border border-gray-200 transition-colors"
                                                    title="Clear color">
                                                    <i class="fa-solid fa-times"></i>
                                                </button>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-400">Color will be applied as inline style
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 pt-4 mt-4 border-t border-gray-200">
                                        <button type="button" @click="closeLinkEditModal"
                                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Cancel</button>
                                        <button type="button" @click="saveLinkEdit"
                                            class="flex-1 px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 transition-colors font-medium">Save</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Element Settings Modal --}}
                            <div v-if="modals.elementSettings.show"
                                class="fixed inset-0 z-[70] flex items-center justify-center p-4"
                                @click.self="closeElementSettingsModal" v-cloak>
                                <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity"></div>
                                <div
                                    class="relative bg-white rounded-xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all border border-gray-100">
                                    <div
                                        class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                                        <h3 class="text-lg font-semibold text-gray-800">Element Settings</h3>
                                        <button type="button" @click="closeElementSettingsModal"
                                            class="text-gray-400 hover:text-gray-600 transition-colors">
                                            <i class="fa-solid fa-times text-lg"></i>
                                        </button>
                                    </div>

                                    <div class="p-6 space-y-5">
                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">HTML
                                                Tag</label>
                                            <select v-model="elementSettingsData.tag"
                                                class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                                <option v-for="tag in elementSettingsTags" :key="tag.tag"
                                                    :value="tag.tag">
                                                    @{{ tag.label }} (@{{ tag.tag }})
                                                </option>
                                            </select>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Alignment</label>
                                            <div class="flex gap-2 p-1 bg-gray-100 rounded-lg">
                                                <button type="button" @click="elementSettingsData.alignment = 'left'"
                                                    :class="{'bg-white text-primary shadow-sm': elementSettingsData.alignment === 'left', 'text-gray-500 hover:text-gray-700': elementSettingsData.alignment !== 'left'}"
                                                    class="flex-1 py-2 rounded-md text-sm font-medium transition-all">
                                                    <i class="fa-solid fa-align-left"></i>
                                                </button>
                                                <button type="button" @click="elementSettingsData.alignment = 'center'"
                                                    :class="{'bg-white text-primary shadow-sm': elementSettingsData.alignment === 'center', 'text-gray-500 hover:text-gray-700': elementSettingsData.alignment !== 'center'}"
                                                    class="flex-1 py-2 rounded-md text-sm font-medium transition-all">
                                                    <i class="fa-solid fa-align-center"></i>
                                                </button>
                                                <button type="button" @click="elementSettingsData.alignment = 'right'"
                                                    :class="{'bg-white text-primary shadow-sm': elementSettingsData.alignment === 'right', 'text-gray-500 hover:text-gray-700': elementSettingsData.alignment !== 'right'}"
                                                    class="flex-1 py-2 rounded-md text-sm font-medium transition-all">
                                                    <i class="fa-solid fa-align-right"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Container
                                                Width</label>
                                            <select v-model="elementSettingsData.containerWidth"
                                                class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                                <option value="none">No Container (Full Width)</option>
                                                <option value="max-w-7xl">Extra Large (max-w-7xl)</option>
                                                <option value="max-w-6xl">Large (max-w-6xl)</option>
                                                <option value="max-w-5xl">Medium-Large (max-w-5xl)</option>
                                                <option value="max-w-4xl">Medium (max-w-4xl)</option>
                                                <option value="max-w-3xl">Medium-Small (max-w-3xl)</option>
                                                <option value="max-w-2xl">Small (max-w-2xl)</option>
                                                <option value="max-w-xl">Extra Small (max-w-xl)</option>
                                                <option value="max-w-lg">Large Text (max-w-lg)</option>
                                                <option value="max-w-md">Medium Text (max-w-md)</option>
                                                <option value="max-w-sm">Small Text (max-w-sm)</option>
                                            </select>
                                            <p class="mt-1 text-xs text-gray-500">Controls max-width and mx-auto classes
                                            </p>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Background
                                                Image</label>
                                            <div class="space-y-2">
                                                <div class="flex gap-2">
                                                    <div class="relative flex-1">
                                                        <i
                                                            class="fa-solid fa-image absolute left-3.5 top-3 text-gray-400 text-xs"></i>
                                                        <input type="text" v-model="elementSettingsData.backgroundImage"
                                                            class="w-full pl-9 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                                            placeholder="https://example.com/image.jpg">
                                                    </div>
                                                    <input type="file" id="element-background-image-upload"
                                                        accept="image/*" style="display: none"
                                                        @change="handleBackgroundImageUpload">
                                                    <button type="button"
                                                        @click="document.getElementById('element-background-image-upload').click()"
                                                        class="px-4 py-2.5 text-sm bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg transition-colors text-gray-700"
                                                        title="Upload Image">
                                                        <i class="fa-solid fa-upload"></i>
                                                    </button>
                                                </div>
                                                <div v-if="elementSettingsData.backgroundImage" class="mt-2">
                                                    <img :src="elementSettingsData.backgroundImage"
                                                        alt="Background preview"
                                                        class="w-full h-32 object-cover rounded-lg border border-gray-200"
                                                        v-on:error="$event.target.style.display='none'">
                                                </div>
                                                <p class="text-xs text-gray-500">URL of the background image. The image
                                                    will be positioned absolutely with z-index -10.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                                        <button type="button" @click="closeElementSettingsModal"
                                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">Cancel</button>
                                        <button type="button" @click="saveElementSettings"
                                            class="px-6 py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg shadow-sm transition-all transform active:scale-95">Apply</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Button Edit Modal --}}
                            <div v-if="modals.buttonEdit.show"
                                class="fixed inset-0 z-[70] flex items-center justify-center p-4"
                                @click.self="closeButtonEditModal" v-cloak>
                                <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity"></div>
                                <div
                                    class="relative bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-gray-100">
                                    <div
                                        class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                                        <h3 class="text-lg font-semibold text-gray-800">Edit Button</h3>
                                        <button type="button" @click="closeButtonEditModal"
                                            class="text-gray-400 hover:text-gray-600 transition-colors">
                                            <i class="fa-solid fa-times text-lg"></i>
                                        </button>
                                    </div>

                                    <div class="p-6 space-y-5">
                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Button
                                                Text</label>
                                            <input type="text" v-model="buttonEditData.text"
                                                class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                                placeholder="Click me">
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Link
                                                URL</label>
                                            <div class="relative">
                                                <i
                                                    class="fa-solid fa-link absolute left-3.5 top-3 text-gray-400 text-xs"></i>
                                                <input type="text" v-model="buttonEditData.href"
                                                    class="w-full pl-9 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                                    placeholder="https://...">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">OnClick
                                                    (JS)</label>
                                                <input type="text" v-model="buttonEditData.onclick"
                                                    class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                                    placeholder="alert('Hi')">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Classes</label>
                                                <input type="text" v-model="buttonEditData.classes"
                                                    class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                                    placeholder="btn-primary">
                                            </div>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Background
                                                Color</label>
                                            <div class="flex items-center gap-3">
                                                <input type="color" v-model="buttonEditData.backgroundColor"
                                                    class="h-10 w-20 rounded-lg border border-gray-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                                                    title="Select background color">
                                                <input type="text" v-model="buttonEditData.backgroundColor"
                                                    class="flex-1 px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                                    placeholder="#3b82f6 or rgba(59, 130, 246, 1)">
                                                <button type="button" @click="buttonEditData.backgroundColor = ''"
                                                    class="px-3 py-2.5 text-sm text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg border border-gray-200 transition-colors"
                                                    title="Clear color">
                                                    <i class="fa-solid fa-times"></i>
                                                </button>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-400">Color will be applied as inline style
                                            </p>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Text
                                                Color</label>
                                            <div class="flex items-center gap-3">
                                                <input type="color" v-model="buttonEditData.color"
                                                    class="h-10 w-20 rounded-lg border border-gray-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                                                    title="Select text color">
                                                <input type="text" v-model="buttonEditData.color"
                                                    class="flex-1 px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                                    placeholder="#ffffff or rgba(255, 255, 255, 1)">
                                                <button type="button" @click="buttonEditData.color = ''"
                                                    class="px-3 py-2.5 text-sm text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg border border-gray-200 transition-colors"
                                                    title="Clear color">
                                                    <i class="fa-solid fa-times"></i>
                                                </button>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-400">Color will be applied as inline style
                                            </p>
                                        </div>
                                    </div>

                                    <div
                                        class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                                        <button type="button" @click="closeButtonEditModal"
                                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">Cancel</button>
                                        <button type="button" @click="saveButtonEdit"
                                            class="px-6 py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg shadow-sm transition-all transform active:scale-95">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Preview Modal --}}
                            <div v-if="modals.preview.show"
                                class="fixed inset-0 z-[60] flex items-center justify-center p-4"
                                @click.self="closePreviewModal" v-cloak>
                                <div class="fixed inset-0 bg-black/50"></div>

                                {{-- Modal Container --}}
                                <div id="preview-modal-container"
                                    class="relative flex flex-col bg-white rounded-lg shadow-2xl z-10 border-4 border-gray-300 overflow-hidden w-[90vw] h-[90vh]">
                                    {{-- Browser-like Header --}}
                                    <div
                                        class="flex items-center justify-between bg-gray-100 border-b border-gray-300 px-4 py-2 flex-shrink-0">
                                        <div class="flex items-center gap-2">
                                            <div class="flex gap-1.5">
                                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                            </div>
                                            <h3 class="text-sm font-medium text-gray-700 ml-2"
                                                v-text="modals.preview.componentName"></h3>
                                        </div>
                                        <button type="button" @click="closePreviewModal"
                                            class="text-gray-500 hover:text-gray-700 transition-colors">
                                            <i class="fa-solid fa-times text-lg"></i>
                                        </button>
                                    </div>

                                    {{-- Content Area --}}
                                    <div class="flex-1 overflow-auto bg-white relative" style="isolation: isolate;">
                                        <div ref="previewContainer" class="preview-content-wrapper p-4 min-h-full"
                                            :class="{'cursor-text': isEditingMode}" @click="handlePreviewClick"
                                            @contextmenu="handlePreviewRightClick"></div>
                                    </div>

                                    {{-- Footer Actions --}}
                                    <div
                                        class="bg-gray-50 border-t border-gray-200 px-4 py-3 flex items-center justify-between flex-shrink-0">
                                        {{-- Left: Add This Block (only visible when previewing component, not when
                                        editing) --}}
                                        <div v-show="!isEditingMode && previewComponentData"
                                            class="flex items-center gap-3">
                                            <button type="button" @click="addComponentFromPreview"
                                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors font-medium flex items-center gap-2">
                                                <i class="fa-solid fa-plus"></i> Add This Block
                                            </button>
                                        </div>
                                        {{-- Right: Buttons --}}
                                        <div class="flex items-center gap-3 ml-auto">
                                            <template v-if="isEditingMode">
                                                <button type="button" @click="openSpacingModal(activeBlockId)"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-medium flex items-center gap-2">
                                                    <i class="fa-solid fa-arrows-up-down-left-right"></i> Set Spacing
                                                </button>
                                                <button type="button" @click="closePreviewModal"
                                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Cancel</button>
                                                <button type="button" @click="savePreviewModal"
                                                    class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 transition-colors font-medium">Save
                                                    & Close</button>
                                            </template>
                                            <template v-else>
                                                <template v-if="previewComponentData">
                                                    <button type="button" @click="navigatePreviewComponent('previous')"
                                                        :disabled="!canNavigatePrevious"
                                                        :class="!canNavigatePrevious ? 'opacity-50 cursor-not-allowed' : ''"
                                                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors font-medium flex items-center gap-2">
                                                        <i class="fa-solid fa-chevron-left"></i> Previous
                                                    </button>
                                                    <button type="button" @click="navigatePreviewComponent('next')"
                                                        :disabled="!canNavigateNext"
                                                        :class="!canNavigateNext ? 'opacity-50 cursor-not-allowed' : ''"
                                                        class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors font-medium flex items-center gap-2">
                                                        Next <i class="fa-solid fa-chevron-right"></i>
                                                    </button>
                                                </template>
                                                <button type="button" @click="closePreviewModal"
                                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Close</button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Title & Slug Modal --}}
                            <div v-if="modals.titleSlug.show"
                                class="fixed inset-0 z-[70] flex items-center justify-center p-4"
                                @click.self="closeTitleSlugModal" v-cloak>
                                <div class="fixed inset-0 bg-black/50"></div>
                                <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-md p-6 z-10">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Page Title & Slug</h3>
                                        <button type="button" @click="closeTitleSlugModal"
                                            class="text-gray-500 hover:text-gray-700 transition-colors">
                                            <i class="fa-solid fa-times text-lg"></i>
                                        </button>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Page
                                                Title</label>
                                            <input type="text" v-model="modals.titleSlug.tempTitle"
                                                @input="generateSlugFromTitle"
                                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none"
                                                placeholder="Enter page title">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">URL Slug</label>
                                            <div class="flex items-center gap-2">
                                                <input type="text" v-model="modals.titleSlug.tempSlug"
                                                    class="flex-1 px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none"
                                                    placeholder="page-slug">
                                                <div class="flex items-center">
                                                    <input type="checkbox" v-model="modals.titleSlug.autoGenerate"
                                                        id="autoGenerateSlug"
                                                        class="rounded border-gray-300 text-primary focus:ring-primary">
                                                    <label for="autoGenerateSlug"
                                                        class="ml-2 text-xs text-gray-600">Auto</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 pt-4 mt-4 border-t border-gray-200">
                                        <button type="button" @click="closeTitleSlugModal"
                                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Cancel</button>
                                        <button type="button" @click="saveTitleSlug"
                                            class="flex-1 px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 transition-colors font-medium">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Save Preset Modal --}}
                            <div v-if="modals.savePreset.show"
                                class="fixed inset-0 z-[70] flex items-center justify-center p-4"
                                @click.self="closeSavePresetModal" v-cloak>
                                <div class="fixed inset-0 bg-black/50"></div>
                                <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-md p-6 z-10">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Save as Preset</h3>
                                        <button type="button" @click="closeSavePresetModal"
                                            class="text-gray-500 hover:text-gray-700 transition-colors">
                                            <i class="fa-solid fa-times text-lg"></i>
                                        </button>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Preset
                                                Name</label>
                                            <input type="text" v-model="modals.savePreset.name"
                                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none"
                                                placeholder="Enter preset name">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Description
                                                (optional)</label>
                                            <textarea v-model="modals.savePreset.description" rows="3"
                                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none"
                                                placeholder="Enter a description..."></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Type</label>
                                            <select v-model="modals.savePreset.type"
                                                class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                                <option value="header">Header</option>
                                                <option value="body">Body</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 pt-4 mt-4 border-t border-gray-200">
                                        <button type="button" @click="closeSavePresetModal"
                                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Cancel</button>
                                        <button type="button" @click="saveCurrentAsPreset"
                                            class="flex-1 px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 transition-colors font-medium">Save
                                            Preset</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Spacing Edit Modal --}}
                            <div v-if="modals.spacingEdit.show"
                                class="fixed inset-0 z-[70] flex items-center justify-center p-4"
                                @click.self="closeSpacingModal" v-cloak>
                                <div class="fixed inset-0 bg-black/50"></div>
                                <div
                                    class="relative bg-white rounded-lg shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto z-10">
                                    <div
                                        class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                                        <h3 class="text-lg font-semibold text-gray-900">Set Spacing</h3>
                                        <button type="button" @click="closeSpacingModal"
                                            class="text-gray-500 hover:text-gray-700 transition-colors">
                                            <i class="fa-solid fa-times text-lg"></i>
                                        </button>
                                    </div>

                                    <div class="p-6 space-y-6">
                                        {{-- Visual SVG Diagram (Top) --}}
                                        <div
                                            class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-8 border border-gray-200 shadow-sm">
                                            <div class="flex items-center justify-between mb-6">
                                                <h4
                                                    class="text-base font-semibold text-gray-900 flex items-center gap-2">
                                                    <i class="fa-solid fa-diagram-project text-primary"></i>
                                                    Visual Guide (Axis Mode)
                                                </h4>
                                            </div>
                                            <div class="flex justify-center">
                                                <svg viewBox="0 0 400 400" class="w-full max-w-md h-auto font-sans">
                                                    {{-- Definitions --}}
                                                    <defs>
                                                        {{-- Arrow Markers --}}
                                                        <marker id="arrow-green-start" markerWidth="6" markerHeight="6"
                                                            refX="0" refY="3" orient="auto">
                                                            <path d="M6,0 L0,3 L6,6" fill="#059669" />
                                                        </marker>
                                                        <marker id="arrow-green-end" markerWidth="6" markerHeight="6"
                                                            refX="6" refY="3" orient="auto">
                                                            <path d="M0,0 L6,3 L0,6" fill="#059669" />
                                                        </marker>

                                                        <marker id="arrow-blue-start" markerWidth="6" markerHeight="6"
                                                            refX="0" refY="3" orient="auto">
                                                            <path d="M6,0 L0,3 L6,6" fill="#2563eb" />
                                                        </marker>
                                                        <marker id="arrow-blue-end" markerWidth="6" markerHeight="6"
                                                            refX="6" refY="3" orient="auto">
                                                            <path d="M0,0 L6,3 L0,6" fill="#2563eb" />
                                                        </marker>

                                                        <filter id="box-shadow" x="-10%" y="-10%" width="120%"
                                                            height="120%">
                                                            <feGaussianBlur in="SourceAlpha" stdDeviation="3" />
                                                            <feOffset dx="2" dy="2" result="offsetblur" />
                                                            <feComponentTransfer>
                                                                <feFuncA type="linear" slope="0.3" />
                                                            </feComponentTransfer>
                                                            <feMerge>
                                                                <feMergeNode />
                                                                <feMergeNode in="SourceGraphic" />
                                                            </feMerge>
                                                        </filter>
                                                    </defs>

                                                    {{-- Background --}}
                                                    <rect x="0" y="0" width="400" height="400" fill="#f9fafb"
                                                        stroke="#e5e7eb" stroke-width="1" />

                                                    {{-- Margin Area (Green) --}}
                                                    <rect x="30" y="30" width="340" height="340" fill="#10b981"
                                                        fill-opacity="0.08" stroke="#10b981" stroke-width="1.5"
                                                        stroke-dasharray="6,4" rx="8" />
                                                    <text x="360" y="45" text-anchor="end" fill="#059669" font-size="11"
                                                        font-weight="700" opacity="0.6">MARGIN</text>

                                                    {{-- Padding Area (Blue) --}}
                                                    <rect x="90" y="90" width="220" height="220" fill="#3b82f6"
                                                        fill-opacity="0.12" stroke="#3b82f6" stroke-width="1.5"
                                                        rx="6" />
                                                    <text x="300" y="105" text-anchor="end" fill="#2563eb"
                                                        font-size="11" font-weight="700" opacity="0.6">PADDING</text>

                                                    {{-- Content Area (White) --}}
                                                    <rect x="150" y="150" width="100" height="100" fill="#ffffff"
                                                        stroke="#374151" stroke-width="2" rx="4"
                                                        filter="url(#box-shadow)" />
                                                    <text x="200" y="200" text-anchor="middle"
                                                        dominant-baseline="middle" fill="#1f2937" font-size="14"
                                                        font-weight="800">Content</text>

                                                    {{--
                                                    AXIS LABELS AND ARROWS
                                                    --}}

                                                    {{-- MARGIN AXIS (mx, my) --}}
                                                    <g>
                                                        <line x1="200" y1="35" x2="200" y2="85" stroke="#059669"
                                                            stroke-width="2" marker-start="url(#arrow-green-start)"
                                                            marker-end="url(#arrow-green-end)" />
                                                        <rect x="185" y="50" width="30" height="20" rx="4"
                                                            fill="#ffffff" fill-opacity="0.9" />
                                                        <text x="200" y="64" text-anchor="middle" fill="#059669"
                                                            font-family="monospace" font-weight="bold"
                                                            font-size="13">my</text>

                                                        <line x1="200" y1="315" x2="200" y2="365" stroke="#059669"
                                                            stroke-width="2" marker-start="url(#arrow-green-start)"
                                                            marker-end="url(#arrow-green-end)" />
                                                        <rect x="185" y="330" width="30" height="20" rx="4"
                                                            fill="#ffffff" fill-opacity="0.9" />
                                                        <text x="200" y="344" text-anchor="middle" fill="#059669"
                                                            font-family="monospace" font-weight="bold"
                                                            font-size="13">my</text>

                                                        <line x1="35" y1="200" x2="85" y2="200" stroke="#059669"
                                                            stroke-width="2" marker-start="url(#arrow-green-start)"
                                                            marker-end="url(#arrow-green-end)" />
                                                        <rect x="45" y="190" width="30" height="20" rx="4"
                                                            fill="#ffffff" fill-opacity="0.9" />
                                                        <text x="60" y="204" text-anchor="middle" fill="#059669"
                                                            font-family="monospace" font-weight="bold"
                                                            font-size="13">mx</text>

                                                        <line x1="315" y1="200" x2="365" y2="200" stroke="#059669"
                                                            stroke-width="2" marker-start="url(#arrow-green-start)"
                                                            marker-end="url(#arrow-green-end)" />
                                                        <rect x="325" y="190" width="30" height="20" rx="4"
                                                            fill="#ffffff" fill-opacity="0.9" />
                                                        <text x="340" y="204" text-anchor="middle" fill="#059669"
                                                            font-family="monospace" font-weight="bold"
                                                            font-size="13">mx</text>
                                                    </g>

                                                    {{-- PADDING AXIS (px, py) --}}
                                                    <g>
                                                        <line x1="200" y1="95" x2="200" y2="145" stroke="#2563eb"
                                                            stroke-width="2" marker-start="url(#arrow-blue-start)"
                                                            marker-end="url(#arrow-blue-end)" />
                                                        <rect x="185" y="110" width="30" height="20" rx="4"
                                                            fill="#eff6ff" fill-opacity="0.9" />
                                                        <text x="200" y="124" text-anchor="middle" fill="#2563eb"
                                                            font-family="monospace" font-weight="bold"
                                                            font-size="13">py</text>

                                                        <line x1="200" y1="255" x2="200" y2="305" stroke="#2563eb"
                                                            stroke-width="2" marker-start="url(#arrow-blue-start)"
                                                            marker-end="url(#arrow-blue-end)" />
                                                        <rect x="185" y="270" width="30" height="20" rx="4"
                                                            fill="#eff6ff" fill-opacity="0.9" />
                                                        <text x="200" y="284" text-anchor="middle" fill="#2563eb"
                                                            font-family="monospace" font-weight="bold"
                                                            font-size="13">py</text>

                                                        <line x1="95" y1="200" x2="145" y2="200" stroke="#2563eb"
                                                            stroke-width="2" marker-start="url(#arrow-blue-start)"
                                                            marker-end="url(#arrow-blue-end)" />
                                                        <rect x="105" y="190" width="30" height="20" rx="4"
                                                            fill="#eff6ff" fill-opacity="0.9" />
                                                        <text x="120" y="204" text-anchor="middle" fill="#2563eb"
                                                            font-family="monospace" font-weight="bold"
                                                            font-size="13">px</text>

                                                        <line x1="255" y1="200" x2="305" y2="200" stroke="#2563eb"
                                                            stroke-width="2" marker-start="url(#arrow-blue-start)"
                                                            marker-end="url(#arrow-blue-end)" />
                                                        <rect x="265" y="190" width="30" height="20" rx="4"
                                                            fill="#eff6ff" fill-opacity="0.9" />
                                                        <text x="280" y="204" text-anchor="middle" fill="#2563eb"
                                                            font-family="monospace" font-weight="bold"
                                                            font-size="13">px</text>
                                                    </g>
                                                </svg>
                                            </div>
                                        </div>

                                        {{-- Responsive Breakpoint Tabs --}}
                                        <div class="mb-4">
                                            <div class="flex border-b border-gray-200">
                                                <button v-for="bp in spacingBreakpoints" :key="bp.key"
                                                    type="button"
                                                    @click="activeSpacingBreakpoint = bp.key"
                                                    :class="{
                                                        'border-b-2 border-primary text-primary font-semibold': activeSpacingBreakpoint === bp.key,
                                                        'text-gray-500 hover:text-gray-700': activeSpacingBreakpoint !== bp.key
                                                    }"
                                                    class="relative px-4 py-2.5 text-sm transition-colors flex items-center gap-1.5">
                                                    <span>@{{ bp.label }}</span>
                                                    <span class="text-[10px] opacity-60">@{{ bp.desc }}</span>
                                                    {{-- Dot indicator if breakpoint has values --}}
                                                    <span
                                                        v-show="spacingEditData[bp.key] && (spacingEditData[bp.key].px || spacingEditData[bp.key].py || spacingEditData[bp.key].mx || spacingEditData[bp.key].my)"
                                                        class="absolute top-1 right-1 w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Input Fields (Bottom) - Two Columns --}}
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            {{-- Padding Section (Left) --}}
                                            <div class="bg-white rounded-lg border border-gray-200 p-6">
                                                <h4
                                                    class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                                    <span class="w-4 h-4 bg-blue-500 rounded"></span>
                                                    Padding
                                                </h4>
                                                <div class="space-y-4">
                                                    {{-- Horizontal Padding (px) --}}
                                                    <div class="flex items-center gap-3">
                                                        <label class="w-32 text-sm font-medium text-gray-700">Horizontal
                                                            (px)</label>
                                                        <select v-model="spacingEditData[activeSpacingBreakpoint].px"
                                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                                            <option v-for="option in spacingOptions"
                                                                :key="'px-' + option.value" :value="option.value">
                                                                @{{ option.value === '' ? 'None (0px)' :
                                                                (activeSpacingBreakpoint !== 'base' ?
                                                                activeSpacingBreakpoint + ':' : '') + 'px-' +
                                                                option.value + ' (' + option.px + 'px)' }}
                                                            </option>
                                                        </select>
                                                    </div>

                                                    {{-- Vertical Padding (py) --}}
                                                    <div class="flex items-center gap-3">
                                                        <label class="w-32 text-sm font-medium text-gray-700">Vertical
                                                            (py)</label>
                                                        <select v-model="spacingEditData[activeSpacingBreakpoint].py"
                                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                                            <option v-for="option in spacingOptions"
                                                                :key="'py-' + option.value" :value="option.value">
                                                                @{{ option.value === '' ? 'None (0px)' :
                                                                (activeSpacingBreakpoint !== 'base' ?
                                                                activeSpacingBreakpoint + ':' : '') + 'py-' +
                                                                option.value + ' (' + option.px + 'px)' }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Margin Section (Right) --}}
                                            <div class="bg-white rounded-lg border border-gray-200 p-6">
                                                <h4
                                                    class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                                    <span class="w-4 h-4 bg-green-500 rounded"></span>
                                                    Margin
                                                </h4>
                                                <div class="space-y-4">
                                                    {{-- Horizontal Margin (mx) --}}
                                                    <div class="flex items-center gap-3">
                                                        <label class="w-32 text-sm font-medium text-gray-700">Horizontal
                                                            (mx)</label>
                                                        <select v-model="spacingEditData[activeSpacingBreakpoint].mx"
                                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
                                                            <option v-for="option in spacingOptions"
                                                                :key="'mx-' + option.value" :value="option.value">
                                                                @{{ option.value === '' ? 'None (0px)' :
                                                                (activeSpacingBreakpoint !== 'base' ?
                                                                activeSpacingBreakpoint + ':' : '') + 'mx-' +
                                                                option.value + ' (' + option.px + 'px)' }}
                                                            </option>
                                                        </select>
                                                    </div>

                                                    {{-- Vertical Margin (my) --}}
                                                    <div class="flex items-center gap-3">
                                                        <label class="w-32 text-sm font-medium text-gray-700">Vertical
                                                            (my)</label>
                                                        <select v-model="spacingEditData[activeSpacingBreakpoint].my"
                                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
                                                            <option v-for="option in spacingOptions"
                                                                :key="'my-' + option.value" :value="option.value">
                                                                @{{ option.value === '' ? 'None (0px)' :
                                                                (activeSpacingBreakpoint !== 'base' ?
                                                                activeSpacingBreakpoint + ':' : '') + 'my-' +
                                                                option.value + ' (' + option.px + 'px)' }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Footer --}}
                                    <div
                                        class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-end gap-3">
                                        <button type="button" @click="closeSpacingModal"
                                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Cancel</button>
                                        <button type="button" @click="saveSpacingEdit"
                                            class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 transition-colors font-medium">Apply
                                            Spacing</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- HTML Editor Modal (CodeMirror) --}}
                        <div v-if="modals.htmlEditor.show"
                            class="fixed inset-0 z-[80] flex items-center justify-center p-4" v-cloak>
                            <div class="fixed inset-0 bg-black/60" @click="closeHtmlEditor"></div>
                            <div
                                class="relative bg-gray-900 rounded-xl shadow-2xl w-full max-w-7xl h-[85vh] overflow-hidden flex flex-col z-10">
                                {{-- Header --}}
                                <div
                                    class="flex items-center justify-between px-6 py-4 border-b border-gray-700 bg-gray-800">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                        </div>
                                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                                            <i class="fa-solid fa-code text-green-400"></i>
                                            Edit HTML
                                            <span class="text-sm font-normal text-gray-400">-</span>
                                            <span class="text-sm font-normal text-gray-400"
                                                v-text="modals.htmlEditor.blockName"></span>
                                        </h3>
                                    </div>
                                    <button type="button" @click="closeHtmlEditor"
                                        class="text-gray-400 hover:text-white transition-colors">
                                        <i class="fa-solid fa-times text-lg"></i>
                                    </button>
                                </div>

                                {{-- Editor Container --}}
                                <div class="flex-1 overflow-hidden">
                                    <textarea ref="htmlEditorTextarea" class="hidden"></textarea>
                                </div>

                                {{-- Footer --}}
                                <div
                                    class="flex items-center justify-between px-6 py-4 border-t border-gray-700 bg-gray-800">
                                    <div class="text-sm text-gray-400">
                                        <i class="fa-solid fa-info-circle mr-1"></i>
                                        Ctrl+S to save, Esc to cancel
                                    </div>
                                    <div class="flex items-center gap-3">
                                        {{-- Fix with AI Button --}}
                                        <div class="relative">
                                            <button type="button"
                                                @click="modals.htmlEditor.showAiPrompt = !modals.htmlEditor.showAiPrompt"
                                                class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors font-medium flex items-center gap-2"
                                                :class="{ 'ring-2 ring-purple-400': modals.htmlEditor.showAiPrompt }">
                                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                                                Fix with AI
                                                <span v-if="modals.htmlEditor.aiLoading" class="ml-1">
                                                    <i class="fa-solid fa-spinner fa-spin"></i>
                                                </span>
                                            </button>

                                            {{-- AI Prompt Popup --}}
                                            <div v-if="modals.htmlEditor.showAiPrompt"
                                                class="absolute bottom-full right-0 mb-2 w-80 bg-gray-900 rounded-lg shadow-xl border border-gray-700 p-4 z-50">
                                                <div class="flex items-center justify-between mb-3">
                                                    <h4
                                                        class="text-sm font-semibold text-white flex items-center gap-2">
                                                        <i class="fa-solid fa-robot text-purple-400"></i>
                                                        AI Assistant
                                                    </h4>
                                                    <button type="button"
                                                        @click="modals.htmlEditor.showAiPrompt = false"
                                                        class="text-gray-400 hover:text-white">
                                                        <i class="fa-solid fa-times"></i>
                                                    </button>
                                                </div>
                                                <textarea v-model="modals.htmlEditor.aiPrompt"
                                                    placeholder="Describe what you want to fix or change..."
                                                    class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none"
                                                    rows="3" @keydown.ctrl.enter="fixWithAI"
                                                    @keydown.meta.enter="fixWithAI"></textarea>
                                                <div class="flex items-center justify-between mt-3">
                                                    <span class="text-xs text-gray-500">Ctrl+Enter to submit</span>
                                                    <button type="button" @click="fixWithAI"
                                                        :disabled="modals.htmlEditor.aiLoading || !modals.htmlEditor.aiPrompt.trim()"
                                                        class="px-3 py-1.5 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors text-sm font-medium flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                                        <i class="fa-solid fa-paper-plane"></i>
                                                        Send
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" @click="closeHtmlEditor"
                                            class="px-4 py-2 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600 transition-colors">
                                            Cancel
                                        </button>
                                        <button type="button" @click="saveHtmlChanges"
                                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors font-medium flex items-center gap-2">
                                            <i class="fa-solid fa-check"></i> Save Changes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="col-span-1">
                        <div class="flex flex-col gap-4">
                            {{-- Page Settings --}}
                            <div class="bg-white rounded-md border border-gray-200">
                                <div class="p-6">
                                    <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                                        <span
                                            class="flex items-center justify-center w-7 h-7 rounded-md bg-gray-100 mr-2.5">
                                            <i class="fa-solid fa-cog text-gray-500 text-xs"></i>
                                        </span>
                                        Page Settings
                                    </h3>
                                    <div class="space-y-4">
                                        {{-- Page URL --}}
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-link text-gray-400 text-sm"></i>
                                                <span class="text-sm font-medium text-gray-700">Page URL</span>
                                            </div>
                                            <a :href="form.home_page ? baseUrl : (baseUrl + '/pagina/' + (form.slug || ''))"
                                                target="_blank"
                                                class="text-xs text-blue-600 hover:underline max-w-[180px] truncate"
                                                v-text="form.home_page ? baseUrl : (baseUrl + '/pagina/' + (form.slug || ''))"></a>
                                        </div>

                                        {{-- Status --}}
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-toggle-on text-gray-400 text-sm"></i>
                                                <span class="text-sm font-medium text-gray-700">Status</span>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer"
                                                :class="{ 'opacity-50 cursor-not-allowed': form.home_page }">
                                                <input type="checkbox" v-model="form.is_active"
                                                    :disabled="form.home_page" @change="handleStatusChange"
                                                    class="sr-only peer"
                                                    :class="{ 'cursor-not-allowed': form.home_page }">
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"
                                                    :class="{ 'opacity-50': form.home_page }"></div>
                                            </label>
                                        </div>
                                        <p v-if="form.home_page" class="text-xs text-amber-600 mt-1">
                                            <i class="fa-solid fa-exclamation-triangle mr-1"></i>Homepage pages cannot
                                            be deactivated. Please unset homepage first.
                                        </p>

                                        {{-- Homepage --}}
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-star text-gray-400 text-sm"></i>
                                                <span class="text-sm font-medium text-gray-700">Set as Homepage</span>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" v-model="form.home_page" class="sr-only peer">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                                </div>
                                            </label>
                                        </div>

                                        {{-- Hide Header/Footer --}}
                                        <div v-show="isShowcase" class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-eye-slash text-gray-400 text-sm"></i>
                                                <span class="text-sm font-medium text-gray-700">Hide Header</span>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" v-model="form.hide_header" class="sr-only peer">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                                </div>
                                            </label>
                                        </div>
                                        <div v-show="isShowcase" class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-eye-slash text-gray-400 text-sm"></i>
                                                <span class="text-sm font-medium text-gray-700">Hide Footer</span>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" v-model="form.hide_footer" class="sr-only peer">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SEO Settings --}}
                            <div class="bg-white rounded-md border border-gray-200">
                                <div class="p-6 space-y-6">
                                    <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                                        <span
                                            class="flex items-center justify-center w-7 h-7 rounded-md bg-blue-50 mr-2.5">
                                            <i class="fa-solid fa-search text-blue-500 text-xs"></i>
                                        </span>
                                        SEO Settings
                                    </h3>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Meta Title</label>
                                        <input type="text" v-model="form.meta_title" name="meta_title"
                                            class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Meta
                                            Description</label>
                                        <textarea v-model="form.meta_body" name="meta_body" rows="3"
                                            class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Meta
                                            Keywords</label>
                                        <textarea v-model="form.meta_keywords" name="meta_keywords" rows="3"
                                            class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-md focus:outline-none"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-end space-x-3 pt-6">
                    <a href="{{ route('admin.content.page.index') }}"
                        class="px-3 py-2 text-sm text-gray-800 bg-white border border-gray-200 rounded-sm hover:bg-gray-50 transition-colors duration-200">Cancel</a>
                    <button type="submit"
                        class="px-3 py-2 text-sm text-white bg-primary rounded-sm hover:bg-primary/80 transition-colors duration-200 flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span class="text-sm">Update Page</span>
                    </button>
                </div>
            </form>
        </div>

        <x-slot:styles>
            @vite(['resources/css/admin/page-builder-toolbox.css'])
            {{-- TipTap Editor styles are handled by the component --}}
            <style>
                [v-cloak] {
                    display: none;
                }
            </style>
        </x-slot:styles>

        <x-slot:scripts>
            {{-- TipTap Editor JS is loaded via Vite in the layout --}}
            @vite(['resources/js/admin/page-builder.js'])

            <script>
                // Pass Blade data to Vue
                window.PageBuilderData = {
                    form: {
                        title: @json(old('title', $page->title ?? '')),
                        slug: @json(old('slug', $page->slug ?? '')),
                        is_active: @json(old('is_active', $page->is_active ?? true)),
                        home_page: @json(old('home_page', $page->home_page ?? false)),
                        page_type: @json(old('page_type', $page->page_type ?? 'static')),

                        short_body: @json(old('short_body', $page->short_body ?? '')),
                        long_body: @json(old('long_body', $page->long_body ?? '')),

                        layout_type: @json(old('layout_type', $page->layout_type ?? '')),
                        design_type: @json(old('design_type', $page->design_type ?? 'general')),
                        header_block: @json(old('header_block', $page->header_block ?? '')),
                        footer_block: @json(old('footer_block', $page->footer_block ?? '')),
                        hide_header: @json(old('hide_header', $page->hide_header ?? false)),
                        hide_footer: @json(old('hide_footer', $page->hide_footer ?? false)),

                        funnel_fase: @json(old('funnel_fase', $page->funnel_fase ?? '')),
                        marketing_persona_id: @json(old('marketing_persona_id', $page->marketing_persona_id ?? '')),
                        content_type_id: @json(old('content_type_id', $page->content_type_id ?? '')),
                        primary_keyword: @json(old('primary_keyword', $page->primary_keyword ?? '')),
                        secondary_keywords: @json(old('secondary_keywords', $page->secondary_keywords ?? [])),
                        ai_briefing: @json(old('ai_briefing', $page->ai_briefing ?? '')),

                        meta_title: @json(old('meta_title', $page->meta_title ?? '')),
                        meta_body: @json(old('meta_body', $page->meta_body ?? '')),
                        meta_keywords: @json(old('meta_keywords', $page->meta_keywords ?? '')),
                    },
                    blocks: @json(json_decode(old('widget_config', $page->widget_config ?? '[]'))),
                    resources: {
                        headerBlocks: @json($headerBlocks ?? []),
                        footerBlocks: @json($footerBlocks ?? []),
                        marketingPersonas: @json($marketingPersonas ?? []),
                        contentTypes: @json($contentTypes ?? []),
                        components: @json($components ?? [])
                    },
                    errors: @json($errors->toArray()),
                    routes: {
                        getComponent: '{{ route("admin.content.page.get-component") }}',
                        uploadImage: '{{ route("admin.content.page.upload-image") }}',
                        store: '{{ route("admin.content.page.store") }}',
                        getPresets: '{{ route("admin.content.page.get-presets") }}',
                        loadPreset: '{{ route("admin.content.page.load-preset", ["preset" => ":id"]) }}',
                        savePreset: '{{ route("admin.content.page.save-preset") }}',
                        deletePreset: '{{ route("admin.content.page.delete-preset", ["preset" => ":id"]) }}'
                    }
                };

                // ========================================
                // TIPTAP EDITOR FOR LONG_BODY
                // ========================================
                // TipTap editor is now handled by the x-editor component
                // No additional JavaScript needed here

            </script>
        </x-slot:scripts>
</x-layouts.admin>