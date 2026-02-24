<?php

namespace App\Models;

/**
 * @mixin IdeHelperCommentVote
 */
class CommentVote extends BaseModel
{
    protected $fillable = [
        'comment_id',
        'user_id',
        'ip_address',
        'user_agent',
        'type'
    ];
}
