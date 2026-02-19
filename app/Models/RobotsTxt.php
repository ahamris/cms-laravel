<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperRobotsTxt
 */
class RobotsTxt extends BaseModel
{
    const CACHE_KEY = 'robots_txt_content';
    const CACHE_DURATION = 60 * 60 * 24; // 24 hours

    protected $fillable = [
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the active robots.txt content from cache
     */
    public static function getCachedContent(): string
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
            $robotsTxt = self::where('is_active', true)->first();

            if (!$robotsTxt) {
                return self::getDefaultContent();
            }

            return $robotsTxt->content;
        });
    }

    /**
     * Clear the robots.txt cache
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get default robots.txt content
     */
    public static function getDefaultContent(): string
    {
        $content = <<<EOT
# robots.txt - Smart optimized version for SEO and security
# Allow major search engines, block known spam/scanner bots

# ✅ Trusted Search Engines
User-agent: Googlebot
Allow: /

User-agent: Bingbot
Allow: /

User-agent: Slurp
Allow: /

User-agent: DuckDuckBot
Allow: /

User-agent: Baiduspider
Allow: /

User-agent: Yandex
Allow: /

User-agent: facebot
Allow: /

User-agent: ia_archiver
Allow: /

# 🚫 Block common bandwidth-hogging or SEO-scraping bots
User-agent: AhrefsBot
Disallow: /

User-agent: SemrushBot
Disallow: /

User-agent: MJ12bot
Disallow: /

User-agent: DotBot
Disallow: /

User-agent: MauiBot
Disallow: /

User-agent: BLEXBot
Disallow: /

User-agent: Bytespider
Disallow: /

User-agent: YandexImages
Disallow: /

# 🧱 Default rule for all other bots
User-agent: *
Disallow:
Allow: /

# 🔍 Sitemap
Sitemap: {SITEMAP_URL}
EOT;

        return str_replace('{SITEMAP_URL}', url('/sitemap.xml'), $content);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::created(fn() => self::clearCache());
        static::updated(fn() => self::clearCache());
        static::deleted(fn() => self::clearCache());
    }
}
