<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageLayoutAssignment extends BaseModel
{
    protected $fillable = [
        'page_id',
        'page_layout_template_row_id',
        'element_id',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function templateRow(): BelongsTo
    {
        return $this->belongsTo(PageLayoutTemplateRow::class, 'page_layout_template_row_id');
    }

    public function element(): BelongsTo
    {
        return $this->belongsTo(Element::class);
    }
}
