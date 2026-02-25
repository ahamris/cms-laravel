<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogType;
use App\Models\User;
use App\Models\MarketingPersona;
use App\Models\ContentType;
use App\Models\SocialMediaPlatform;
use App\Models\AIServiceSetting;
use App\Services\SocialMediaPostingService;
use App\Services\MarketingIntelligence;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class BlogController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.blog.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $blogCategories = BlogCategory::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $blogTypes = BlogType::orderBy('name')->get();

        $authors = User::query()->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        // Marketing Automation data
        $marketingPersonas = MarketingPersona::active()->ordered()->get();
        $contentTypes = ContentType::active()
            ->where(function($query) {
                $query->whereJsonContains('applicable_models', 'App\Models\Blog')
                      ->orWhereNull('applicable_models');
            })
            ->ordered()
            ->get();

        return view('admin.blog.create', compact(
            'blogCategories',
            'blogTypes',
            'authors',
            'marketingPersonas',
            'contentTypes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        // Sluggable package will handle slug generation/sanitization via setSlugAttribute mutator
        $blog = Blog::create($validated);

        // Log activity
        $this->logCreate($blog);

        // Check if user wants to continue editing
        if ($request->input('action') === 'save_and_stay') {
            return redirect()->route('admin.blog.edit', $blog)
                ->with('success', 'Blog created successfully! You can continue editing.');
        }

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog): View
    {
        $blog->load(['blog_category', 'blog_type', 'author']);

        return view('admin.blog.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog, MarketingIntelligence $intelligence): View
    {
        $blog->load(['blog_category', 'author', 'marketingPersona', 'contentType', 'contentPlan']);

        // Update SEO analysis if not recent
        if (!$blog->seo_score || !isset($blog->seo_analysis['last_analyzed'])) {
            $intelligence->updateSEOAnalysis($blog);
            $blog->refresh();
        }

        $blogCategories = BlogCategory::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $blogTypes = BlogType::orderBy('name')->get();

        $authors = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        // Marketing Automation data
        $marketingPersonas = MarketingPersona::active()->ordered()->get();
        $contentTypes = ContentType::active()
            ->where(function($query) {
                $query->whereJsonContains('applicable_models', 'App\Models\Blog')
                      ->orWhereNull('applicable_models');
            })
            ->ordered()
            ->get();

        // Get SEO recommendations
        $seoRecommendations = $intelligence->getOptimizationRecommendations($blog);
        $internalLinkSuggestions = $intelligence->getInternalLinkSuggestions($blog);

        return view('admin.blog.edit', compact(
            'blog',
            'blogCategories',
            'blogTypes',
            'authors',
            'marketingPersonas', 
            'contentTypes',
            'seoRecommendations',
            'internalLinkSuggestions'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, Blog $blog)
    {
        $validated = $request->validated();
        $validated = $this->purifyHtmlKeys($validated, ['short_body', 'long_body']);

        // Handle image deletion
        if ($request->has('remove_image') && $request->input('remove_image') == '1') {
            // Delete old image from storage if exists
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            // Set image to null in database
            $validated['image'] = null;
        }
        // Handle image upload
        elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        $blog->update($validated);

        // Log activity
        $this->logUpdate($blog);

        // Update SEO analysis after every update so score and recommendations reflect meta_title, meta_description, etc.
        $intelligence = app(MarketingIntelligence::class);
        $intelligence->updateSEOAnalysis($blog);

        // Check if user wants to continue editing
        if ($request->input('action') === 'save_and_stay') {
            return redirect()->route('admin.blog.edit', $blog)
                ->with('success', 'Blog updated successfully! You can continue editing.');
        }

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog updated successfully!');
    }

    /**
     * Analyze SEO for a blog
     */
    public function analyzeSEO(Blog $blog, MarketingIntelligence $intelligence): JsonResponse
    {
        $intelligence->updateSEOAnalysis($blog);
        $blog->refresh();

        return response()->json([
            'success' => true,
            'seo_score' => $blog->seo_score,
            'seo_status' => $blog->seo_status,
            'analysis' => $blog->seo_analysis,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        // Log activity before deletion
        $this->logDelete($blog);

        // Delete image if exists
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog deleted successfully!');
    }

    /**
     * Toggle blog active status
     */
    public function toggleActive(Blog $blog)
    {
        $oldStatus = $blog->is_active ? 'active' : 'inactive';
        $blog->update(['is_active' => ! $blog->is_active]);
        $newStatus = $blog->is_active ? 'active' : 'inactive';

        // Log status change
        $this->logStatusChange($blog, $oldStatus, $newStatus);

        return response()->json([
            'success' => true,
            'is_active' => $blog->is_active,
            'message' => $blog->is_active ? 'Blog activated successfully!' : 'Blog deactivated successfully!',
        ]);
    }

    /**
     * Toggle blog featured status
     */
    public function toggleFeatured(Blog $blog)
    {
        $oldStatus = $blog->is_featured ? 'featured' : 'not featured';
        $blog->update(['is_featured' => ! $blog->is_featured]);
        $newStatus = $blog->is_featured ? 'featured' : 'not featured';

        // Log status change
        $this->logStatusChange($blog, $oldStatus, $newStatus);

        return response()->json([
            'success' => true,
            'is_featured' => $blog->is_featured,
            'message' => $blog->is_featured ? 'Blog featured successfully!' : 'Blog unfeatured successfully!',
        ]);
    }

    /**
     * Create social media posts for a blog.
     * Use post_to_all=true to post to all active platforms with API credentials configured.
     */
    public function createSocialMediaPost(Request $request, Blog $blog, SocialMediaPostingService $socialMediaService)
    {
        $request->validate([
            'platforms' => 'nullable|array',
            'platforms.*' => 'exists:social_media_platforms,id',
            'post_to_all' => 'nullable|boolean',
            'schedule_type' => 'required|in:now,scheduled',
            'scheduled_at' => 'required_if:schedule_type,scheduled|nullable|date|after:now',
            'custom_content' => 'nullable|string|max:5000',
        ]);

        $platformIds = $request->boolean('post_to_all')
            ? $socialMediaService->getActiveConfiguredPlatforms()->pluck('id')->toArray()
            : ($request->input('platforms', []));

        if (empty($platformIds)) {
            return response()->json([
                'success' => false,
                'message' => $request->boolean('post_to_all')
                    ? 'No active platforms with API credentials configured. Add credentials in Settings → Social Media Platforms.'
                    : 'Please select at least one platform.',
            ], 422);
        }

        try {
            $scheduledAt = null;
            if ($request->schedule_type === 'scheduled') {
                $scheduledAt = Carbon::parse($request->scheduled_at);
            }

            $results = $socialMediaService->quickPostToMultiplePlatforms(
                $blog,
                $platformIds,
                $scheduledAt
            );

            $successCount = collect($results)->where('success', true)->count();
            $totalCount = count($results);

            if ($successCount === $totalCount) {
                $message = $request->schedule_type === 'scheduled' 
                    ? "Successfully scheduled posts to {$successCount} platform(s)!"
                    : "Successfully posted to {$successCount} platform(s)!";
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'results' => $results,
                ]);
            } else {
                $message = "Posted to {$successCount} out of {$totalCount} platforms. Check the details below.";
                
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'results' => $results,
                ], 422);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating social media posts: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get social media posts for a blog
     */
    public function socialMediaPosts(Blog $blog)
    {
        $posts = $blog->socialMediaPosts()
            ->with('socialMediaPlatform')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'posts' => $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'platform' => [
                        'name' => $post->socialMediaPlatform->name,
                        'icon' => $post->socialMediaPlatform->icon,
                        'color' => $post->socialMediaPlatform->color,
                    ],
                    'content' => $post->content,
                    'status' => $post->status,
                    'status_badge' => $post->status_badge,
                    'scheduled_at' => $post->formatted_scheduled_at,
                    'posted_at' => $post->formatted_posted_at,
                    'external_post_url' => $post->external_post_url,
                    'error_message' => $post->error_message,
                ];
            }),
        ]);
    }

    /**
     * Generate blog content with AI
     */
    public function generateWithAI(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'topic' => 'required|string|max:500',
            'keywords' => 'nullable|string|max:500',
            'tone' => 'nullable|string|in:professional,casual,expert,persuasive,neutral',
            'length' => 'nullable|string|in:short,medium,long',
        ]);

        $topic = $validated['topic'];
        $keywords = $validated['keywords'] ?? '';
        $tone = $validated['tone'] ?? 'professional';
        $length = $validated['length'] ?? 'medium';

        $lengthGuide = match($length) {
            'short' => '800-1200 words',
            'medium' => '1500-2000 words',
            'long' => '2500-3500 words',
            default => '1500-2000 words',
        };

        $systemPrompt = "You are an expert content writer specializing in SEO-optimized blog posts. 
