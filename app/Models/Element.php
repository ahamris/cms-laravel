<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class Element extends BaseModel
{
    const TYPE_CTA = 'cta';
    const TYPE_FAQ = 'faq';
    const TYPE_RELATED_CONTENT = 'related_content';

    protected $fillable = [
        'type',
        'title',
        'sub_title',
        'description',
        'options',
        'entity_type',
        'entity_id',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
