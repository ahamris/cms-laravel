<?php

namespace App\Models\Pivots;

use App\Models\StaticPage;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ElementStaticPagePivot extends Pivot
{
    protected $table = 'element_static_page';

    /**
     * @var bool
     */
    public $incrementing = true;

    protected static function booted(): void
    {
        static::saved(fn () => StaticPage::forgetCache());
        static::deleted(fn () => StaticPage::forgetCache());
    }
}
