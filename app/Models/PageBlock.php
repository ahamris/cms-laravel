<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageBlock extends BaseModel
{
    protected $fillable = [
        'page_id',
        'type',
        'sort_order',
        'content',
        'settings',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'content'    => 'array',
            'settings'   => 'array',
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public static function supportedTypes(): array
    {
        return [
            'hero', 'text', 'text_image', 'cta', 'faq',
            'embed', 'gallery', 'testimonial', 'form', 'html',
        ];
    }
}
