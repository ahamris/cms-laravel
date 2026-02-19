<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;

/**
 * @mixin IdeHelperAcademyCategory
 */
class AcademyCategory extends BaseModel
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_path',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function chapters()
    {
        return $this->hasMany(AcademyChapter::class, 'academy_category_id')->ordered();
    }

    public function videos()
    {
        return $this->hasMany(AcademyVideo::class, 'academy_category_id')->orderBy('sort_order')->orderBy('title');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
