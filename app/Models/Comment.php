<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperComment
 */
class Comment extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'body',
        'rating',
        'parent_id',
        'likes',
        'dislikes',
        'is_approved',
        'entity_id',
        'entity_type',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'rating' => 'integer',
        'likes' => 'integer',
        'dislikes' => 'integer',
    ];

    /**
     * Scope a query to only include approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', 1);
    }

    /**
     * Scope to get only parent comments (not replies).
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get the parent entity model (e.g., Blog, Service).
     */
    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment of this reply.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the replies for this comment.
     */
    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->approved()->latest();
    }
}
