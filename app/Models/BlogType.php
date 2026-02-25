<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperBlogType
 */
class BlogType extends BaseModel
{
    protected $fillable = ['name'];

    /**
     * Blogs that use this type.
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'blog_type_id');
    }
}
