<?php

namespace App\Models;

use App\Models\Traits\ElementTrait;
use App\Models\Traits\FaqTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperStaticPage
 */
class StaticPage extends BaseModel
{
    use ElementTrait, FaqTrait, Sluggable;

    const CACHE_KEY = 'static_pages';

    protected $table = 'static_pages';

    protected $fillable = [
        'title',
        'slug',
        'body',
        'is_active',
        'meta_title',
        'meta_description',
        'keywords',
        'image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    protected static function booted(): void
    {
        static::saved(fn () => self::forgetCache());
        static::deleted(fn () => self::forgetCache());
    }

    public static function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY.'_rows_v1');
    }

    /**
     * Cached active static pages with related elements eager-loaded (pivot order by id).
     */
    public static function getCached(): Collection
    {
        return static::cacheRememberManyRows(
            self::CACHE_KEY.'_rows_v1',
            86400,
            fn () => static::query()
                ->where('is_active', true)
                ->orderBy('title')
                ->get(),
            [self::CACHE_KEY],
            'elements',
        );
    }
}
