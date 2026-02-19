<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SocialMediaPlatform;

class SocialMediaPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = [
            [
                'name' => 'Facebook',
                'slug' => 'facebook',
                'icon' => 'fab fa-facebook',
                'color' => '#1877F2',
                'settings' => [
                    'character_limit' => 63206,
                    'supports_images' => true,
                    'supports_videos' => true,
                    'supports_hashtags' => true,
                    'optimal_hashtags' => 3,
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Twitter',
                'slug' => 'twitter',
                'icon' => 'fab fa-twitter',
                'color' => '#1DA1F2',
                'settings' => [
                    'character_limit' => 280,
                    'supports_images' => true,
                    'supports_videos' => true,
                    'supports_hashtags' => true,
                    'optimal_hashtags' => 5,
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'LinkedIn',
                'slug' => 'linkedin',
                'icon' => 'fab fa-linkedin',
                'color' => '#0A66C2',
                'settings' => [
                    'character_limit' => 3000,
                    'supports_images' => true,
                    'supports_videos' => true,
                    'supports_hashtags' => true,
                    'optimal_hashtags' => 5,
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Instagram',
                'slug' => 'instagram',
                'icon' => 'fab fa-instagram',
                'color' => '#E4405F',
                'settings' => [
                    'character_limit' => 2200,
                    'supports_images' => true,
                    'supports_videos' => true,
                    'supports_hashtags' => true,
                    'optimal_hashtags' => 10,
                ],
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'YouTube',
                'slug' => 'youtube',
                'icon' => 'fab fa-youtube',
                'color' => '#FF0000',
                'settings' => [
                    'character_limit' => 5000,
                    'supports_images' => false,
                    'supports_videos' => true,
                    'supports_hashtags' => true,
                    'optimal_hashtags' => 15,
                ],
                'is_active' => false,
                'sort_order' => 5,
            ],
        ];

        foreach ($platforms as $platform) {
            SocialMediaPlatform::updateOrCreate(
                ['slug' => $platform['slug']],
                $platform
            );
        }
    }
}
