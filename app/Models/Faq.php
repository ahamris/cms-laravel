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
        $rowKey = "faqs.identifier.{$identifier}.row_v1";
        $legacyKey = "faqs.identifier.{$identifier}";

        return self::cacheRememberNullableModelRow(
            $rowKey,
            3600,
            fn () => self::where('identifier', $identifier)->first(),
            [$legacyKey],
        );
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
                Cache::forget("faqs.identifier.{$faq->identifier}.row_v1");
            }
        });

        static::deleted(function ($faq) {
            if ($faq->identifier) {
                Cache::forget("faqs.identifier.{$faq->identifier}");
                Cache::forget("faqs.identifier.{$faq->identifier}.row_v1");
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
