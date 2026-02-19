import { EditorView, minimalSetup } from "codemirror";
import { html } from "@codemirror/lang-html";
import { dracula } from "@uiw/codemirror-theme-dracula";
import { EditorState } from "@codemirror/state";
import { keymap, lineNumbers } from "@codemirror/view";
import { defaultKeymap } from "@codemirror/commands";

window.CM6 = {
    EditorView,
    EditorState,
    minimalSetup,
    lineNumbers,
    html,
    dracula,
    keymap,
    defaultKeymap
};
