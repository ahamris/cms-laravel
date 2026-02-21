<?php

namespace Database\Seeders;

use App\Models\AcademyCategory;
use App\Models\AcademyChapter;
use App\Models\AcademyVideo;
use Illuminate\Database\Seeder;

class AcademySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates academy categories, chapters, and videos (including remote URL videos).
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        if (AcademyCategory::count() > 0) {
            return;
        }

        $categories = [
            [
                'name' => 'Getting Started',
                'description' => 'Learn the basics and get up and running quickly.',
                'sort_order' => 1,
                'image_path' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=450&fit=crop',
                'chapters' => [
                    [
                        'name' => 'Introduction',
                        'description' => 'Welcome and overview of the platform.',
                        'sort_order' => 1,
                        'videos' => [
                            [
                                'title' => 'Welcome to the Academy',
                                'description' => 'A quick introduction to our learning platform and what you can expect.',
                                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                                'thumbnail_path' => 'https://images.unsplash.com/photo-1547658719-da2b51169166?w=640&h=360&fit=crop',
                                'duration_seconds' => 212,
                                'sort_order' => 1,
                            ],
                            [
                                'title' => 'Setting Up Your Account',
                                'description' => 'Step-by-step guide to configuring your account and preferences.',
                                'video_url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
                                'thumbnail_path' => 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?w=640&h=360&fit=crop',
                                'duration_seconds' => 19 * 60,
                                'sort_order' => 2,
                            ],
                        ],
                    ],
                    [
                        'name' => 'First Steps',
                        'description' => 'Your first actions and common workflows.',
                        'sort_order' => 2,
                        'videos' => [
                            [
                                'title' => 'Creating Your First Project',
                                'description' => 'How to create and configure a new project from scratch.',
                                'video_url' => 'https://vimeo.com/1140004517',
                                'thumbnail_path' => 'https://picsum.photos/seed/academy-project/640/360',
                                'duration_seconds' => 8 * 60 + 45,
                                'sort_order' => 1,
                            ],
                        ],
                    ],
                ],
                'videos_without_chapter' => [
                    [
                        'title' => 'Quick Start Overview',
                        'description' => 'A high-level overview for busy people. Get the gist in under five minutes.',
                        'video_url' => 'https://www.youtube.com/watch?v=9bZkp7q19f0',
                        'duration_seconds' => 4 * 60 + 12,
                        'sort_order' => 0,
                    ],
                ],
            ],
            [
                'name' => 'Advanced Features',
                'description' => 'Deep dives into advanced functionality and best practices.',
                'sort_order' => 2,
                'image_path' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&h=450&fit=crop',
                'chapters' => [
                    [
                        'name' => 'API & Integrations',
                        'description' => 'Connect with external services and automate workflows.',
                        'sort_order' => 1,
                        'videos' => [
                            [
                                'title' => 'REST API Overview',
                                'description' => 'Understanding our REST API, authentication, and rate limits.',
                                'video_url' => 'https://www.youtube.com/watch?v=SLwpqDx8Xc0',
                                'thumbnail_path' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=640&h=360&fit=crop',
                                'duration_seconds' => 15 * 60,
                                'sort_order' => 1,
                            ],
                            [
                                'title' => 'Webhooks Explained',
                                'description' => 'How to subscribe to events and handle webhook payloads.',
                                'video_url' => 'https://vimeo.com/90509568',
                                'thumbnail_path' => 'https://picsum.photos/seed/academy-webhooks/640/360',
                                'duration_seconds' => 11 * 60 + 30,
                                'sort_order' => 2,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Performance',
                        'description' => 'Optimize speed and resource usage.',
                        'sort_order' => 2,
                        'videos' => [
                            [
                                'title' => 'Caching Strategies',
                                'description' => 'When and how to use caching to improve performance.',
                                'video_url' => 'https://www.youtube.com/watch?v=R2e3bF6_wYo',
                                'duration_seconds' => 18 * 60,
                                'sort_order' => 1,
                            ],
                        ],
                    ],
                ],
                'videos_without_chapter' => [],
            ],
            [
                'name' => 'Best Practices',
                'description' => 'Guidelines and patterns recommended by our team.',
                'sort_order' => 3,
                'image_path' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=450&fit=crop',
                'chapters' => [
                    [
                        'name' => 'Security',
                        'description' => 'Keeping your data and integrations secure.',
                        'sort_order' => 1,
                        'videos' => [
                            [
                                'title' => 'Authentication Best Practices',
                                'description' => 'Tokens, expiry, and secure storage.',
                                'video_url' => 'https://www.youtube.com/watch?v=2PPSXcfH7Ks',
                                'duration_seconds' => 12 * 60,
                                'sort_order' => 1,
                            ],
                        ],
                    ],
                ],
                'videos_without_chapter' => [
                    [
                        'title' => 'Code Review Checklist',
                        'description' => 'A practical checklist for reviewing your implementation.',
                        'video_url' => 'https://vimeo.com/22439234',
                        'duration_seconds' => 6 * 60,
                        'sort_order' => 1,
                    ],
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $videosWithoutChapter = $catData['videos_without_chapter'] ?? [];
            $chaptersData = $catData['chapters'] ?? [];
            unset($catData['chapters'], $catData['videos_without_chapter']);

            $category = AcademyCategory::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($catData['name'])],
                array_merge($catData, ['is_active' => true])
            );

            foreach ($videosWithoutChapter as $videoData) {
                AcademyVideo::firstOrCreate(
                    [
                        'academy_category_id' => $category->id,
                        'slug' => \Illuminate\Support\Str::slug($videoData['title']),
                    ],
                    array_merge($videoData, [
                        'academy_chapter_id' => null,
                        'is_active' => true,
                        'video_path' => null,
                        'thumbnail_path' => $videoData['thumbnail_path'] ?? null,
                        ])
                );
            }

            foreach ($chaptersData as $chapterData) {
                $videos = $chapterData['videos'] ?? [];
                unset($chapterData['videos']);

                $chapter = AcademyChapter::firstOrCreate(
                    [
                        'academy_category_id' => $category->id,
                        'name' => $chapterData['name'],
                    ],
                    [
                        'description' => $chapterData['description'] ?? null,
                        'sort_order' => $chapterData['sort_order'] ?? 0,
                    ]
                );

                foreach ($videos as $videoData) {
                    AcademyVideo::firstOrCreate(
                        [
                            'academy_category_id' => $category->id,
                            'slug' => \Illuminate\Support\Str::slug($videoData['title']),
                        ],
                        array_merge($videoData, [
                            'academy_chapter_id' => $chapter->id,
                            'is_active' => true,
                            'video_path' => null,
                            'thumbnail_path' => $videoData['thumbnail_path'] ?? null,
                        ])
                    );
                }
            }
        }
    }
}
