<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin IdeHelperSocialMediaPlatform
 */
class SocialMediaPlatform extends BaseModel
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
        'api_credentials',
        'settings',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'api_credentials' => 'encrypted:array',
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function socialMediaPosts(): HasMany
    {
        return $this->hasMany(SocialMediaPost::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Helper methods
    public function isConfigured(): bool
    {
        return !empty($this->api_credentials);
    }

    public function getPostsCount(): int
    {
        return $this->socialMediaPosts()->count();
    }

    public function getSuccessfulPostsCount(): int
    {
        return $this->socialMediaPosts()->where('status', 'posted')->count();
    }

    public function getFailedPostsCount(): int
    {
        return $this->socialMediaPosts()->where('status', 'failed')->count();
    }

    /**
     * Supported slugs for auto-posting (must match hamzahassanm/laravel-social-auto-post).
     */
    public static function supportedAutoPostSlugs(): array
    {
        return array_keys(config('social_platform_credentials', []));
    }

    /**
     * Credential field definitions for a given slug (for CRUD forms).
     *
     * @return array<int, array{key: string, config: string, label: string, type: string}>
     */
    public static function getCredentialFieldsForSlug(string $slug): array
    {
        $all = config('social_platform_credentials', []);

        return $all[$slug] ?? [];
    }

    /**
     * Whether this platform slug is supported for auto-posting.
     */
    public function supportsAutoPost(): bool
    {
        return in_array($this->slug, self::supportedAutoPostSlugs(), true);
    }
}
