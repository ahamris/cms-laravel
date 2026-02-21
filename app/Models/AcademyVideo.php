<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperAcademyVideo
 */
class AcademyVideo extends BaseModel
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'academy_category_id',
        'academy_chapter_id',
        'title',
        'slug',
        'description',
        'video_path',
        'video_url',
        'thumbnail_path',
        'duration_seconds',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    /**
     * Get the route key for the model (URL uses slug).
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(AcademyCategory::class, 'academy_category_id');
    }

    public function chapter()
    {
        return $this->belongsTo(AcademyChapter::class, 'academy_chapter_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Scope: items that have a video (uploaded file or external URL).
     */
    public function scopeWithVideo(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereNotNull('video_path')->orWhereNotNull('video_url');
        });
    }

    /**
     * Scope: plain documentation (no video file, no video URL).
     */
    public function scopePlainDocumentation(Builder $query): Builder
    {
        return $query->whereNull('video_path')->whereNull('video_url');
    }

    /**
     * Whether this item has a video (file or URL).
     */
    public function hasVideo(): bool
    {
        return $this->video_path !== null || $this->video_url !== null;
    }

    /**
     * Whether this item is plain documentation (no video).
     */
    public function isPlainDocumentation(): bool
    {
        return ! $this->hasVideo();
    }

    /**
     * Get the video URL (uploaded file or external URL).
     */
    public function getVideoSourceUrlAttribute(): ?string
    {
        if ($this->video_path) {
            return asset('storage/' . $this->video_path);
        }
        return $this->video_url;
    }

    /**
     * Get the video provider (youtube, vimeo, local, or unknown).
     */
    public function getVideoProviderAttribute(): string
    {
        if ($this->video_path) {
            return 'local';
        }

        if (!$this->video_url) {
            return 'unknown';
        }

        if (str_contains($this->video_url, 'youtube.com') || str_contains($this->video_url, 'youtu.be')) {
            return 'youtube';
        }

        if (str_contains($this->video_url, 'vimeo.com')) {
            return 'vimeo';
        }

        return 'unknown'; // Could be other external URL (mp4 link)
    }

    /**
     * Get the video ID for YouTube or Vimeo.
     */
    public function getVideoIdAttribute(): ?string
    {
        if ($this->video_provider === 'youtube') {
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->video_url, $matches);
            return $matches[1] ?? null;
        }

        if ($this->video_provider === 'vimeo') {
            preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $this->video_url, $matches);
            return $matches[1] ?? null;
        }

        return null;
    }

    /**
     * Get the thumbnail URL (remote URL, local storage, or YouTube/Vimeo fallback).
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_path) {
            if (str_starts_with($this->thumbnail_path, 'http://') || str_starts_with($this->thumbnail_path, 'https://')) {
                return $this->thumbnail_path;
            }
            return Storage::url($this->thumbnail_path);
        }

        // Auto-thumbnail for YouTube
        if ($this->video_provider === 'youtube' && $this->video_id) {
            return "https://img.youtube.com/vi/{$this->video_id}/mqdefault.jpg";
        }

        return null;
    }

    /**
     * Format duration as "X min" or "X:XX".
     */
    public function getDurationFormattedAttribute(): ?string
    {
        if ($this->duration_seconds === null) {
            return null;
        }

        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
