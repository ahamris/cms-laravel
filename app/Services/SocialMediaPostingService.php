<?php

namespace App\Services;

use App\Models\SocialMediaPlatform;
use App\Models\SocialMediaPost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SocialMediaPostingService
{
    /**
     * Create a social media post for a given content item
     */
    public function createPost(
        Model $postable,
        SocialMediaPlatform $platform,
        string $content,
        array $hashtags = [],
        array $mediaUrls = [],
        Carbon $scheduledAt = null
    ): SocialMediaPost {
        return SocialMediaPost::create([
            'social_media_platform_id' => $platform->id,
            'postable_type' => get_class($postable),
            'postable_id' => $postable->id,
            'content' => $content,
            'hashtags' => $hashtags,
            'media_urls' => $mediaUrls,
            'status' => $scheduledAt ? 'scheduled' : 'draft',
            'scheduled_at' => $scheduledAt,
        ]);
    }

    /**
     * Generate content for any postable model
     */
    private function generatePostContent(Model $postable, SocialMediaPlatform $platform): string
    {
        $characterLimit = $platform->settings['character_limit'] ?? 5000;

        // Start with title
        $content = $postable->title;

        // Add description/excerpt based on model type
        $excerpt = $this->getModelExcerpt($postable);

        // Check if we can fit the excerpt
        if ($excerpt && strlen($content . ' - ' . $excerpt) <= $characterLimit - 50) { // Leave room for hashtags
            $content .= ' - ' . $excerpt;
        }

        return $content;
    }

    /**
     * Get appropriate excerpt based on model type
     */
    private function getModelExcerpt(Model $model): string
    {
        // Try different excerpt fields based on model
        if (isset($model->excerpt) && $model->excerpt) {
            return Str::limit($model->excerpt, 100);
        }

        if (isset($model->description) && $model->description) {
            return Str::limit($model->description, 100);
        }

        if (isset($model->short_body) && $model->short_body) {
            return Str::limit($model->short_body, 100);
        }

        return '';
    }

    /**
     * Generate content for blog posts (legal method for backward compatibility)
     */
    private function generateBlogPostContent(Model $blog, SocialMediaPlatform $platform): string
    {
        return $this->generatePostContent($blog, $platform);
    }

    /**
     * Generate default content for a blog post
     */
    public function generateDefaultBlogPostContent(Model $blog, SocialMediaPlatform $platform): string
    {
        $baseContent = $blog->title;

        if ($blog->short_body) {
            $baseContent .= "\n\n" . substr($blog->short_body, 0, 200);
            if (strlen($blog->short_body) > 200) {
                $baseContent .= '...';
            }
        }

        // Add blog URL
        if (method_exists($blog, 'getLinkUrlAttribute')) {
            $baseContent .= "\n\n" . $blog->link_url;
        }

        // Platform-specific adjustments
        switch ($platform->slug) {
            case 'twitter':
                // Twitter has character limits
                if (strlen($baseContent) > 240) {
                    $baseContent = substr($baseContent, 0, 237) . '...';
                }
                break;
            case 'linkedin':
                // LinkedIn allows longer posts
                $baseContent .= "\n\n#OpenPublicatie #Accounting #Business";
                break;
            case 'facebook':
                // Facebook allows longer posts with more context
                $baseContent .= "\n\nRead more on our blog!";
                break;
        }

        return $baseContent;
    }

    /**
     * Generate hashtags for any postable model
     */
    private function generateHashtags(Model $postable, SocialMediaPlatform $platform): array
    {
        $hashtags = [];
        $maxHashtags = $platform->settings['optimal_hashtags'] ?? 5;

        // Add model-specific hashtags
        $hashtags = array_merge($hashtags, $this->getModelSpecificHashtags($postable));

        // Add marketing persona hashtag if available
        if (isset($postable->marketingPersona) && $postable->marketingPersona) {
            $hashtags[] = '#' . Str::camel($postable->marketingPersona->name);
        }

        // Add generic hashtags
        $genericHashtags = ['#OpenPublicatie', '#Business', '#Productivity'];
        foreach ($genericHashtags as $tag) {
            if (count($hashtags) < $maxHashtags) {
                $hashtags[] = $tag;
                    break;
            }
        }

        // Platform-specific hashtags
        switch ($platform->slug) {
            case 'twitter':
                $hashtags[] = '#Accounting';
                $hashtags[] = '#FinTech';
                break;
            case 'linkedin':
                $hashtags[] = '#Accounting';
                $hashtags[] = '#BusinessSoftware';
                $hashtags[] = '#Productivity';
                break;
            case 'facebook':
                // Facebook uses fewer hashtags
                $hashtags = array_slice($hashtags, 0, 3);
                break;
        }

        return array_unique($hashtags);
    }

    /**
     * Get model-specific hashtags
     */
    private function getModelSpecificHashtags(Model $model): array
    {
        $hashtags = [];
        $modelClass = get_class($model);

        switch ($modelClass) {
            case 'App\Models\Blog':
                if (isset($model->blog_category) && $model->blog_category) {
                    $hashtags[] = '#' . Str::camel($model->blog_category->name);
                }
                $hashtags[] = '#Blog';
                break;

            case 'App\Models\Changelog':
                $hashtags[] = '#Changelog';
                $hashtags[] = '#Updates';
                if (isset($model->status)) {
                    $hashtags[] = '#' . ucfirst($model->status);
                }
                break;

            case 'App\Models\Feature':
                $hashtags[] = '#Features';
                $hashtags[] = '#ProductUpdate';
                break;

            case 'App\Models\Module':
                $hashtags[] = '#Modules';
                $hashtags[] = '#Development';
                break;

            case 'App\Models\Page':
                $hashtags[] = '#Information';
                break;
        }

        return $hashtags;
    }

    /**
     * Generate hashtags for blog posts (legal method for backward compatibility)
     */
    private function generateBlogHashtags(Model $blog, SocialMediaPlatform $platform): array
    {
        return $this->generateHashtags($blog, $platform);
    }

    /**
     * Post immediately to social media platform
     */
    public function postNow(SocialMediaPost $post): bool
    {
        try {
            $platform = $post->socialMediaPlatform;

            if (!$platform->isConfigured()) {
                $post->markAsFailed('Platform not configured with API credentials');
                return false;
            }

            // Here you would integrate with actual social media APIs
            // For now, we'll simulate the posting
            $success = $this->simulatePosting($post);

            if ($success) {
                $post->markAsPosted(
                    'sim_' . uniqid(), // Simulated external post ID
                    'https://example.com/post/' . uniqid(), // Simulated post URL
                    ['simulated' => true, 'posted_at' => now()->toISOString()]
                );

                Log::info('Social media post successful', [
                    'post_id' => $post->id,
                    'platform' => $platform->name,
                    'content_type' => $post->postable_type,
                    'content_id' => $post->postable_id,
                ]);

                return true;
            } else {
                $post->markAsFailed('Simulated posting failed');
                return false;
            }

        } catch (\Exception $e) {
            $post->markAsFailed($e->getMessage());

            return false;
        }
    }

    /**
     * Schedule a post for later
     */
    public function schedulePost(SocialMediaPost $post, Carbon $scheduledAt): bool
    {
        $post->update([
            'status' => 'scheduled',
            'scheduled_at' => $scheduledAt,
        ]);

        Log::info('Social media post scheduled', [
            'post_id' => $post->id,
            'platform' => $post->socialMediaPlatform->name,
            'scheduled_at' => $scheduledAt->toISOString(),
        ]);

        return true;
    }

    /**
     * Process all due scheduled posts
     */
    public function processDuePosts(): int
    {
        $duePosts = SocialMediaPost::due()->get();
        $processed = 0;

        foreach ($duePosts as $post) {
            if ($this->postNow($post)) {
                $processed++;
            }
        }

        Log::info('Processed due social media posts', [
            'total_due' => $duePosts->count(),
            'successfully_processed' => $processed,
        ]);

        return $processed;
    }

    /**
     * Get posting statistics for a content item
     */
    public function getPostingStats(Model $postable): array
    {
        $posts = $postable->socialMediaPosts;

        return [
            'total' => $posts->count(),
            'posted' => $posts->where('status', 'posted')->count(),
            'scheduled' => $posts->where('status', 'scheduled')->count(),
            'failed' => $posts->where('status', 'failed')->count(),
            'draft' => $posts->where('status', 'draft')->count(),
        ];
    }

    /**
     * Simulate posting to social media (for demo purposes)
     * In production, this would be replaced with actual API calls
     */
    private function simulatePosting(SocialMediaPost $post): bool
    {
        // Simulate a 90% success rate
        return rand(1, 10) <= 9;
    }

    /**
     * Get available platforms for posting
     */
    public function getAvailablePlatforms(): \Illuminate\Database\Eloquent\Collection
    {
        return SocialMediaPlatform::active()->ordered()->get();
    }

    /**
     * Quick post to multiple platforms
     */
    public function quickPostToMultiplePlatforms(
        Model $postable,
        array $platformIds,
        Carbon $scheduledAt = null
    ): array {
        $results = [];
        $platforms = SocialMediaPlatform::whereIn('id', $platformIds)->get();

        foreach ($platforms as $platform) {
            try {
                $content = $this->generatePostContent($postable, $platform);
                $hashtags = $this->generateHashtags($postable, $platform);
                $mediaUrls = [];

                // Add blog image if available
                if (isset($postable->image) && $postable->image) {
                    $mediaUrls[] = asset('storage/' . $postable->image);
                }

                $post = $this->createPost(
                    $postable,
                    $platform,
                    $content,
                    $hashtags,
                    $mediaUrls,
                    $scheduledAt
                );

                if (!$scheduledAt) {
                    $success = $this->postNow($post);
                    $results[$platform->id] = [
                        'platform' => $platform->name,
                        'success' => $success,
                        'post_id' => $post->id,
                        'status' => $post->status,
                    ];
                } else {
                    $results[$platform->id] = [
                        'platform' => $platform->name,
                        'success' => true,
                        'post_id' => $post->id,
                        'status' => 'scheduled',
                    ];
                }

            } catch (\Exception $e) {
                $results[$platform->id] = [
                    'platform' => $platform->name,
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
