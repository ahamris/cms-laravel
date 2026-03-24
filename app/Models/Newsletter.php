<?php

namespace App\Models;

class Newsletter extends BaseModel
{
    protected $fillable = [
        'email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
