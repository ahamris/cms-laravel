<?php

namespace App\Models\Traits;

use App\Models\Element;
use App\Models\Pivots\ElementPagePivot;
use App\Models\Pivots\ElementStaticPagePivot;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait ElementTrait
{
    public function elements(): BelongsToMany
    {
        return $this->belongsToMany(
            Element::class,
            $this->elementPivotTable(),
            $this->getForeignKey(),
            'element_id'
        )
            ->using($this->elementPivotClass())
            ->withTimestamps()
            ->orderByPivot('id');
    }

    protected function elementPivotTable(): string
    {
        return $this->getTable() === 'static_pages'
            ? 'element_static_page'
            : 'element_page';
    }

    /**
     * @return class-string<ElementPagePivot|ElementStaticPagePivot>
     */
    protected function elementPivotClass(): string
    {
        return $this->getTable() === 'static_pages'
            ? ElementStaticPagePivot::class
            : ElementPagePivot::class;
    }
}
