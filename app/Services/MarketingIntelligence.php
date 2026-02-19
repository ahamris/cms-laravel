<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\ContentPerformance;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class MarketingIntelligence
{
    /**
     * Parse meta_keywords string (comma-separated) into array of non-empty trimmed keywords.
     */
    protected static function parseMetaKeywords(?string $metaKeywords): array
    {
        if (empty($metaKeywords)) {
            return [];
        }
        $tags = array_map('trim', explode(',', $metaKeywords));

        return array_values(array_filter($tags));
    }

    /**
     * Count internal links in HTML: relative paths (e.g. /blog/slug) or same-domain absolute URLs.
     */
    protected static function countInternalLinks(string $html): int
    {
        if (empty($html)) {
            return 0;
        }
        if (!preg_match_all('/<a[^>]+href=["\']([^"\']+)["\']/i', $html, $matches)) {
            return 0;
        }
        $appHost = parse_url(config('app.url', ''), PHP_URL_HOST) ?? '';
        $count = 0;
        foreach ($matches[1] as $href) {
            $href = trim($href);
            if ($href === '' || $href === '#') {
                continue;
            }
            // Relative path (no scheme)
            if (!preg_match('#^https?://#i', $href)) {
                $count++;
                continue;
            }
            // Absolute URL: same domain?
            $linkHost = parse_url($href, PHP_URL_HOST);
            if ($linkHost !== null && strtolower($linkHost) === strtolower($appHost)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Analyze SEO and return status
     */
    public function analyzeSEO(Blog $blog): array
    {
        $score = 0;
        $maxScore = 100;
        $issues = [];
        $strengths = [];

        // Check title / meta title (10 points) – prefer meta_title for SEO snippet length (50-60), else post title (30-60)
        $titleForSeo = !empty($blog->meta_title) ? $blog->meta_title : $blog->title;
        $titleMin = !empty($blog->meta_title) ? 50 : 30;
        $titleMax = 60;
        if (!empty($titleForSeo) && strlen($titleForSeo) >= $titleMin && strlen($titleForSeo) <= $titleMax) {
            $score += 10;
            $strengths[] = 'Optimal title length';
        } else {
            $issues[] = !empty($blog->meta_title)
                ? 'Meta title should be 50-60 characters'
                : 'Title should be 30-60 characters';
        }

        // Check meta description (10 points) – prefer meta_description, fallback to short_body
        $metaDesc = !empty($blog->meta_description) ? $blog->meta_description : $blog->short_body;
        if (!empty($metaDesc) && strlen($metaDesc) >= 120 && strlen($metaDesc) <= 160) {
            $score += 10;
            $strengths[] = 'Good meta description';
        } else {
            $issues[] = 'Meta description should be 120-160 characters';
        }

        // Check content length (15 points)
        $contentLength = strlen(strip_tags($blog->long_body ?? ''));
        if ($contentLength >= 1500) {
            $score += 15;
            $strengths[] = 'Comprehensive content length';
        } elseif ($contentLength >= 1000) {
            $score += 10;
            $issues[] = 'Content could be more comprehensive';
        } else {
            $issues[] = 'Content is too short (aim for 1500+ words)';
        }

        // Resolve primary keyword: use primary_keyword if set, else first from meta_keywords (form only has Meta Keywords)
        $metaKeywordsArray = self::parseMetaKeywords($blog->meta_keywords);
        $primaryKeyword = !empty($blog->primary_keyword)
            ? $blog->primary_keyword
            : ($metaKeywordsArray[0] ?? null);

        // Check primary keyword (10 points)
        if (!empty($primaryKeyword)) {
            $score += 10;
            $strengths[] = 'Primary keyword defined';
        } else {
            $issues[] = 'No primary keyword set';
        }

        // Enough secondary: either dedicated field has 3+, or Meta Keywords has 3+ tags (form only has Meta Keywords)
        $secondaryKeywords = !empty($blog->secondary_keywords) && is_array($blog->secondary_keywords)
            ? $blog->secondary_keywords
            : array_slice($metaKeywordsArray, 1);
        $hasEnoughSecondary = count($secondaryKeywords) >= 3 || count($metaKeywordsArray) >= 3;

        // Check secondary keywords (10 points)
        if ($hasEnoughSecondary) {
            $score += 10;
            $strengths[] = 'Good secondary keyword coverage';
        } else {
            $issues[] = 'Add more secondary keywords (3+)';
        }

        // Check keyword in title (10 points) – pass if any keyword (from Meta Keywords or primary/secondary) appears in title
        $titleToCheck = !empty($blog->meta_title) ? $blog->meta_title : $blog->title;
        $keywordsToCheck = $metaKeywordsArray !== []
            ? $metaKeywordsArray
            : array_filter(array_merge(
                $primaryKeyword ? [$primaryKeyword] : [],
                is_array($blog->secondary_keywords ?? null) ? $blog->secondary_keywords : []
            ));
        $keywordInTitle = false;
        foreach ($keywordsToCheck as $kw) {
            if (!empty($kw) && !empty($titleToCheck) && stripos($titleToCheck, $kw) !== false) {
                $keywordInTitle = true;
                break;
            }
        }
        if ($keywordInTitle) {
            $score += 10;
            $strengths[] = 'Keyword in title';
        } else {
            $issues[] = 'Include primary keyword in title';
        }

        // Check headings structure (10 points)
        $hasH2 = preg_match('/<h2[^>]*>/i', $blog->long_body ?? '');
        $hasH3 = preg_match('/<h3[^>]*>/i', $blog->long_body ?? '');
        if ($hasH2 && $hasH3) {
            $score += 10;
            $strengths[] = 'Good heading structure';
        } else {
            $issues[] = 'Improve heading structure (H2, H3)';
        }

        // Check internal links (10 points) – relative paths or same-domain URLs
        $internalLinks = self::countInternalLinks($blog->long_body ?? '');
        if ($internalLinks >= 3) {
            $score += 10;
            $strengths[] = 'Good internal linking';
        } else {
            $issues[] = 'Add more internal links (3+)';
        }

        // Check image (5 points)
        if (!empty($blog->image)) {
            $score += 5;
            $strengths[] = 'Featured image present';
        } else {
            $issues[] = 'Add a featured image';
        }

        // Check slug (5 points)
        if (!empty($blog->slug)) {
            $score += 5;
            $strengths[] = 'SEO-friendly slug';
        } else {
            $issues[] = 'Generate SEO-friendly slug';
        }

        // Check E-E-A-T signals (5 points)
        if (!empty($blog->author_id)) {
            $score += 5;
            $strengths[] = 'Author attribution (E-E-A-T)';
        } else {
            $issues[] = 'Assign an author for E-E-A-T';
        }

        // Determine status
        $status = 'needs-improvement';
        if ($score >= 85) {
            $status = 'high-potential';
        } elseif ($score >= 70) {
            $status = 'google-friendly';
        }

        return [
            'score' => $score,
            'status' => $status,
            'issues' => $issues,
            'strengths' => $strengths,
        ];
    }

    /**
     * Update SEO analysis for a blog.
     * Only updates columns that exist in the table (production may not have seo_analysis).
     */
    public function updateSEOAnalysis(Blog $blog): void
    {
        $analysis = $this->analyzeSEO($blog);
        $table = $blog->getTable();

        $data = [];
        if (Schema::hasColumn($table, 'seo_score')) {
            $data['seo_score'] = $analysis['score'];
        }
        if (Schema::hasColumn($table, 'seo_status')) {
            $data['seo_status'] = $analysis['status'];
        }
        if (Schema::hasColumn($table, 'seo_analysis')) {
            $data['seo_analysis'] = array_merge($blog->seo_analysis ?? [], [
                'last_analyzed' => now()->toISOString(),
                'issues' => $analysis['issues'],
                'strengths' => $analysis['strengths'],
            ]);
        }

        if ($data !== []) {
            $blog->update($data);
        }
    }

    /**
     * Get internal link suggestions
     */
    public function getInternalLinkSuggestions(Blog $blog): array
    {
        $suggestions = [];
        
        // Find related blogs by category
        if ($blog->blog_category_id) {
            $related = Blog::where('blog_category_id', $blog->blog_category_id)
                ->where('id', '!=', $blog->id)
                ->where('is_active', true)
                ->limit(5)
                ->get();
            
            foreach ($related as $relatedBlog) {
                $suggestions[] = [
                    'title' => $relatedBlog->title,
                    'url' => $relatedBlog->link_url,
                    'relevance' => 'high',
                ];
            }
        }

        // Find blogs with similar keywords
        if (!empty($blog->primary_keyword)) {
            $similar = Blog::where('primary_keyword', 'like', '%' . $blog->primary_keyword . '%')
                ->orWhereJsonContains('secondary_keywords', $blog->primary_keyword)
                ->where('id', '!=', $blog->id)
                ->where('is_active', true)
                ->limit(3)
                ->get();
            
            foreach ($similar as $similarBlog) {
                $suggestions[] = [
                    'title' => $similarBlog->title,
                    'url' => $similarBlog->link_url,
                    'relevance' => 'medium',
                ];
            }
        }

        return array_unique($suggestions, SORT_REGULAR);
    }

    /**
     * Analyze performance data
     */
    public function analyzePerformance(string $contentableType, int $contentableId, int $days = 30): array
    {
        $performances = ContentPerformance::forContent($contentableType, $contentableId)
            ->recent($days)
            ->orderBy('measured_at', 'desc')
            ->get();

        if ($performances->isEmpty()) {
            return [
                'avg_ctr' => 0,
                'avg_engagement' => 0,
                'total_impressions' => 0,
                'trend' => 'no_data',
            ];
        }

        $avgCtr = $performances->avg('ctr') ?? 0;
        $avgEngagement = $performances->avg('engagement') ?? 0;
        $totalImpressions = $performances->sum('impressions') ?? 0;

        // Determine trend
        $recent = $performances->take(7);
        $older = $performances->skip(7)->take(7);
        
        $trend = 'stable';
        if ($recent->avg('ctr') > $older->avg('ctr') * 1.1) {
            $trend = 'improving';
        } elseif ($recent->avg('ctr') < $older->avg('ctr') * 0.9) {
            $trend = 'declining';
        }

        return [
            'avg_ctr' => round($avgCtr, 4),
            'avg_engagement' => round($avgEngagement, 2),
            'total_impressions' => $totalImpressions,
            'trend' => $trend,
            'data_points' => $performances->count(),
        ];
    }

    /**
     * Get content optimization recommendations
     */
    public function getOptimizationRecommendations(Blog $blog): array
    {
        $recommendations = [];
        $analysis = $this->analyzeSEO($blog);

        // Add recommendations based on issues
        foreach ($analysis['issues'] as $issue) {
            $recommendations[] = [
                'type' => 'seo',
                'priority' => 'high',
                'message' => $issue,
                'action' => $this->getActionForIssue($issue),
            ];
        }

        // Performance-based recommendations
        $performance = $this->analyzePerformance(Blog::class, $blog->id);
        if ($performance['trend'] === 'declining') {
            $recommendations[] = [
                'type' => 'performance',
                'priority' => 'medium',
                'message' => 'Content performance is declining',
                'action' => 'Consider updating content or adjusting keywords',
            ];
        }

        return $recommendations;
    }

    /**
     * Get action suggestion for an issue
     */
    protected function getActionForIssue(string $issue): string
    {
        $actions = [
            'Title should be 30-60 characters' => 'Edit the title to be between 30-60 characters',
            'Meta title should be 50-60 characters' => 'Edit the Meta Title in SEO & Metadata to be 50-60 characters',
            'Meta description should be 120-160 characters' => 'Update the Meta Description in SEO & Metadata to be 120-160 characters',
            'Content is too short' => 'Expand the content to at least 1500 words',
            'No primary keyword set' => 'Set a primary keyword in the blog settings',
            'Add more secondary keywords' => 'Add at least 3 secondary keywords',
            'Include primary keyword in title' => 'Add the primary keyword to the title',
            'Improve heading structure' => 'Add H2 and H3 headings to structure the content',
            'Add more internal links' => 'Link to at least 3 other related blog posts',
            'Add a featured image' => 'Upload a featured image for the blog post',
            'Generate SEO-friendly slug' => 'Ensure the slug is generated from the title',
            'Assign an author for E-E-A-T' => 'Assign an author to the blog post',
        ];

        return $actions[$issue] ?? 'Review and improve this aspect';
    }
}

