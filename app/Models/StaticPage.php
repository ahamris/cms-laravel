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
        'selected_call_actions',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'selected_call_actions' => 'array',
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
     * Get available call actions for selection
     */
    public static function getAvailableCallActions()
    {
        return CallAction::active()
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    /**
     * Get selected call actions
     */
    public function getSelectedCallActions()
    {
        if (! $this->selected_call_actions) {
            return collect();
        }

        return CallAction::whereIn('id', $this->selected_call_actions)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
