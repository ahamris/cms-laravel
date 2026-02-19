<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\PageRequest;
use App\Models\AIServiceSetting;
use App\Models\ContentType;
use App\Models\MarketingPersona;
use App\Models\Page;
use App\Models\PageBlockPreset;
use App\Models\TailwindPlus;
use App\Services\TailwindPlusComponentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PageController extends AdminBaseController
{
    public function __construct(
        private TailwindPlusComponentService $componentService
    ) {}

    /**
     * Display a listing of the resource.
     * Table is rendered by Livewire admin.table component.
     */
    public function index(): View
    {
        return view('admin.content.page.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $marketingPersonas = MarketingPersona::active()
            ->ordered()
            ->get();

        $contentTypes = ContentType::active()
            ->ordered()
            ->get();

        $components = $this->componentService->getComponentsStructureFromDatabase();

        // Get Header and Footer blocks from service (mega-menu and footer-links compatible)
        $headerBlocks = $this->getHeaderBlocks();
        $footerBlocks = $this->getFooterBlocks();

        return view('admin.content.page.create', compact(
            'marketingPersonas',
            'contentTypes',
            'components',
            'headerBlocks',
            'footerBlocks'
        ));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(PageRequest $request)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('pages', 'public');
            $validated['image'] = $imagePath;
        }

        // Handle secondary keywords array
        if (isset($validated['secondary_keywords'])) {
            $validated['secondary_keywords'] = array_filter(array_map('trim', $validated['secondary_keywords']));
            $validated['secondary_keywords'] = ! empty($validated['secondary_keywords']) ? $validated['secondary_keywords'] : null;
        }

        // Handle widget_config (store as JSON)
        // widget_config is already decoded in PageRequest::prepareForValidation()
        $widgetConfig = $validated['widget_config'] ?? null;
        $validated['widget_config'] = $widgetConfig ? json_encode($widgetConfig) : null;

        // Validate that only active pages can be set as homepage
        // (The boot method will automatically unset other homepages)
        // Ensure home_page is always boolean
        $validated['home_page'] = isset($validated['home_page']) ? (bool)$validated['home_page'] : false;
        
        // Prevent deactivating if homepage is set
        if ($validated['home_page'] === true && isset($validated['is_active']) && $validated['is_active'] === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Homepage pages cannot be deactivated. Please unset homepage first.');
        }
        
        if ($validated['home_page'] === true) {
            if (!($validated['is_active'] ?? false)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Only active pages can be set as homepage.');
            }
        }

        $page = Page::create($validated);

        // Handle widget components for showcase pages
        if ($page->isShowcase() && $widgetConfig && is_array($widgetConfig)) {
            $this->attachWidgetComponents($page, $widgetConfig);
        }

        // Clear cache for this page
        Cache::forget("page.{$page->id}");
        Cache::forget("page.slug.{$page->slug}");
        if ($page->isHomepage()) {
            Cache::forget('page.homepage');
        }

        // Log activity
        $this->logCreate($page);

        return redirect()->route('admin.content.page.index')
            ->with('success', 'Page created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page): View
    {
        return view('admin.content.page.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page): View
    {
        $page->load(['marketingPersona', 'contentType', 'tailwindPlusComponents']);

        $marketingPersonas = MarketingPersona::active()
            ->ordered()
            ->get();

        $contentTypes = ContentType::active()
            ->ordered()
            ->get();

        $components = $this->componentService->getComponentsStructureFromDatabase();

        // Get Header and Footer blocks from service (mega-menu and footer-links compatible)
        $headerBlocks = $this->getHeaderBlocks();
        $footerBlocks = $this->getFooterBlocks();

        return view('admin.content.page.edit', compact(
            'page',
            'marketingPersonas',
            'contentTypes',
            'components',
            'headerBlocks',
            'footerBlocks'
        ));
    }

    /**
     * Get Header blocks from TailwindPlusComponentService (mega-menu compatible).
     */
    private function getHeaderBlocks(): array
    {
        return $this->componentService->getHeaderComponents();
    }

    /**
     * Get Footer blocks from TailwindPlusComponentService (footer-links compatible).
     */
    private function getFooterBlocks(): array
    {
        return $this->componentService->getFooterComponents();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageRequest $request, Page $page)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

        // Handle image deletion
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            // Delete old image from storage if exists
            if ($page->image) {
                \Storage::disk('public')->delete($page->image);
            }
            // Set image to null in database
            $validated['image'] = null;
        }
        // Handle image upload
        elseif ($request->hasFile('image')) {
            // Delete old image
            if ($page->image) {
                \Storage::disk('public')->delete($page->image);
            }

            $imagePath = $request->file('image')->store('pages', 'public');
            $validated['image'] = $imagePath;
        }

        // Handle secondary keywords array
        if (isset($validated['secondary_keywords'])) {
            $validated['secondary_keywords'] = array_filter(array_map('trim', $validated['secondary_keywords']));
            $validated['secondary_keywords'] = ! empty($validated['secondary_keywords']) ? $validated['secondary_keywords'] : null;
        }

        // Handle widget_config (store as JSON)
        // widget_config is already decoded in PageRequest::prepareForValidation()
        $widgetConfig = $validated['widget_config'] ?? null;
        $validated['widget_config'] = $widgetConfig ? json_encode($widgetConfig) : null;

        // Validate that only active pages can be set as homepage
        // (The boot method will automatically unset other homepages)
        // Ensure home_page is always boolean
        $validated['home_page'] = isset($validated['home_page']) ? (bool)$validated['home_page'] : ($page->home_page ?? false);
        
        // Prevent deactivating if homepage is set
        if ($validated['home_page'] === true && isset($validated['is_active']) && $validated['is_active'] === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Homepage pages cannot be deactivated. Please unset homepage first.');
        }
        
        if ($validated['home_page'] === true) {
            if (!($validated['is_active'] ?? $page->is_active)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Only active pages can be set as homepage.');
            }
        }

        $page->update($validated);

        // Handle widget components for showcase pages
        if ($page->isShowcase()) {
            // Remove existing widget components
            $page->tailwindPlusComponents()->detach();

            // Attach new widget components if provided
            if ($widgetConfig && is_array($widgetConfig)) {
                $this->attachWidgetComponents($page, $widgetConfig);
            }
        }

        // Clear cache for this page
        Cache::forget("page.{$page->id}");
        Cache::forget("page.slug.{$page->slug}");
        if ($page->isHomepage()) {
            Cache::forget('page.homepage');
        }

        // Log activity
        $this->logUpdate($page);

        return redirect()->route('admin.content.page.index')
            ->with('success', 'Page updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        // Log activity before deletion
        $this->logDelete($page);

        // Clear homepage flag if this is the homepage
        if ($page->isHomepage()) {
            $page->update(['home_page' => false]);
        }

        // Delete image if exists
        if ($page->image) {
            \Storage::disk('public')->delete($page->image);
        }

        $page->delete();

        return redirect()->route('admin.content.page.index')
            ->with('success', 'Page deleted successfully!');
    }

    /**
     * Toggle page active status
     */
    public function toggleActive(Page $page)
    {
        // Prevent deactivating if it's the homepage
        if ($page->isHomepage() && $page->is_active) {
            return response()->json([
                'success' => false,
                'error' => 'Cannot deactivate the homepage page. Please set another page as homepage first.',
            ], 422);
        }

        $page->update(['is_active' => ! $page->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $page->is_active,
            'message' => $page->is_active ? 'Page activated successfully!' : 'Page deactivated successfully!',
        ]);
    }

    /**
     * Set a page as homepage
     */
    public function setAsHomepage(Page $page): JsonResponse|RedirectResponse
    {
        try {
            // Ensure page is active
            if (!$page->is_active) {
                if (request()->expectsJson() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Only active pages can be set as homepage.',
                    ], 422);
                }

                return redirect()->route('admin.content.page.index')
                    ->with('error', 'Only active pages can be set as homepage.');
            }

            // Set as homepage directly - boot method will handle unsetting others
            $page->home_page = true;
            $page->save();

            // Refresh the page to get updated data
            $page->refresh();

            // Log activity
            try {
                $this->logUpdate($page);
            } catch (\Exception $logError) {
                // Don't fail the request if logging fails
                \Log::warning('Failed to log homepage update: ' . $logError->getMessage());
            }

            if (request()->expectsJson() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Page set as homepage successfully!',
                ]);
            }

            return redirect()->route('admin.content.page.index')
                ->with('success', 'Page set as homepage successfully!');
        } catch (\Exception $e) {
            \Log::error('Error setting homepage: ' . $e->getMessage(), [
                'page_id' => $page->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'An error occurred while setting the homepage: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->route('admin.content.page.index')
                ->with('error', 'An error occurred while setting the homepage.');
        }
    }

    /**
     * Remove homepage status from a page
     */
    public function removeHomepage(Page $page): JsonResponse|RedirectResponse
    {
        try {
            if (!$page->isHomepage()) {
                if (request()->expectsJson() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'This page is not set as homepage.',
                    ], 422);
                }

                return redirect()->route('admin.content.page.index')
                    ->with('error', 'This page is not set as homepage.');
            }

            // Remove homepage status directly
            $page->home_page = false;
            $page->save();

            // Refresh the page to get updated data
            $page->refresh();

            // Log activity
            try {
                $this->logUpdate($page);
            } catch (\Exception $logError) {
                // Don't fail the request if logging fails
                \Log::warning('Failed to log homepage removal: ' . $logError->getMessage());
            }

            if (request()->expectsJson() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Homepage status removed successfully!',
                ]);
            }

            return redirect()->route('admin.content.page.index')
                ->with('success', 'Homepage status removed successfully!');
        } catch (\Exception $e) {
            \Log::error('Error removing homepage: ' . $e->getMessage(), [
                'page_id' => $page->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'An error occurred while removing the homepage: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->route('admin.content.page.index')
                ->with('error', 'An error occurred while removing the homepage.');
        }
    }

    /**
     * Show component management interface
     */
    public function manageComponents(Page $page): View
    {
        // Only allow component management for showcase pages
        if (! $page->isShowcase()) {
            abort(404, 'Component management is only available for showcase pages.');
        }

        $page->load('tailwindPlusComponents');
        $availableComponents = TailwindPlus::active()
            ->orderBy('category')
            ->orderBy('component_group')
            ->orderBy('component_name')
            ->get();

        // Get components already attached to this page
        $attachedComponentIds = $page->tailwindPlusComponents->pluck('id')->toArray();

        return view('admin.content.page.components', compact('page', 'availableComponents', 'attachedComponentIds'));
    }

    /**
     * Add component to page
     */
    public function addComponent(Request $request, Page $page): JsonResponse|RedirectResponse
    {
        // Only allow for showcase pages
        if (! $page->isShowcase()) {
            return response()->json(['error' => 'Component management is only available for showcase pages.'], 403);
        }

        $request->validate([
            'tailwind_plus_id' => 'required|exists:tailwind_plus,id',
        ]);

        $component = TailwindPlus::findOrFail($request->tailwind_plus_id);

        // Check if component is already attached
        if ($page->tailwindPlusComponents()->where('tailwind_plus_id', $component->id)->exists()) {
            return response()->json(['error' => 'Component is already attached to this page.'], 422);
        }

        // Get the highest sort_order and add 1
        $maxSortOrder = $page->tailwindPlusComponents()->max('sort_order') ?? 0;

        // Attach component with pivot data
        $page->tailwindPlusComponents()->attach($component->id, [
            'sort_order' => $maxSortOrder + 1,
            'is_active' => true,
            'custom_config' => null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Component added successfully!',
            ]);
        }

        return redirect()->route('admin.content.page.components', $page)
            ->with('success', 'Component added successfully!');
    }

    /**
     * Remove component from page
     */
    public function removeComponent(Page $page, TailwindPlus $component): JsonResponse|RedirectResponse
    {
        // Only allow for showcase pages
        if (! $page->isShowcase()) {
            return response()->json(['error' => 'Component management is only available for showcase pages.'], 403);
        }

        $page->tailwindPlusComponents()->detach($component->id);

        // Reorder remaining components
        $this->reorderComponents($page);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Component removed successfully!',
            ]);
        }

        return redirect()->route('admin.content.page.components', $page)
            ->with('success', 'Component removed successfully!');
    }

    /**
     * Update component order
     */
    public function updateComponentOrder(Request $request, Page $page): JsonResponse
    {
        // Only allow for showcase pages
        if (! $page->isShowcase()) {
            return response()->json(['error' => 'Component management is only available for showcase pages.'], 403);
        }

        $request->validate([
            'components' => 'required|array',
            'components.*.id' => 'required|exists:tailwind_plus,id',
            'components.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->components as $componentData) {
            $page->tailwindPlusComponents()->updateExistingPivot($componentData['id'], [
                'sort_order' => $componentData['sort_order'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Component order updated successfully!',
        ]);
    }

    /**
     * Update component configuration
     */
    public function updateComponentConfig(Request $request, Page $page, TailwindPlus $component): JsonResponse
    {
        // Only allow for showcase pages
        if (! $page->isShowcase()) {
            return response()->json(['error' => 'Component management is only available for showcase pages.'], 403);
        }

        $request->validate([
            'is_active' => 'sometimes|boolean',
            'custom_config' => 'sometimes|array',
        ]);

        $pivotData = [];
        if ($request->has('is_active')) {
            $pivotData['is_active'] = $request->is_active;
        }
        if ($request->has('custom_config')) {
            // Encode array to JSON for database storage
            $pivotData['custom_config'] = is_array($request->custom_config)
                ? json_encode($request->custom_config)
                : $request->custom_config;
        }

        if (! empty($pivotData)) {
            $page->tailwindPlusComponents()->updateExistingPivot($component->id, $pivotData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Component configuration updated successfully!',
        ]);
    }

    /**
     * Get component HTML from database.
     */
    public function getComponent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'path' => 'required|string',
        ]);

        $component = $this->componentService->getComponentByPath($validated['path']);

        if (! $component || ! $component->code) {
            return response()->json([
                'html' => null,
                'error' => 'Component not found',
            ], 404);
        }

        return response()->json([
            'html' => $component->code,
        ]);
    }

    /**
     * Upload image for inline editing.
     */
    public function uploadImageForEditing(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
        ]);

        try {
            $imagePath = $this->uploadImage($request->file('image'), 'widget-images');
            $imageUrl = asset('storage/'.$imagePath);

            return response()->json([
                'success' => true,
                'url' => $imageUrl,
                'path' => $imagePath,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Attach widget components to page from widget_config.
     */
    protected function attachWidgetComponents(Page $page, array $widgetConfig): void
    {
        foreach ($widgetConfig as $index => $block) {
            // Skip blocks without id (custom blocks without database component)
            if (! isset($block['id']) || $block['id'] === null) {
                continue;
            }

            if (! isset($block['path'])) {
                continue;
            }

            $componentId = $block['id'];
            $component = null;

            // Try to find by ID if it's a valid numeric ID
            if (is_numeric($componentId)) {
                $component = TailwindPlus::find($componentId);
            }

            // If not found by ID (or ID was a generated string), try to find by path
            if (! $component && isset($block['path'])) {
                $component = $this->componentService->getComponentByPath($block['path']);
            }

            if (! $component) {
                continue;
            }

            // Prepare custom_config - encode to JSON if array
            $customConfig = null;
            if (isset($block['html'])) {
                $customConfig = json_encode(['html' => $block['html']]);
            }

            // Get position from block, fallback to index
            $sortOrder = $block['position'] ?? $index;

            // Attach component with pivot data
            // Note: detach() is called before this function, so all components are new
            $page->tailwindPlusComponents()->attach($component->id, [
                'sort_order' => $sortOrder,
                'is_active' => true,
                'custom_config' => $customConfig,
            ]);
        }
    }

    /**
     * Reorder components to ensure sequential sort_order
     */
    protected function reorderComponents(Page $page): void
    {
        $components = $page->tailwindPlusComponents()->orderByPivot('sort_order')->get();

        foreach ($components as $index => $component) {
            $page->tailwindPlusComponents()->updateExistingPivot($component->id, [
                'sort_order' => $index + 1,
            ]);
        }
    }

    /**
     * Get presets by type (header/body) via AJAX
     */
    public function getPresets(Request $request): JsonResponse
    {
        $query = PageBlockPreset::active()->orderBy('name');

        // Optional type filter
        if ($request->has('type') && in_array($request->type, ['header', 'body'])) {
            $query->where('type', $request->type);
        }

        $presets = $query->get(['id', 'name', 'description', 'type', 'blocks']);

        return response()->json([
            'success' => true,
            'presets' => $presets,
        ]);
    }

    /**
     * Save current blocks as preset
     */
    public function savePreset(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:header,body',
            'blocks' => 'required|array',
        ]);

        $preset = PageBlockPreset::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'blocks' => $validated['blocks'],
            'is_active' => true,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Preset saved successfully!',
            'preset' => $preset,
        ]);
    }

    /**
     * Load preset data via AJAX
     */
    public function loadPreset(PageBlockPreset $preset): JsonResponse
    {
        return response()->json([
            'success' => true,
            'preset' => [
                'id' => $preset->id,
                'name' => $preset->name,
                'description' => $preset->description,
                'type' => $preset->type,
                'blocks' => $preset->blocks,
            ],
        ]);
    }

    /**
     * Delete preset
     */
    public function deletePreset(PageBlockPreset $preset): JsonResponse
    {
        $preset->delete();

        return response()->json([
            'success' => true,
            'message' => 'Preset deleted successfully!',
        ]);
    }

    /**
     * Fix HTML code using AI (Groq primary, Gemini fallback)
     */
    public function fixWithAI(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'html' => 'required|string',
            'prompt' => 'required|string|max:1000',
        ]);

        $systemPrompt = "You are an expert HTML/TailwindCSS developer. The user will provide HTML code and a request to modify or fix it. 
