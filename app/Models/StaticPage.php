<?php

namespace App\Models;

use App\Models\Traits\FaqTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin IdeHelperStaticPage
 */
class StaticPage extends BaseModel
{
    use FaqTrait, Sluggable;

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

    public function getCached(): array
    {
        return Cache::remember(self::CACHE_KEY, 86400, function () {
            return self::toBase()
                ->where('is_active', true)
                ->get();
        });
    }
}
