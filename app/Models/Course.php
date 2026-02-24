<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Course extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'course_category_id',
        'name',
        'description',
        'sort_order',
    ];

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function videos()
    {
        return $this->hasMany(CourseVideo::class, 'course_id')->orderBy('sort_order')->orderBy('title');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
