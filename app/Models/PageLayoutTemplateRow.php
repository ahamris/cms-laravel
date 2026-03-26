<?php

namespace App\Models;

use App\Enums\PageLayoutRowKind;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageLayoutTemplateRow extends BaseModel
{
    protected $fillable = [
        'page_layout_template_id',
        'row_kind',
        'sort_order',
        'label',
        'section_category',
    ];

    protected function casts(): array
    {
        return [
            'row_kind' => PageLayoutRowKind::class,
            'sort_order' => 'integer',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(PageLayoutTemplate::class, 'page_layout_template_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(PageLayoutAssignment::class, 'page_layout_template_row_id');
    }
}
