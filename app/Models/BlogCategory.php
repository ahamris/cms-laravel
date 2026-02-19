<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperBlogCategory
 */
class BlogCategory extends BaseModel
{
    use HasFactory, Sluggable;

    protected $table = 'blog_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'maxLength' => 255,
                'separator' => '-',
                'includeTrashed' => true,
            ],
        ];
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'blog_category_id');
    }
}
