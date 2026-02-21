<?php

namespace Database\Seeders;

use App\Models\Changelog;
use Illuminate\Database\Seeder;

class ChangelogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates changelog entries with various statuses, features, and steps.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        if (Changelog::count() > 0) {
            return;
        }

        $entries = [
            [
                'title' => 'REST API v2 released',
                'description' => 'New REST API version with improved pagination and filtering.',
                'content' => 'We have released REST API v2 with consistent pagination (page, per_page), new filter parameters, and OpenAPI 3.0 documentation. All existing endpoints remain available under v1.',
                'video_url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
                'date' => now()->subDays(5),
                'status' => 'api',
                'sort_order' => 1,
                'features' => [
                    'Paginated responses with meta (current_page, last_page, total)',
                    'Query filters: status, date_from, date_to',
                    'OpenAPI 3.0 spec at /api/documentation/json',
                ],
                'steps' => [
                    'Update base URL to /api/v2 when ready',
                    'Replace deprecated fields with new response shape',
                ],
            ],
            [
                'title' => 'Academy video categories',
                'description' => 'Organize academy videos into categories and chapters.',
                'content' => 'Academy content can now be grouped into categories and chapters. Each category supports an optional image and sort order. Videos can be linked to chapters and support external URLs (YouTube, Vimeo).',
                'video_url' => null,
                'date' => now()->subDays(12),
                'status' => 'new',
                'sort_order' => 2,
                'features' => [
                    'Academy categories with slug and image',
                    'Chapters per category',
                    'Videos with remote URL or uploaded file',
                ],
                'steps' => null,
            ],
            [
                'title' => 'Changelog API endpoint',
                'description' => 'Public API to fetch changelog entries for API consumers.',
                'content' => 'A new endpoint GET /api/changelog returns paginated changelog entries. Use status=api to show only API-related updates, or status=all for everything.',
                'date' => now()->subDays(20),
                'status' => 'api',
                'sort_order' => 3,
                'features' => [
                    'GET /api/changelog with per_page and status',
                    'GET /api/changelog/{slug} for a single entry',
                ],
                'steps' => null,
            ],
            [
                'title' => 'Search performance improved',
                'description' => 'Faster search results across pages, blog, and docs.',
                'content' => 'Search queries now use optimized indexes and return results in under 100ms for typical datasets. Highlighting of matched terms is available in the response.',
                'date' => now()->subDays(28),
                'status' => 'improved',
                'sort_order' => 4,
                'features' => [
                    'Indexed full-text search',
                    'Optional snippet/highlight in response',
                ],
                'steps' => null,
            ],
            [
                'title' => 'Fixed date formatting in feeds',
                'description' => 'ISO 8601 dates in API responses were sometimes missing timezone.',
                'content' => 'All date and datetime fields in JSON responses now consistently use ISO 8601 format with timezone (e.g. 2025-02-21T12:00:00+00:00).',
                'date' => now()->subDays(45),
                'status' => 'fixed',
                'sort_order' => 5,
                'features' => null,
                'steps' => null,
            ],
            [
                'title' => 'Webhook retry policy',
                'description' => 'Configurable retries for failed webhook deliveries.',
                'content' => 'Webhooks now support a retry policy: you can set max_retries and retry_delay_seconds. Failed deliveries are retried with exponential backoff.',
                'date' => now()->subDays(60),
                'status' => 'api',
                'sort_order' => 6,
                'features' => [
                    'max_retries (default 3)',
                    'retry_delay_seconds and exponential backoff',
                ],
                'steps' => [
                    'Configure webhook endpoint in dashboard',
                    'Optional: set X-Webhook-Signature header for verification',
                ],
            ],
        ];

        foreach ($entries as $entry) {
            $slug = \Illuminate\Support\Str::slug($entry['title']);
            Changelog::updateOrCreate(
                ['slug' => $slug],
                array_merge($entry, ['is_active' => true])
            );
        }
    }
}
