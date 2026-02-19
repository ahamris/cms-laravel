<?php

namespace App\Models\Traits;

use App\Models\Faq;

trait FaqTrait
{
    public function faqs()
    {
        return $this->morphMany(Faq::class, 'entity');
    }
}
