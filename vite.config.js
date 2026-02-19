import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/admin.css",
                "resources/js/admin.js",
                "resources/js/bootstrap.js",
                "resources/js/toast.js",
                "resources/js/datepicker.js",
                "resources/js/quill.js",
                "resources/js/tiptap.js",
                // Image Optimizer (Vue app)
                "resources/js/admin/image-optimizer.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            "vue": "vue/dist/vue.esm-bundler.js",
        },
    },
    define: {
        __VUE_OPTIONS_API__: true,
        __VUE_PROD_DEVTOOLS__: false,
        __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: false,
    },
    build: {
        chunkSizeWarningLimit: 1000,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes("node_modules")) {
                        // Dynamic imports handle chunking automatically
                    }
                },
            },
        },
    },
});
