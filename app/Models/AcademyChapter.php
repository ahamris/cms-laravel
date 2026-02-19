<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin IdeHelperAcademyChapter
 */
class AcademyChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'academy_category_id',
        'name',
        'description',
        'sort_order',
    ];

    public function category()
    {
        return $this->belongsTo(AcademyCategory::class, 'academy_category_id');
    }

    public function videos()
    {
        return $this->hasMany(AcademyVideo::class, 'academy_chapter_id')->orderBy('sort_order')->orderBy('title');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
