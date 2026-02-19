import { createApp, ref, computed, reactive, onMounted, nextTick } from 'vue';

const PageBuilder = {
    data() {
        return {
            // ========================================================================
            // Core Data & Configuration
            // ========================================================================
            csrfToken:
                document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content") || "",
            baseUrl: window.location.origin,

            // Form Fields (mapped from Blade)
            form: {
                title: "",
                slug: "",
                is_active: true,
                home_page: false,
                page_type: "static", // 'static' | 'showcase'

                // Static Page Fields
                short_body: "",
                long_body: "", // Quill content

                // Showcase Fields
                layout_type: "", // '' | 'full-width' | 'container' | 'max-w-*'
                design_type: "general", // 'general' | 'custom'
                header_block: "",
                footer_block: "",
                hide_header: false,
                hide_footer: false,

                // Marketing & SEO
                funnel_fase: "",
                marketing_persona_id: "",
                content_type_id: "",
                primary_keyword: "",
                secondary_keywords: [], // Array of strings
                ai_briefing: "",
                meta_title: "",
                meta_body: "",
                meta_keywords: "",
            },

            // Validation Errors
            errors: {},

            // ========================================================================
            // Page Builder State (Showcase)
            // ========================================================================
            blocks: [],

            // UI State
            isDragging: false,
            draggedBlockIndex: null,
            draggedBlockRegion: null,
            dragOverBlockIndex: null,

            // Block Selector Modal State
            showBlockSelector: false,
            blockSelectorType: "body", // 'header' | 'body'
            componentSearchQuery: "",
            activeCategoryTab: "",
            activeSelectorTab: "select", // 'select' | 'paste' | 'saved'

            // ========================================================================
            // Editor State (Inline Editing)
            // ========================================================================
            isReady: false,
            isEditingMode: false,
            editingBlockId: null,
            activeBlockId: null,
            previewHtml: "",

            // Modals State
            modals: {
                imageEdit: {
                    show: false,
                    data: { url: "", alt: "", disabled: false },
                    targetElement: null,
                },
                linkEdit: {
                    show: false,
                    data: { href: "", text: "" },
                    targetElement: null,
                },
                buttonEdit: {
                    show: false,
                    data: { text: "", href: "", onclick: "", type: "button" },
                    targetElement: null,
                },
                elementSettings: {
                    show: false,
                    targetElement: null,
                    selectedTag: "",
                    selectedAlignment: "",
                    selectedWidth: "",
                },
                spacingEdit: {
                    show: false,
                    targetElement: null,
                },
                titleSlug: {
                    show: false,
                    tempTitle: "",
                    tempSlug: "",
                    autoGenerate: true,
                },
                savePreset: {
                    show: false,
                    name: "",
                    description: "",
                    type: "body",
                },
                preview: {
                    show: false,
                    componentName: "",
                },
                htmlEditor: {
                    show: false,
                    blockId: null,
                    blockName: "",
                    editor: null, // CodeMirror instance
                    showAiPrompt: false,
                    aiPrompt: "",
                    aiLoading: false,
                },
            },

            // Preview Navigation State
            previewComponentData: null,
            canNavigatePrevious: false,
            canNavigateNext: false,

            // Toolbox State
            toolbox: {
                show: true,
                isMinimized: true, // Start minimized by default
                showTooltip: false, // Tooltip visibility state
                isDragging: false,
                position: { top: 100, left: 20 },
                activeTools: [], // Array of active tool names (e.g., 'bold', 'h1')
                tools: [
                    {
                        name: "bold",
                        icon: "fa-bold",
                        action: "bold",
                        group: "format",
                    },
                    {
                        name: "italic",
                        icon: "fa-italic",
                        action: "italic",
                        group: "format",
                    },
                    {
                        name: "underline",
                        icon: "fa-underline",
                        action: "underline",
                        group: "format",
                    },
                    {
                        name: "strike",
                        icon: "fa-strikethrough",
                        action: "strikethrough",
                        group: "format",
                    },
                    {
                        name: "link",
                        icon: "fa-link",
                        action: "link",
                        group: "format",
                    },
                    {
                        name: "clean",
                        icon: "fa-eraser",
                        action: "removeFormat",
                        group: "format",
                    },

                    {
                        name: "align-left",
                        icon: "fa-align-left",
                        action: "justifyLeft",
                        group: "align",
                    },
                    {
                        name: "align-center",
                        icon: "fa-align-center",
                        action: "justifyCenter",
                        group: "align",
                    },
                    {
                        name: "align-right",
                        icon: "fa-align-right",
                        action: "justifyRight",
                        group: "align",
                    },
                    {
                        name: "align-justify",
                        icon: "fa-align-justify",
                        action: "justifyFull",
                        group: "align",
                    },

                    {
                        name: "h1",
                        icon: "fa-heading",
                        text: "1",
                        action: "formatBlock",
                        value: "H1",
                        group: "block",
                    },
                    {
                        name: "h2",
                        icon: "fa-heading",
                        text: "2",
                        action: "formatBlock",
                        value: "H2",
                        group: "block",
                    },
                    {
                        name: "h3",
                        icon: "fa-heading",
                        text: "3",
                        action: "formatBlock",
                        value: "H3",
                        group: "block",
                    },
                    {
                        name: "h4",
                        icon: "fa-heading",
                        text: "4",
                        action: "formatBlock",
                        value: "H4",
                        group: "block",
                    },
                    {
                        name: "h5",
                        icon: "fa-heading",
                        text: "5",
                        action: "formatBlock",
                        value: "H5",
                        group: "block",
                    },
                    {
                        name: "h6",
                        icon: "fa-heading",
                        text: "6",
                        action: "formatBlock",
                        value: "H6",
                        group: "block",
                    },
                    {
                        name: "p",
                        icon: "fa-paragraph",
                        action: "formatBlock",
                        value: "P",
                        group: "block",
                    },
                    {
                        name: "quote",
                        icon: "fa-quote-right",
                        action: "formatBlock",
                        value: "BLOCKQUOTE",
                        group: "block",
                    },

                    {
                        name: "ul",
                        icon: "fa-list-ul",
                        action: "insertUnorderedList",
                        group: "list",
                    },
                    {
                        name: "ol",
                        icon: "fa-list-ol",
                        action: "insertOrderedList",
                        group: "list",
                    },
                    {
                        name: "indent",
                        icon: "fa-indent",
                        action: "indent",
                        group: "list",
                    },
                    {
                        name: "outdent",
                        icon: "fa-outdent",
                        action: "outdent",
                        group: "list",
                    },

                    {
                        name: "image",
                        icon: "fa-image",
                        action: "image",
                        group: "media",
                    },
                    {
                        name: "video",
                        icon: "fa-video",
                        action: "video",
                        group: "media",
                    },
                    {
                        name: "hr",
                        icon: "fa-minus",
                        action: "insertHorizontalRule",
                        group: "media",
                    },

                    {
                        name: "edit-link",
                        icon: "fa-pen-to-square",
                        action: "editLink",
                        group: "context",
                        text: "Link",
                    },
                    {
                        name: "edit-image",
                        icon: "fa-pen-to-square",
                        action: "editImage",
                        group: "context",
                        text: "Img",
                    },

                    {
                        name: "move-up",
                        icon: "fa-arrow-up",
                        action: "moveUp",
                        group: "move",
                    },
                    {
                        name: "move-down",
                        icon: "fa-arrow-down",
                        action: "moveDown",
                        group: "move",
                    },
                    {
                        name: "select-parent",
                        icon: "fa-level-up-alt",
                        action: "selectParent",
                        group: "move",
                    },
                    {
                        name: "select-child",
                        icon: "fa-level-down-alt",
                        action: "selectChild",
                        group: "move",
                    },
                    {
                        name: "clone",
                        icon: "fa-clone",
                        action: "clone",
                        group: "move",
                    },

                    // Container Width Tools
                    {
                        name: "width-full",
                        icon: "fa-arrows-left-right",
                        text: "Full",
                        action: "setContainerWidth",
                        value: "none",
                        group: "layout",
                    },
                    {
                        name: "width-7xl",
                        icon: "fa-window-maximize",
                        text: "7XL",
                        action: "setContainerWidth",
                        value: "max-w-7xl",
                        group: "layout",
                    },
                    {
                        name: "width-6xl",
                        icon: "fa-window-restore",
                        text: "6XL",
                        action: "setContainerWidth",
                        value: "max-w-6xl",
                        group: "layout",
                    },
                    {
                        name: "width-5xl",
                        icon: "fa-square",
                        text: "5XL",
                        action: "setContainerWidth",
                        value: "max-w-5xl",
                        group: "layout",
                    },
                    {
                        name: "width-4xl",
                        icon: "fa-square",
                        text: "4XL",
                        action: "setContainerWidth",
                        value: "max-w-4xl",
                        group: "layout",
                    },
                    {
                        name: "width-3xl",
                        icon: "fa-square",
                        text: "3XL",
                        action: "setContainerWidth",
                        value: "max-w-3xl",
                        group: "layout",
                    },
                    {
                        name: "width-2xl",
                        icon: "fa-square",
                        text: "2XL",
                        action: "setContainerWidth",
                        value: "max-w-2xl",
                        group: "layout",
                    },
                    {
                        name: "width-xl",
                        icon: "fa-compress",
                        text: "XL",
                        action: "setContainerWidth",
                        value: "max-w-xl",
                        group: "layout",
                    },
                    {
                        name: "width-lg",
                        icon: "fa-compress-alt",
                        text: "LG",
                        action: "setContainerWidth",
                        value: "max-w-lg",
                        group: "layout",
                    },
                    {
                        name: "width-md",
                        icon: "fa-compress-arrows-alt",
                        text: "MD",
                        action: "setContainerWidth",
                        value: "max-w-md",
                        group: "layout",
                    },
                    {
                        name: "width-sm",
                        icon: "fa-compress-arrows-alt",
                        text: "SM",
                        action: "setContainerWidth",
                        value: "max-w-sm",
                        group: "layout",
                    },

                    // Text Color Tools
                    {
                        name: "stone",
                        icon: "fa-circle",
                        style: "color: #78716c",
                        action: "setTextColor",
                        value: "text-stone-600",
                        group: "color",
                    },

                    // Reds & Oranges
                    {
                        name: "red",
                        icon: "fa-circle",
                        style: "color: #ef4444",
                        action: "setTextColor",
                        value: "text-red-600",
                        group: "color",
                    },
                    {
                        name: "orange",
                        icon: "fa-circle",
                        style: "color: #f97316",
                        action: "setTextColor",
                        value: "text-orange-600",
                        group: "color",
                    },
                    {
                        name: "amber",
                        icon: "fa-circle",
                        style: "color: #f59e0b",
                        action: "setTextColor",
                        value: "text-amber-600",
                        group: "color",
                    },
                    {
                        name: "yellow",
                        icon: "fa-circle",
                        style: "color: #eab308",
                        action: "setTextColor",
                        value: "text-yellow-600",
                        group: "color",
                    },

                    // Greens
                    {
                        name: "lime",
                        icon: "fa-circle",
                        style: "color: #84cc16",
                        action: "setTextColor",
                        value: "text-lime-600",
                        group: "color",
                    },
                    {
                        name: "green",
                        icon: "fa-circle",
                        style: "color: #22c55e",
                        action: "setTextColor",
                        value: "text-green-600",
                        group: "color",
                    },
                    {
                        name: "emerald",
                        icon: "fa-circle",
                        style: "color: #10b981",
                        action: "setTextColor",
                        value: "text-emerald-600",
                        group: "color",
                    },
                    {
                        name: "teal",
                        icon: "fa-circle",
                        style: "color: #14b8a6",
                        action: "setTextColor",
                        value: "text-teal-600",
                        group: "color",
                    },

                    // Blues
                    {
                        name: "cyan",
                        icon: "fa-circle",
                        style: "color: #06b6d4",
                        action: "setTextColor",
                        value: "text-cyan-600",
                        group: "color",
                    },
                    {
                        name: "sky",
                        icon: "fa-circle",
                        style: "color: #0ea5e9",
                        action: "setTextColor",
                        value: "text-sky-600",
                        group: "color",
                    },
                    {
                        name: "blue",
                        icon: "fa-circle",
                        style: "color: #3b82f6",
                        action: "setTextColor",
                        value: "text-blue-600",
                        group: "color",
                    },
                    {
                        name: "indigo",
                        icon: "fa-circle",
                        style: "color: #6366f1",
                        action: "setTextColor",
                        value: "text-indigo-600",
                        group: "color",
                    },

                    // Purples & Pinks
                    {
                        name: "violet",
                        icon: "fa-circle",
                        style: "color: #8b5cf6",
                        action: "setTextColor",
                        value: "text-violet-600",
                        group: "color",
                    },
                    {
                        name: "purple",
                        icon: "fa-circle",
                        style: "color: #a855f7",
                        action: "setTextColor",
                        value: "text-purple-600",
                        group: "color",
                    },
                    {
                        name: "fuchsia",
                        icon: "fa-circle",
                        style: "color: #d946ef",
                        action: "setTextColor",
                        value: "text-fuchsia-600",
                        group: "color",
                    },
                    {
                        name: "pink",
                        icon: "fa-circle",
                        style: "color: #ec4899",
                        action: "setTextColor",
                        value: "text-pink-600",
                        group: "color",
                    },
                    {
                        name: "rose",
                        icon: "fa-circle",
                        style: "color: #f43f5e",
                        action: "setTextColor",
                        value: "text-rose-600",
                        group: "color",
                    },

                    {
                        name: "black",
                        icon: "fa-circle",
                        style: "color: #000",
                        action: "setTextColor",
                        value: "text-black",
                        group: "color",
                    },
                    {
                        name: "white",
                        icon: "fa-circle",
                        style: "color: #fff; background: #ccc; border-radius: 50%",
                        action: "setTextColor",
                        value: "text-white",
                        group: "color",
                    },

                    {
                        name: "undo",
                        icon: "fa-undo",
                        action: "undo",
                        group: "history",
                    },
                    {
                        name: "redo",
                        icon: "fa-redo",
                        action: "redo",
                        group: "history",
                    },
                    {
                        name: "trash",
                        icon: "fa-trash",
                        action: "delete",
                        group: "history",
                    },
                ],
            },

            // Toast Notifications
            toasts: [],

            // Editor State
            quillEditor: null,

            // Current Editing Element References
            currentEditingImageElement: null,
            currentEditingLinkElement: null,
            currentEditingButtonElement: null,
            currentSettingsElement: null,

            // Event listener cleanup function
            cleanupPreviewListeners: null,

            // Element Settings Configuration
            elementSettingsTags: [
                { tag: "p", label: "Paragraph" },
                { tag: "h1", label: "Heading 1" },
                { tag: "h2", label: "Heading 2" },
                { tag: "h3", label: "Heading 3" },
                { tag: "h4", label: "Heading 4" },
                { tag: "div", label: "Div" },
                { tag: "section", label: "Section" },
                { tag: "article", label: "Article" },
                { tag: "header", label: "Header" },
                { tag: "footer", label: "Footer" },
                { tag: "span", label: "Span" },
            ],

            // Preset State
            presets: [],
            selectedPresetType: "header",

            // Custom Block (Paste HTML Tab)
            customBlockName: "",
            customBlockHtml: "",

            // Editing State - Store original HTML for cancel functionality
            originalBlockHtml: null,

            // Image Edit Data
            imageEditData: {
                url: "",
                alt: "",
                disabled: false,
            },

            // Link Edit Data
            linkEditData: {
                href: "",
                text: "",
                backgroundColor: "",
                color: "",
            },

            // Button Edit Data
            buttonEditData: {
                text: "",
                href: "",
                onclick: "",
                type: "button",
                classes: "",
                backgroundColor: "",
                color: "",
            },

            // Element Settings Data
            elementSettingsData: {
                tag: "div",
                alignment: "left",
                width: "auto",
                containerWidth: "none",
                backgroundImage: "",
                backgroundImageElement: null, // Reference to the img element
            },

            // Spacing Edit Data (per breakpoint)
            activeSpacingBreakpoint: 'base',
            spacingBreakpoints: [
                { key: 'base', label: 'Base', prefix: '', icon: '📱', desc: 'All screens' },
                { key: 'sm', label: 'SM', prefix: 'sm:', icon: '📱', desc: '≥640px' },
                { key: 'md', label: 'MD', prefix: 'md:', icon: '💻', desc: '≥768px' },
                { key: 'lg', label: 'LG', prefix: 'lg:', icon: '🖥️', desc: '≥1024px' },
                { key: 'xl', label: 'XL', prefix: 'xl:', icon: '🖥️', desc: '≥1280px' },
            ],
            spacingEditData: {
                base: { px: '', py: '', mx: '', my: '' },
                sm: { px: '', py: '', mx: '', my: '' },
                md: { px: '', py: '', mx: '', my: '' },
                lg: { px: '', py: '', mx: '', my: '' },
                xl: { px: '', py: '', mx: '', my: '' },
            },

            // Resources (passed from Blade)
            resources: {
                headerBlocks: [],
                footerBlocks: [],
                marketingPersonas: [],
                contentTypes: [],
                components: {},
                routes: {},
            },
        };
    },

    computed: {
        // Tailwind spacing options for dropdowns
        spacingOptions() {
            // Tailwind spacing scale: value -> px
            const spacingScale = {
                0: 0,
                0.5: 2,
                1: 4,
                1.5: 6,
                2: 8,
                2.5: 10,
                3: 12,
                3.5: 14,
                4: 16,
                5: 20,
                6: 24,
                7: 28,
                8: 32,
                9: 36,
                10: 40,
                11: 44,
                12: 48,
                14: 56,
                16: 64,
                20: 80,
                24: 96,
                28: 112,
                32: 128,
                36: 144,
                40: 160,
                44: 176,
                48: 192,
                52: 208,
                56: 224,
                60: 240,
                64: 256,
                72: 288,
                80: 320,
                96: 384,
            };

            // Create options array with empty option first
            const options = [{ value: "", label: "None (0px)", px: 0 }];

            // Add all spacing values
            Object.keys(spacingScale)
                .sort((a, b) => parseFloat(a) - parseFloat(b))
                .forEach((key) => {
                    const value = key;
                    const px = spacingScale[key];
                    options.push({
                        value: value,
                        label: `${value} (${px}px)`,
                        px: px,
                    });
                });

            return options;
        },

        getCurrentSpacingClasses() {
            return () => {
                const element = this.modals.spacingEdit.targetElement;
                if (!element) return [];

                const classes = Array.from(element.classList);
                const spacingClasses = classes.filter((cls) =>
                    cls.match(/^(?:sm:|md:|lg:|xl:|2xl:)?(px|py|mx|my)-[\d.]+$/)
                );
                return spacingClasses;
            };
        },
        headerBlocks() {
            return this.blocks.filter((b) => b.region === "header");
        },
        bodyBlocks() {
            return this.blocks.filter((b) => b.region === "body");
        },

        uniqueToolGroups() {
            return [...new Set(this.toolbox.tools.map((t) => t.group))];
        },

        filteredComponents() {
            if (!this.resources.components) return {};

            const query = this.componentSearchQuery.trim().toLowerCase();
            const isHeaderMode = this.blockSelectorType === "header";
            const filtered = {};

            const excludedBodySections = [
                "404 pages",
                "banners",
                "flyout menus",
                "footers",
            ];

            Object.keys(this.resources.components).forEach((category) => {
                const sections = this.resources.components[category];
                const filteredSections = {};
                let hasMatchingSections = false;

                Object.keys(sections).forEach((sectionName) => {
                    const section = sections[sectionName];
                    const sectionNameLower = sectionName.toLowerCase();
                    const sectionDisplayLower = (
                        section.name || ""
                    ).toLowerCase();

                    const isHeaderOrHero =
                        sectionNameLower.includes("header") ||
                        sectionNameLower.includes("hero") ||
                        sectionDisplayLower.includes("header") ||
                        sectionDisplayLower.includes("hero");

                    if (isHeaderMode && !isHeaderOrHero) return;
                    if (!isHeaderMode && isHeaderOrHero) return;

                    if (!isHeaderMode) {
                        const shouldExclude = excludedBodySections.some(
                            (excluded) =>
                                sectionNameLower.includes(excluded) ||
                                sectionDisplayLower.includes(excluded)
                        );
                        if (shouldExclude) return;
                    }

                    if (query) {
                        if (
                            sectionNameLower.includes(query) ||
                            sectionDisplayLower.includes(query)
                        ) {
                            filteredSections[sectionName] = section;
                            hasMatchingSections = true;
                            return;
                        }

                        const matchingComponents = section.components.filter(
                            (comp) =>
                                comp.name.toLowerCase().includes(query) ||
                                (comp.raw_name &&
                                    comp.raw_name.toLowerCase().includes(query))
                        );

                        if (matchingComponents.length > 0) {
                            filteredSections[sectionName] = {
                                ...section,
                                components: matchingComponents,
                            };
                            hasMatchingSections = true;
                        }
                    } else {
                        filteredSections[sectionName] = section;
                        hasMatchingSections = true;
                    }
                });

                if (hasMatchingSections) {
                    filtered[category] = filteredSections;
                }
            });

            return filtered;
        },

        isShowcase() {
            return this.form.page_type === "showcase";
        },
        isStatic() {
            return this.form.page_type === "static";
        },
        isCustomDesign() {
            return this.form.design_type === "custom";
        },
    },

    methods: {
        // ========================================================================
        // Initialization
        // ========================================================================
        init(initialData) {
            if (!initialData) return;

            if (initialData.form)
                this.form = { ...this.form, ...initialData.form };
            if (initialData.errors) this.errors = initialData.errors;

            if (initialData.blocks) {
                this.blocks = initialData.blocks.map((block) => {
                    // Recover component_id if missing
                    if (!block.component_id) {
                        // If id is numeric/DB-like, assume it's the component_id
                        if (
                            block.id &&
                            !String(block.id).startsWith("block_")
                        ) {
                            block.component_id = block.id;
                        }
                        // Otherwise try to find by path
                        else if (block.path) {
                            block.component_id = this.findComponentIdByPath(
                                block.path
                            );
                        }
                    }

                    // Ensure unique instance ID for Vue
                    block.id =
                        "block_" +
                        Date.now() +
                        "_" +
                        Math.random().toString(36).substr(2, 9);

                    if (!block.region) {
                        const sectionName = (
                            block.section ||
                            block.sectionDisplay ||
                            ""
                        ).toLowerCase();
                        const categoryName = (
                            block.category || ""
                        ).toLowerCase();
                        const isHeader =
                            sectionName.includes("header") ||
                            sectionName.includes("hero") ||
                            categoryName.includes("header") ||
                            categoryName.includes("hero");
                        block.region = isHeader ? "header" : "body";
                    }
                    return block;
                });
            }

            if (initialData.resources)
                this.resources = {
                    ...this.resources,
                    ...initialData.resources,
                };
            if (initialData.routes) this.resources.routes = initialData.routes;
        },

        // ========================================================================
        // Quill Editor for Static Page Long Body
        // ========================================================================
        // DISABLED: Quill editor is now handled by quill-editor-vue component
        // This prevents duplicate Quill instances
        initQuillEditor() {
            // console.log('[PageBuilder] initQuillEditor called but disabled - using quill-editor-vue component');
            // Do nothing - quill-editor-vue component handles Quill initialization
        },

        setupQuillEditor() {
            console.log('[PageBuilder] setupQuillEditor called but disabled - using quill-editor-vue component');
            // Do nothing - quill-editor-vue component handles Quill initialization
        },

        destroyQuillEditor() {
            console.log('[PageBuilder] destroyQuillEditor called');
            // Do nothing - quill-editor-vue component handles cleanup
        },

        findComponentIdByPath(path) {
            if (!this.resources.components) return null;

            for (const category in this.resources.components) {
                const sections = this.resources.components[category];
                for (const sectionKey in sections) {
                    const section = sections[sectionKey];
                    if (section.components) {
                        const component = section.components.find(
                            (c) => c.path === path
                        );
                        if (component) return component.id;
                    }
                }
            }
            return null;
        },

        // ========================================================================
        // Helper Methods (Iframe-Aware)
        // ========================================================================
        // These methods are critical for visual editing functionality.
        // They work with both main document and iframe preview.

        /**
         * Get the current selection node from iframe or main window
         */
        getSelectionNode() {
            const targetWindow = this.getTargetWindow();
            const selection = targetWindow?.getSelection() || window.getSelection();
            if (!selection || selection.rangeCount === 0) return null;
            return selection.anchorNode;
        },

        // Helper: Debounce function
        debounce(func, wait) {
            let timeout;
            return function (...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        },

        /**
         * Get the widget/block wrapper element containing the node
         * Used for move up/down, clone, delete operations
         */
        getWidgetWrapper(node) {
            // Previously this method traversed up to the top-level wrapper.
            // Now, to support nested editing, we simply return the closest block.
            // This allows selecting and manipulating inner elements (divs, p, etc.)
            return this.getClosestBlock(node);
        },

        /**
         * Get the closest block-level element for text operations
         */
        getClosestBlock(node) {
            if (!node) return null;
            const el = node.nodeType === 1 ? node : node.parentNode;
            if (!el) return null;

            const targetDoc = this.getTargetDocument();

            // If it's the wrapper itself, return null
            if (
                el.classList?.contains("preview-content-wrapper") ||
                el.id === "page-builder-preview" ||
                el === targetDoc?.body
            )
                return null;

            // List of block tags that can be selected/edited
            const blockTags = [
                "P", "H1", "H2", "H3", "H4", "H5", "H6",
                "UL", "OL", "LI", "BLOCKQUOTE",
                "DIV", "SECTION", "ARTICLE", "HEADER", "FOOTER", "MAIN", "ASIDE", "NAV",
                "FIGURE", "IMG", "SPAN", "A", "BUTTON",
                "FORM", "INPUT", "LABEL", "TEXTAREA", "SELECT",
                "SVG", "PATH", "CIRCLE", "RECT", "POLYGON", "DEFS", "G"
            ];

            // Find closest block using CSS selector
            const selector = blockTags.join(",");
            let block = el.closest ? el.closest(selector) : null;

            // Fallback for older browsers
            if (!block) {
                let current = el;
                while (current && current !== targetDoc?.body) {
                    if (blockTags.includes(current.tagName)) {
                        block = current;
                        break;
                    }
                    current = current.parentNode;
                }
            }

            // Ensure we don't go outside the wrapper
            if (block) {
                const wrapper = targetDoc?.body || document.getElementById("page-builder-preview");
                if (wrapper && wrapper.contains(block)) {
                    return block;
                }
            }
            return null;
        },

        /**
         * Get the target document (iframe or main document)
         */
        getTargetDocument() {
            if (this.previewIframe) {
                try {
                    return this.previewIframe.contentDocument || this.previewIframe.contentWindow?.document;
                } catch (e) {
                    console.warn('Cannot access iframe document:', e);
                    return document;
                }
            }
            return document;
        },

        /**
         * Get the target window (iframe or main window)
         */
        getTargetWindow() {
            if (this.previewIframe) {
                try {
                    return this.previewIframe.contentWindow;
                } catch (e) {
                    console.warn('Cannot access iframe window:', e);
                    return window;
                }
            }
            return window;
        },


        // ========================================================================
        // Preview Rendering (Iframe-based for complete CSS isolation)
        // Best practice approach used by Tailwind UI, Storybook, CodePen
        // ========================================================================
        renderPreview(html) {
            const container = this.$refs.previewContainer;
            if (!container) return;

            // Strip <script> tags from block HTML to prevent conflicts and security issues
            // Preview should only render visual HTML, not execute stored scripts
            const sanitizedHtml = html.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');

            this.previewHtml = sanitizedHtml;

            // Clear existing content
            container.innerHTML = '';

            // Create iframe for complete CSS isolation
            const iframe = document.createElement('iframe');
            iframe.style.width = '100%';
            iframe.style.border = 'none';
            iframe.style.display = 'block';
            iframe.style.overflow = 'hidden';
            iframe.id = 'preview-iframe';

            // Store reference for later use
            this.previewIframe = iframe;

            container.appendChild(iframe);

            // Function to adjust iframe height to fit content exactly
            const adjustHeight = () => {
                try {
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    if (!iframeDoc || !iframeDoc.body) return;

                    // Get the actual content height
                    const body = iframeDoc.body;
                    const html = iframeDoc.documentElement;

                    // Reset height to auto to get true content height
                    body.style.height = 'auto';
                    html.style.height = 'auto';

                    // Calculate height from content
                    const contentHeight = Math.max(
                        body.scrollHeight,
                        body.offsetHeight,
                        body.getBoundingClientRect().height
                    );

                    // Set iframe height to content height (with small buffer for rounding)
                    iframe.style.height = Math.ceil(contentHeight) + 'px';
                } catch (e) {
                    console.warn('Could not adjust iframe height:', e);
                }
            };

            // Wait for iframe to be ready
            iframe.onload = () => {
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

                // Build the complete HTML document with Tailwind CSS
                const fullHtml = `
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#081245',
                        secondary: '#0073e6',
                    }
                }
            }
        }
    </script>
    <style>
        /* Critical: Remove default margins and ensure content fits */
        html, body {
            margin: 0;
            padding: 0;
            min-height: 0 !important;
            height: auto !important;
            overflow: visible;
        }
        *, *::before, *::after {
            box-sizing: border-box;
        }
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        /* Editing mode styles */
        .editable-element:hover {
            outline: 2px dashed rgba(59, 130, 246, 0.5);
            outline-offset: 2px;
        }
        .block-active {
            outline: 2px solid #3b82f6 !important;
            outline-offset: 2px;
        }
        [contenteditable="true"]:focus {
            outline: none;
        }
    </style>
</head>
<body>
    ${sanitizedHtml}
    <script>
        (function() {
            console.log('[PageBuilder] Iframe Script Loaded - v3 (Scoped)');
            
            // Notify parent when Tailwind CSS is ready and content is rendered
            function notifyReady() {
                window.parent.postMessage({ type: 'preview-ready' }, '*');
            }
            
            // Wait for Tailwind to process styles
            if (typeof tailwind !== 'undefined') {
                // Tailwind CDN uses requestAnimationFrame for processing
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        notifyReady();
                    });
                });
            } else {
                // Fallback: wait a bit
                setTimeout(notifyReady, 100);
            }
            
            // Also notify on any resize
            const iframeResizeObserver = new ResizeObserver(() => {
                window.parent.postMessage({ type: 'preview-resize' }, '*');
            });
            iframeResizeObserver.observe(document.body);
            
            // Observe DOM mutations (for dynamic content)
            const iframeMutationObserver = new MutationObserver(() => {
                window.parent.postMessage({ type: 'preview-resize' }, '*');
            });
            iframeMutationObserver.observe(document.body, { 
                childList: true, 
                subtree: true, 
                attributes: true 
            });
        })();
    </script>
</body>
</html>`;

                iframeDoc.open();
                iframeDoc.write(fullHtml);
                iframeDoc.close();

                // Setup inline editing if in edit mode
                if (this.isEditingMode) {
                    // Wait a bit for Tailwind to process
                    setTimeout(() => {
                        this.setupIframeEditing(iframe);
                    }, 150);
                }
            };

            // Listen for messages from iframe
            const messageHandler = (event) => {
                if (event.data && (event.data.type === 'preview-ready' || event.data.type === 'preview-resize')) {
                    adjustHeight();
                }
            };

            // Remove old handler if exists
            if (this._previewMessageHandler) {
                window.removeEventListener('message', this._previewMessageHandler);
            }
            this._previewMessageHandler = messageHandler;
            window.addEventListener('message', messageHandler);

            // Trigger load for about:blank
            iframe.src = 'about:blank';
        },

        // Setup inline editing for iframe content
        // Setup inline editing for iframe content
        setupIframeEditing(iframe) {
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            if (!iframeDoc) return;

            // Make text elements editable
            const editableSelectors = 'h1, h2, h3, h4, h5, h6, p, span, a, button, li, td, th, label, blockquote, div, section';

            // Helper to make element editable
            const makeEditable = (el) => {
                // Only make leaf nodes or specific text containers editable
                // Avoid making layout containers editable directly unless they have text
                if (el.children.length === 0 || ['P', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'LI', 'SPAN', 'A', 'BUTTON', 'LABEL'].includes(el.tagName)) {
                    if (!el.hasAttribute('contenteditable')) {
                        el.setAttribute('contenteditable', 'true');
                        el.classList.add('editable-element');
                    }
                }
            };

            iframeDoc.querySelectorAll(editableSelectors).forEach(makeEditable);

            // Show disabled elements in editor (remove hidden class, add visual cues)
            iframeDoc.querySelectorAll('[data-disabled="true"]').forEach(el => {
                el.classList.remove('hidden');
                el.style.display = ''; // Ensure display is not none
                el.style.filter = 'grayscale(100%)';
                el.style.opacity = '0.3';
            });

            // Observe for new elements to make them editable
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === 1) { // Element node
                            if (node.matches && node.matches(editableSelectors)) {
                                makeEditable(node);
                            }
                            node.querySelectorAll && node.querySelectorAll(editableSelectors).forEach(makeEditable);
                        }
                    });
                });
            });
            observer.observe(iframeDoc.body, { childList: true, subtree: true });

            // Add event listeners for content changes with debounce
            const debouncedSync = this.debounce(() => {
                this.syncIframeChanges(iframe);
            }, 500);

            iframeDoc.body.addEventListener('input', () => {
                debouncedSync();
            });

            iframeDoc.body.addEventListener('blur', (e) => {
                if (e.target.hasAttribute('contenteditable')) {
                    this.syncIframeChanges(iframe); // Immediate sync on blur
                }
            }, true);

            // Handle clicks to select blocks
            iframeDoc.body.addEventListener('click', (e) => {
                // Don't follow links in edit mode
                if (e.target.closest('a')) {
                    e.preventDefault();
                }
                this.handleIframeClick(e, iframe);
            });

            // Handle hover effects to visualize blocks (including containers)
            let lastHovered = null;

            iframeDoc.body.addEventListener('mouseover', (e) => {
                // Stop propagation to avoid highlighting parents when child is hovered
                e.stopPropagation();

                const target = e.target;
                const block = this.getClosestBlock(target);

                // Clear previous hover if different
                if (lastHovered && lastHovered !== block) {
                    if (!lastHovered.classList.contains('block-active')) {
                        lastHovered.style.outline = "";
                    }
                    lastHovered = null;
                }

                if (block && !block.classList.contains('block-active')) {
                    // Light blue dashed outline for hover
                    block.style.outline = "1px dashed rgba(59, 130, 246, 0.5)";
                    lastHovered = block;
                }
            });

            iframeDoc.body.addEventListener('mouseout', (e) => {
                const block = this.getClosestBlock(e.target);
                if (block && !block.classList.contains('block-active')) {
                    block.style.outline = "";
                }
                if (lastHovered === block) {
                    lastHovered = null;
                }
            });

            // Handle double clicks for specialized editors (Link, Image, Button)
            iframeDoc.body.addEventListener('dblclick', (e) => {
                e.preventDefault();
                this.handleIframeDblClick(e, iframe);
            });

            // Floating toolbar removed as per user request (using main toolbox instead)
        },

        handleIframeDblClick(e, iframe) {
            if (!this.isEditingMode) return;
            const target = e.target;

            // Handle Links - Only if directly double-clicking on the A tag itself or inside it
            const link = target.closest('a');
            if (link) {
                e.preventDefault();
                this.openLinkEditModal(
                    this.activeBlockId,
                    link.getAttribute("href"),
                    link.innerText,
                    link
                );
                return;
            }

            // Handle Images
            if (target.tagName === "IMG") {
                e.preventDefault();
                this.openImageEditModal(
                    this.activeBlockId,
                    target.src,
                    target.alt,
                    target
                );
                return;
            }

            // Handle Buttons
            const btn = target.closest('button') || (target.classList.contains("btn") ? target : null);
            if (btn) {
                e.preventDefault();
                this.openButtonEditModal(this.activeBlockId, btn);
                return;
            }
        },

        handleIframeClick(e, iframe) {
            e.stopPropagation();
            const target = e.target;

            // Find closest block-level element
            const block = this.getClosestBlock(target);

            if (block) {
                this.activateBlock(block);

                // Update toolbox state based on selection
                this.updateToolboxState(block);
            } else {
                this.deactivateAllBlocks();
            }
        },



        // Sync changes from iframe back to previewHtml
        syncIframeChanges(iframe) {
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            if (!iframeDoc || !iframeDoc.body) return;

            // Store block-active elements and their styles before cloning (to restore later)
            const activeElements = [];
            iframeDoc.querySelectorAll(".block-active").forEach((el) => {
                activeElements.push({
                    element: el,
                    outline: el.style.outline,
                    outlineOffset: el.style.outlineOffset,
                    backgroundColor: el.style.backgroundColor
                });
            });

            // Clone the body content and clean it up for saving
            const clone = iframeDoc.body.cloneNode(true);

            // 1. Remove contenteditable attributes and classes
            clone.querySelectorAll('[contenteditable]').forEach(el => {
                el.removeAttribute('contenteditable');
                el.classList.remove('editable-element');
            });

            // 2. Remove visual editor specific classes and styles from ALL elements
            clone.querySelectorAll('*').forEach(el => {
                // Remove active class
                if (el.classList.contains('block-active')) {
                    el.classList.remove('block-active');
                }

                // Remove inline styles added by editor (outline)
                // We check if style attribute exists to avoid errors
                // Remove inline styles added by editor (outline)
                // We check if style attribute exists to avoid errors
                if (el.getAttribute('style')) {
                    // Remove outline styles
                    el.style.outline = '';
                    el.style.outlineOffset = '';

                    // Handle disabled elements: Add 'hidden' class for frontend, remove visual cues
                    if (el.dataset.disabled === 'true') {
                        el.classList.add('hidden');
                        el.style.filter = '';
                        el.style.opacity = '';
                        el.style.display = ''; // Remove inline display style
                    }

                    // If style attribute is empty after removal, remove it entirely
                    if (el.getAttribute('style') === '') {
                        el.removeAttribute('style');
                    }
                } else if (el.dataset.disabled === 'true') {
                    // Even if no style attribute, add hidden class if disabled
                    el.classList.add('hidden');
                }
            });

            // Update previewHtml with cleaned content
            this.previewHtml = clone.innerHTML;

            // Restore block-active styles on original iframe elements after sync
            // This ensures selected elements keep their border visible in the editor
            activeElements.forEach(({ element, outline, outlineOffset, backgroundColor }) => {
                if (element && element.classList.contains("block-active")) {
                    if (outline) element.style.outline = outline;
                    if (outlineOffset) element.style.outlineOffset = outlineOffset;
                    if (backgroundColor) element.style.backgroundColor = backgroundColor;
                }
            });

            // Update the block if editing
            if (this.activeBlockId) {
                const block = this.blocks.find(b => b.id === this.activeBlockId);
                if (block) {
                    block.html = this.previewHtml;
                }
            }
        },

        // ========================================================================
        // Drag and Drop Logic
        // ========================================================================
        handleDragStart(index, region, event) {
            this.isDragging = true;
            this.draggedBlockIndex = index;
            this.draggedBlockRegion = region;
            event.dataTransfer.effectAllowed = "move";
            event.dataTransfer.setData("text/plain", index);
        },

        handleDragOver(index, region, event) {
            event.preventDefault();
            if (this.draggedBlockRegion !== region) return;
            this.dragOverBlockIndex = index;
        },

        handleDrop(index, region, event) {
            event.preventDefault();
            if (this.draggedBlockRegion !== region) return;

            const fromIndex = this.draggedBlockIndex;
            const toIndex = index;

            if (fromIndex === toIndex) return;

            const blocksInRegion =
                region === "header" ? this.headerBlocks : this.bodyBlocks;
            const itemToMove = blocksInRegion[fromIndex];

            // Reorder logic within the region
            const reorderedRegionBlocks = [...blocksInRegion];
            reorderedRegionBlocks.splice(fromIndex, 1);
            reorderedRegionBlocks.splice(toIndex, 0, itemToMove);

            // Update position values within the region (0-based for each region)
            reorderedRegionBlocks.forEach((block, idx) => {
                block.position = idx;
            });

            // Get other blocks (from the other region)
            const otherBlocks = this.blocks.filter((b) => b.region !== region);

            // Reconstruct blocks array: headers first, then body blocks
            let finalBlocks = [];
            if (region === "header") {
                // Header region was reordered
                finalBlocks = [...reorderedRegionBlocks, ...otherBlocks];
            } else {
                // Body region was reordered
                const headers = otherBlocks.filter(
                    (b) => b.region === "header"
                );
                finalBlocks = [...headers, ...reorderedRegionBlocks];
            }

            // Update position values for body blocks (start after header count)
            const headerCount = finalBlocks.filter(
                (b) => b.region === "header"
            ).length;
            finalBlocks.forEach((block, idx) => {
                if (block.region === "header") {
                    // Header blocks: position 0, 1, 2, ...
                    block.position = idx;
                } else {
                    // Body blocks: position starts after header count
                    block.position = headerCount + (idx - headerCount);
                }
            });

            this.blocks = finalBlocks;

            this.resetDragState();
        },

        handleDragEnd() {
            this.resetDragState();
        },

        resetDragState() {
            this.isDragging = false;
            this.draggedBlockIndex = null;
            this.draggedBlockRegion = null;
            this.dragOverBlockIndex = null;
        },

        // ========================================================================
        // Block Management
        // ========================================================================
        openBlockSelector(type) {
            this.blockSelectorType = type;
            this.showBlockSelector = true;
            this.activeSelectorTab = "select";

            const categories = Object.keys(this.filteredComponents);
            if (categories.length > 0) {
                this.activeCategoryTab = categories[0];
            }
        },

        async addComponent(category, sectionName, component) {
            try {
                let routeUrl =
                    this.resources.routes?.getComponent ||
                    "/admin/content/page/api/components/get";

                // Defensive check for URL
                if (!routeUrl || typeof routeUrl !== "string") {
                    console.error("Invalid route URL for getComponent");
                    alert("Configuration error: Invalid API route.");
                    return;
                }

                const url = new URL(routeUrl, window.location.origin);
                url.searchParams.append("path", component.path);

                const response = await fetch(url.toString(), {
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                });

                let html =
                    '<div class="p-4 text-center text-gray-500">Error loading component</div>';
                if (response.ok) {
                    const data = await response.json();
                    html = data.html || html;
                }

                // Calculate position based on region
                let position = 0;
                if (this.blockSelectorType === "header") {
                    // Header blocks: position is the current header count
                    position = this.headerBlocks.length;
                } else {
                    // Body blocks: position starts after header count
                    position =
                        this.headerBlocks.length + this.bodyBlocks.length;
                }

                const newBlock = {
                    id:
                        "block_" +
                        Date.now() +
                        "_" +
                        Math.random().toString(36).substr(2, 9),
                    region: this.blockSelectorType,
                    name: component.name,
                    category: category,
                    section: sectionName,
                    path: component.path,
                    html: html,
                    position: position,
                };

                this.blocks.push(newBlock);
                this.showBlockSelector = false;
            } catch (error) {
                console.error("Error adding component:", error);
                alert("Error loading component. Please try again.");
            }
        },

        removeBlock(blockId) {
            if (confirm("Are you sure you want to remove this block?")) {
                const removedBlock = this.blocks.find((b) => b.id === blockId);
                const removedRegion = removedBlock?.region;

                this.blocks = this.blocks.filter((b) => b.id !== blockId);

                // Update position values: headers first (0-based), then body (after header count)
                const headerBlocks = this.blocks.filter(
                    (b) => b.region === "header"
                );
                const bodyBlocks = this.blocks.filter(
                    (b) => b.region === "body"
                );

                // Update header positions (0-based)
                headerBlocks.forEach((block, idx) => {
                    block.position = idx;
                });

                // Update body positions (start after header count)
                const headerCount = headerBlocks.length;
                bodyBlocks.forEach((block, idx) => {
                    block.position = headerCount + idx;
                });
            }
        },

        viewBlock(blockId) {
            const block = this.blocks.find((b) => b.id === blockId);
            if (!block) return;

            this.modals.preview.componentName = block.name || "Block Preview";
            this.isEditingMode = false;
            this.activeBlockId = blockId;
            this.previewComponentData = null; // Clear navigation data
            this.canNavigatePrevious = false;
            this.canNavigateNext = false;
            this.modals.preview.show = true;

            this.$nextTick(() => {
                this.renderPreview(
                    block.html ||
                    '<div class="p-4 text-center text-gray-500">No preview available</div>'
                );
            });
        },

        editBlock(blockId) {
            const block = this.blocks.find((b) => b.id === blockId);
            if (!block) return;

            // Store original HTML for cancel functionality
            this.originalBlockHtml = block.html;

            this.modals.preview.componentName = block.name || "Edit Block";
            this.isEditingMode = true;
            this.activeBlockId = blockId;
            this.previewComponentData = null; // Clear navigation data
            this.canNavigatePrevious = false;
            this.canNavigateNext = false;
            this.modals.preview.show = true;

            this.$nextTick(() => {
                this.renderPreview(
                    block.html || '<div class="p-4">No content to edit</div>'
                );

                const previewContainer = this.$refs.previewContainer;
                if (previewContainer) {
                    this.setupInlineEditing(previewContainer);
                }
            });
        },

        // ========================================================================
        // HTML Editor (CodeMirror)
        // ========================================================================
        async editBlockHtml(blockId) {
            const block = this.blocks.find((b) => b.id === blockId);
            if (!block) return;

            this.modals.htmlEditor.blockId = blockId;
            this.modals.htmlEditor.blockName = block.name || "Block";
            this.modals.htmlEditor.show = true;

            // Dynamic Import CodeMirror if needed
            if (!window.CM6) {
                try {
                    await import('../codemirror.js');
                } catch (e) {
                    console.error("Failed to load CodeMirror", e);
                    this.showToast("Failed to load Code Editor", "error");
                    return;
                }
            }

            // Dynamic Import Beautify if needed
            if (typeof html_beautify === 'undefined') {
                try {
                    const beautify = await import('js-beautify');
                    window.html_beautify = beautify.html;
                } catch (e) {
                    console.warn("Failed to load js-beautify", e);
                }
            }

            this.$nextTick(() => {
                this.initCodeMirror(block.html || "");
            });
        },

        initCodeMirror(html) {
            const textarea = this.$refs.htmlEditorTextarea;
            const container = textarea.parentElement; // CM6 mounts to a div, not textarea replacement

            if (!container) {
                console.error("HTML Editor container not found");
                return;
            }

            // Destroy existing editor if any
            if (this.modals.htmlEditor.editor) {
                this.modals.htmlEditor.editor.destroy();
                this.modals.htmlEditor.editor = null;
            }

            // Check if CodeMirror 6 is loaded
            if (!window.CM6) {
                console.error("CodeMirror 6 is not loaded");
                return;
            }

            const { EditorView, EditorState, minimalSetup, lineNumbers, html: htmlLang, dracula, keymap } = window.CM6;

            // Format HTML for better readability
            const formattedHtml = this.formatHtml(html || "");

            // Custom keymap
            const customKeymap = keymap.of([
                {
                    key: "Mod-s",
                    run: () => {
                        this.saveHtmlChanges();
                        return true;
                    }
                },
                {
                    key: "Escape",
                    run: () => {
                        this.closeHtmlEditor();
                        return true;
                    }
                }
            ]);

            // Custom theme for font size (16px) and non-monospace font
            const customTheme = EditorView.theme({
                "&": {
                    fontSize: "16px",
                    fontFamily: "Inter, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif !important"
                },
                ".cm-content": {
                    fontFamily: "Inter, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif !important"
                },
                ".cm-scroller": {
                    fontFamily: "Inter, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif !important"
                }
            });

            // Create Editor State
            const state = EditorState.create({
                doc: formattedHtml,
                extensions: [
                    minimalSetup, // No autocompletion
                    lineNumbers(), // Add line numbers explicitly
                    htmlLang(),
                    dracula,
                    customTheme,
                    customKeymap,
                    EditorView.lineWrapping,
                    EditorView.updateListener.of((update) => {
                        if (update.docChanged) {
                            // Optional: Sync back to textarea or data if needed
                        }
                    })
                ]
            });

            // Create Editor View
            this.modals.htmlEditor.editor = new EditorView({
                state: state,
                parent: container
            });

            // Hide the original textarea (if it wasn't already)
            textarea.style.display = 'none';

            // Ensure proper sizing
            const editorElement = this.modals.htmlEditor.editor.dom;
            editorElement.style.height = '100%';
            editorElement.style.width = '100%';
        },

        formatHtml(html) {
            if (!html) return "";

            // Use js-beautify if available
            if (typeof html_beautify !== 'undefined') {
                return html_beautify(html, {
                    indent_size: 2,
                    wrap_line_length: 0, // Disable wrapping to prevent breaking long attributes like clip-path
                    preserve_newlines: true,
                    max_preserve_newlines: 1,
                    indent_inner_html: true,
                    extra_liners: []
                });
            }

            // Fallback: Return raw HTML instead of trying to format it manually
            // The previous manual formatter was breaking complex HTML structures (SVG, clip-path, etc.)
            return html;
        },

        saveHtmlChanges() {
            if (!this.modals.htmlEditor.editor) return;

            const blockId = this.modals.htmlEditor.blockId;
            const block = this.blocks.find((b) => b.id === blockId);
            if (!block) return;

            // Get HTML from editor (CM6)
            const newHtml = this.modals.htmlEditor.editor.state.doc.toString();
            block.html = newHtml;

            // Show success message
            this.showToast("HTML changes saved successfully", "success");

            // Close editor
            this.closeHtmlEditor();
        },

        closeHtmlEditor() {
            // Destroy CodeMirror instance (CM6)
            if (this.modals.htmlEditor.editor) {
                this.modals.htmlEditor.editor.destroy();
                this.modals.htmlEditor.editor = null;
            }

            // Reset textarea visibility
            if (this.$refs.htmlEditorTextarea) {
                this.$refs.htmlEditorTextarea.style.display = '';
            }

            // Reset state
            this.modals.htmlEditor.show = false;
            this.modals.htmlEditor.blockId = null;
            this.modals.htmlEditor.blockName = "";
            this.modals.htmlEditor.showAiPrompt = false;
            this.modals.htmlEditor.aiPrompt = "";
            this.modals.htmlEditor.aiLoading = false;
        },

        async fixWithAI() {
            const prompt = this.modals.htmlEditor.aiPrompt?.trim();
            if (!prompt || this.modals.htmlEditor.aiLoading) return;

            const editor = this.modals.htmlEditor.editor;
            if (!editor) return;

            // Get content (CM6)
            const currentHtml = editor.state.doc.toString();
            if (!currentHtml) {
                this.showToast("No HTML content to fix", "warning");
                return;
            }

            this.modals.htmlEditor.aiLoading = true;

            try {
                const routeUrl = this.resources.routes?.fixWithAI || '/admin/content/page/api/fix-with-ai';

                const response = await fetch(routeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({
                        html: currentHtml,
                        prompt: prompt,
                    }),
                });

                const data = await response.json();

                if (data.success && data.html) {
                    // Update CodeMirror with the fixed HTML (CM6)
                    editor.dispatch({
                        changes: {
                            from: 0,
                            to: editor.state.doc.length,
                            insert: data.html
                        }
                    });

                    this.showToast("HTML updated by AI", "success");
                    this.modals.htmlEditor.showAiPrompt = false;
                    this.modals.htmlEditor.aiPrompt = "";
                } else {
                    this.showToast(data.error || "Failed to fix HTML", "error");
                }
            } catch (error) {
                console.error("AI Fix Error:", error);
                this.showToast("An error occurred while processing your request", "error");
            } finally {
                this.modals.htmlEditor.aiLoading = false;
            }
        },

        formatCategoryName(name) {
            if (!name) return "";
            return (
                name.charAt(0).toUpperCase() + name.slice(1).replace(/_/g, " ")
            );
        },

        async previewComponent(component) {
            try {
                let routeUrl =
                    this.resources.routes?.getComponent ||
                    "/admin/content/page/api/components/get";

                if (!routeUrl) throw new Error("Invalid route URL");

                const url = new URL(routeUrl, window.location.origin);
                url.searchParams.append("path", component.path);

                const response = await fetch(url.toString(), {
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    this.modals.preview.componentName = component.name;

                    // Store component data for navigation
                    const category = this.getComponentCategory(component.path);
                    const sectionName = this.getComponentSection(
                        component.path
                    );

                    this.previewComponentData = {
                        component: component,
                        category: category,
                        sectionName: sectionName,
                        blockSelectorType: this.blockSelectorType || "body",
                    };

                    // Update navigation buttons
                    this.updateNavigationButtons();

                    this.modals.preview.show = true;
                    this.isEditingMode = false;
                    document.body.style.overflow = "hidden";

                    this.$nextTick(() => {
                        this.renderPreview(data.html);
                    });
                } else {
                    this.showToast("Error loading preview", "error");
                }
            } catch (error) {
                console.error("Error loading preview:", error);
                this.showToast("Error loading preview", "error");
            }
        },

        getComponentCategory(componentPath) {
            const parts = componentPath.split("/");
            return parts[0] || "marketing";
        },

        getComponentSection(componentPath) {
            const parts = componentPath.split("/");
            return parts[1] || "";
        },

        getAllComponentsFlat() {
            // Use filteredComponents to respect the current filter
            // Previous/Next navigation will only show filtered components
            const currentMode =
                this.previewComponentData?.blockSelectorType ||
                this.blockSelectorType ||
                "body";
            const sourceComponents =
                currentMode === "body"
                    ? this.filteredComponents
                    : this.resources.components;
            const allComponents = [];

            for (const [category, sections] of Object.entries(
                sourceComponents || {}
            )) {
                for (const [sectionName, section] of Object.entries(
                    sections || {}
                )) {
                    if (
                        section.components &&
                        Array.isArray(section.components)
                    ) {
                        section.components.forEach((comp) => {
                            allComponents.push({
                                ...comp,
                                category: category,
                                sectionName: sectionName,
                            });
                        });
                    }
                }
            }
            return allComponents;
        },

        updateNavigationButtons() {
            if (!this.previewComponentData) {
                this.canNavigatePrevious = false;
                this.canNavigateNext = false;
                return;
            }

            const allComponents = this.getAllComponentsFlat();
            const currentIndex = allComponents.findIndex(
                (comp) => comp.path === this.previewComponentData.component.path
            );

            this.canNavigatePrevious = currentIndex > 0;
            this.canNavigateNext = currentIndex < allComponents.length - 1;
        },

        // NOTE: addComponentFromPreview is defined later in the file (in Preview Modal Control section)
        // with showToast notification - that version is the primary one.

        addCustomBlock() {
            if (!this.customBlockName || !this.customBlockHtml) {
                this.showToast(
                    "Please enter both component name and HTML code.",
                    "warning"
                );
                return;
            }

            // Calculate position based on region
            let position = 0;
            if (this.blockSelectorType === "header") {
                // Header blocks: position is the current header count
                position = this.headerBlocks.length;
            } else {
                // Body blocks: position starts after header count
                position = this.headerBlocks.length + this.bodyBlocks.length;
            }

            const newBlock = {
                id:
                    "block_" +
                    Date.now() +
                    "_" +
                    Math.random().toString(36).substr(2, 9),
                region: this.blockSelectorType,
                name: this.customBlockName,
                category: "custom",
                section: "custom",
                path: "custom",
                html: this.customBlockHtml,
                position: position,
            };

            this.blocks.push(newBlock);
            this.customBlockName = "";
            this.customBlockHtml = "";
            this.showBlockSelector = false;
        },

        // ========================================================================
        // Inline Editing System (ContentTools Style)
        // ========================================================================
        // ========================================================================
        // Fixed Toolbox System (ContentTools Style)
        // ========================================================================
        setupInlineEditing(wrapper) {
            // Cleanup existing listeners
            if (this.cleanupPreviewListeners) {
                this.cleanupPreviewListeners();
            }

            // 1. Selection Listener (Update Toolbox State)
            // Use iframe document if available, otherwise main document
            const targetDoc = this.getTargetDocument();
            const targetWindow = this.getTargetWindow();

            // If using iframe, use iframe's body as wrapper, otherwise use provided wrapper
            const actualWrapper = targetDoc && targetDoc.body ? targetDoc.body : wrapper;

            const selectionHandler = () => {
                if (!this.isEditingMode) return;
                this.updateToolboxState();
            };

            if (targetDoc && targetWindow) {
                targetDoc.addEventListener("selectionchange", selectionHandler);
            } else {
                document.addEventListener("selectionchange", selectionHandler);
            }

            // 2. Block Interaction Listeners (Hover/Click)
            const mouseOverHandler = (e) => {
                if (!this.isEditingMode) return;
                let target = e.target;

                // Prevent hover on wrapper itself
                const isWrapper =
                    target === actualWrapper ||
                    target === targetDoc?.body ||
                    target.id === "page-builder-preview";
                if (isWrapper) return;

                // If hovering over a background image, highlight its parent div
                if (target.tagName === "IMG") {
                    const computedStyle = targetWindow?.getComputedStyle(target) || window.getComputedStyle(target);
                    const position = computedStyle.position;
                    const zIndex = computedStyle.zIndex;
                    const isBackgroundImage =
                        position === "absolute" ||
                        target.classList.contains("absolute") ||
                        (zIndex && parseInt(zIndex) < 0) ||
                        target.classList.contains("-z-10") ||
                        target.classList.contains("-z-20");

                    if (
                        isBackgroundImage &&
                        target.parentNode &&
                        target.parentNode.tagName === "DIV"
                    ) {
                        target = target.parentNode; // Use parent div for hover effect
                    }
                }

                // Identify editable content elements
                const contentSelectors =
                    "h1, h2, h3, h4, h5, h6, p, ul, ol, li, blockquote, figure, img, a, td, th, span";
                const contentBlock = target.closest(contentSelectors);

                // Identify header/footer elements
                const isHeaderFooter =
                    target.tagName === "HEADER" || target.tagName === "FOOTER";

                // Identify container DIVs (with layout classes)
                const isContainerDiv =
                    target.tagName === "DIV" &&
                    (target.classList.contains("max-w-sm") ||
                        target.classList.contains("max-w-md") ||
                        target.classList.contains("max-w-lg") ||
                        target.classList.contains("max-w-xl") ||
                        target.classList.contains("max-w-2xl") ||
                        target.classList.contains("max-w-3xl") ||
                        target.classList.contains("max-w-4xl") ||
                        target.classList.contains("max-w-5xl") ||
                        target.classList.contains("max-w-6xl") ||
                        target.classList.contains("max-w-7xl") ||
                        target.classList.contains("container") ||
                        target.classList.contains("mx-auto") ||
                        target.classList.contains("w-full") ||
                        target.classList.contains("relative") ||
                        target.classList.contains("isolate"));

                // Check if this div has a background image (absolute positioned img with negative z-index)
                const hasBackgroundImage = (() => {
                    if (target.tagName !== "DIV") return false;
                    const imgs = target.querySelectorAll("img");
                    for (const img of imgs) {
                        const computedStyle = targetWindow?.getComputedStyle(img) || window.getComputedStyle(img);
                        const position = computedStyle.position;
                        const zIndex = computedStyle.zIndex;
                        if (
                            position === "absolute" ||
                            img.classList.contains("absolute") ||
                            (zIndex && parseInt(zIndex) < 0) ||
                            img.classList.contains("-z-10") ||
                            img.classList.contains("-z-20")
                        ) {
                            return true;
                        }
                    }
                    return false;
                })();

                if (
                    contentBlock &&
                    actualWrapper.contains(contentBlock) &&
                    contentBlock !== actualWrapper
                ) {
                    // Text elements - Yellow hover
                    // Skip hover effect if element is already selected (block-active)
                    if (contentBlock.classList.contains("block-active")) {
                        return; // Don't apply hover to selected elements
                    }
                    if (!contentBlock.classList.contains("block-active")) {
                        // Store original backgroundColor if it exists (user-defined)
                        if (
                            contentBlock.style.backgroundColor &&
                            !contentBlock.dataset.originalBgColor
                        ) {
                            contentBlock.dataset.originalBgColor =
                                contentBlock.style.backgroundColor;
                        }
                        // Store original color if it exists (user-defined)
                        if (
                            contentBlock.style.color &&
                            !contentBlock.dataset.originalColor
                        ) {
                            contentBlock.dataset.originalColor =
                                contentBlock.style.color;
                        }

                        contentBlock.style.outline = "2px dashed #fde047"; // Light yellow
                        contentBlock.style.outlineOffset = "2px";
                        // Only apply hover background if there's no user-defined background
                        if (!contentBlock.dataset.originalBgColor) {
                            contentBlock.style.backgroundColor =
                                "rgba(254, 243, 199, 0.3)"; // Light yellow bg
                        }
                    }
                } else if (
                    isHeaderFooter &&
                    actualWrapper.contains(target) &&
                    target !== actualWrapper
                ) {
                    // Header/Footer - Red/Orange hover
                    // Skip hover effect if element is already selected
                    if (target.classList.contains("block-active")) {
                        return; // Don't apply hover to selected elements
                    }
                    if (!target.classList.contains("block-active")) {
                        // Store original styles
                        if (
                            target.style.backgroundColor &&
                            !target.dataset.originalBgColor
                        ) {
                            target.dataset.originalBgColor =
                                target.style.backgroundColor;
                        }
                        if (
                            target.style.color &&
                            !target.dataset.originalColor
                        ) {
                            target.dataset.originalColor = target.style.color;
                        }

                        target.style.outline = "2px dashed #fb923c"; // Orange
                        target.style.outlineOffset = "2px";
                        if (!target.dataset.originalBgColor) {
                            target.style.backgroundColor =
                                "rgba(254, 215, 170, 0.3)"; // Light orange bg
                        }
                    }
                } else if (
                    isContainerDiv &&
                    actualWrapper.contains(target) &&
                    target !== actualWrapper
                ) {
                    // Container DIVs - Light blue hover
                    // Skip hover effect if element is already selected
                    if (target.classList.contains("block-active")) {
                        return; // Don't apply hover to selected elements
                    }
                    if (!target.classList.contains("block-active")) {
                        // Store original styles
                        if (
                            target.style.backgroundColor &&
                            !target.dataset.originalBgColor
                        ) {
                            target.dataset.originalBgColor =
                                target.style.backgroundColor;
                        }
                        if (
                            target.style.color &&
                            !target.dataset.originalColor
                        ) {
                            target.dataset.originalColor = target.style.color;
                        }

                        target.style.outline = "2px dashed #93c5fd"; // Light blue
                        target.style.outlineOffset = "2px";
                        if (!target.dataset.originalBgColor) {
                            target.style.backgroundColor =
                                "rgba(239, 246, 255, 0.5)"; // Very light blue bg
                        }
                        target.style.cursor = "crosshair"; // Targeting cursor
                    }
                } else if (
                    hasBackgroundImage &&
                    actualWrapper.contains(target) &&
                    target !== actualWrapper &&
                    target.tagName === "DIV"
                ) {
                    // Background image container - Simple hover effect
                    // Skip hover effect if element is already selected
                    if (target.classList.contains("block-active")) {
                        return; // Don't apply hover to selected elements
                    }
                    if (!target.classList.contains("block-active")) {
                        // Store original styles
                        if (
                            target.style.backgroundColor &&
                            !target.dataset.originalBgColor
                        ) {
                            target.dataset.originalBgColor =
                                target.style.backgroundColor;
                        }
                        if (
                            target.style.color &&
                            !target.dataset.originalColor
                        ) {
                            target.dataset.originalColor = target.style.color;
                        }

                        target.classList.add("has-background-image-hover");
                        target.style.outline = "2px dashed #60a5fa";
                        target.style.outlineOffset = "2px";
                        if (!target.dataset.originalBgColor) {
                            target.style.backgroundColor =
                                "rgba(239, 246, 255, 0.4)"; // Light blue bg
                        }
                        target.style.cursor = "pointer"; // Pointer cursor
                    }
                }
            };

            const mouseOutHandler = (e) => {
                if (!this.isEditingMode) return;
                let target = e.target;

                // If leaving a background image, check if we should use parent div
                if (target.tagName === "IMG") {
                    const computedStyle = targetWindow?.getComputedStyle(target) || window.getComputedStyle(target);
                    const position = computedStyle.position;
                    const zIndex = computedStyle.zIndex;
                    const isBackgroundImage =
                        position === "absolute" ||
                        target.classList.contains("absolute") ||
                        (zIndex && parseInt(zIndex) < 0) ||
                        target.classList.contains("-z-10") ||
                        target.classList.contains("-z-20");

                    if (
                        isBackgroundImage &&
                        target.parentNode &&
                        target.parentNode.tagName === "DIV"
                    ) {
                        target = target.parentNode; // Use parent div for hover removal
                    }
                }

                const contentSelectors =
                    "h1, h2, h3, h4, h5, h6, p, ul, ol, li, blockquote, figure, img, a, td, th, span";
                const contentBlock = target.closest(contentSelectors);

                const isHeaderFooter =
                    target.tagName === "HEADER" || target.tagName === "FOOTER";

                const isContainerDiv =
                    target.tagName === "DIV" &&
                    (target.classList.contains("max-w-sm") ||
                        target.classList.contains("max-w-md") ||
                        target.classList.contains("max-w-lg") ||
                        target.classList.contains("max-w-xl") ||
                        target.classList.contains("max-w-2xl") ||
                        target.classList.contains("max-w-3xl") ||
                        target.classList.contains("max-w-4xl") ||
                        target.classList.contains("max-w-5xl") ||
                        target.classList.contains("max-w-6xl") ||
                        target.classList.contains("max-w-7xl") ||
                        target.classList.contains("container") ||
                        target.classList.contains("mx-auto") ||
                        target.classList.contains("w-full") ||
                        target.classList.contains("relative") ||
                        target.classList.contains("isolate"));

                // Remove hover outline and background if not active
                // Restore original user-defined styles
                if (
                    contentBlock &&
                    !contentBlock.classList.contains("block-active")
                ) {
                    contentBlock.style.outline = "";
                    contentBlock.style.outlineOffset = "";
                    // Restore original backgroundColor if it was stored
                    if (contentBlock.dataset.originalBgColor) {
                        contentBlock.style.backgroundColor =
                            contentBlock.dataset.originalBgColor;
                        delete contentBlock.dataset.originalBgColor;
                    } else {
                        contentBlock.style.backgroundColor = "";
                    }
                    // Restore original color if it was stored
                    if (contentBlock.dataset.originalColor) {
                        contentBlock.style.color =
                            contentBlock.dataset.originalColor;
                        delete contentBlock.dataset.originalColor;
                    }
                } else if (
                    isHeaderFooter &&
                    !target.classList.contains("block-active")
                ) {
                    target.style.outline = "";
                    target.style.outlineOffset = "";
                    if (target.dataset.originalBgColor) {
                        target.style.backgroundColor =
                            target.dataset.originalBgColor;
                        delete target.dataset.originalBgColor;
                    } else {
                        target.style.backgroundColor = "";
                    }
                    if (target.dataset.originalColor) {
                        target.style.color = target.dataset.originalColor;
                        delete target.dataset.originalColor;
                    }
                } else if (
                    isContainerDiv &&
                    !target.classList.contains("block-active")
                ) {
                    target.style.outline = "";
                    target.style.outlineOffset = "";
                    if (target.dataset.originalBgColor) {
                        target.style.backgroundColor =
                            target.dataset.originalBgColor;
                        delete target.dataset.originalBgColor;
                    } else {
                        target.style.backgroundColor = "";
                    }
                    if (target.dataset.originalColor) {
                        target.style.color = target.dataset.originalColor;
                        delete target.dataset.originalColor;
                    }
                    target.style.cursor = "";
                }

                // Remove background image hover class only from the specific element
                if (
                    target.classList.contains("has-background-image-hover") &&
                    !target.classList.contains("block-active")
                ) {
                    target.classList.remove("has-background-image-hover");
                    target.style.outline = "";
                    target.style.outlineOffset = "";
                    if (target.dataset.originalBgColor) {
                        target.style.backgroundColor =
                            target.dataset.originalBgColor;
                        delete target.dataset.originalBgColor;
                    } else {
                        target.style.backgroundColor = "";
                    }
                    if (target.dataset.originalColor) {
                        target.style.color = target.dataset.originalColor;
                        delete target.dataset.originalColor;
                    }
                    target.style.cursor = "";
                }
            };

            const clickHandler = (e) => {
                if (!this.isEditingMode) return;

                const target = e.target;

                // Handle Links - Only if directly clicking on the A tag itself
                // If clicking on a child element (like span), select the child instead
                if (target.tagName === "A") {
                    e.preventDefault();
                    this.openLinkEditModal(
                        this.activeBlockId,
                        target.getAttribute("href"),
                        target.innerText,
                        target
                    );
                    return;
                }

                // If clicking on a child element inside a link (like span), don't open link modal
                // Instead, let it fall through to normal content selection
                if (target.closest("a") && target.tagName !== "A") {
                    // This is a child element inside a link, don't open link modal
                    // Continue to normal selection logic below
                }

                // Handle Images
                if (target.tagName === "IMG") {
                    e.preventDefault();

                    // Check if this is a background image (absolute positioned, negative z-index)
                    const computedStyle = targetWindow?.getComputedStyle(target) || window.getComputedStyle(target);
                    const position = computedStyle.position;
                    const zIndex = computedStyle.zIndex;
                    const isBackgroundImage =
                        position === "absolute" ||
                        target.classList.contains("absolute") ||
                        (zIndex && parseInt(zIndex) < 0) ||
                        target.classList.contains("-z-10") ||
                        target.classList.contains("-z-20");

                    // If it's a background image, select the parent container instead
                    if (isBackgroundImage && target.parentNode) {
                        const parent = target.parentNode;
                        // Check if parent is a valid container div
                        const isContainerDiv =
                            parent.tagName === "DIV" &&
                            (parent.classList.contains("max-w-sm") ||
                                parent.classList.contains("max-w-md") ||
                                parent.classList.contains("max-w-lg") ||
                                parent.classList.contains("max-w-xl") ||
                                parent.classList.contains("max-w-2xl") ||
                                parent.classList.contains("max-w-3xl") ||
                                parent.classList.contains("max-w-4xl") ||
                                parent.classList.contains("max-w-5xl") ||
                                parent.classList.contains("max-w-6xl") ||
                                parent.classList.contains("max-w-7xl") ||
                                parent.classList.contains("container") ||
                                parent.classList.contains("mx-auto") ||
                                parent.classList.contains("w-full") ||
                                parent.classList.contains("relative") ||
                                parent.classList.contains("isolate"));

                        if (isContainerDiv || parent.tagName === "DIV") {
                            // Select parent div instead
                            actualWrapper
                                .querySelectorAll(".block-active")
                                .forEach((el) => {
                                    el.classList.remove("block-active");
                                    el.style.outline = "";
                                    el.style.outlineOffset = "";
                                    el.style.backgroundColor = "";
                                });

                            parent.classList.add("block-active");
                            parent.style.outline = "2px solid #60a5fa"; // Solid when selected
                            parent.style.outlineOffset = "2px";
                            parent.style.backgroundColor =
                                "rgba(239, 246, 255, 0.5)";

                            this.updateToolboxState();
                            return;
                        }
                    }

                    // Regular image - open image edit modal
                    this.openImageEditModal(
                        this.activeBlockId,
                        target.src,
                        target.alt,
                        target
                    );
                    return;
                }

                // Handle Buttons
                if (
                    target.tagName === "BUTTON" ||
                    target.classList.contains("btn")
                ) {
                    e.preventDefault();
                    this.openButtonEditModal(this.activeBlockId, target);
                    return;
                }

                // Handle Block Activation (Content Elements + Container DIVs + Header/Footer)
                const contentSelectors =
                    "h1, h2, h3, h4, h5, h6, p, ul, ol, li, blockquote, figure, img, a, td, th, span";
                const contentBlock = target.closest(contentSelectors);

                const isHeaderFooter =
                    target.tagName === "HEADER" || target.tagName === "FOOTER";

                const isContainerDiv =
                    target.tagName === "DIV" &&
                    (target.classList.contains("max-w-sm") ||
                        target.classList.contains("max-w-md") ||
                        target.classList.contains("max-w-lg") ||
                        target.classList.contains("max-w-xl") ||
                        target.classList.contains("max-w-2xl") ||
                        target.classList.contains("max-w-3xl") ||
                        target.classList.contains("max-w-4xl") ||
                        target.classList.contains("max-w-5xl") ||
                        target.classList.contains("max-w-6xl") ||
                        target.classList.contains("max-w-7xl") ||
                        target.classList.contains("container") ||
                        target.classList.contains("mx-auto") ||
                        target.classList.contains("w-full") ||
                        target.classList.contains("relative") ||
                        target.classList.contains("isolate") ||
                        // Also check if div has children (likely a container)
                        (target.children.length > 0 && !contentBlock));

                // Prevent selecting the wrapper itself or any parent/layout wrapper DIVs
                const isWrapper =
                    target === actualWrapper ||
                    target === targetDoc?.body ||
                    target.id === "page-builder-preview" ||
                    target.classList.contains("preview-wrapper") ||
                    (target.tagName === "DIV" &&
                        !isContainerDiv &&
                        !contentBlock &&
                        !isHeaderFooter);

                // Special handling for span elements inside links
                // If clicking on a span inside a link, select the span, not the link
                let elementToActivate = null;
                if (target.tagName === "SPAN" && target.closest("a")) {
                    // Clicking on a span inside a link - select the span
                    elementToActivate = target;
                } else if (
                    !isWrapper &&
                    ((contentBlock &&
                        actualWrapper.contains(contentBlock) &&
                        contentBlock !== actualWrapper) ||
                        (isHeaderFooter &&
                            actualWrapper.contains(target) &&
                            target !== actualWrapper) ||
                        (isContainerDiv &&
                            actualWrapper.contains(target) &&
                            target !== actualWrapper))
                ) {
                    // Normal selection - use contentBlock or target
                    elementToActivate = contentBlock || target;
                }

                if (elementToActivate) {
                    // Remove previous active class from all elements
                    actualWrapper.querySelectorAll(".block-active").forEach((el) => {
                        el.classList.remove("block-active");
                        el.style.outline = "";
                        el.style.outlineOffset = "";
                        el.style.backgroundColor = ""; // Clear background
                    });

                    // Add active class to current element
                    elementToActivate.classList.add("block-active");

                    // Different colors for text vs containers vs header/footer (with backgrounds)
                    // Keep the outline visible when selected (solid instead of dashed for better visibility)
                    if (isHeaderFooter && !contentBlock) {
                        // Header/Footer - Orange border + light orange background
                        elementToActivate.style.outline = "2px solid #f97316"; // Orange (solid when selected)
                        elementToActivate.style.backgroundColor =
                            "rgba(254, 215, 170, 0.3)"; // Light orange bg
                    } else if (isContainerDiv && !contentBlock) {
                        // Container DIV - Blue border + light blue background
                        elementToActivate.style.outline = "2px solid #60a5fa"; // Solid when selected
                        elementToActivate.style.backgroundColor =
                            "rgba(239, 246, 255, 0.5)"; // Light blue bg
                    } else {
                        // Text elements (including spans) - Yellow border + light yellow background
                        elementToActivate.style.outline = "2px solid #eab308"; // Solid when selected
                        elementToActivate.style.backgroundColor =
                            "rgba(254, 243, 199, 0.3)"; // Light yellow bg
                    }
                    elementToActivate.style.outlineOffset = "2px";

                    this.updateToolboxState();
                } else {
                    // Clicked outside content or on wrapper - clear all selections
                    actualWrapper.querySelectorAll(".block-active").forEach((el) => {
                        el.classList.remove("block-active");
                        el.style.outline = "";
                        el.style.outlineOffset = "";
                        el.style.backgroundColor = ""; // Clear background
                    });
                }
            };

            const dblClickHandler = (e) => {
                if (!this.isEditingMode) return;
                const target = e.target;

                // Handle Links - Only if directly double-clicking on the A tag itself
                if (target.tagName === "A") {
                    e.preventDefault();
                    this.openLinkEditModal(
                        this.activeBlockId,
                        target.getAttribute("href"),
                        target.innerText,
                        target
                    );
                    return;
                }

                // If double-clicking on a child element inside a link (like span), don't open link modal
                // Let it fall through to normal editing

                // Handle Images
                if (target.tagName === "IMG") {
                    e.preventDefault();
                    this.openImageEditModal(
                        this.activeBlockId,
                        target.src,
                        target.alt,
                        target
                    );
                    return;
                }

                // Handle Buttons
                if (
                    target.tagName === "BUTTON" ||
                    target.classList.contains("btn")
                ) {
                    e.preventDefault();
                    this.openButtonEditModal(this.activeBlockId, target);
                    return;
                }
            };

            // 3. Input Handling (Sync & UX)
            const inputHandler = (e) => {
                if (!this.isEditingMode) return;

                // Sync on keyup
                if (e.type === "keyup") {
                    this.syncChanges();
                    this.updateToolboxState();
                }

                // Handle Enter key for Paragraphs
                if (e.type === "keydown" && e.key === "Enter" && !e.shiftKey) {
                    const selection = targetWindow?.getSelection() || window.getSelection();
                    if (selection && selection.rangeCount > 0) {
                        const range = selection.getRangeAt(0);
                        const block =
                            range.commonAncestorContainer.nodeType === 1
                                ? range.commonAncestorContainer
                                : range.commonAncestorContainer.parentNode;

                        // If inside a header or other non-paragraph block, prevent default and insert P
                        if (
                            ["H1", "H2", "H3", "H4", "H5", "H6"].includes(
                                block.tagName
                            )
                        ) {
                            e.preventDefault();
                            // Insert paragraph after
                            const p = targetDoc?.createElement("p") || document.createElement("p");
                            p.innerHTML = "<br>";
                            block.after(p);

                            // Move cursor to new paragraph
                            const newRange = (targetDoc?.createRange() || document.createRange());
                            newRange.setStart(p, 0);
                            newRange.collapse(true);
                            selection.removeAllRanges();
                            selection.addRange(newRange);
                        }
                    }
                }
            };

            // 4. Paste Handling (Plain Text)
            const pasteHandler = (e) => {
                if (!this.isEditingMode) return;
                e.preventDefault();
                const text = (e.originalEvent || e).clipboardData.getData(
                    "text/plain"
                );
                const targetDocForPaste = this.getTargetDocument();
                if (targetDocForPaste) {
                    targetDocForPaste.execCommand("insertText", false, text);
                } else {
                    document.execCommand("insertText", false, text);
                }
                this.syncChanges();
            };

            // Attach Listeners to actual wrapper (iframe body or main wrapper)
            actualWrapper.addEventListener("mouseover", mouseOverHandler);
            actualWrapper.addEventListener("mouseout", mouseOutHandler);
            actualWrapper.addEventListener("click", clickHandler);
            actualWrapper.addEventListener("dblclick", dblClickHandler);
            actualWrapper.addEventListener("keydown", inputHandler);
            actualWrapper.addEventListener("keyup", inputHandler);
            actualWrapper.addEventListener("paste", pasteHandler);

            // Store cleanup function
            this.cleanupPreviewListeners = () => {
                if (targetDoc && targetWindow) {
                    targetDoc.removeEventListener("selectionchange", selectionHandler);
                } else {
                    document.removeEventListener("selectionchange", selectionHandler);
                }
                actualWrapper.removeEventListener("mouseover", mouseOverHandler);
                actualWrapper.removeEventListener("mouseout", mouseOutHandler);
                actualWrapper.removeEventListener("click", clickHandler);
                actualWrapper.removeEventListener("dblclick", dblClickHandler);
                actualWrapper.removeEventListener("keydown", inputHandler);
                actualWrapper.removeEventListener("keyup", inputHandler);
                actualWrapper.removeEventListener("paste", pasteHandler);
            };

            // Make all text elements editable
            this.makeContentEditable(actualWrapper);
        },

        updateToolboxState(node = null) {
            // Get the correct document and window (iframe or main document)
            const targetDoc = this.getTargetDocument();
            const targetWindow = this.getTargetWindow();

            if (!targetDoc || !targetWindow) return;

            this.toolbox.activeTools = [];

            // Check active formats using queryCommandState (only works with selection)
            try {
                if (targetDoc.queryCommandState("bold")) this.toolbox.activeTools.push("bold");
                if (targetDoc.queryCommandState("italic")) this.toolbox.activeTools.push("italic");
                if (targetDoc.queryCommandState("underline")) this.toolbox.activeTools.push("underline");
                if (targetDoc.queryCommandState("strikethrough")) this.toolbox.activeTools.push("strike");
                if (targetDoc.queryCommandState("justifyLeft")) this.toolbox.activeTools.push("align-left");
                if (targetDoc.queryCommandState("justifyCenter")) this.toolbox.activeTools.push("align-center");
                if (targetDoc.queryCommandState("justifyRight")) this.toolbox.activeTools.push("align-right");
                if (targetDoc.queryCommandState("justifyFull")) this.toolbox.activeTools.push("align-justify");
                if (targetDoc.queryCommandState("insertUnorderedList")) this.toolbox.activeTools.push("ul");
                if (targetDoc.queryCommandState("insertOrderedList")) this.toolbox.activeTools.push("ol");
            } catch (e) {
                // Ignore errors if no selection or command not supported
            }

            // Check block type
            if (!node) {
                const selection = targetWindow.getSelection();
                if (selection && selection.rangeCount > 0) {
                    node = selection.anchorNode;
                }
            }

            const blockEl = this.getClosestBlock(node);
            if (blockEl) {
                const tagName = blockEl.tagName;
                if (tagName === "BLOCKQUOTE")
                    this.toolbox.activeTools.push("quote");
                else this.toolbox.activeTools.push(tagName.toLowerCase());

                // Add select-parent capability if parent exists and is within editable area
                // This allows the main toolbox to show a "Select Parent" button if implemented
                if (blockEl.parentNode &&
                    blockEl.parentNode !== targetDoc.body &&
                    blockEl.parentNode.id !== 'page-builder-preview' &&
                    !blockEl.parentNode.classList.contains('preview-content-wrapper')) {
                    this.toolbox.activeTools.push("select-parent");
                }
            }

            // Check context (Link/Image)
            if (node) {
                const el = node.nodeType === 1 ? node : node.parentNode;
                if (el.closest("a")) this.toolbox.activeTools.push("edit-link");
                if (el.tagName === "IMG" || el.closest("img"))
                    this.toolbox.activeTools.push("edit-image");
            }
        },

        // NOTE: getSelectionNode(), getClosestBlock(), getWidgetWrapper() are defined in Helper Methods section above

        ensureSelection() {
            // Get the correct document (iframe or main document)
            const targetDoc = this.getTargetDocument();
            const targetWindow = this.getTargetWindow();

            if (!targetDoc || !targetWindow) return;

            const selection = targetWindow.getSelection();

            // If we have a valid selection inside the preview container, we are good
            if (selection.rangeCount > 0) {
                const node = selection.anchorNode;
                if (node) {
                    const el = node.nodeType === 1 ? node : node.parentNode;
                    if (
                        el &&
                        el.closest &&
                        (el.closest(".preview-content-wrapper") ||
                            el.closest("body") === targetDoc.body)
                    ) {
                        return;
                    }
                }
            }

            // If no valid selection, try to select the active block's content
            const activeBlock = targetDoc.querySelector(".block-active");
            if (activeBlock) {
                // Find the first contenteditable element inside
                const editable =
                    activeBlock.querySelector('[contenteditable="true"]') ||
                    activeBlock;

                // Create range
                const range = targetDoc.createRange();
                range.selectNodeContents(editable);
                selection.removeAllRanges();
                selection.addRange(range);
            }
        },

        // NOTE: getTargetDocument() and getTargetWindow() are defined in Helper Methods section above

        executeTool(tool) {
            if (!this.isEditingMode) return;

            this.ensureSelection();

            // Get the correct document (iframe or main document)
            const targetDoc = this.getTargetDocument();
            const targetWindow = this.getTargetWindow();

            if (!targetDoc || !targetWindow) return;

            // Focus editor if needed (though clicking button might have blurred it)
            if (targetWindow) {
                targetWindow.focus();
            }
            // document.execCommand usually works on the current selection.

            switch (tool.action) {
                case "formatBlock":
                    // Use custom method to preserve classes and styles
                    this.changeBlockTag(tool.value);
                    break;
                case "link":
                    const url = prompt("Enter link URL:", "http://");
                    if (url) targetDoc.execCommand("createLink", false, url);
                    break;
                case "image":
                    // Trigger hidden file input
                    const fileInput = document.getElementById(
                        "toolbox-image-upload"
                    );
                    if (fileInput) fileInput.click();
                    break;
                case "editLink":
                    this.editCurrentLink();
                    break;
                case "editImage":
                    this.editCurrentImage();
                    break;
                case "moveUp":
                    this.moveBlock("up");
                    break;
                case "moveDown":
                    this.moveBlock("down");
                    break;
                case "selectParent":
                    this.selectParentBlock();
                    break;
                case "selectChild":
                    this.selectChildBlock();
                    break;
                case "clone":
                    this.cloneBlock();
                    break;
                case "toggleWidth":
                    this.toggleWidth(tool.value);
                    break;
                case "setContainerWidth":
                    this.setContainerWidth(tool.value);
                    break;
                case "setTextColor":
                    this.setTextColor(tool.value);
                    break;
                case "delete":
                    this.deleteCurrentBlock();
                    break;
                default:
                    targetDoc.execCommand(tool.action, false, null);
            }

            this.syncChanges();
            this.updateToolboxState();
        },

        getToolsByGroup(group) {
            return this.toolbox.tools.filter((t) => t.group === group);
        },

        handleImageUpload(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Get the correct document (iframe or main document)
            const targetDoc = this.getTargetDocument();
            if (!targetDoc) return;

            // Simple local preview for now, or upload logic
            // Ideally upload to server and get URL.
            // For now, let's use FileReader for immediate preview
            const reader = new FileReader();
            reader.onload = (event) => {
                targetDoc.execCommand("insertImage", false, event.target.result);
                // Reset input
                e.target.value = "";
                this.syncChanges();
            };
            reader.readAsDataURL(file);
        },

        editCurrentLink() {
            // Use iframe context if available
            const targetWindow = this.getTargetWindow();
            const selection = targetWindow?.getSelection() || window.getSelection();
            if (!selection || !selection.rangeCount) return;
            const node = selection.anchorNode;
            const el = node.nodeType === 1 ? node : node.parentNode;
            const link = el.closest("a");
            if (link) {
                this.openLinkEditModal(
                    this.activeBlockId,
                    link.getAttribute("href"),
                    link.innerText,
                    link
                );
            } else {
                this.showToast("Please select a link to edit.", "warning");
            }
        },

        editCurrentImage() {
            // Use iframe context if available
            const targetWindow = this.getTargetWindow();
            const selection = targetWindow?.getSelection() || window.getSelection();
            if (!selection || !selection.rangeCount) return;
            const node = selection.anchorNode;
            const el = node.nodeType === 1 ? node : node.parentNode;
            const img = el.tagName === "IMG" ? el : el.closest("img");
            if (img) {
                this.openImageEditModal(
                    this.activeBlockId,
                    img.src,
                    img.alt,
                    img
                );
            } else {
                this.showToast("Please select an image to edit.", "warning");
            }
        },

        moveBlock(direction) {
            // Get the target document (iframe or main)
            const targetDoc = this.getTargetDocument();

            // Prioritize the active block (visual selection) - search in iframe first
            let node = targetDoc?.querySelector(".block-active");

            // Fallback to main container if needed
            if (!node && this.$refs.previewContainer) {
                node = this.$refs.previewContainer.querySelector(".block-active");
            }

            // Fallback to browser selection if no active block
            if (!node) {
                node = this.getSelectionNode();
            }

            const el = this.getWidgetWrapper(node);

            if (!el) {
                this.showToast("Select a block to move.", "warning");
                return;
            }

            // Helper to check if element is a valid block (not script/style)
            const isValidBlock = (element) => {
                return element &&
                    element.nodeType === 1 &&
                    element.tagName !== 'SCRIPT' &&
                    element.tagName !== 'STYLE' &&
                    element.tagName !== 'LINK';
            };

            if (direction === "up") {
                let prev = el.previousElementSibling;
                // Skip non-visual elements like scripts/styles
                while (prev && !isValidBlock(prev)) {
                    prev = prev.previousElementSibling;
                }

                if (prev) {
                    el.parentNode.insertBefore(el, prev);
                    this.syncChanges();
                    this.showToast("Block moved up", "success");
                } else {
                    this.showToast("Already at the top", "info");
                }
            } else {
                let next = el.nextElementSibling;
                // Skip non-visual elements like scripts/styles
                while (next && !isValidBlock(next)) {
                    next = next.nextElementSibling;
                }

                if (next) {
                    // insertBefore inserts before the reference node. 
                    // To move down, we insert before the node AFTER the next node.
                    // If next is the last node, next.nextElementSibling is null, which appends to end.
                    el.parentNode.insertBefore(el, next.nextElementSibling);
                    this.syncChanges();
                    this.showToast("Block moved down", "success");
                } else {
                    this.showToast("Already at the bottom", "info");
                }
            }

            // Re-highlight
            el.scrollIntoView({ behavior: "smooth", block: "center" });

            // Restore active state
            this.activateBlock(el);
            this.updateToolboxState(el);

            // Restore text selection to ensure next click works
            const targetWindow = this.getTargetWindow();
            const editable = el.querySelector('[contenteditable="true"]') || el;
            if (editable && targetDoc && targetWindow) {
                const range = targetDoc.createRange();
                range.selectNodeContents(editable);
                range.collapse(true); // Collapse to start
                const sel = targetWindow.getSelection();
                sel.removeAllRanges();
                sel.addRange(range);
            }
        },

        selectParentBlock() {
            // Use iframe context if available
            const targetDoc = this.getTargetDocument();
            const targetWindow = this.getTargetWindow();
            const selection = targetWindow?.getSelection() || window.getSelection();
            if (!selection || !selection.rangeCount) return;

            const node = selection.anchorNode;
            let el = node.nodeType === 1 ? node : node.parentNode;

            // Check for wrapper boundary in iframe
            const isWrapper = el?.parentNode?.id === "page-builder-preview" ||
                el?.parentNode === targetDoc?.body;

            if (el && el.parentNode && !isWrapper) {
                const parent = el.parentNode;

                // Create range for parent
                const range = targetDoc?.createRange() || document.createRange();
                range.selectNodeContents(parent);
                selection.removeAllRanges();
                selection.addRange(range);

                // Trigger click to activate
                parent.click();
            } else {
                this.showToast("No parent block to select", "info");
            }
        },

        selectChildBlock() {
            // Use iframe context if available
            const targetDoc = this.getTargetDocument();
            const targetWindow = this.getTargetWindow();
            const selection = targetWindow?.getSelection() || window.getSelection();
            if (!selection || !selection.rangeCount) return;

            const node = selection.anchorNode;
            let el = node.nodeType === 1 ? node : node.parentNode;

            // If we selected a text node, go to parent element
            if (node.nodeType === 3) el = node.parentNode;

            // Find first element child
            let child = el.firstElementChild;

            if (child) {
                const range = targetDoc?.createRange() || document.createRange();
                // Try to select text inside if possible, otherwise select the node
                if (child.childNodes.length > 0) {
                    range.selectNodeContents(child);
                } else {
                    range.selectNode(child);
                }

                selection.removeAllRanges();
                selection.addRange(range);

                // Trigger click to activate
                child.click();
            } else {
                this.showToast("No child block to select", "info");
            }
        },

        changeBlockTag(tagName) {
            const targetDoc = this.getTargetDocument();
            const targetWindow = this.getTargetWindow();

            // Try to find block from selection first
            let block = this.getClosestBlock(this.getSelectionNode());

            // Fallback to active block
            if (!block) {
                block = targetDoc.querySelector(".block-active");
            }

            if (!block) {
                this.showToast("Select a block to format", "warning");
                return;
            }

            // If already same tag, do nothing
            if (block.tagName.toLowerCase() === tagName.toLowerCase()) return;

            // Create new element
            const newBlock = targetDoc.createElement(tagName);

            // Copy attributes (id, class, style, etc.)
            Array.from(block.attributes).forEach(attr => {
                newBlock.setAttribute(attr.name, attr.value);
            });

            // Update typography classes based on new tag
            // Remove existing size/weight classes to avoid conflicts
            newBlock.classList.remove(
                "text-5xl", "text-4xl", "text-3xl", "text-2xl", "text-xl", "text-lg", "text-base", "text-sm",
                "font-bold", "font-semibold", "font-medium", "font-normal"
            );

            // Add appropriate classes for the new tag
            switch (tagName.toLowerCase()) {
                case 'h1':
                    newBlock.classList.add("text-4xl", "font-bold");
                    break;
                case 'h2':
                    newBlock.classList.add("text-3xl", "font-bold");
                    break;
                case 'h3':
                    newBlock.classList.add("text-2xl", "font-semibold");
                    break;
                case 'h4':
                    newBlock.classList.add("text-xl", "font-semibold");
                    break;
                case 'h5':
                    newBlock.classList.add("text-lg", "font-semibold");
                    break;
                case 'h6':
                    newBlock.classList.add("text-base", "font-semibold");
                    break;
                case 'p':
                    newBlock.classList.add("text-base");
                    break;
            }

            // Copy content
            newBlock.innerHTML = block.innerHTML;

            // Replace old block
            if (block.parentNode) {
                block.parentNode.replaceChild(newBlock, block);

                // Restore selection
                if (targetWindow) {
                    const selection = targetWindow.getSelection();
                    const newRange = targetDoc.createRange();
                    newRange.selectNodeContents(newBlock);
                    newRange.collapse(false); // Collapse to end
                    selection.removeAllRanges();
                    selection.addRange(newRange);
                }

                // Activate new block
                this.activateBlock(newBlock);
                this.updateToolboxState(newBlock);

                // Sync changes
                this.syncChanges();
            }
        },

        // Toolbox Dragging Logic
        startDrag(e) {
            this.toolbox.isDragging = true;
            this.toolbox.dragOffset = {
                x: e.clientX - this.toolbox.position.left,
                y: e.clientY - this.toolbox.position.top,
            };

            // Cache the element for direct manipulation
            this.toolbox.el = document.querySelector(".ct-toolbox");
            document.addEventListener("mousemove", this.onDrag);
            document.addEventListener("mouseup", this.stopDrag);
        },

        onDrag(e) {
            if (!this.toolbox.isDragging) return;
            e.preventDefault();

            // Direct DOM manipulation for performance
            const newLeft = e.clientX - this.toolbox.dragOffset.x;
            const newTop = e.clientY - this.toolbox.dragOffset.y;

            if (this.toolbox.el) {
                this.toolbox.el.style.left = `${newLeft}px`;
                this.toolbox.el.style.top = `${newTop}px`;
            }
        },

        stopDrag(e) {
            if (!this.toolbox.isDragging) return;
            this.toolbox.isDragging = false;

            // Sync final position to state
            if (this.toolbox.el) {
                const rect = this.toolbox.el.getBoundingClientRect();
                this.toolbox.position = {
                    left: rect.left,
                    top: rect.top,
                };
            }

            document.removeEventListener("mousemove", this.onDrag);
            document.removeEventListener("mouseup", this.stopDrag);
        },

        toggleToolboxMinimize() {
            this.toolbox.isMinimized = !this.toolbox.isMinimized;
        },

        cloneBlock() {
            // Get the target document (iframe or main)
            const targetDoc = this.getTargetDocument();

            // Prioritize the active block (visual selection) - search in iframe first
            let node = targetDoc?.querySelector(".block-active");

            // Fallback to main container if needed
            if (!node && this.$refs.previewContainer) {
                node = this.$refs.previewContainer.querySelector(".block-active");
            }

            // Fallback to browser selection if no active block
            if (!node) {
                node = this.getSelectionNode();
            }

            // Clone the main widget wrapper
            const el = this.getWidgetWrapper(node);

            if (el) {
                const clone = el.cloneNode(true);
                // Remove active classes from clone
                clone.classList.remove("block-active");
                clone
                    .querySelectorAll(".block-label")
                    .forEach((l) => l.remove());
                clone.style.outline = "";

                el.after(clone);
                this.syncChanges();
                this.showToast("Block cloned", "success");

                // Select the clone
                this.activateBlock(clone);
                this.updateToolboxState(clone);
            } else {
                this.showToast("Select a block to clone", "warning");
            }
        },

        toggleWidth(widthClass) {
            const node = this.getSelectionNode();
            const el = this.getWidgetWrapper(node);

            if (el) {
                // Remove existing width classes
                el.classList.remove(
                    "max-w-2xl",
                    "max-w-3xl",
                    "max-w-4xl",
                    "max-w-5xl",
                    "max-w-6xl",
                    "max-w-7xl",
                    "w-full",
                    "mx-auto"
                );

                // Add new class
                el.classList.add(widthClass);
                if (widthClass !== "w-full") el.classList.add("mx-auto"); // Center it

                this.syncChanges();
                this.showToast(`Width set to ${widthClass}`, "success");
            }
        },

        setContainerWidth(widthValue) {
            // Get the target document (iframe or main)
            const targetDoc = this.getTargetDocument();

            // Get the currently selected element directly (the one with block-active class)
            let activeElement = targetDoc?.querySelector(".block-active");

            // Fallback to main container
            if (!activeElement && this.$refs.previewContainer) {
                activeElement = this.$refs.previewContainer.querySelector(".block-active");
            }

            if (!activeElement) {
                this.showToast("Please select an element first", "warning");
                return;
            }

            // Debug: Show which element is selected
            const elementInfo = `${activeElement.tagName}${activeElement.className
                ? "." + activeElement.className.split(" ").join(".")
                : ""
                }`;

            // Remove all existing max-w-* classes (including sm, md, lg, xl, 2xl-7xl)
            const maxWClasses = [
                "max-w-sm",
                "max-w-md",
                "max-w-lg",
                "max-w-xl",
                "max-w-2xl",
                "max-w-3xl",
                "max-w-4xl",
                "max-w-5xl",
                "max-w-6xl",
                "max-w-7xl",
            ];
            maxWClasses.forEach((cls) => activeElement.classList.remove(cls));

            if (widthValue === "none") {
                // Full width - remove mx-auto and add w-full
                activeElement.classList.remove("mx-auto");
                activeElement.classList.add("w-full"); // Add w-full so DIV remains selectable
                this.showToast(
                    `${activeElement.tagName}: Full width`,
                    "success"
                );
            } else {
                // Remove w-full if switching to constrained width
                activeElement.classList.remove("w-full");

                // Apply selected max-w-* class
                activeElement.classList.add(widthValue);

                // Only add mx-auto for container elements (DIV, SECTION, etc), not for text elements
                const containerElements = [
                    "DIV",
                    "SECTION",
                    "ARTICLE",
                    "HEADER",
                    "FOOTER",
                    "MAIN",
                    "NAV",
                ];
                if (containerElements.includes(activeElement.tagName)) {
                    if (!activeElement.classList.contains("mx-auto")) {
                        activeElement.classList.add("mx-auto");
                    }
                }

                this.showToast(
                    `${activeElement.tagName}: ${widthValue}`,
                    "success"
                );
            }

            this.syncChanges();
        },

        setTextColor(colorClass) {
            const node = this.getSelectionNode();
            if (!node) return;

            // Use execCommand for text color if possible, but it uses hex.
            // For Tailwind classes, we need to apply it to the block or span.

            let el = node.nodeType === 1 ? node : node.parentNode;

            // Check if we're directly on an inline element (span, link, button)
            const isInlineElement =
                el.tagName === "SPAN" ||
                el.tagName === "A" ||
                el.tagName === "BUTTON";

            // If selection is collapsed or spans whole block, apply to block
            // Use iframe context if available
            const targetWindow = this.getTargetWindow();
            const selection = targetWindow?.getSelection() || window.getSelection();
            if (selection.isCollapsed || isInlineElement) {
                // If directly on a span/link/button, use it directly
                // Otherwise, find the closest block
                if (!isInlineElement) {
                    el = this.getClosestBlock(el);
                }

                if (el) {
                    // Remove existing text colors (simple regex or list)
                    const classes = el.className.split(" ");
                    const newClasses = classes.filter(
                        (c) => !c.startsWith("text-")
                    );
                    el.className = newClasses.join(" ");

                    el.classList.add(colorClass);
                    this.syncChanges();
                    this.showToast("Text color updated", "success");
                } else {
                    this.showToast(
                        "Please select an element to apply color",
                        "warning"
                    );
                }
            } else {
                // Wrap selection in span
                // This is complex with execCommand.
                // Simple fallback: Apply to block
                el = this.getClosestBlock(el);
                if (el) {
                    const classes = el.className.split(" ");
                    const newClasses = classes.filter(
                        (c) => !c.startsWith("text-")
                    );
                    el.className = newClasses.join(" ");
                    el.classList.add(colorClass);
                    this.syncChanges();
                    this.showToast("Text color updated", "success");
                } else {
                    this.showToast(
                        "Please select an element to apply color",
                        "warning"
                    );
                }
            }
        },

        deleteCurrentBlock() {
            // Get the target document (iframe or main)
            const targetDoc = this.getTargetDocument();

            // Prioritize the active block (visual selection) - search in iframe first
            let node = targetDoc?.querySelector(".block-active");

            // Fallback to main container if needed
            if (!node && this.$refs.previewContainer) {
                node = this.$refs.previewContainer.querySelector(".block-active");
            }

            // Fallback to browser selection if no active block
            if (!node) {
                node = this.getSelectionNode();
            }

            if (!node) {
                this.showToast("No block selected to delete.", "warning");
                return;
            }

            // We want to delete the main widget wrapper if possible, or the closest block
            let el = this.getWidgetWrapper(node);
            if (!el) el = this.getClosestBlock(node);

            if (el) {
                if (confirm("Are you sure you want to delete this block?")) {
                    el.remove();
                    this.syncChanges();
                    this.showToast("Block deleted", "success");
                }
            }
        },

        makeContentEditable(wrapper) {
            // Text tags that should always be editable
            const textTags = [
                "p",
                "h1",
                "h2",
                "h3",
                "h4",
                "h5",
                "h6",
                "span",
                "li",
                "blockquote",
            ];
            wrapper.querySelectorAll(textTags.join(",")).forEach((el) => {
                el.setAttribute("contenteditable", "true");
            });

            // Handle DIVs separately - only make them editable if they are leaf nodes (no children)
            // This prevents container DIVs from becoming editable text areas
            wrapper.querySelectorAll("div").forEach((el) => {
                if (
                    el.children.length === 0 &&
                    el.innerText.trim().length > 0
                ) {
                    el.setAttribute("contenteditable", "true");
                } else {
                    el.removeAttribute("contenteditable");
                }
            });
        },

        // ========================================================================
        // Toolbars & Actions
        // ========================================================================


        activateBlock(element) {
            this.deactivateAllBlocks();

            element.classList.add("block-active");
            // Green dashed border with opacity as requested
            element.style.outline = "2px dashed rgba(34, 197, 94, 0.7)";
            element.style.outlineOffset = "2px";
        },



        deactivateAllBlocks() {
            // Clear main container selections
            const wrapper = this.$refs.previewContainer;
            if (wrapper) {
                wrapper.querySelectorAll(".block-active").forEach((el) => {
                    el.classList.remove("block-active");
                    el.style.outline = "";
                    el.style.outlineOffset = "";
                    el.style.backgroundColor = "";
                });
            }

            // Clear iframe selections
            const targetDoc = this.getTargetDocument();
            if (targetDoc && targetDoc !== document) {
                targetDoc.querySelectorAll(".block-active").forEach((el) => {
                    el.classList.remove("block-active");
                    el.style.outline = "";
                    el.style.outlineOffset = "";
                    el.style.backgroundColor = "";
                });
            }

            document
                .querySelectorAll(".block-toolbar")
                .forEach((el) => el.remove());

            // Also remove from iframe if exists
            if (targetDoc && targetDoc !== document) {
                targetDoc.querySelectorAll(".block-toolbar").forEach((el) => el.remove());
            }
        },

        moveElement(element, direction) {
            if (direction === "up") {
                const prev = element.previousElementSibling;
                if (prev) {
                    element.parentNode.insertBefore(element, prev);
                    this.syncChanges();
                    this.showBlockToolbar(element); // Reposition
                }
            } else {
                const next = element.nextElementSibling;
                if (next) {
                    element.parentNode.insertBefore(next, element);
                    this.syncChanges();
                    this.showBlockToolbar(element); // Reposition
                }
            }
        },

        // ========================================================================
        // State Sync
        // ========================================================================
        syncChanges() {
            // Check if we're using iframe-based preview
            const iframe = document.getElementById('preview-iframe');
            if (iframe) {
                this.syncIframeChanges(iframe);
                return;
            }

            // Fallback to old method for non-iframe scenarios
            const wrapper = this.$refs.previewContainer;
            if (!wrapper) return;

            // First, clean up selection indicators from the original wrapper
            // This ensures they won't be copied to the clone
            // BUT preserve styles for block-active elements (selected elements)
            wrapper.querySelectorAll("*").forEach((el) => {
                // Skip block-active elements - preserve their outline
                if (el.classList.contains("block-active")) {
                    return;
                }

                // Remove selection-related inline styles directly
                if (el.style.outline && el.style.outline.includes("dashed")) {
                    el.style.outline = "";
                    el.style.outlineOffset = "";
                }
                if (
                    el.style.backgroundColor &&
                    (el.style.backgroundColor.includes("rgba(239, 246, 255") ||
                        el.style.backgroundColor.includes(
                            "rgba(254, 243, 199"
                        ) ||
                        el.style.backgroundColor.includes("rgba(254, 215, 170"))
                ) {
                    el.style.backgroundColor = "";
                }
                if (el.style.cursor === "crosshair") {
                    el.style.cursor = "";
                }
            });

            // Clone wrapper to clean up artifacts before syncing
            const clone = wrapper.cloneNode(true);

            // Remove artifacts
            clone
                .querySelectorAll(
                    ".floating-toolbar, .block-toolbar, .edit-indicator"
                )
                .forEach((el) => el.remove());

            // Store block-active elements and their styles before removing
            const activeElements = [];
            wrapper.querySelectorAll(".block-active").forEach((el) => {
                activeElements.push({
                    element: el,
                    outline: el.style.outline,
                    outlineOffset: el.style.outlineOffset,
                    backgroundColor: el.style.backgroundColor
                });
            });

            // Remove block-active from clone (for saving, but we'll restore it after)
            clone.querySelectorAll(".block-active").forEach((el) => {
                el.classList.remove("block-active");
                el.style.outline = "";
                el.style.outlineOffset = "";
                el.style.backgroundColor = "";
                el.style.cursor = "";
            });

            // Also clean up any inline styles that might have been added for visual selection
            // (outline, outlineOffset, backgroundColor, cursor from visual editor selection)
            // BUT preserve user-defined colors (backgroundColor and color)
            clone.querySelectorAll("*").forEach((el) => {
                if (!el.hasAttribute("style")) return;

                const styleAttr = el.getAttribute("style");
                if (!styleAttr) return;

                // Store user-defined colors before cleaning
                const userBgColor = el.style.backgroundColor;
                const userColor = el.style.color;

                // Check if these are user-defined colors (not selection indicators)
                const isUserBgColor =
                    userBgColor &&
                    !userBgColor.includes("rgba(239, 246, 255") &&
                    !userBgColor.includes("rgba(254, 243, 199") &&
                    !userBgColor.includes("rgba(254, 215, 170");

                // Remove selection-related inline styles using regex patterns
                let cleanedStyle = styleAttr;

                // Remove outline styles (dashed borders with selection colors)
                cleanedStyle = cleanedStyle.replace(
                    /outline\s*:\s*[^;]*(?:dashed|#60a5fa|#3b82f6|#eab308|#f97316)[^;]*;?/gi,
                    ""
                );
                cleanedStyle = cleanedStyle.replace(
                    /outline-offset\s*:\s*[^;]*;?/gi,
                    ""
                );

                // Remove background-color styles (selection indicator colors only)
                // Match with or without spaces, and handle closing parenthesis
                cleanedStyle = cleanedStyle.replace(
                    /background-color\s*:\s*rgba\s*\(\s*239\s*,\s*246\s*,\s*255[^;)]*[^;]*;?/gi,
                    ""
                );
                cleanedStyle = cleanedStyle.replace(
                    /background-color\s*:\s*rgba\s*\(\s*254\s*,\s*243\s*,\s*199[^;)]*[^;]*;?/gi,
                    ""
                );
                cleanedStyle = cleanedStyle.replace(
                    /background-color\s*:\s*rgba\s*\(\s*254\s*,\s*215\s*,\s*170[^;)]*[^;]*;?/gi,
                    ""
                );

                // Remove cursor: crosshair
                cleanedStyle = cleanedStyle.replace(
                    /cursor\s*:\s*crosshair\s*;?/gi,
                    ""
                );

                // Clean up multiple semicolons and whitespace
                cleanedStyle = cleanedStyle.replace(/;\s*;/g, ";");
                cleanedStyle = cleanedStyle.replace(/^\s*;\s*|\s*;\s*$/g, "");
                cleanedStyle = cleanedStyle.trim();

                // Restore user-defined colors if they were removed
                if (
                    isUserBgColor &&
                    !cleanedStyle.includes("background-color")
                ) {
                    cleanedStyle =
                        (cleanedStyle ? cleanedStyle + "; " : "") +
                        "background-color: " +
                        userBgColor;
                }
                if (userColor && !cleanedStyle.includes("color:")) {
                    cleanedStyle =
                        (cleanedStyle ? cleanedStyle + "; " : "") +
                        "color: " +
                        userColor;
                }

                // Update or remove style attribute
                if (cleanedStyle && cleanedStyle !== ";") {
                    el.setAttribute("style", cleanedStyle);
                } else {
                    el.removeAttribute("style");
                }
            });

            // Clean up data attributes (these are only for hover state, shouldn't be in saved HTML)
            clone.querySelectorAll("*").forEach((el) => {
                if (el.dataset.originalBgColor) {
                    delete el.dataset.originalBgColor;
                }
                if (el.dataset.originalColor) {
                    delete el.dataset.originalColor;
                }
            });
            clone
                .querySelectorAll("[contenteditable]")
                .forEach((el) => el.removeAttribute("contenteditable"));

            // Update previewHtml but DON'T auto-save to block
            // Saves will only happen on explicit Save & Close button click
            this.previewHtml = clone.innerHTML;

            // Restore block-active styles on original elements after sync
            // This ensures selected elements keep their border visible
            activeElements.forEach(({ element, outline, outlineOffset, backgroundColor }) => {
                if (element && element.classList.contains("block-active")) {
                    if (outline) element.style.outline = outline;
                    if (outlineOffset) element.style.outlineOffset = outlineOffset;
                    if (backgroundColor) element.style.backgroundColor = backgroundColor;
                }
            });
        },

        // ========================================================================
        // Modals & Settings
        // ========================================================================
        openElementSettingsModal(blockId, element) {
            this.activeBlockId = blockId;
            this.modals.elementSettings.targetElement = element;

            // Detect alignment
            let alignment = "left";
            if (element.classList.contains("text-center")) alignment = "center";
            if (element.classList.contains("text-right")) alignment = "right";

            // Detect old width system
            let width = "auto";
            if (element.classList.contains("w-full")) width = "full";
            if (element.classList.contains("container")) width = "container";

            // Detect new container width system (max-w-* classes)
            let containerWidth = "none";
            const maxWClasses = [
                "max-w-7xl",
                "max-w-6xl",
                "max-w-5xl",
                "max-w-4xl",
                "max-w-3xl",
                "max-w-2xl",
                "max-w-xl",
                "max-w-lg",
                "max-w-md",
                "max-w-sm",
            ];
            for (const cls of maxWClasses) {
                if (element.classList.contains(cls)) {
                    containerWidth = cls;
                    break;
                }
            }

            // Detect background image (look for img tags with absolute positioning inside the element)
            let backgroundImage = "";
            let backgroundImageElement = null;
            const imgs = element.querySelectorAll("img");

            // Use correct window context for computed styles
            const targetWindow = this.getTargetWindow();

            for (const img of imgs) {
                // Check if this img is likely a background image (absolute positioned, z-index negative or low)
                const style = targetWindow.getComputedStyle(img);
                const position = style.position;
                const zIndex = style.zIndex;

                // Also check for common background image patterns
                if (
                    position === "absolute" ||
                    img.classList.contains("absolute") ||
                    (zIndex && parseInt(zIndex) < 0) ||
                    img.classList.contains("-z-10") ||
                    img.classList.contains("-z-20")
                ) {
                    backgroundImage = img.src;
                    backgroundImageElement = img;
                    break; // Use the first matching image
                }
            }

            this.elementSettingsData = {
                tag: element.tagName.toLowerCase(),
                alignment: alignment,
                width: width,
                containerWidth: containerWidth,
                backgroundImage: backgroundImage,
                backgroundImageElement: backgroundImageElement,
            };
            this.modals.elementSettings.show = true;
        },

        closeElementSettingsModal() {
            this.modals.elementSettings.show = false;
            this.modals.elementSettings.targetElement = null;
        },

        saveElementSettings() {
            const el = this.modals.elementSettings.targetElement;
            if (!el) return;

            // 1. Apply Alignment Classes
            el.classList.remove("text-left", "text-center", "text-right");
            if (this.elementSettingsData.alignment !== "left") {
                el.classList.add("text-" + this.elementSettingsData.alignment);
            }

            // 2. OLD Width System (deprecated, keeping for backwards compatibility)
            el.classList.remove("w-full", "container");
            if (this.elementSettingsData.width !== "auto") {
                el.classList.add(
                    this.elementSettingsData.width === "full"
                        ? "w-full"
                        : "container"
                );
            }

            // 3. NEW Container Width System (Tailwind max-w-* and mx-auto)
            // Remove all existing max-w-* classes
            const maxWClasses = [
                "max-w-sm",
                "max-w-md",
                "max-w-lg",
                "max-w-xl",
                "max-w-2xl",
                "max-w-3xl",
                "max-w-4xl",
                "max-w-5xl",
                "max-w-6xl",
                "max-w-7xl",
            ];
            maxWClasses.forEach((cls) => el.classList.remove(cls));

            if (this.elementSettingsData.containerWidth === "none") {
                // Full width - remove mx-auto
                el.classList.remove("mx-auto");
            } else {
                // Apply selected max-w-* class and ensure mx-auto
                el.classList.add(this.elementSettingsData.containerWidth);
                if (!el.classList.contains("mx-auto")) {
                    el.classList.add("mx-auto");
                }
            }

            // 4. Handle Background Image
            if (this.elementSettingsData.backgroundImage) {
                // Find or create background image element
                let bgImg = this.elementSettingsData.backgroundImageElement;

                if (!bgImg) {
                    // Look for existing background image in the element
                    const imgs = el.querySelectorAll("img");
                    for (const img of imgs) {
                        const style = window.getComputedStyle(img);
                        const position = style.position;
                        const zIndex = style.zIndex;

                        if (
                            position === "absolute" ||
                            img.classList.contains("absolute") ||
                            (zIndex && parseInt(zIndex) < 0) ||
                            img.classList.contains("-z-10") ||
                            img.classList.contains("-z-20")
                        ) {
                            bgImg = img;
                            break;
                        }
                    }
                }

                if (bgImg) {
                    // Update existing background image
                    bgImg.src = this.elementSettingsData.backgroundImage;
                } else {
                    // Create new background image
                    bgImg = document.createElement("img");
                    bgImg.src = this.elementSettingsData.backgroundImage;
                    bgImg.alt = "";
                    bgImg.className =
                        "absolute inset-0 -z-10 size-full object-cover";
                    el.insertBefore(bgImg, el.firstChild);
                }
            } else if (this.elementSettingsData.backgroundImageElement) {
                // Remove background image if URL is cleared
                if (
                    this.elementSettingsData.backgroundImageElement.parentNode
                ) {
                    this.elementSettingsData.backgroundImageElement.remove();
                }
            }

            // 5. Handle Tag Change (The Tricky Part)
            if (el.tagName.toLowerCase() !== this.elementSettingsData.tag) {
                if (!el.parentNode) {
                    console.error("Cannot change tag: element has no parent");
                    return;
                }

                const newEl = document.createElement(
                    this.elementSettingsData.tag
                );
                newEl.innerHTML = el.innerHTML;

                // Copy Attributes
                Array.from(el.attributes).forEach((attr) => {
                    newEl.setAttribute(attr.name, attr.value);
                });

                // Replace in DOM
                el.parentNode.replaceChild(newEl, el);

                // Update Reference
                this.modals.elementSettings.targetElement = newEl;

                // Re-activate block to show toolbar on new element
                this.activateBlock(newEl);

                // IMPORTANT: Re-apply contenteditable if it was lost
                this.makeContentEditable(this.$refs.previewContainer);
            }

            this.syncChanges();
            this.closeElementSettingsModal();
        },

        // ... (Image, Link, Button Modals - Keep existing logic but ensure syncChanges is called)
        openImageEditModal(blockId, url, alt, target) {
            this.activeBlockId = blockId;
            this.modals.imageEdit.targetElement = target;
            this.imageEditData = {
                url: url || "",
                alt: alt || "",
                disabled: target.dataset.disabled === 'true' || target.classList.contains('hidden'),
            };
            this.modals.imageEdit.show = true;
        },

        saveImageEdit() {
            const img = this.modals.imageEdit.targetElement;
            if (img) {
                img.src = this.imageEditData.url;
                img.alt = this.imageEditData.alt;

                if (this.imageEditData.disabled) {
                    img.classList.remove('hidden'); // Ensure hidden class is removed in editor
                    img.style.display = ''; // Reset display style
                    img.style.filter = 'grayscale(100%)';
                    img.style.opacity = '0.6'; // Increased opacity to be more visible
                    img.dataset.disabled = 'true';
                } else {
                    img.classList.remove('hidden');
                    img.style.display = '';
                    img.style.filter = '';
                    img.style.opacity = '';
                    delete img.dataset.disabled;
                }

                this.syncChanges();
            }
            this.modals.imageEdit.show = false;
        },

        closeImageEditModal() {
            this.modals.imageEdit.show = false;
        },

        openLinkEditModal(blockId, href, text, target) {
            this.activeBlockId = blockId;
            this.modals.linkEdit.targetElement = target;

            // Get current background color from inline style or computed style
            let backgroundColor = "";
            if (target.style.backgroundColor) {
                backgroundColor = target.style.backgroundColor;
            } else {
                const computedStyle = window.getComputedStyle(target);
                const bgColor = computedStyle.backgroundColor;
                // Only use if it's not transparent or default
                if (
                    bgColor &&
                    bgColor !== "rgba(0, 0, 0, 0)" &&
                    bgColor !== "transparent"
                ) {
                    backgroundColor = bgColor;
                }
            }

            // Get current text color from inline style or computed style
            let textColor = "";
            if (target.style.color) {
                textColor = target.style.color;
            } else {
                const computedStyle = window.getComputedStyle(target);
                const color = computedStyle.color;
                // Only use if it's not the default black
                if (
                    color &&
                    color !== "rgb(0, 0, 0)" &&
                    color !== "rgba(0, 0, 0, 0)"
                ) {
                    textColor = color;
                }
            }

            this.linkEditData = {
                href: href || "",
                text: text || "",
                backgroundColor: backgroundColor,
                color: textColor,
            };
            this.modals.linkEdit.show = true;
        },

        saveLinkEdit() {
            const link = this.modals.linkEdit.targetElement;
            if (link) {
                link.href = this.linkEditData.href;

                // Only update text if link has no child elements (like spans, images, etc.)
                // If link has child elements, preserve them and don't change the text
                const hasChildElements =
                    link.children.length > 0 ||
                    (link.childNodes.length > 0 &&
                        Array.from(link.childNodes).some(
                            (node) =>
                                node.nodeType === 1 && node.tagName !== "BR"
                        ));

                if (!hasChildElements) {
                    // Link only contains text, safe to update
                    link.innerText = this.linkEditData.text;
                }
                // If link has child elements (spans, etc.), don't change the text
                // User can edit child elements separately

                // Apply background color as inline style
                if (
                    this.linkEditData.backgroundColor &&
                    this.linkEditData.backgroundColor.trim() !== ""
                ) {
                    link.style.backgroundColor =
                        this.linkEditData.backgroundColor;
                } else {
                    // Remove background color if empty
                    link.style.backgroundColor = "";
                }

                // Apply text color as inline style
                if (
                    this.linkEditData.color &&
                    this.linkEditData.color.trim() !== ""
                ) {
                    link.style.color = this.linkEditData.color;
                } else {
                    // Remove text color if empty
                    link.style.color = "";
                }

                this.syncChanges();
            }
            this.modals.linkEdit.show = false;
        },

        closeLinkEditModal() {
            this.modals.linkEdit.show = false;
        },

        openButtonEditModal(blockId, target) {
            this.activeBlockId = blockId;
            this.modals.buttonEdit.targetElement = target;

            // Get current background color from inline style or computed style
            let backgroundColor = "";
            if (target.style.backgroundColor) {
                backgroundColor = target.style.backgroundColor;
            } else {
                const computedStyle = window.getComputedStyle(target);
                const bgColor = computedStyle.backgroundColor;
                // Only use if it's not transparent or default
                if (
                    bgColor &&
                    bgColor !== "rgba(0, 0, 0, 0)" &&
                    bgColor !== "transparent"
                ) {
                    backgroundColor = bgColor;
                }
            }

            // Get current text color from inline style or computed style
            let textColor = "";
            if (target.style.color) {
                textColor = target.style.color;
            } else {
                const computedStyle = window.getComputedStyle(target);
                const color = computedStyle.color;
                // Only use if it's not the default black
                if (
                    color &&
                    color !== "rgb(0, 0, 0)" &&
                    color !== "rgba(0, 0, 0, 0)"
                ) {
                    textColor = color;
                }
            }

            this.buttonEditData = {
                text: target.innerText,
                href: target.getAttribute("href") || "",
                onclick: target.getAttribute("onclick") || "",
                classes: target.className,
                backgroundColor: backgroundColor,
                color: textColor,
            };
            this.modals.buttonEdit.show = true;
        },

        saveButtonEdit() {
            const btn = this.modals.buttonEdit.targetElement;
            if (btn) {
                btn.innerText = this.buttonEditData.text;
                if (this.buttonEditData.href)
                    btn.setAttribute("href", this.buttonEditData.href);
                if (this.buttonEditData.onclick)
                    btn.setAttribute("onclick", this.buttonEditData.onclick);
                btn.className = this.buttonEditData.classes;

                // Apply background color as inline style
                if (
                    this.buttonEditData.backgroundColor &&
                    this.buttonEditData.backgroundColor.trim() !== ""
                ) {
                    btn.style.backgroundColor =
                        this.buttonEditData.backgroundColor;
                } else {
                    // Remove background color if empty
                    btn.style.backgroundColor = "";
                }

                // Apply text color as inline style
                if (
                    this.buttonEditData.color &&
                    this.buttonEditData.color.trim() !== ""
                ) {
                    btn.style.color = this.buttonEditData.color;
                } else {
                    // Remove text color if empty
                    btn.style.color = "";
                }

                this.syncChanges();
            }
            this.modals.buttonEdit.show = false;
        },

        closeButtonEditModal() {
            this.modals.buttonEdit.show = false;
        },

        // Spacing Modal Functions
        openSpacingModal(blockId) {
            this.activeBlockId = blockId;

            // Find the outermost element inside the iframe (not the iframe element itself)
            const iframe = document.getElementById('preview-iframe');
            if (!iframe) return;

            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            if (!iframeDoc || !iframeDoc.body) return;

            const outermostElement = iframeDoc.body.firstElementChild;
            if (!outermostElement) return;

            this.modals.spacingEdit.targetElement = outermostElement;
            this.activeSpacingBreakpoint = 'base';

            // Parse existing Tailwind spacing classes
            const classes = Array.from(outermostElement.classList);

            // Reset all breakpoint spacing data
            this.spacingEditData = {
                base: { px: '', py: '', mx: '', my: '' },
                sm: { px: '', py: '', mx: '', my: '' },
                md: { px: '', py: '', mx: '', my: '' },
                lg: { px: '', py: '', mx: '', my: '' },
                xl: { px: '', py: '', mx: '', my: '' },
            };

            // Parse classes into the correct breakpoint bucket
            const breakpointMap = { 'sm:': 'sm', 'md:': 'md', 'lg:': 'lg', 'xl:': 'xl', '2xl:': 'xl' };

            classes.forEach((cls) => {
                const match = cls.match(/^(?:(sm|md|lg|xl|2xl):)?(px|py|mx|my)-(\d[\d.]*)$/);
                if (match) {
                    const bp = match[1] ? (match[1] === '2xl' ? 'xl' : match[1]) : 'base';
                    const type = match[2];
                    const value = match[3];
                    this.spacingEditData[bp][type] = value;
                }
            });

            this.modals.spacingEdit.show = true;
        },

        saveSpacingEdit() {
            const element = this.modals.spacingEdit.targetElement;
            if (!element) return;

            // Remove all existing spacing classes including responsive variants
            const classes = Array.from(element.classList);
            const spacingRegex = /^(?:sm:|md:|lg:|xl:|2xl:)?(px|py|mx|my|p|m)-[\d.]+$/;

            classes.forEach((cls) => {
                if (spacingRegex.test(cls)) {
                    element.classList.remove(cls);
                }
            });

            // Helper function to check if value is valid
            const isValidValue = (val) => {
                if (val === null || val === undefined || val === '') return false;
                const numVal = parseFloat(val);
                return !isNaN(numVal) && numVal >= 0;
            };

            // Apply spacing classes for each breakpoint
            const prefixMap = { base: '', sm: 'sm:', md: 'md:', lg: 'lg:', xl: 'xl:' };
            const types = ['px', 'py', 'mx', 'my'];

            Object.keys(prefixMap).forEach((bp) => {
                const prefix = prefixMap[bp];
                const data = this.spacingEditData[bp];
                if (!data) return;

                types.forEach((type) => {
                    if (isValidValue(data[type])) {
                        element.classList.add(`${prefix}${type}-${data[type]}`);
                    }
                });
            });

            // Sync changes
            this.syncChanges();

            // Update block HTML to persist changes
            const block = this.blocks.find((b) => b.id === this.activeBlockId);
            if (block) {
                block.html = this.previewHtml;
            }

            this.closeSpacingModal();
        },

        closeSpacingModal() {
            this.modals.spacingEdit.show = false;
            this.modals.spacingEdit.targetElement = null;
        },

        // ========================================================================
        // Preview Modal Control
        // ========================================================================
        closePreviewModal() {
            // If in editing mode, restore original HTML on cancel
            if (
                this.isEditingMode &&
                this.originalBlockHtml &&
                this.activeBlockId
            ) {
                const block = this.blocks.find(
                    (b) => b.id === this.activeBlockId
                );
                if (block) {
                    block.html = this.originalBlockHtml;
                }
            }

            // Don't call syncChanges here - it would overwrite the restored HTML!
            this.modals.preview.show = false;
            this.activeBlockId = null;
            this.previewHtml = "";
            this.isEditingMode = false;
            this.deactivateAllBlocks();
            this.previewComponentData = null;
            this.canNavigatePrevious = false;
            this.canNavigateNext = false;
            this.originalBlockHtml = null; // Clear original HTML
            document.body.style.overflow = "";
        },

        savePreviewModal() {
            // Save changes when in editing mode
            if (this.isEditingMode && this.activeBlockId) {
                // Get iframe and sync content from inside it
                const iframe = document.getElementById('preview-iframe');
                if (iframe) {
                    this.syncIframeChanges(iframe);
                }

                // Save the synced content to the block
                const block = this.blocks.find(
                    (b) => b.id === this.activeBlockId
                );
                if (block) {
                    block.html = this.previewHtml; // Explicitly save
                }
                this.originalBlockHtml = null; // Clear original HTML after successful save
            }

            this.modals.preview.show = false;
            this.activeBlockId = null;
            this.previewHtml = "";
            this.isEditingMode = false;
            this.deactivateAllBlocks();
            this.previewComponentData = null;
            this.canNavigatePrevious = false;
            this.canNavigateNext = false;
            document.body.style.overflow = "";
        },

        addComponentFromPreview() {
            if (!this.previewComponentData) return;

            const { component, category, sectionName } =
                this.previewComponentData;

            // Calculate position based on region
            let position = 0;
            if (this.blockSelectorType === "header") {
                // Header blocks: position is the current header count
                position = this.headerBlocks.length;
            } else {
                // Body blocks: position starts after header count
                position = this.headerBlocks.length + this.bodyBlocks.length;
            }

            const newBlock = {
                id:
                    "block_" +
                    Date.now() +
                    "_" +
                    Math.random().toString(36).substr(2, 9),
                component_id: component.id,
                region: this.blockSelectorType,
                name: component.name,
                category: category,
                section: sectionName,
                path: component.path,
                html: this.previewHtml,
                position: position,
            };

            this.blocks.push(newBlock);
            this.closePreviewModal();
            this.showBlockSelector = false;
            this.showToast("Block added successfully", "success");
        },

        navigatePreviewComponent(direction) {
            if (!this.previewComponentData) return;

            const allComponents = this.getAllComponentsFlat();
            const currentIndex = allComponents.findIndex(
                (comp) => comp.path === this.previewComponentData.component.path
            );

            let newIndex;
            if (direction === "next") {
                newIndex = currentIndex + 1;
                if (newIndex >= allComponents.length) return;
            } else {
                newIndex = currentIndex - 1;
                if (newIndex < 0) return;
            }

            const nextComponent = allComponents[newIndex];
            if (nextComponent) {
                this.previewComponent(nextComponent);
            }
        },

        handlePreviewClick(e) {
            // Prevent links from navigating in preview mode
            if (e.target.tagName === "A") {
                e.preventDefault();
                this.showToast("Links are disabled in preview mode", "info");
            }
        },

        handlePreviewRightClick(e) {
            // Optional: Prevent context menu or show custom one
            // For now, we'll just allow the default behavior but log it if needed
            // e.preventDefault();
        },

        // ========================================================================
        // Other Helpers (Presets, etc.)
        // ========================================================================
        async loadPresets(type) {
            try {
                const routeUrl =
                    this.resources.routes?.getPresets ||
                    "/admin/content/page/get-presets";
                const url = type ? `${routeUrl}?type=${type}` : routeUrl;
                const response = await fetch(url, {
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                });
                if (response.ok) {
                    const data = await response.json();
                    this.presets = data.presets || [];
                }
            } catch (e) {
                console.error("Error loading presets", e);
            }
        },

        async usePreset(preset) {
            if (!preset || !preset.id) return;
            try {
                const routeUrl = (
                    this.resources.routes?.loadPreset ||
                    "/admin/content/page/load-preset/:id"
                ).replace(":id", preset.id);
                const response = await fetch(routeUrl, {
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                });
                if (response.ok) {
                    const data = await response.json();
                    const presetBlocks = data.preset?.blocks || [];

                    // Add blocks from preset to blocks array
                    presetBlocks.forEach((block, index) => {
                        const blockRegion =
                            preset.type ||
                            this.blockSelectorType ||
                            block.region ||
                            "body";

                        // Calculate position based on region
                        let position = 0;
                        if (blockRegion === "header") {
                            // Header blocks: position is the current header count + index
                            position = this.headerBlocks.length + index;
                        } else {
                            // Body blocks: position starts after header count
                            position =
                                this.headerBlocks.length +
                                this.bodyBlocks.length +
                                index;
                        }

                        this.blocks.push({
                            ...block,
                            id:
                                "block_" +
                                Date.now() +
                                "_" +
                                Math.random().toString(36).substr(2, 9),
                            region: blockRegion,
                            position: position,
                        });
                    });

                    this.showBlockSelector = false;
                    this.showToast("Preset loaded successfully", "success");
                } else {
                    this.showToast("Error loading preset", "error");
                }
            } catch (e) {
                console.error("Error using preset:", e);
                this.showToast("Error using preset", "error");
            }
        },

        async saveCurrentAsPreset() {
            // ... (Keep existing logic)
            const name = this.modals.savePreset.name;
            const description = this.modals.savePreset.description;
            const type = this.modals.savePreset.type;

            if (!name) {
                alert("Please enter a name");
                return;
            }

            const blocksToSave =
                type === "header" ? this.headerBlocks : this.bodyBlocks;
            if (blocksToSave.length === 0) {
                alert("No blocks to save");
                return;
            }

            try {
                const routeUrl =
                    this.resources.routes?.savePreset ||
                    "/admin/content/page/save-preset";
                const response = await fetch(routeUrl, {
                    method: "POST",
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                    body: JSON.stringify({
                        name,
                        description,
                        type,
                        blocks: blocksToSave,
                    }),
                });
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        await this.loadPresets();
                        this.modals.savePreset.show = false;
                        alert("Preset saved!");
                    }
                }
            } catch (e) {
                console.error(e);
            }
        },

        async deletePreset(preset) {
            if (!confirm("Delete preset?")) return;
            try {
                const routeUrl = (
                    this.resources.routes?.deletePreset ||
                    "/admin/content/page/delete-preset/:id"
                ).replace(":id", preset.id);
                const response = await fetch(routeUrl, {
                    method: "DELETE",
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": this.csrfToken,
                    },
                });
                if (response.ok) await this.loadPresets();
            } catch (e) {
                console.error(e);
            }
        },

        openSavePresetModal(type) {
            this.modals.savePreset.type = type;
            this.modals.savePreset.show = true;
        },

        closeSavePresetModal() {
            this.modals.savePreset.show = false;
        },

        // Title & Slug
        openTitleSlugModal() {
            this.modals.titleSlug.tempTitle = this.form.title;
            this.modals.titleSlug.tempSlug = this.form.slug;
            this.modals.titleSlug.show = true;
        },
        closeTitleSlugModal() {
            this.modals.titleSlug.show = false;
        },
        generateSlugFromTitle() {
            if (!this.modals.titleSlug.autoGenerate) return;
            this.form.slug = this.slugify(this.form.title);
        },
        saveTitleSlug() {
            this.form.title = this.modals.titleSlug.tempTitle;
            this.form.slug = this.modals.titleSlug.tempSlug;
            this.closeTitleSlugModal();
        },
        slugify(text) {
            return text
                .toString()
                .toLowerCase()
                .replace(/\s+/g, "-")
                .replace(/[^\w\-]+/g, "")
                .replace(/\-\-+/g, "-")
                .replace(/^-+/, "")
                .replace(/-+$/, "");
        },

        // Quill (Static Page)
        // NOTE: initQuillEditor is now defined at the top of methods section.
        // The quill-editor-vue component handles Quill initialization.
        // This duplicate has been removed to prevent conflicts.

        // Form Submission
        async submitForm() {
            const formElement = document.getElementById("vue-page-form");
            if (!formElement) return;

            const formData = new FormData(formElement);

            // Sync TipTap Editor content for Static pages
            console.log('[PageBuilder::submitForm] ========== TIPTAP SYNC START ==========');
            let longBodyContent = '';

            // Try Vue.js editor first
            if (window.vueTipTapEditors && window.vueTipTapEditors['long_body']) {
                const editorInstance = window.vueTipTapEditors['long_body'];
                if (editorInstance.updateHiddenInput) {
                    editorInstance.updateHiddenInput();
                }
                longBodyContent = editorInstance.getHTML() || '';
                console.log('[PageBuilder::submitForm] Got from Vue.js TipTap editor');
            }

            // Fallback: Get from hidden input
            if (!longBodyContent) {
                const tipTapHiddenInput = document.getElementById('input-long_body');
                if (tipTapHiddenInput) {
                    longBodyContent = tipTapHiddenInput.value || '';
                    console.log('[PageBuilder::submitForm] Got from hidden input');
                }
            }

            // Set the content in FormData
            if (longBodyContent) {
                formData.set('long_body', longBodyContent);
                console.log('[PageBuilder::submitForm] Set long_body in formData, length:', longBodyContent.length);
            } else {
                console.warn('[PageBuilder::submitForm] WARNING: No long_body content found!');
                formData.set('long_body', ''); // Set empty string to prevent validation issues
            }
            console.log('[PageBuilder::submitForm] ========== TIPTAP SYNC END ==========');

            // Explicitly set boolean fields to 1 or 0
            formData.set("is_active", this.form.is_active ? "1" : "0");
            formData.set("home_page", this.form.home_page ? "1" : "0");
            formData.set("hide_header", this.form.hide_header ? "1" : "0");
            formData.set("hide_footer", this.form.hide_footer ? "1" : "0");

            // Explicitly set widget_config from Vue state
            // Ensure blocks are sorted: headers first, then body blocks
            const headerBlocks = this.blocks.filter(
                (b) => b.region === "header"
            );
            const bodyBlocks = this.blocks.filter((b) => b.region === "body");
            const sortedBlocks = [...headerBlocks, ...bodyBlocks];

            // Map blocks to use component_id as id for the backend
            // Ensure position values are correct (headers: 0,1,2... body: headerCount, headerCount+1...)
            const headerCount = headerBlocks.length;
            const blocksToSubmit = sortedBlocks.map((block, index) => ({
                ...block,
                id: block.component_id || block.id, // Use component_id if available, else fallback to id
                position: index, // Ensure position matches the sorted order
            }));

            formData.set("widget_config", JSON.stringify(blocksToSubmit));

            // Also update the hidden input for form fallback
            const hiddenInput = formElement.querySelector(
                'input[name="widget_config"]'
            );
            if (hiddenInput) {
                hiddenInput.value = JSON.stringify(blocksToSubmit);
            }

            try {
                const response = await fetch(formElement.action, {
                    method: "POST", // Laravel handles PUT via _method field in FormData
                    headers: {
                        "X-CSRF-TOKEN": this.csrfToken,
                        Accept: "application/json",
                    },
                    body: formData,
                });

                if (response.ok) {
                    // Redirect to index or show success
                    // We assume the backend redirects to the index page on success
                    // If the backend returns JSON with a redirect url, use that.
                    // For now, let's reload or go to index.
                    // Checking if response is a redirect:
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        // Fallback: Check if JSON has redirect
                        try {
                            const data = await response.json();
                            if (data.redirect)
                                window.location.href = data.redirect;
                            else window.location.href = "/admin/content/page";
                        } catch (e) {
                            window.location.href = "/admin/content/page";
                        }
                    }
                } else {
                    const data = await response.json();
                    if (data.errors) {
                        this.errors = data.errors;
                        alert("Please check the form for errors.");
                    } else {
                        alert(
                            "Error saving page: " +
                            (data.message || response.statusText)
                        );
                    }
                }
            } catch (e) {
                console.error(e);
                this.showToast("An error occurred while saving.", "error");
            }
        },

        showToast(message, type = "info") {
            const id = Date.now();
            const icon =
                type === "success"
                    ? "fa-check-circle"
                    : type === "error"
                        ? "fa-exclamation-circle"
                        : type === "warning"
                            ? "fa-exclamation-triangle"
                            : "fa-info-circle";

            this.toasts.push({ id, message, type, icon });

            setTimeout(() => {
                this.toasts = this.toasts.filter((t) => t.id !== id);
            }, 3000);
        },

        // Image Upload Helper
        async handleImageFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;
            const formData = new FormData();
            formData.append("image", file);
            try {
                const response = await fetch(
                    this.resources.routes.uploadImage,
                    {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": this.csrfToken,
                            Accept: "application/json",
                        },
                        body: formData,
                    }
                );
                const data = await response.json();
                if (data.url) this.imageEditData.url = data.url;
                else alert("Upload failed");
            } catch (e) {
                console.error(e);
                alert("Error uploading image");
            }
        },

        // Background Image Upload Helper
        async handleBackgroundImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            const formData = new FormData();
            formData.append("image", file);
            try {
                const response = await fetch(
                    this.resources.routes.uploadImage,
                    {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": this.csrfToken,
                            Accept: "application/json",
                        },
                        body: formData,
                    }
                );
                const data = await response.json();
                if (data.url) {
                    this.elementSettingsData.backgroundImage = data.url;
                } else {
                    alert("Upload failed");
                }
            } catch (e) {
                console.error(e);
                alert("Error uploading image");
            }
            // Reset input
            event.target.value = "";
        },

        deleteImage() {
            if (confirm("Delete image?")) {
                this.modals.imageEdit.targetElement.remove();
                this.syncChanges();
                this.closeImageEditModal();
            }
        },

        toggleImageVisibility() {
            this.imageEditData.disabled = !this.imageEditData.disabled;
            const img = this.modals.imageEdit.targetElement;
            if (this.imageEditData.disabled) {
                img.classList.add("hidden");
                img.style.opacity = "0.5";
                img.style.filter = "grayscale(100%)";
            } else {
                img.classList.remove("hidden");
                img.style.opacity = "";
                img.style.filter = "";
            }
            this.syncChanges();
        },

        // Secondary Keywords
        addSecondaryKeyword(e) {
            const val = e.target.value.trim();
            if (val && !this.form.secondary_keywords.includes(val)) {
                this.form.secondary_keywords.push(val);
            }
            e.target.value = "";
        },
        removeSecondaryKeyword(index) {
            this.form.secondary_keywords.splice(index, 1);
        },
        centerToolbox() {
            this.$nextTick(() => {
                let container = document.getElementById(
                    "preview-modal-container"
                );

                // Fallback to wrapper if modal is not visible or not found
                // We check if modal is actually shown in state as well
                if (
                    !this.modals.preview.show ||
                    !container ||
                    container.offsetParent === null
                ) {
                    container = document.getElementById("page-builder-wrapper");
                }

                const toolbox = document.querySelector(".ct-toolbox");

                if (container && toolbox) {
                    const containerRect = container.getBoundingClientRect();

                    // Position 30px from top and 5px from left of the container
                    const top = containerRect.top + 60;
                    const left = containerRect.left + 20;

                    this.toolbox.position = {
                        top: Math.max(60, top),
                        left: Math.max(20, left),
                    };
                }
            });
        },

        handleStatusChange() {
            // If trying to deactivate while homepage is true, prevent it
            if (!this.form.is_active && this.form.home_page) {
                this.$nextTick(() => {
                    this.form.is_active = true;
                    alert(
                        "Homepage pages cannot be deactivated. Please unset homepage first."
                    );
                });
            }
        },
    },

    watch: {
        "modals.preview.show"(val) {
            if (val) {
                this.centerToolbox();

                // Show tooltip for 2 seconds to indicate toolbox location
                this.toolbox.showTooltip = true;
                setTimeout(() => {
                    this.toolbox.showTooltip = false;
                }, 2000);
            }
        },
        "form.home_page"(newVal) {
            // If homepage is set to true, ensure is_active is also true
            if (newVal === true && !this.form.is_active) {
                this.form.is_active = true;
            }
        },
        "form.is_active"(newVal) {
            // If trying to deactivate while homepage is true, prevent it
            if (newVal === false && this.form.home_page === true) {
                this.$nextTick(() => {
                    this.form.is_active = true;
                    alert(
                        "Homepage pages cannot be deactivated. Please unset homepage first."
                    );
                });
            }
        },
        // DISABLED: Quill editor is now handled by quill-editor-vue component
        // Watch for page_type changes to init/destroy Quill for static pages
        "form.page_type"(newVal, oldVal) {
            console.log('[PageBuilder] page_type changed from', oldVal, 'to', newVal);
            // Do nothing - quill-editor-vue component handles initialization
            // The component is already rendered in the template
        },
    },

    mounted() {
        if (window.PageBuilderData) {
            this.init(window.PageBuilderData);
        }
        this.loadPresets();
        this.initQuillEditor();
        this.isReady = true;

        // Center toolbox initially
        this.centerToolbox();

        // Re-center on window resize
        window.addEventListener("resize", () => {
            this.centerToolbox();
        });

        // Initialize form submit listener for standard Blade submission
        const form = document.getElementById('vue-page-form');
        if (form) {
            form.addEventListener('submit', () => {
                // Sync TipTap Editor content
                try {
                    const longBodyInput = document.getElementById('input-long_body');
                    if (longBodyInput) {
                        if (window.vueTipTapEditors && window.vueTipTapEditors['long_body']) {
                            const editorRef = window.vueTipTapEditors['long_body'];
                            if (editorRef.updateHiddenInput) {
                                editorRef.updateHiddenInput();
                            }
                        }
                        // Update Vue state from hidden input (which TipTap updated)
                        this.form.long_body = longBodyInput.value || '';

                        // Ensure the form's specific hidden input for this field is also updated
                        const formHiddenInput = form.querySelector('input[name="long_body"]');
                        if (formHiddenInput) {
                            formHiddenInput.value = longBodyInput.value || '';
                        }
                    }
                } catch (e) {
                    console.error("Sync Error:", e);
                }

                // Sync Boolean Fields
                const booleanFields = ['home_page', 'is_active', 'hide_header', 'hide_footer'];
                booleanFields.forEach(field => {
                    const input = form.querySelector(`input[name="${field}"]`);
                    if (input && this.form.hasOwnProperty(field)) {
                        input.value = this.form[field] ? '1' : '0';
                    }
                });

                // Sync Widget Config
                const hiddenInput = form.querySelector('input[name="widget_config"]');
                if (hiddenInput) {
                    // Recalculate positions and sort
                    const headerBlocks = this.blocks.filter(b => b.region === "header");
                    const bodyBlocks = this.blocks.filter(b => b.region === "body");
                    const sortedBlocks = [...headerBlocks, ...bodyBlocks];
                    const blocksToSubmit = sortedBlocks.map((block, index) => ({
                        ...block,
                        id: block.component_id || block.id,
                        position: index,
                    }));
                    hiddenInput.value = JSON.stringify(blocksToSubmit);
                }
            });
        }
    },
};

// Mount the app
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('page-builder-app');
    if (el) {
        const app = createApp(PageBuilder);
        app.mount('#page-builder-app');
    }
});
