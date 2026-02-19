/**
 * Image Optimizer admin page – Vue app.
 * Expects window.IMAGE_OPTIMIZER_DATA with { streamUrl, translations }.
 */
import { createApp } from "vue";

const streamUrl = window.IMAGE_OPTIMIZER_DATA?.streamUrl ?? "";
const translations = window.IMAGE_OPTIMIZER_DATA?.translations ?? {};

createApp({
    components: {
        "image-optimizer-component": {
            data() {
                return {
                    translations,
                    isOptimizing: false,
                    output: [],
                    options: {
                        includePublic: false,
                        createWebP: false,
                    },
                    stats: {
                        jpegCount: 0,
                        jpegErrors: 0,
                        pngCount: 0,
                        pngErrors: 0,
                        webpCount: 0,
                        webpErrors: 0,
                        totalOriginalSize: 0,
                        totalOptimizedSize: 0,
                        totalSaved: 0,
                        savingsPercent: 0,
                        jpegSaved: 0,
                        pngSaved: 0,
                    },
                    completed: false,
                    error: null,
                };
            },
            methods: {
                async startOptimization() {
                    this.isOptimizing = true;
                    this.output = [];
                    this.completed = false;
                    this.error = null;
                    this.stats = {
                        jpegCount: 0,
                        jpegErrors: 0,
                        pngCount: 0,
                        pngErrors: 0,
                        webpCount: 0,
                        webpErrors: 0,
                        totalOriginalSize: 0,
                        totalOptimizedSize: 0,
                        totalSaved: 0,
                        savingsPercent: 0,
                        jpegSaved: 0,
                        pngSaved: 0,
                    };

                    try {
                        const params = new URLSearchParams({
                            "include-public": this.options.includePublic,
                            "no-webp": !this.options.createWebP,
                        });

                        const response = await fetch(`${streamUrl}?${params.toString()}`, {
                            method: "GET",
                            headers: {
                                Accept: "text/event-stream",
                                "X-CSRF-TOKEN":
                                    document.querySelector('meta[name="csrf-token"]')?.content ?? "",
                            },
                        });

                        if (!response.ok) {
                            throw new Error("Failed to start optimization");
                        }

                        const reader = response.body.getReader();
                        const decoder = new TextDecoder();

                        while (true) {
                            const { done, value } = await reader.read();
                            if (done) break;

                            const chunk = decoder.decode(value);
                            const lines = chunk.split("\n");

                            for (const line of lines) {
                                if (line.startsWith("data: ")) {
                                    try {
                                        const data = JSON.parse(line.substring(6));
                                        this.handleMessage(data);
                                    } catch {
                                        // Skip invalid JSON
                                    }
                                }
                            }
                        }
                    } catch (error) {
                        this.error = error.message;
                        this.isOptimizing = false;
                        console.error("Optimization error:", error);
                    }
                },
                handleMessage(data) {
                    if (data.type === "output") {
                        const message = (data.data || "").trim().replace(/\r$/, "");
                        if (message) {
                            this.output.push(message);
                            this.parseStats(message);
                        }
                    } else if (data.type === "error") {
                        this.output.push("ERROR: " + data.data);
                    } else if (data.type === "complete") {
                        this.completed = true;
                        this.isOptimizing = false;
                        if (data.exitCode !== 0) {
                            this.error = "Optimization completed with errors";
                        }
                    }
                },
                parseStats(message) {
                    const jpegMatch = message.match(
                        /✅ Optimized (\d+) JPEG files(?: \((\d+) skipped\))?/
                    );
                    if (jpegMatch) {
                        this.stats.jpegCount = parseInt(jpegMatch[1], 10);
                        this.stats.jpegErrors = jpegMatch[2] ? parseInt(jpegMatch[2], 10) : 0;
                    }

                    const pngMatch = message.match(
                        /✅ Optimized (\d+) PNG files(?: \((\d+) skipped\))?/
                    );
                    if (pngMatch) {
                        this.stats.pngCount = parseInt(pngMatch[1], 10);
                        this.stats.pngErrors = pngMatch[2] ? parseInt(pngMatch[2], 10) : 0;
                    }

                    const webpMatch = message.match(
                        /✅ Created (\d+) WebP backup files(?: \((\d+) skipped\))?/
                    );
                    if (webpMatch) {
                        this.stats.webpCount = parseInt(webpMatch[1], 10);
                        this.stats.webpErrors = webpMatch[2] ? parseInt(webpMatch[2], 10) : 0;
                    }

                    const savingsMatch = message.match(
                        /Total saved: ([\d.]+) KB \(([\d.]+)%\)/
                    );
                    if (savingsMatch) {
                        this.stats.totalSaved = parseFloat(savingsMatch[1]);
                        this.stats.savingsPercent = parseFloat(savingsMatch[2]);
                    }

                    const originalMatch = message.match(/Original total size: ([\d.]+) KB/);
                    if (originalMatch) {
                        this.stats.totalOriginalSize = parseFloat(originalMatch[1]);
                    }

                    const optimizedMatch = message.match(/Optimized total size: ([\d.]+) KB/);
                    if (optimizedMatch) {
                        this.stats.totalOptimizedSize = parseFloat(optimizedMatch[1]);
                    }

                    const jpegSavedMatch = message.match(/💾 JPEG savings: ([\d.]+) KB/);
                    if (jpegSavedMatch) {
                        this.stats.jpegSaved = parseFloat(jpegSavedMatch[1]);
                    }

                    const pngSavedMatch = message.match(/💾 PNG savings: ([\d.]+) KB/);
                    if (pngSavedMatch) {
                        this.stats.pngSaved = parseFloat(pngSavedMatch[1]);
                    }
                },
                formatKB(bytes) {
                    return Number(bytes).toFixed(2);
                },
            },
            template: `
                <div>
                    <div class="mb-6">
                        <div class="flex items-center space-x-6 mb-4">
                            <label for="includePublicToggle" class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" id="includePublicToggle" class="sr-only" v-model="options.includePublic">
                                    <div class="block bg-gray-600 w-14 h-8 rounded-full"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                                </div>
                                <div class="ml-3 text-gray-700 font-medium">Include Public Images</div>
                            </label>
                            <label for="createWebPToggle" class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" id="createWebPToggle" class="sr-only" v-model="options.createWebP" :disabled="!options.includePublic">
                                    <div class="block w-14 h-8 rounded-full" :class="options.includePublic ? 'bg-gray-600' : 'bg-gray-400'"></div>
                                    <div class="dot absolute left-1 top-1 w-6 h-6 rounded-full transition" :class="options.includePublic ? 'bg-white' : 'bg-gray-200'"></div>
                                </div>
                                <div class="ml-3 font-medium" :class="options.includePublic ? 'text-gray-700' : 'text-gray-400'">Create WebP Backups</div>
                            </label>
                        </div>
                        <button
                            @click="startOptimization"
                            :disabled="isOptimizing"
                            class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-6 py-3 rounded-lg flex items-center space-x-2 transition-colors"
                        >
                            <i :class="isOptimizing ? 'fas fa-spinner fa-spin' : 'fas fa-compress'"></i>
                            <span>{{ isOptimizing ? translations.optimizing + '...' : translations.start_optimization }}</span>
                        </button>
                    </div>
                    <div v-if="error" class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0"><i class="fas fa-exclamation-circle text-red-400"></i></div>
                            <div class="ml-3"><p class="text-sm text-red-700">{{ error }}</p></div>
                        </div>
                    </div>
                    <div v-if="isOptimizing || output.length > 0" class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ translations.output_log }}</h3>
                        <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm max-h-96 overflow-y-auto">
                            <div v-for="(line, index) in output" :key="index" class="mb-1">{{ line }}</div>
                            <div v-if="isOptimizing" class="flex items-center space-x-2">
                                <i class="fas fa-spinner fa-spin"></i>
                                <span>{{ translations.processing }}...</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="completed || stats.totalSaved > 0" class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ translations.statistics }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-blue-900">{{ translations.jpeg_files }}</span>
                                    <span class="text-2xl font-bold text-blue-600">{{ stats.jpegCount }}</span>
                                </div>
                                <div v-if="stats.jpegErrors > 0" class="mt-2 text-xs text-blue-700">{{ translations.errors }}: {{ stats.jpegErrors }}</div>
                                <div v-if="stats.jpegSaved > 0" class="mt-2 text-xs text-blue-700">{{ translations.saved }}: {{ formatKB(stats.jpegSaved) }} KB</div>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-purple-900">{{ translations.png_files }}</span>
                                    <span class="text-2xl font-bold text-purple-600">{{ stats.pngCount }}</span>
                                </div>
                                <div v-if="stats.pngErrors > 0" class="mt-2 text-xs text-purple-700">{{ translations.errors }}: {{ stats.pngErrors }}</div>
                                <div v-if="stats.pngSaved > 0" class="mt-2 text-xs text-purple-700">{{ translations.saved }}: {{ formatKB(stats.pngSaved) }} KB</div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-green-900">{{ translations.webp_files }}</span>
                                    <span class="text-2xl font-bold text-green-600">{{ stats.webpCount }}</span>
                                </div>
                                <div v-if="stats.webpErrors > 0" class="mt-2 text-xs text-green-700">{{ translations.errors }}: {{ stats.webpErrors }}</div>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg md:col-span-2 lg:col-span-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-medium text-yellow-900">{{ translations.total_savings }}</span>
                                    <div class="text-right">
                                        <span class="text-3xl font-bold text-yellow-600">{{ formatKB(stats.totalSaved) }} KB</span>
                                        <span class="text-sm text-yellow-700 ml-2">({{ stats.savingsPercent }}%)</span>
                                    </div>
                                </div>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <div>{{ translations.original_size }}: {{ formatKB(stats.totalOriginalSize) }} KB</div>
                                    <div>{{ translations.optimized_size }}: {{ formatKB(stats.totalOptimizedSize) }} KB</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="completed && !error" class="mt-6 bg-green-50 border-l-4 border-green-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0"><i class="fas fa-check-circle text-green-400"></i></div>
                            <div class="ml-3"><p class="text-sm text-green-700 font-medium">{{ translations.optimization_complete }}</p></div>
                        </div>
                    </div>
                    <div v-if="completed && !error && stats.jpegCount === 0 && stats.pngCount === 0" class="mt-4 bg-amber-50 border-l-4 border-amber-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0"><i class="fas fa-info-circle text-amber-500"></i></div>
                            <div class="ml-3">
                                <p class="text-sm text-amber-800 font-medium">No images found to optimize.</p>
                                <p class="text-sm text-amber-700 mt-1">The script scans <code class="bg-amber-100 px-1 rounded">storage/app/public</code> always. Enable &quot;Include Public Images&quot; to also scan <code class="bg-amber-100 px-1 rounded">public/frontend</code>, <code class="bg-amber-100 px-1 rounded">public/front</code>, <code class="bg-amber-100 px-1 rounded">public/images</code>, and <code class="bg-amber-100 px-1 rounded">public/assets</code>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            `,
        },
    },
}).mount("#image-optimizer-app");
