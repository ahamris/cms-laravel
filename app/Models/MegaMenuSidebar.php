<?php

namespace App\Models;

use App\Models\MegaMenuItem;

/**
 * @mixin IdeHelperMegaMenuSidebar
 */
class MegaMenuSidebar extends BaseModel
{
    protected $table = 'mega_menu_sidebars';

    protected $fillable = [
        'mega_menu_item_id',
        'title',
        'description',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function megaMenuItem()
    {
        return $this->belongsTo(MegaMenuItem::class, 'mega_menu_item_id');
    }

    protected static function boot(): void
    {
        parent::boot();
        static::saved(fn () => MegaMenuItem::clearCache());
        static::deleted(fn () => MegaMenuItem::clearCache());
    }
}
