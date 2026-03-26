<?php

namespace App\Services;

use App\Jobs\PublishScheduledContentJob;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\ContentPlanItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExecutionEngine extends AIService
{
    protected SocialMediaPostingService $socialMediaService;

    public function __construct(SocialMediaPostingService $socialMediaService)
    {
        $this->socialMediaService = $socialMediaService;
    }

    /**
     * Generate blog post content from plan item
     */
    public function generateBlogContent(ContentPlanItem $item): ?Blog
    {
        $item->update(['status' => 'generating']);

        try {
            $contentData = $item->content_data ?? [];
            $title = $contentData['title'] ?? 'Untitled Article';
            $keywords = $contentData['keywords'] ?? [];
            $brief = $contentData['brief'] ?? '';

            // Generate content using AI
            $content = $this->generateBlogPostContent($title, $brief, $keywords, $item->contentPlan->intentBrief);

            // Get or create blog category
            $category = BlogCategory::first();
            if (! $category) {
                $category = BlogCategory::create([
                    'name' => 'General',
                    'slug' => 'general',
                    'is_active' => true,
                ]);
            }

            // Create blog post
            $blog = Blog::create([
                'blog_category_id' => $category->id,
                'author_id' => Auth::id() ?? 1,
                'title' => $content['title'],
                'short_body' => $content['excerpt'],
                'long_body' => $content['body'],
                'primary_keyword' => $keywords['primary'] ?? null,
                'secondary_keywords' => $keywords['secondary'] ?? [],
                'is_active' => $item->contentPlan->isFullAutopilot() || $item->contentPlan->isGuided(),
                'content_plan_id' => $item->content_plan_id,
                'autopilot_mode' => $item->contentPlan->autopilot_mode,
            ]);

            // Update plan item
            $item->update([
                'status' => 'draft',
                'related_content_id' => $blog->id,
                'related_content_type' => Blog::class,
            ]);

            // Schedule if needed
            if ($item->scheduled_at && $item->scheduled_at->isFuture()) {
                PublishScheduledContentJob::dispatch($blog)
                    ->delay($item->scheduled_at);
            }

            // Generate social media posts if this is a pillar or supporting article
            if (in_array($item->item_type, ['pillar', 'supporting'])) {
                $this->generateSocialPosts($blog, $item);
            }

            return $blog;

        } catch (\Exception $e) {
            Log::error('Execution Engine Error - Blog Generation', [
                'item_id' => $item->id,
                'error' => $e->getMessage(),
            ]);
            $item->update(['status' => 'failed']);

            return null;
        }
    }

    /**
     * Generate blog post content using AI
     */
    protected function generateBlogPostContent(string $title, string $brief, array $keywords, $intentBrief): array
    {
        $contentGen = app(ContentGenerationService::class);
        $structured = $contentGen->generatePlanBlog($title, $brief, $keywords, $intentBrief);
        if ($structured['success'] && isset($structured['data'])) {
            $d = $structured['data'];

            return [
                'title' => $d['title'] ?? $title,
                'excerpt' => $d['excerpt'] ?? $brief,
                'body' => $d['body'] ?? '',
            ];
        }

        $systemPrompt = "You are an expert content writer specializing in SEO-optimized blog posts. 
Your task is to write a comprehensive, engaging blog post based on the provided title and brief.

Requirements:
1. Write a compelling, well-structured blog post
2. Use SEO best practices (natural keyword usage, proper headings, etc.)
3. Include an engaging introduction and conclusion
4. Use clear headings (H2, H3) to structure the content
5. Write in a {$intentBrief->tone} tone
6. Target audience: {$intentBrief->audience}
7. Primary keyword: ".($keywords['primary'] ?? 'N/A').'
8. Make it comprehensive but readable (1500-2500 words)

Return your response as JSON in this exact format:
{
  "title": "Final optimized title",
  "excerpt": "150-200 word excerpt/summary",
  "body": "Full blog post content in HTML format with proper headings"
}';

        $userMessage = "Title: {$title}\n\nBrief: {$brief}\n\nWrite a comprehensive blog post.";

        $result = $this->callAI($systemPrompt, $userMessage, 0.8, 16384);

        if (! $result['success']) {
            throw new \Exception('Failed to generate content: '.($result['error'] ?? 'Unknown error'));
        }

        // Parse JSON response
        $content = $result['content'];
        $json = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Try to extract JSON
            if (preg_match('/```json\s*(.*?)\s*```/s', $content, $matches)) {
                $json = json_decode($matches[1], true);
            } elseif (preg_match('/\{.*\}/s', $content, $matches)) {
                $json = json_decode($matches[0], true);
            }
        }

        if (json_last_error() !== JSON_ERROR_NONE || ! $json) {
            // Fallback: use title and brief
            return [
                'title' => $title,
                'excerpt' => $brief,
                'body' => "<h2>Introduction</h2><p>{$brief}</p><h2>Main Content</h2><p>Content to be expanded...</p>",
            ];
        }

        return $json;
    }

    /**
     * Generate social media posts from blog content.
     * Only posts to platforms that are active and have API credentials configured.
     */
    protected function generateSocialPosts(Blog $blog, ContentPlanItem $item): void
    {
        try {
            $platforms = $this->socialMediaService->getActiveConfiguredPlatforms();
            if ($platforms->isEmpty()) {
                return;
            }

            $platformIds = $platforms->pluck('id')->toArray();
            $scheduledAt = $item->scheduled_at ? $item->scheduled_at->addHours(2) : null;

            $this->socialMediaService->quickPostToMultiplePlatforms(
                $blog,
                $platformIds,
                $scheduledAt
            );

        } catch (\Exception $e) {
            Log::error('Failed to generate social posts', [
                'blog_id' => $blog->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Publish content based on autopilot mode
     */
    public function publishContent(ContentPlanItem $item): bool
    {
        $plan = $item->contentPlan;

        // Check if approval is needed
        if ($plan->isAssisted() && $plan->status !== 'approved') {
            return false; // Needs approval
        }

        if ($item->status !== 'draft' && $item->status !== 'scheduled') {
            return false;
        }

        try {
            $content = $item->relatedContent;

            if (! $content) {
                // Generate content if not exists
                if (in_array($item->item_type, ['pillar', 'supporting', 'evergreen'])) {
                    $content = $this->generateBlogContent($item);
                }
            }

            if ($content instanceof Blog) {
                $content->update(['is_active' => true]);
                $item->markAsPublished();

                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Failed to publish content', [
                'item_id' => $item->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