Your task is to:
1. Analyze the provided HTML code
2. Apply the requested changes/fixes
3. Return ONLY the fixed/modified HTML code, no explanations
4. Preserve the existing structure and TailwindCSS classes unless specifically asked to change them
5. Ensure the output is valid HTML

Important: Return ONLY the HTML code, nothing else. No markdown code blocks, no explanations.";

        $userMessage = "HTML Code:\n```html\n{$validated['html']}\n```\n\nRequest: {$validated['prompt']}";

        // Try Groq first: use Admin AI Settings (Settings → AI) then fall back to .env
        $groqApiKey = AIServiceSetting::getApiKey('groq') ?? config('services.groq.api_key');
        if (!empty($groqApiKey)) {
            $groqModel = AIServiceSetting::getModel('groq', 'llama-3.3-70b-versatile') ?? config('services.groq.model', 'llama-3.3-70b-versatile');
            $result = $this->callGroqAI($groqApiKey, $systemPrompt, $userMessage, $groqModel);
            if ($result['success']) {
                return response()->json($result);
            }
            \Log::warning('Groq API failed, falling back to Gemini', ['error' => $result['error'] ?? 'Unknown']);
        }

        // Fallback to Gemini: use Admin AI Settings then .env
        $geminiApiKey = AIServiceSetting::getApiKey('gemini') ?? config('services.gemini.api_key');
        if (!empty($geminiApiKey)) {
            $geminiModel = AIServiceSetting::getModel('gemini', 'gemini-2.0-flash') ?? config('services.gemini.model', 'gemini-2.0-flash');
            $result = $this->callGeminiAI($geminiApiKey, $systemPrompt, $userMessage, $geminiModel);
            if ($result['success']) {
                return response()->json($result);
            }
            return response()->json($result, 500);
        }

        return response()->json([
            'success' => false,
            'error' => 'No AI service configured. Please configure Groq or Gemini in Admin → Settings → AI Settings, or add GROQ_API_KEY / GEMINI_API_KEY to your .env file.',
        ], 500);
    }

    /**
     * Call Groq AI API
     */
    private function callGroqAI(string $apiKey, string $systemPrompt, string $userMessage, ?string $model = null): array
    {
        try {
            $model = $model ?? config('services.groq.model', 'llama-3.3-70b-versatile');

            $payload = [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => 0.3,
                'max_tokens' => 8192,
            ];

            $response = \Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$apiKey}",
            ])->timeout(60)->post('https://api.groq.com/openai/v1/chat/completions', $payload);

            if ($response->failed()) {
                \Log::error('Groq API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                if ($response->status() === 429) {
                    return ['success' => false, 'error' => 'API rate limit exceeded. Please wait a moment and try again.'];
                }
                
                return ['success' => false, 'error' => 'Groq API request failed'];
            }

            $data = $response->json();
            $fixedHtml = $data['choices'][0]['message']['content'] ?? null;

            if (!$fixedHtml) {
                return ['success' => false, 'error' => 'No response from Groq AI'];
            }

            // Clean up the response
            $fixedHtml = $this->cleanAIResponse($fixedHtml);

            return ['success' => true, 'html' => $fixedHtml];

        } catch (\Exception $e) {
            \Log::error('Groq AI Error', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Call Gemini AI API
     */
    private function callGeminiAI(string $apiKey, string $systemPrompt, string $userMessage, ?string $model = null): array
    {
        try {
            $model = $model ?? config('services.gemini.model', 'gemini-2.0-flash');

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\n\n" . $userMessage]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'maxOutputTokens' => 8192,
                ]
            ];

            $response = \Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(60)->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", $payload);

            if ($response->failed()) {
                \Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                if ($response->status() === 429) {
                    return ['success' => false, 'error' => 'API rate limit exceeded. Please wait a moment and try again.'];
                }
                
                return ['success' => false, 'error' => 'Gemini API request failed'];
            }

            $data = $response->json();
            $fixedHtml = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$fixedHtml) {
                return ['success' => false, 'error' => 'No response from Gemini AI'];
            }

            // Clean up the response
            $fixedHtml = $this->cleanAIResponse($fixedHtml);

            return ['success' => true, 'html' => $fixedHtml];

        } catch (\Exception $e) {
            \Log::error('Gemini AI Error', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Clean AI response - remove markdown code blocks
     */
    private function cleanAIResponse(string $html): string
    {
        // Remove markdown code blocks if present
        $html = preg_replace('/^```html?\s*/i', '', $html);
        $html = preg_replace('/\s*```$/i', '', $html);
        $html = preg_replace('/```/i', '', $html); // Remove any remaining backticks
        return trim($html);
    }
}
