<?php

namespace App\Models\Pivots;

use App\Models\Page;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ElementPagePivot extends Pivot
{
    protected $table = 'element_page';

    /**
     * @var bool
     */
    public $incrementing = true;

    protected static function booted(): void
    {
        static::saved(fn () => Page::forgetCache());
        static::deleted(fn () => Page::forgetCache());
    }
}
