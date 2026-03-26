<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class PageLayoutTemplate extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'use_header_section',
        'use_hero_section',
    ];

    protected function casts(): array
    {
        return [
            'use_header_section' => 'boolean',
            'use_hero_section' => 'boolean',
        ];
    }

    public function rows(): HasMany
    {
        return $this->hasMany(PageLayoutTemplateRow::class)->orderBy('sort_order')->orderBy('id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'page_layout_template_id');
    }
}
