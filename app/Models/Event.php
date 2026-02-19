<?php

namespace App\Models;

use App\Models\Traits\ImageGetterTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
;

/**
 * @mixin IdeHelperEvent
 */
class Event extends BaseModel
{
    use ImageGetterTrait, Sluggable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cover_image',
        'image',
        'title',
        'slug',
        'short_body',
        'long_body',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'address',
        'price',
        'registration_url',
        'is_active',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'maxLength' => 255,
                'separator' => '-',
                'includeTrashed' => true,
            ],
        ];
    }
}
