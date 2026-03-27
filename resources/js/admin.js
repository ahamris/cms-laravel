import "./bootstrap";
import "./toast";
import { registerSeoAssist } from "./seo-assist.js";
// import "./datepicker"; // Converted to dynamic import
// import "./tiptap"; // Converted to dynamic import
// import "./codemirror"; // Removed (handled by PageBuilder)
// import { html_beautify } from "js-beautify"; // Removed (handled by PageBuilder)

// Alpine.js Store - Global state management (compatible with Livewire)
document.addEventListener("alpine:init", () => {
    // ... (rest of Alpine code is fine, no changes needed inside)
    // I need to be careful not to delete the Alpine code.
    // The target content must include the start of the file.
});
// Wait, I can't match the whole Alpine block easily.
// I will just replace the imports and the start of initializeDatePickers.

document.addEventListener("alpine:init", () => {
    registerSeoAssist(Alpine);

    // Sidebar Store
    Alpine.store("sidebar", {
        isOpen: window.innerWidth > 1024, // Default open on wide screens
        toggle() {
            this.isOpen = !this.isOpen;
        },
    });

    Alpine.store("darkMode", {
        mode: (function () {
            const stored = localStorage.getItem("theme");
            if (stored === "light" || stored === "dark") return stored;
            return "system"; // default
        })(),

        get isDark() {
            if (this.mode === "dark") return true;
            if (this.mode === "light") return false;
            return window.matchMedia("(prefers-color-scheme: dark)").matches;
        },

        set(next) {
            this.mode = next;
            if (next === "light" || next === "dark") {
                localStorage.setItem("theme", next);
            } else {
                localStorage.removeItem("theme");
            }
            this.apply();
        },

        toggle() {
            this.set(this.isDark ? "light" : "dark");
        },

        apply() {
            document.documentElement.classList.toggle("dark", this.isDark);
        },

        init() {
            this.apply();
        },
    });

    // AI banner "remind later" - hide banner based on user choice (localStorage)
    const AI_BANNER_KEY = "ai-banner-remind";
    Alpine.data("aiBannerRemind", () => ({
        bannerVisible: true,
        modalOpen: false,
        remindOption: "",

        init() {
            const stored = localStorage.getItem(AI_BANNER_KEY);
            if (stored === "never") {
                this.bannerVisible = false;
                return;
            }
            const until = parseInt(stored, 10);
            if (Number.isFinite(until) && Date.now() < until) {
                this.bannerVisible = false;
            }
        },

        openModal() {
            this.remindOption = "";
            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
        },

        confirmRemind() {
            if (!this.remindOption) return;
            const now = Date.now();
            let until = 0;
            switch (this.remindOption) {
                case "8h":
                    until = now + 8 * 60 * 60 * 1000;
                    break;
                case "1d":
                    until = now + 24 * 60 * 60 * 1000;
                    break;
                case "1w":
                    until = now + 7 * 24 * 60 * 60 * 1000;
                    break;
                case "never":
                    localStorage.setItem(AI_BANNER_KEY, "never");
                    this.bannerVisible = false;
                    this.closeModal();
                    return;
                default:
                    this.closeModal();
                    return;
            }
            localStorage.setItem(AI_BANNER_KEY, String(until));
            this.bannerVisible = false;
            this.closeModal();
        },
    }));
    Alpine.data("tipTapLazy", (value = "", placeholder = "Start typing...") => ({
        activeTab: "editor",
        sourceCode: "",
        content: value,
        editorInstance: null,
        editorModule: null,
        isLoading: false,

        async init() {
            // Load module if not already loaded
            if (!this.editorModule) {
                this.isLoading = true;
                try {
                    this.editorModule = await import("./tiptap.js");
                } catch (e) {
                    console.error("Failed to load TipTap:", e);
                    this.isLoading = false;
                    return;
                }
                this.isLoading = false;
            }

            // Init editor using the loaded module
            this.initEditor();
        },

        initEditor() {
            if (!this.editorModule || !this.$refs.editor) return;

            const editorData = this.editorModule.setupEditor(
                this.content,
                placeholder
            );

            // Merge methods from setupEditor into this component
            Object.assign(this, editorData);

            // Initialize the actual editor instance
            this.editorInstance = this.init(this.$refs.editor);
        },

        // Wrapper to safely check active state
        isActive(type, opts = {}) {
            if (
                this.editorInstance &&
                typeof this.editorInstance.isActive === "function"
            ) {
                return this.editorInstance.isActive(type, opts);
            }
            return false;
        },

        switchTab(tab) {
            if (tab === "html" && this.activeTab === "editor") {
                const html = this.getHTML ? this.getHTML() : this.content;
                this.sourceCode = this.formatHTML
                    ? this.formatHTML(html)
                    : html;
            } else if (tab === "editor" && this.activeTab === "html") {
                const unformatted = this.sourceCode
                    .replace(/\n\s*/g, " ")
                    .trim();
                if (this.setHTML) {
                    this.setHTML(unformatted);
                } else {
                    this.content = unformatted;
                }
            }
            this.activeTab = tab;
        },
    }));
});

// Re-initialize date pickers for dynamically added content
// This function is called when new content is added dynamically (e.g., via Livewire)
window.initializeDatePickers = async function () {
    // Wait for flatpickr to be available, or load it
    if (typeof window.flatpickr === 'undefined') {
        try {
            await import("./datepicker.js");
        } catch (e) {
            console.error("Failed to load datepicker:", e);
            return;
        }
    }

    // Find all date picker inputs that need initialization
    // This matches the selector used in date-picker.blade.php component
    document.querySelectorAll('[data-flatpickr]:not(.flatpickr-input)').forEach(function (element) {
        try {
            // Skip if already initialized
            if (element._flatpickrInstance || element._flatpickr) {
                return;
            }

            // Skip initialization if input is readonly or disabled
            if (element.hasAttribute('readonly') || element.hasAttribute('disabled')) {
                return;
            }

            // Get options from data attribute
            const optionsJson = element.getAttribute('data-options');
            const options = optionsJson ? JSON.parse(optionsJson) : {};

            // Get theme and locale
            const theme = element.getAttribute('data-theme') || 'auto';
            const locale = element.getAttribute('data-locale');

            // Load theme if needed
            if (window.loadFlatpickrTheme && theme && theme !== 'auto') {
                window.loadFlatpickrTheme(theme);
            }

            // Load locale if needed
            if (window.loadFlatpickrLocale && locale && locale !== 'en' && locale !== 'default') {
                window.loadFlatpickrLocale(locale);
            }

            // Initialize flatpickr
            if (window.flatpickr) {
                window.flatpickr(element, options);
            }
        } catch (e) {
            console.error('Error initializing date picker:', e);
        }
    });
};