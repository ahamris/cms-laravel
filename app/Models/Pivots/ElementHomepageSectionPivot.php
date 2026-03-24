<?php

namespace App\Models\Pivots;

use App\Models\HomepageSection;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Cache;

class ElementHomepageSectionPivot extends Pivot
{
    protected $table = 'element_homepage_section';

    /**
     * @var bool
     */
    public $incrementing = true;

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(HomepageSection::CACHE_KEY));
        static::deleted(fn () => Cache::forget(HomepageSection::CACHE_KEY));
    }
}
