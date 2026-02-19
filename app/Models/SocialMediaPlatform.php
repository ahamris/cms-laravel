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
}