Your task is to write a comprehensive, engaging blog post based on the provided topic.

Requirements:
1. Write a compelling, well-structured blog post
2. Use SEO best practices (natural keyword usage, proper headings, etc.)
3. Include an engaging introduction and conclusion
4. Use clear headings (H2, H3) to structure the content with proper HTML tags
5. Write in a {$tone} tone
6. Target length: {$lengthGuide}
7. Make it comprehensive but readable
8. Include relevant examples and actionable insights
9. Format the body content as clean HTML (no markdown)

Return your response as valid JSON in this exact format:
{
  \"title\": \"SEO-optimized title (60 characters max)\",
  \"short_body\": \"Engaging excerpt/summary (150 characters max)\",
  \"long_body\": \"Full blog post content in HTML format with proper h2/h3 headings, paragraphs, lists where appropriate\",
  \"meta_title\": \"SEO meta title (60 characters max)\",
  \"meta_description\": \"SEO meta description (160 characters max)\",
  \"meta_keywords\": \"comma, separated, keywords\"
}";

        $userMessage = "Topic: {$topic}";
        if (!empty($keywords)) {
            $userMessage .= "\n\nTarget keywords to include: {$keywords}";
        }
        $userMessage .= "\n\nWrite a comprehensive blog post about this topic.";

        // Try AI services in priority order
        $result = $this->callAI($systemPrompt, $userMessage);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Failed to generate content. Please try again.',
            ], 500);
        }

        // Parse JSON response
        $content = $result['content'];
        $json = $this->parseAIJsonResponse($content);

        if (!$json) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to parse AI response. Please try again.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'title' => $json['title'] ?? '',
                'short_body' => substr($json['short_body'] ?? '', 0, 150),
                'long_body' => $json['long_body'] ?? '',
                'meta_title' => $json['meta_title'] ?? '',
                'meta_description' => $json['meta_description'] ?? '',
                'meta_keywords' => $json['meta_keywords'] ?? '',
            ],
        ]);
    }

    /**
     * Call AI service (Groq or Gemini based on configuration)
     */
    protected function callAI(string $systemPrompt, string $userMessage): array
    {
        // Get active services ordered by priority
        $activeServices = AIServiceSetting::getActiveServices();

        if ($activeServices->isEmpty()) {
            return [
                'success' => false,
                'error' => 'No AI service is configured. Please configure at least one AI service in Admin → Settings → AI Settings.',
            ];
        }

        // Try services in priority order
        foreach ($activeServices as $service) {
            if ($service->service === 'groq') {
                $result = $this->callGroqAI($systemPrompt, $userMessage);
                if ($result['success']) {
                    return $result;
                }
                Log::warning('Groq API failed, trying next service', ['error' => $result['error'] ?? 'Unknown']);
            } elseif ($service->service === 'gemini') {
                $result = $this->callGeminiAI($systemPrompt, $userMessage);
                if ($result['success']) {
                    return $result;
                }
                Log::warning('Gemini API failed, trying next service', ['error' => $result['error'] ?? 'Unknown']);
            }
        }

        return [
            'success' => false,
            'error' => 'All configured AI services failed. Please check your API keys in Admin → Settings → AI Settings.',
        ];
    }

    /**
     * Call Groq AI API
     */
    protected function callGroqAI(string $systemPrompt, string $userMessage): array
    {
        try {
            $apiKey = AIServiceSetting::getApiKey('groq');
            if (empty($apiKey)) {
                return ['success' => false, 'error' => 'Groq API key not configured.'];
            }

            $model = AIServiceSetting::getModel('groq', 'llama-3.3-70b-versatile');
            
            $payload = [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => 0.7,
                'max_tokens' => 16384,
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$apiKey}",
            ])->timeout(120)->post('https://api.groq.com/openai/v1/chat/completions', $payload);

            if ($response->failed()) {
                Log::error('Groq API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                if ($response->status() === 429) {
                    return ['success' => false, 'error' => 'API rate limit exceeded. Please wait a moment and try again.'];
                }
                
                return ['success' => false, 'error' => 'Groq API request failed'];
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;

            if (!$content) {
                return ['success' => false, 'error' => 'No response from Groq AI'];
            }

            return ['success' => true, 'content' => $content];

        } catch (\Exception $e) {
            Log::error('Groq AI Error', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Call Gemini AI API
     */
    protected function callGeminiAI(string $systemPrompt, string $userMessage): array
    {
        try {
            $apiKey = AIServiceSetting::getApiKey('gemini');
            if (empty($apiKey)) {
                return ['success' => false, 'error' => 'Gemini API key not configured.'];
            }

            $model = AIServiceSetting::getModel('gemini', 'gemini-2.0-flash');
            
            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\n\n" . $userMessage]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 16384,
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(120)->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", $payload);

            if ($response->failed()) {
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                if ($response->status() === 429) {
                    return ['success' => false, 'error' => 'API rate limit exceeded. Please wait a moment and try again.'];
                }
                
                return ['success' => false, 'error' => 'Gemini API request failed'];
            }

            $data = $response->json();
            $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$content) {
                return ['success' => false, 'error' => 'No response from Gemini AI'];
            }

            return ['success' => true, 'content' => $content];

        } catch (\Exception $e) {
            Log::error('Gemini AI Error', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Parse AI JSON response
     */
    protected function parseAIJsonResponse(string $content): ?array
    {
        // Clean the response
        $content = trim($content);
        
        // Remove markdown code blocks if present
        $content = preg_replace('/^```json?\s*/i', '', $content);
        $content = preg_replace('/\s*```$/i', '', $content);

        // Try direct JSON parse
        $json = json_decode($content, true);
        if (json_last_error() === JSON_ERROR_NONE && $json) {
            return $json;
        }

        // Try to extract JSON from response
        if (preg_match('/\{[\s\S]*\}/s', $content, $matches)) {
            $json = json_decode($matches[0], true);
            if (json_last_error() === JSON_ERROR_NONE && $json) {
                return $json;
            }
        }

        return null;
    }
}
