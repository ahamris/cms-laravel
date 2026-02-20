<?php

namespace App\Services;

use App\Models\SocialMediaPlatform;
use HamzaHassanM\LaravelSocialAutoPost\Facades\SocialMedia;
use HamzaHassanM\LaravelSocialAutoPost\Services\FacebookService;
use HamzaHassanM\LaravelSocialAutoPost\Services\TwitterService;
use HamzaHassanM\LaravelSocialAutoPost\Services\LinkedInService;
use HamzaHassanM\LaravelSocialAutoPost\Services\InstagramService;
use HamzaHassanM\LaravelSocialAutoPost\Services\TikTokService;
use HamzaHassanM\LaravelSocialAutoPost\Services\YouTubeService;
use HamzaHassanM\LaravelSocialAutoPost\Services\PinterestService;
use HamzaHassanM\LaravelSocialAutoPost\Services\TelegramService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use ReflectionClass;

/**
 * Posts to social platforms using hamzahassanm/laravel-social-auto-post with credentials from SocialMediaPlatform.
 */
class SocialMediaAutoPostService
{
    private const SERVICE_CLASSES = [
        'facebook' => FacebookService::class,
        'twitter' => TwitterService::class,
        'linkedin' => LinkedInService::class,
        'instagram' => InstagramService::class,
        'tiktok' => TikTokService::class,
        'youtube' => YouTubeService::class,
        'pinterest' => PinterestService::class,
        'telegram' => TelegramService::class,
    ];

    /**
     * Post content (and optional media/link) to the given platform using its stored credentials.
     *
     * @return array{success: bool, external_post_id?: string, external_post_url?: string, response_data?: array, error?: string}
     */
    public function post(
        SocialMediaPlatform $platform,
        string $content,
        string $url = '',
        array $mediaUrls = []
    ): array {
        if (!$platform->supportsAutoPost()) {
            return ['success' => false, 'error' => 'Platform slug not supported for auto-posting.'];
        }

        $credentials = $platform->api_credentials;
        if (empty($credentials)) {
            return ['success' => false, 'error' => 'Platform has no API credentials.'];
        }

        $slug = $platform->slug;
        $configMap = $this->credentialsToConfig($slug, $credentials);
        if (empty($configMap)) {
            return ['success' => false, 'error' => 'Could not map credentials to config.'];
        }

        $this->injectConfigAndClearSingleton($slug, $configMap);

        try {
            $firstImage = $this->firstUrlByExtension($mediaUrls, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            $firstVideo = $this->firstUrlByExtension($mediaUrls, ['mp4', 'webm', 'mov']);

            if ($firstVideo) {
                $result = SocialMedia::shareVideo([$slug], $content, $firstVideo);
            } elseif ($firstImage) {
                $result = $this->callShareImage($slug, $content, $firstImage);
            } else {
                $result = SocialMedia::share([$slug], $content, $url ?: $content);
            }

            $platformResult = $result['results'][$slug] ?? null;
            if ($platformResult && !empty($platformResult['success'])) {
                $data = $platformResult['data'] ?? [];
                $externalId = is_array($data) ? ($data['id'] ?? $data['post_id'] ?? null) : null;
                $externalUrl = is_array($data) ? ($data['permalink_url'] ?? $data['url'] ?? $url) : $url;

                return [
                    'success' => true,
                    'external_post_id' => $externalId ? (string) $externalId : null,
                    'external_post_url' => $externalUrl ? (string) $externalUrl : null,
                    'response_data' => $data,
                ];
            }

            $error = $result['errors'][$slug] ?? $platformResult['error'] ?? 'Unknown error';
            return ['success' => false, 'error' => is_string($error) ? $error : json_encode($error)];
        } catch (\Throwable $e) {
            Log::error('SocialMediaAutoPostService post failed', [
                'platform' => $slug,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Map api_credentials (key => value) to autopost config keys (config_key => value).
     */
    private function credentialsToConfig(string $slug, array $credentials): array
    {
        $fields = SocialMediaPlatform::getCredentialFieldsForSlug($slug);
        $out = [];
        foreach ($fields as $def) {
            $key = $def['key'] ?? null;
            $configKey = $def['config'] ?? null;
            if ($key && $configKey && array_key_exists($key, $credentials) && $credentials[$key] !== '') {
                $out['autopost.' . $configKey] = $credentials[$key];
            }
        }

        return $out;
    }

    private function injectConfigAndClearSingleton(string $slug, array $configMap): void
    {
        foreach ($configMap as $key => $value) {
            Config::set($key, $value);
        }

        $serviceClass = self::SERVICE_CLASSES[$slug] ?? null;
        if ($serviceClass) {
            App::forgetInstance($serviceClass);
            $this->clearStaticInstance($serviceClass);
        }
    }

    private function clearStaticInstance(string $className): void
    {
        try {
            $ref = new ReflectionClass($className);
            if ($ref->hasProperty('instance')) {
                $prop = $ref->getProperty('instance');
                $prop->setAccessible(true);
                $prop->setValue(null);
            }
        } catch (\Throwable $e) {
            // Ignore; getInstance() may still use fresh config when container rebuilds
        }
    }

    private function firstUrlByExtension(array $urls, array $extensions): ?string
    {
        foreach ($urls as $url) {
            $path = parse_url($url, PHP_URL_PATH);
            if ($path && in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $extensions, true)) {
                return $url;
            }
        }

        return null;
    }

    private function callShareImage(string $slug, string $caption, string $imageUrl): array
    {
        return SocialMedia::shareImage([$slug], $caption, $imageUrl);
    }
}
