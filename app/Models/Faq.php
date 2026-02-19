<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperFaq
 */
class Faq extends BaseModel
{
    protected $fillable = [
        'identifier',
        'title',
        'subtitle',
        'items',
        'question',
        'answer',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    /**
     * Get FAQs by identifier (for page builder)
     */
    public static function getByIdentifier(string $identifier)
    {
        return Cache::remember("faqs.identifier.{$identifier}", 3600, function () use ($identifier) {
            return self::where('identifier', $identifier)->first();
        });
    }

    /**
     * Clear cache when FAQ is saved or deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($faq) {
            if ($faq->identifier) {
                Cache::forget("faqs.identifier.{$faq->identifier}");
            }
        });

        static::deleted(function ($faq) {
            if ($faq->identifier) {
                Cache::forget("faqs.identifier.{$faq->identifier}");
            }
        });
    }

    /**
     * Scope to get FAQs by identifier
     */
    public function scopeByIdentifier($query, string $identifier)
    {
        return $query->where('identifier', $identifier);
    }
}
