<?php

namespace App\Models;

use Illuminate\Support\Str;

/**
 * @mixin IdeHelperMarketingPersona
 */
class MarketingPersona extends BaseModel
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'demographics',
        'pain_points',
        'goals',
        'preferred_channels',
        'avatar_image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'demographics' => 'array',
        'pain_points' => 'array',
        'goals' => 'array',
        'preferred_channels' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($persona) {
            if (empty($persona->slug)) {
                $persona->slug = Str::slug($persona->name);
            }
        });
    }

    /**
     * Scope for active personas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered personas
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
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
