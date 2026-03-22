<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleSeries extends BaseModel
{
    use Sluggable;

    protected $table = 'article_series';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'    => 'title',
                'maxLength' => 200,
                'separator' => '-',
            ],
        ];
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Blog::class, 'series_id')->orderBy('series_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
