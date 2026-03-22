<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends BaseModel
{
    use Sluggable;

    protected $fillable = ['name', 'slug', 'type'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source'         => 'name',
                'maxLength'      => 100,
                'separator'      => '-',
                'includeTrashed' => true,
            ],
        ];
    }

    public function articles(): MorphToMany
    {
        return $this->morphedByMany(Blog::class, 'taggable');
    }

    public function pages(): MorphToMany
    {
        return $this->morphedByMany(Page::class, 'taggable');
    }
}
