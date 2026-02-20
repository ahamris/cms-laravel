<x-layouts.admin title="AI Settings">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">AI Settings</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Configure AI for content generation: Groq, Gemini, or your own Ollama instance</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-check-circle text-green-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-exclamation-circle text-red-500 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">
                        {{ $errors->first() }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.ai.update') }}" id="ai-settings-form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column - 2/3 --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Groq Settings Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Groq AI Settings</h2>
                            <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Configure Groq API for fast AI content generation</p>
                        </div>
                        <button type="button" 
                                onclick="testConnection('groq')"
                                class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fa-solid fa-plug mr-2"></i>Test Connection
                        </button>
                    </div>

                    <div class="space-y-6">
                        {{-- Groq API Key --}}
                        <div>
                            <label for="groq_api_key" class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">
                                Groq API Key
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       id="groq_api_key" 
                                       name="groq_api_key" 
                                       value="{{ old('groq_api_key', $settings['groq_api_key']) }}"
                                       placeholder="gsk_..."
                                       class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('groq_api_key') outline-red-500 dark:outline-red-500 @enderror">
                                <button type="button" 
                                        onclick="togglePasswordVisibility('groq_api_key')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <i class="fa-solid fa-eye" id="groq_api_key_icon"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Get your API key from <a href="https://console.groq.com/" target="_blank" class="text-blue-600 hover:underline">Groq Console</a>
                            </p>
                            @error('groq_api_key')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Groq Model --}}
                        <div>
                            <label for="groq_model" class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">
                                Groq Model
                            </label>
                            <select id="groq_model" 
                                    name="groq_model" 
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:focus:outline-[var(--color-accent)]">
                                @if(!empty($groqModels))
                                    <optgroup label="From Groq API (your account)">
                                        @foreach($groqModels as $m)
                                            <option value="{{ $m['id'] }}" {{ old('groq_model', $settings['groq_model']) === $m['id'] ? 'selected' : '' }}>{{ $m['label'] }}</option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    <optgroup label="Production (recommended)">
                                        <option value="llama-3.3-70b-versatile" {{ old('groq_model', $settings['groq_model']) === 'llama-3.3-70b-versatile' ? 'selected' : '' }}>Llama 3.3 70B Versatile</option>
                                        <option value="llama-3.1-8b-instant" {{ old('groq_model', $settings['groq_model']) === 'llama-3.1-8b-instant' ? 'selected' : '' }}>Llama 3.1 8B Instant</option>
                                        <option value="openai/gpt-oss-120b" {{ old('groq_model', $settings['groq_model']) === 'openai/gpt-oss-120b' ? 'selected' : '' }}>OpenAI GPT-OSS 120B</option>
                                        <option value="openai/gpt-oss-20b" {{ old('groq_model', $settings['groq_model']) === 'openai/gpt-oss-20b' ? 'selected' : '' }}>OpenAI GPT-OSS 20B</option>
                                    </optgroup>
                                    <optgroup label="Production systems (Compound)">
                                        <option value="groq/compound" {{ old('groq_model', $settings['groq_model']) === 'groq/compound' ? 'selected' : '' }}>Groq Compound</option>
                                        <option value="groq/compound-mini" {{ old('groq_model', $settings['groq_model']) === 'groq/compound-mini' ? 'selected' : '' }}>Groq Compound Mini</option>
                                    </optgroup>
                                    <optgroup label="Preview">
                                        <option value="meta-llama/llama-4-maverick-17b-128e-instruct" {{ old('groq_model', $settings['groq_model']) === 'meta-llama/llama-4-maverick-17b-128e-instruct' ? 'selected' : '' }}>Llama 4 Maverick 17B</option>
                                        <option value="meta-llama/llama-4-scout-17b-16e-instruct" {{ old('groq_model', $settings['groq_model']) === 'meta-llama/llama-4-scout-17b-16e-instruct' ? 'selected' : '' }}>Llama 4 Scout 17B</option>
                                        <option value="qwen/qwen3-32b" {{ old('groq_model', $settings['groq_model']) === 'qwen/qwen3-32b' ? 'selected' : '' }}>Qwen3 32B</option>
                                        <option value="moonshotai/kimi-k2-instruct-0905" {{ old('groq_model', $settings['groq_model']) === 'moonshotai/kimi-k2-instruct-0905' ? 'selected' : '' }}>Kimi K2 Instruct</option>
                                        <option value="openai/gpt-oss-safeguard-20b" {{ old('groq_model', $settings['groq_model']) === 'openai/gpt-oss-safeguard-20b' ? 'selected' : '' }}>GPT-OSS Safeguard 20B</option>
                                    </optgroup>
                                    <optgroup label="Legacy">
                                        <option value="llama-3.1-70b-versatile" {{ old('groq_model', $settings['groq_model']) === 'llama-3.1-70b-versatile' ? 'selected' : '' }}>Llama 3.1 70B Versatile</option>
                                        <option value="mixtral-8x7b-32768" {{ old('groq_model', $settings['groq_model']) === 'mixtral-8x7b-32768' ? 'selected' : '' }}>Mixtral 8x7B</option>
                                    </optgroup>
                                @endif
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                @if(!empty($groqModels))
                                    Models loaded from <code class="text-xs">GET https://api.groq.com/openai/v1/models</code>. Save your API key and reload to refresh.
                                @else
                                    <a href="https://console.groq.com/docs/models" target="_blank" rel="noopener" class="text-blue-600 hover:underline">Groq models docs</a>. Enter and save your API key to load models from the API.
                                @endif
                            </p>
                            @error('groq_model')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Groq Active Toggle --}}
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-white/5 rounded-lg">
                            <div>
                                <label for="groq_is_active" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    Activate Groq Service
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Enable this service to use it for AI content generation
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       id="groq_is_active" 
                                       name="groq_is_active" 
                                       value="1"
                                       {{ old('groq_is_active', $settings['groq_is_active']) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/40 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>

                        {{-- Groq Priority --}}
                        <div>
                            <label for="groq_priority" class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">
                                Priority (0 = highest priority)
                            </label>
                            <input type="number" 
                                   id="groq_priority" 
                                   name="groq_priority" 
                                   value="{{ old('groq_priority', $settings['groq_priority']) }}"
                                   min="0" 
                                   max="10"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:focus:outline-[var(--color-accent)]">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Lower numbers are tried first. Set to 0 for highest priority.
                            </p>
                            @error('groq_priority')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Gemini Settings Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Google Gemini Settings</h2>
                            <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Configure Google Gemini API for AI content generation</p>
                        </div>
                        <button type="button" 
                                onclick="testConnection('gemini')"
                                class="px-4 py-2 text-sm bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fa-solid fa-plug mr-2"></i>Test Connection
                        </button>
                    </div>

                    <div class="space-y-6">
                        {{-- Gemini API Key --}}
                        <div>
                            <label for="gemini_api_key" class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">
                                Gemini API Key
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       id="gemini_api_key" 
                                       name="gemini_api_key" 
                                       value="{{ old('gemini_api_key', $settings['gemini_api_key']) }}"
                                       placeholder="AIza..."
                                       class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('gemini_api_key') outline-red-500 dark:outline-red-500 @enderror">
                                <button type="button" 
                                        onclick="togglePasswordVisibility('gemini_api_key')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <i class="fa-solid fa-eye" id="gemini_api_key_icon"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Get your API key from <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-blue-600 hover:underline">Google AI Studio</a>
                            </p>
                            @error('gemini_api_key')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Gemini Model --}}
                        <div>
                            <label for="gemini_model" class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">
                                Gemini Model
                            </label>
                            <select id="gemini_model" 
                                    name="gemini_model" 
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:focus:outline-[var(--color-accent)]">
                                {{-- Free Tier Models --}}
                                <optgroup label="Free Tier">
                                    <option value="gemini-2.0-flash" {{ old('gemini_model', $settings['gemini_model']) === 'gemini-2.0-flash' ? 'selected' : '' }}>Gemini 2.0 Flash</option>
                                    <option value="gemini-2.0-flash-exp" {{ old('gemini_model', $settings['gemini_model']) === 'gemini-2.0-flash-exp' ? 'selected' : '' }}>Gemini 2.0 Flash (Experimental)</option>
                                    <option value="gemini-1.5-flash" {{ old('gemini_model', $settings['gemini_model']) === 'gemini-1.5-flash' ? 'selected' : '' }}>Gemini 1.5 Flash</option>
                                    <option value="gemini-1.5-flash-8b" {{ old('gemini_model', $settings['gemini_model']) === 'gemini-1.5-flash-8b' ? 'selected' : '' }}>Gemini 1.5 Flash 8B</option>
                                    <option value="gemini-1.5-pro" {{ old('gemini_model', $settings['gemini_model']) === 'gemini-1.5-pro' ? 'selected' : '' }}>Gemini 1.5 Pro (Free Tier)</option>
                                </optgroup>
                                {{-- Pro Models --}}
                                <optgroup label="Pro Models">
                                    <option value="gemini-1.5-pro-latest" {{ old('gemini_model', $settings['gemini_model']) === 'gemini-1.5-pro-latest' ? 'selected' : '' }}>Gemini 1.5 Pro (Latest)</option>
                                    <option value="gemini-1.5-pro-002" {{ old('gemini_model', $settings['gemini_model']) === 'gemini-1.5-pro-002' ? 'selected' : '' }}>Gemini 1.5 Pro 002</option>
                                    <option value="gemini-1.5-pro-001" {{ old('gemini_model', $settings['gemini_model']) === 'gemini-1.5-pro-001' ? 'selected' : '' }}>Gemini 1.5 Pro 001</option>
                                    <option value="gemini-pro" {{ old('gemini_model', $settings['gemini_model']) === 'gemini-pro' ? 'selected' : '' }}>Gemini Pro</option>
                                    <option value="gemini-pro-vision" {{ old('gemini_model', $settings['gemini_model']) === 'gemini-pro-vision' ? 'selected' : '' }}>Gemini Pro Vision</option>
                                </optgroup>
                                {{-- Experimental Pro Models --}}
                                <optgroup label="Experimental Pro">
                                    <option value="gemini-2.0-flash-thinking-exp" {{ old('gemini_model', $settings['gemini_model']) === 'gemini-2.0-flash-thinking-exp' ? 'selected' : '' }}>Gemini 2.0 Flash Thinking (Experimental)</option>
                                </optgroup>
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Pro models may require a paid API plan. Free tier models have rate limits.
                            </p>
                            @error('gemini_model')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Gemini Active Toggle --}}
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-white/5 rounded-lg">
                            <div>
                                <label for="gemini_is_active" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    Activate Gemini Service
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Enable this service to use it for AI content generation
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       id="gemini_is_active" 
                                       name="gemini_is_active" 
                                       value="1"
                                       {{ old('gemini_is_active', $settings['gemini_is_active']) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/40 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>

                        {{-- Gemini Priority --}}
                        <div>
                            <label for="gemini_priority" class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">
                                Priority (0 = highest priority)
                            </label>
                            <input type="number" 
                                   id="gemini_priority" 
                                   name="gemini_priority" 
                                   value="{{ old('gemini_priority', $settings['gemini_priority']) }}"
                                   min="0" 
                                   max="10"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:focus:outline-[var(--color-accent)]">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Lower numbers are tried first. Set to 0 for highest priority.
                            </p>
                            @error('gemini_priority')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Ollama Settings Section --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-base/7 font-semibold text-gray-900 dark:text-white">Ollama (self-hosted)</h2>
                            <p class="mt-1 text-sm/6 text-gray-600 dark:text-gray-400">Use your own Ollama server for private AI content generation</p>
                        </div>
                        <button type="button" 
                                onclick="testConnection('ollama')"
                                class="px-4 py-2 text-sm bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                            <i class="fa-solid fa-plug mr-2"></i>Test Connection
                        </button>
                    </div>

                    <div class="space-y-6">
                        {{-- Ollama Base URL --}}
                        <div>
                            <label for="ollama_base_url" class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">
                                Base URL
                            </label>
                            <input type="url" 
                                   id="ollama_base_url" 
                                   name="ollama_base_url" 
                                   value="{{ old('ollama_base_url', $settings['ollama_base_url'] ?? 'http://localhost:11434') }}"
                                   placeholder="http://localhost:11434"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-[var(--color-accent)] @error('ollama_base_url') outline-red-500 dark:outline-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Your Ollama server URL (e.g. <code class="text-xs bg-gray-100 dark:bg-gray-800 px-1 rounded">http://localhost:11434</code> or your internal host)
                            </p>
                            @error('ollama_base_url')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Ollama Model --}}
                        <div>
                            <label for="ollama_model" class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">
                                Model
                            </label>
                            <select id="ollama_model" 
                                    name="ollama_model" 
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:focus:outline-[var(--color-accent)]">
                                <optgroup label="Llama">
                                    <option value="llama3.2" {{ old('ollama_model', $settings['ollama_model'] ?? '') === 'llama3.2' ? 'selected' : '' }}>Llama 3.2</option>
                                    <option value="llama3.1" {{ old('ollama_model', $settings['ollama_model'] ?? '') === 'llama3.1' ? 'selected' : '' }}>Llama 3.1</option>
                                    <option value="llama3" {{ old('ollama_model', $settings['ollama_model'] ?? '') === 'llama3' ? 'selected' : '' }}>Llama 3</option>
                                    <option value="llama2" {{ old('ollama_model', $settings['ollama_model'] ?? '') === 'llama2' ? 'selected' : '' }}>Llama 2</option>
                                </optgroup>
                                <optgroup label="Other">
                                    <option value="mistral" {{ old('ollama_model', $settings['ollama_model'] ?? '') === 'mistral' ? 'selected' : '' }}>Mistral</option>
                                    <option value="mixtral" {{ old('ollama_model', $settings['ollama_model'] ?? '') === 'mixtral' ? 'selected' : '' }}>Mixtral</option>
                                    <option value="codellama" {{ old('ollama_model', $settings['ollama_model'] ?? '') === 'codellama' ? 'selected' : '' }}>Code Llama</option>
                                    <option value="qwen2.5" {{ old('ollama_model', $settings['ollama_model'] ?? '') === 'qwen2.5' ? 'selected' : '' }}>Qwen 2.5</option>
                                    <option value="phi3" {{ old('ollama_model', $settings['ollama_model'] ?? '') === 'phi3' ? 'selected' : '' }}>Phi-3</option>
                                    <option value="gemma2" {{ old('ollama_model', $settings['ollama_model'] ?? '') === 'gemma2' ? 'selected' : '' }}>Gemma 2</option>
                                </optgroup>
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Model must be pulled on your Ollama server (e.g. <code class="text-xs bg-gray-100 dark:bg-gray-800 px-1 rounded">ollama pull llama3.2</code>)
                            </p>
                            @error('ollama_model')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Ollama Active Toggle --}}
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-white/5 rounded-lg">
                            <div>
                                <label for="ollama_is_active" class="block text-sm font-medium text-gray-900 dark:text-white">
                                    Activate Ollama Service
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Enable to use your Ollama instance for AI content generation
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       id="ollama_is_active" 
                                       name="ollama_is_active" 
                                       value="1"
                                       {{ old('ollama_is_active', $settings['ollama_is_active'] ?? false) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 dark:peer-focus:ring-primary/40 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                            </label>
                        </div>

                        {{-- Ollama Priority --}}
                        <div>
                            <label for="ollama_priority" class="block text-sm/6 font-medium text-gray-900 dark:text-white mb-2">
                                Priority (0 = highest priority)
                            </label>
                            <input type="number" 
                                   id="ollama_priority" 
                                   name="ollama_priority" 
                                   value="{{ old('ollama_priority', $settings['ollama_priority'] ?? 2) }}"
                                   min="0" 
                                   max="10"
                                   class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[var(--color-accent)] sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:focus:outline-[var(--color-accent)]">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Lower numbers are tried first. Set to 0 to prefer Ollama over Groq/Gemini.
                            </p>
                            @error('ollama_priority')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - 1/3 --}}
            <div class="space-y-6">
                {{-- Info Card --}}
                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-6">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fa-solid fa-info-circle mr-2"></i>About AI Settings
                    </h3>
                    <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                        <p>
                            The system will try services in <strong>priority order</strong> (lower number = higher priority). Services must be <strong>activated</strong> to be used.
                        </p>
                        <p>
                            You can use Groq, Gemini, and/or your own <strong>Ollama</strong> instance. Configure one or more; the system tries them in priority order.
                        </p>
                        <p class="pt-3 border-t border-gray-200 dark:border-white/10">
                            <strong class="text-gray-900 dark:text-white">Note:</strong> Settings are saved to the database. Make sure to activate at least one service for AI features to work.
                        </p>
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="sticky top-6">
                    <button type="submit" 
                            class="w-full px-4 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-medium">
                        <i class="fa-solid fa-save mr-2"></i>
                        Save AI Settings
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function testConnection(provider) {
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Testing...';

            fetch('{{ route("admin.settings.ai.test-connection") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ provider: provider })
            })
            .then(response => response.json())
            .then(data => {
                button.disabled = false;
                button.innerHTML = originalText;
                
                if (data.success) {
                    alert('✓ ' + data.message);
                } else {
                    alert('✗ ' + data.message);
                }
            })
            .catch(error => {
                button.disabled = false;
                button.innerHTML = originalText;
                alert('✗ Connection test failed: ' + error.message);
            });
        }
    </script>
</x-layouts.admin>

