import { Editor } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";
import Placeholder from "@tiptap/extension-placeholder";
import Link from "@tiptap/extension-link";
import TextAlign from "@tiptap/extension-text-align";
import TaskList from "@tiptap/extension-task-list";
import TaskItem from "@tiptap/extension-task-item";
import Underline from "@tiptap/extension-underline";
import Subscript from "@tiptap/extension-subscript";
import Superscript from "@tiptap/extension-superscript";

export function setupEditor(content, placeholder = "Start typing...") {
    let editor;

    return {
        content: content,
        updatedAt: Date.now(),

        init(element) {
            if (!element || typeof element.getAttribute !== "function") {
                return null;
            }
            const placeholderText =
                element.getAttribute("data-placeholder") || placeholder;

            editor = new Editor({
                element: element,
                extensions: [
                    StarterKit,
                    Placeholder.configure({
                        placeholder: placeholderText,
                    }),
                    Link.configure({
                        openOnClick: false,
                        HTMLAttributes: {
                            class: "text-blue-600 dark:text-blue-400 hover:underline",
                        },
                    }),
                    TextAlign.configure({
                        types: ["heading", "paragraph"],
                    }),
                    TaskList,
                    TaskItem.configure({
                        nested: true,
                    }),
                    Underline,
                    Subscript,
                    Superscript,
                ],
                content: this.content || "",
                onUpdate: ({ editor }) => {
                    this.content = editor.getHTML() || "";
                    this.updatedAt = Date.now();

                    // Update hidden input immediately on content change
                    const hiddenInputId = element
                        .closest(".tiptap-editor-wrapper")
                        ?.querySelector('input[type="hidden"]')?.id;
                    if (hiddenInputId) {
                        const hiddenInput =
                            document.getElementById(hiddenInputId);
                        if (hiddenInput) {
                            hiddenInput.value = this.content;
                            hiddenInput.dispatchEvent(
                                new Event("input", { bubbles: true })
                            );
                            hiddenInput.dispatchEvent(
                                new Event("change", { bubbles: true })
                            );
                        }
                    }
                },
                onSelectionUpdate: () => {
                    this.updatedAt = Date.now();
                },
            });

            return editor;
        },

        isLoaded() {
            return !!editor;
        },

        isActive(type, opts = {}) {
            try {
                if (!editor) {
                    return false;
                }
                return editor.isActive(type, opts);
            } catch (error) {
                console.warn("isActive error:", error);
                return false;
            }
        },

        // Başlık toggle fonksiyonları
        toggleHeading(level) {
            if (!editor) {
                return;
            }
            editor.chain().focus().toggleHeading({ level }).run();
        },

        // Metin formatlama
        toggleBold() {
            if (!editor) {
                return;
            }
            editor.chain().focus().toggleBold().run();
        },

        toggleItalic() {
            if (!editor) {
                return;
            }
            editor.chain().focus().toggleItalic().run();
        },

        toggleStrike() {
            if (!editor) {
                return;
            }
            editor.chain().focus().toggleStrike().run();
        },

        toggleCode() {
            if (!editor) {
                return;
            }
            editor.chain().focus().toggleCode().run();
        },

        toggleUnderline() {
            if (!editor) {
                return;
            }
            editor.chain().focus().toggleUnderline().run();
        },

        toggleSubscript() {
            if (!editor) {
                return;
            }
            editor.chain().focus().toggleSubscript().run();
        },

        toggleSuperscript() {
            if (!editor) {
                return;
            }
            editor.chain().focus().toggleSuperscript().run();
        },

        // Listeler
        toggleBulletList() {
            if (!editor) {
                return;
            }
            editor.chain().focus().toggleBulletList().run();
        },

        toggleOrderedList() {
            if (!editor) {
                return;
            }
            editor.chain().focus().toggleOrderedList().run();
        },

        toggleTaskList() {
            if (!editor) {
                return;
            }
            editor.chain().focus().toggleTaskList().run();
        },

        // Blok elementler
        toggleBlockquote() {
            if (!editor) {
                return;
            }
            editor.chain().focus().toggleBlockquote().run();
        },

        setCodeBlock() {
            if (!editor) {
                return;
            }
            editor.chain().focus().toggleCodeBlock().run();
        },

        setHorizontalRule() {
            if (!editor) {
                return;
            }
            editor.chain().focus().setHorizontalRule().run();
        },

        // Link işlemleri
        setLink() {
            if (!editor) {
                return;
            }
            const url = window.prompt("Enter URL:");
            if (url) {
                editor.chain().focus().setLink({ href: url }).run();
            }
        },

        unsetLink() {
            if (!editor) {
                return;
            }
            editor.chain().focus().unsetLink().run();
        },

        // Hizalama
        setTextAlign(align) {
            if (!editor) {
                return;
            }
            editor.chain().focus().setTextAlign(align).run();
        },

        // Undo/Redo
        undo() {
            if (!editor) {
                return;
            }
            editor.chain().focus().undo().run();
        },

        redo() {
            if (!editor) {
                return;
            }
            editor.chain().focus().redo().run();
        },

        // HTML alma/ayarlama
        getHTML() {
            if (!editor) {
                return "";
            }
            return editor.getHTML();
        },

        setHTML(html) {
            if (!editor) {
                return;
            }
            editor.commands.setContent(html, false);
            this.content = html;
        },

        // HTML formatlama (güzel görünüm için)
        formatHTML(html) {
            if (!html || html.trim() === "") {
                return "";
            }

            let formatted = html;
            let indent = 0;
            const indentSize = 2;

            formatted = formatted.replace(/>/g, ">\n");
            formatted = formatted.replace(/</g, "\n<");

            const lines = formatted.split("\n");
            const formattedLines = [];

            for (let line of lines) {
                line = line.trim();
                if (!line) {
                    continue;
                }

                if (line.match(/^<\/\w/)) {
                    indent = Math.max(0, indent - indentSize);
                }

                formattedLines.push(" ".repeat(indent) + line);

                if (line.match(/^<\w[^>]*>/) && !line.match(/\/>$/)) {
                    indent += indentSize;
                }
            }

            return formattedLines.join("\n").trim();
        },
    };
};

window.setupEditor = setupEditor;
