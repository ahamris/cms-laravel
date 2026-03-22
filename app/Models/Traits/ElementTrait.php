<?php

namespace App\Models\Traits;

use App\Models\Element;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait ElementTrait
{
    public function elements(): MorphMany
    {
        return $this->morphMany(Element::class, 'entity');
    }
}
