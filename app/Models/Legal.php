<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperLegal
 */
class Legal extends BaseModel
{
    use Sluggable;

    const string CACHE_KEY = 'legal_pages';

    protected $table = 'legal_pages';

    protected $fillable = [
        'title',
        'slug',
        'body',
        'is_active',
        'meta_title',
        'meta_description',
        'keywords',
        'image',
        'current_version',
        'versioning_enabled',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'current_version' => 'integer',
        'versioning_enabled' => 'boolean',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'maxLength' => 255,
                'separator' => '-',
                'includeTrashed' => true,
            ],
        ];
    }

    public function getCached(): array
    {
        return Cache::remember(self::CACHE_KEY, 86400, function () {
            return self::toBase()
                ->where('is_active', true)
                ->get();
        });
    }

    /**
     * Get all versions for this legal page.
     */
    public function versions()
    {
        return $this->hasMany(LegalPageVersion::class, 'legal_page_id');
    }

    /**
     * Get the current version record.
     */
    public function currentVersionRecord()
    {
        return $this->hasOne(LegalPageVersion::class, 'legal_page_id')
            ->where('version_number', $this->current_version)
            ->latestOfMany('version_number');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Set initial version on create
        static::creating(function ($legal) {
            if (! isset($legal->current_version)) {
                $legal->current_version = 1;
            }
            if (! isset($legal->versioning_enabled)) {
                $legal->versioning_enabled = true;
            }
        });

        // Create initial version after creation
        static::created(function ($legal) {
            if ($legal->versioning_enabled) {
                $legal->createVersionWithoutUpdate('Initial version');
            }
        });
    }

    /**
     * Manually create a version snapshot.
     */
    public function createVersion($notes = null): LegalPageVersion
    {
        // Get the next version number
        $nextVersion = ($this->current_version ?? 0) + 1;

        // Create version snapshot
        $version = LegalPageVersion::create([
            'legal_page_id' => $this->id,
            'version_number' => $nextVersion,
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'is_active' => $this->is_active,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'keywords' => $this->keywords,
            'image' => $this->image,
            'created_by' => auth()->id(),
            'version_notes' => $notes,
        ]);

        // Update current version number without triggering events
        $this->timestamps = false;
        $this->updateQuietly(['current_version' => $nextVersion]);
        $this->timestamps = true;

        // Clear cache
        Cache::forget(self::CACHE_KEY);

        return $version;
    }

    /**
     * Create a version snapshot without updating the current_version (used internally).
     */
    protected function createVersionWithoutUpdate($notes = null): LegalPageVersion
    {
        // Get the next version number
        $nextVersion = ($this->current_version ?? 0) + 1;

        // Create version snapshot
        $version = LegalPageVersion::create([
            'legal_page_id' => $this->id,
            'version_number' => $nextVersion,
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'is_active' => $this->is_active,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'keywords' => $this->keywords,
            'image' => $this->image,
            'created_by' => auth()->id(),
            'version_notes' => $notes,
        ]);

        // Update current version number without triggering events
        $this->timestamps = false;
        $this->updateQuietly(['current_version' => $nextVersion]);
        $this->timestamps = true;

        // Clear cache
        Cache::forget(self::CACHE_KEY);

        return $version;
    }

    /**
     * Get a specific version by version number.
     */
    public function getVersion($versionNumber): ?LegalPageVersion
    {
        return $this->versions()
            ->where('version_number', $versionNumber)
            ->first();
    }

    /**
     * Restore a specific version.
     */
    public function restoreVersion($versionNumber): bool
    {
        $version = $this->getVersion($versionNumber);

        if (! $version) {
            return false;
        }

        // Create a new version with current state before restoring
        if ($this->versioning_enabled) {
            $this->createVersion("Restored from version {$versionNumber}");
        }

        // Restore the version data
        $this->update([
            'title' => $version->title,
            'slug' => $version->slug,
            'body' => $version->body,
            'is_active' => $version->is_active,
            'meta_title' => $version->meta_title,
            'meta_description' => $version->meta_description,
            'keywords' => $version->keywords,
            'image' => $version->image,
        ]);

        return true;
    }

    /**
     * Get the total number of versions.
     */
    public function getVersionsCount(): int
    {
        return $this->versions()->count();
    }
}
