<?php

namespace App\Models;

/**
 * @mixin IdeHelperPageTailwindPlus
 */
class PageTailwindPlus extends BaseModel
{
    protected $table = 'page_tailwind_plus';

    protected $fillable = [
        'page_id',
        'tailwind_plus_id',
        'sort_order',
        'is_active',
        'custom_config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'custom_config' => 'array',
    ];

    /**
     * Get the page that owns this pivot
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the component that owns this pivot
     */
    public function tailwindPlus()
    {
        return $this->belongsTo(TailwindPlus::class);
    }
}

