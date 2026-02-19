<?php

namespace App\Models;

use Illuminate\Support\Str;

/**
 * @mixin IdeHelperContentType
 */
class ContentType extends BaseModel
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'applicable_models',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'applicable_models' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contentType) {
            if (empty($contentType->slug)) {
                $contentType->slug = Str::slug($contentType->name);
            }
        });
    }

    /**
     * Scope for active content types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered content types
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Check if this content type is applicable to a specific model
     */
    public function isApplicableToModel(string $modelClass): bool
    {
        if (empty($this->applicable_models)) {
            return true; // If no restrictions, applicable to all
        }

        return in_array($modelClass, $this->applicable_models);
    }

    /**
     * Relationships
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

}
