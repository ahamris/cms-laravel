function stripTags(html) {
    if (!html) return "";
    const d = document.createElement("div");
    d.innerHTML = html;
    return d.textContent || d.innerText || "";
}

function normalizeSpace(s) {
    return String(s).replace(/\s+/g, " ").trim();
}

function truncateAtWord(text, max) {
    const t = normalizeSpace(text);
    if (!t) return "";
    if (t.length <= max) return t;
    const slice = t.slice(0, max + 1);
    const lastSpace = slice.lastIndexOf(" ");
    if (lastSpace > max * 0.5) {
        return slice.slice(0, lastSpace).trimEnd() + "…";
    }
    return t.slice(0, max - 1).trimEnd() + "…";
}

export function registerSeoAssist(Alpine) {
    Alpine.data("seoAssistFromSummary", (config = {}) => ({
        programmatic: false,
        metaTitleLocked: Boolean(config.metaTitleHasValue),
        metaDescLocked: Boolean(config.metaDescHasValue),
        descriptionFieldId: config.descriptionFieldId || "meta_description",
        debounceMs: config.debounceMs ?? 320,
        syncEventName: config.syncEventName || null,
        _t: null,
        _boundSync: null,

        formatMetaTitle(raw) {
            return truncateAtWord(normalizeSpace(stripTags(raw)), 60);
        },

        formatMetaDescription(raw) {
            return truncateAtWord(normalizeSpace(stripTags(raw)), 158);
        },

        applyAuto() {
            const titleEl = document.getElementById("title");
            const shortEl = document.getElementById("short_body");
            const metaTitleEl = document.getElementById("meta_title");
            const metaDescEl = document.getElementById(this.descriptionFieldId);
            const titleVal = titleEl?.value?.trim() || "";
            const shortVal = shortEl?.value?.trim() || "";

            this.programmatic = true;
            try {
                if (metaTitleEl && !this.metaTitleLocked) {
                    const s = this.formatMetaTitle(titleVal);
                    metaTitleEl.value = s;
                    metaTitleEl.dispatchEvent(new Event("input", { bubbles: true }));
                }
                if (metaDescEl && !this.metaDescLocked) {
                    const s = this.formatMetaDescription(shortVal);
                    metaDescEl.value = s;
                    metaDescEl.dispatchEvent(new Event("input", { bubbles: true }));
                }
            } finally {
                this.programmatic = false;
            }
        },

        syncFromSummary() {
            this.metaTitleLocked = false;
            this.metaDescLocked = false;
            this.applyAuto();
        },

        init() {
            const titleEl = document.getElementById("title");
            const shortEl = document.getElementById("short_body");
            const metaTitleEl = document.getElementById("meta_title");
            const metaDescEl = document.getElementById(this.descriptionFieldId);

            const schedule = () => {
                clearTimeout(this._t);
                this._t = setTimeout(() => this.applyAuto(), this.debounceMs);
            };

            titleEl?.addEventListener("input", schedule);
            shortEl?.addEventListener("input", schedule);

            metaTitleEl?.addEventListener("input", () => {
                if (this.programmatic) return;
                if (!metaTitleEl.value.trim()) {
                    this.metaTitleLocked = false;
                } else {
                    this.metaTitleLocked = true;
                }
            });

            metaDescEl?.addEventListener("input", () => {
                if (this.programmatic) return;
                if (!metaDescEl.value.trim()) {
                    this.metaDescLocked = false;
                } else {
                    this.metaDescLocked = true;
                }
            });

            this.$nextTick(() => this.applyAuto());

            if (this.syncEventName) {
                this._boundSync = () => this.syncFromSummary();
                window.addEventListener(this.syncEventName, this._boundSync);
            }
        },

        destroy() {
            if (this.syncEventName && this._boundSync) {
                window.removeEventListener(this.syncEventName, this._boundSync);
            }
        },
    }));
}
