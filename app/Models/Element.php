<?php

namespace App\Models;

use App\Enums\ElementType;
use App\Models\Pivots\ElementHomepageSectionPivot;
use App\Models\Pivots\ElementPagePivot;
use App\Models\Pivots\ElementStaticPagePivot;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Element extends BaseModel
{
    protected $fillable = [
        'type',
        'title',
        'sub_title',
        'description',
        'options',
    ];

    protected $casts = [
        'type' => ElementType::class,
        'options' => 'array',
    ];

    public function scopeByType($query, ElementType|string $type)
    {
        $value = $type instanceof ElementType ? $type->value : $type;

        return $query->where('type', $value);
    }

    protected static function booted(): void
    {
        static::saved(fn () => self::forgetParentCaches());
        static::deleted(fn () => self::forgetParentCaches());
    }

    protected static function forgetParentCaches(): void
    {
        Page::forgetCache();
        StaticPage::forgetCache();
    }

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'element_page', 'element_id', 'page_id')
            ->using(ElementPagePivot::class)
            ->withTimestamps()
            ->orderByPivot('id');
    }

    public function staticPages(): BelongsToMany
    {
        return $this->belongsToMany(StaticPage::class, 'element_static_page', 'element_id', 'static_page_id')
            ->using(ElementStaticPagePivot::class)
            ->withTimestamps()
            ->orderByPivot('id');
    }

    public function homepageSections(): BelongsToMany
    {
        return $this->belongsToMany(HomepageSection::class, 'element_homepage_section', 'element_id', 'homepage_section_id')
            ->using(ElementHomepageSectionPivot::class)
            ->withTimestamps()
            ->orderByPivot('id');
    }
}
