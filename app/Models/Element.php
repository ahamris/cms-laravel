<?php

namespace App\Models;

use App\Enums\ElementType;

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
}
