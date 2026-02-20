<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Laravel\Scout\Searchable;

/**
 * @mixin IdeHelperOrganizationName
 */
class OrganizationName extends BaseModel
{
    use Searchable;

    const CACHE_KEY = 'organization_names';

    protected $table = 'organization_names';

    protected $fillable = [
        'name',
        'abbreviation',
        'address',
        'email',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'abbreviation' => $this->abbreviation,
            'address' => $this->address,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->timestamp,
            'updated_at' => $this->updated_at?->timestamp,
        ];
    }

    /**
     * Modify the collection used when syncing.
     */
    public function searchableAs(): string
    {
        return 'organization_names';
    }

    /**
     * Get the value used to index the model.
     */
    public function getScoutKey(): mixed
    {
        return $this->id;
    }

    /**
     * Get the key name used to index the model.
     */
    public function getScoutKeyName(): mixed
    {
        return 'id';
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        // Disable syncing during import if DISABLE_SCOUT_SYNC is set
        if (config('app.disable_scout_sync', false)) {
            return false;
        }
        
        return $this->is_active;
    }

    /**
     * Scope for active organization names
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered organization names by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get formatted display name with abbreviation
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->abbreviation) {
            return "{$this->name} ({$this->abbreviation})";
        }
        return $this->name;
    }

    /**
     * Link identifier for headless (id; no slug on this model; frontend builds URL).
     */
    public function getLinkUrlAttribute(): string
    {
        return (string) $this->id;
    }

    protected static function boot()
    {
        parent::boot();

        // Clear cache on model events
        static::created(fn () => Cache::forget(self::CACHE_KEY));
        static::updated(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Get cached organization names
     */
    public static function getCached()
    {
        if (!Cache::has(self::CACHE_KEY)) {
            return Cache::remember(self::CACHE_KEY, 60 * 60, function () {
                return self::active()->ordered()->get();
            });
        }

        return Cache::get(self::CACHE_KEY);
    }

    /**
     * Clear cache
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
    }
}
