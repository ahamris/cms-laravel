<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperModuleListWidget
 */
class ModuleListWidget extends Model
{
    const CACHE_KEY = 'module_list_widgets';

    protected $fillable = [
        'title',
        'description',
        'modules',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'modules' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $attributes = [
        'title' => 'Modules Header',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad alias architecto blanditiis commodi deleniti deserunt dolorem est expedita id impedit minima nisi numquam officia optio quaerat quasi quos, suscipit voluptas?',
        'is_active' => true,
        'sort_order' => 0,
    ];

    /**
     * Boot method to handle cache invalidation
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when model is created, updated, or deleted
        static::created(fn () => self::clearCache());
        static::saved(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
    }

    /**
     * Get all cached module list widgets
     */
    public static function getCached()
    {
        return Cache::remember(self::CACHE_KEY, 86400, function () {
            return self::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('created_at')
                ->get();
        });
    }

    /**
     * Clear all module list widget caches
     */
    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Scope for active module list widgets
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered module list widgets
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Get default module structure
     */
    public static function getDefaultModuleStructure()
    {
        return [
            'name' => 'Module Name',
            'items' => [
                'Feature 1',
                'Feature 2',
                'Feature 3',
            ]
        ];
    }

    /**
     * Get modules with default structure if empty
     */
    public function getModulesAttribute($value)
    {
        $existingModules = json_decode($value, true) ?: [];

        // If no modules exist, return default structure
        if (empty($existingModules)) {
            return [
                self::getDefaultModuleStructure(),
                self::getDefaultModuleStructure(),
                self::getDefaultModuleStructure(),
                self::getDefaultModuleStructure(),
                self::getDefaultModuleStructure(),
            ];
        }

        return $existingModules;
    }

    /**
     * Set modules attribute
     */
    public function setModulesAttribute($value)
    {
        $this->attributes['modules'] = is_array($value) ? json_encode($value) : $value;
    }
}

