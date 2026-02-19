<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCommentVote
 */
class CommentVote extends Model
{
    protected $fillable = [
        'comment_id',
        'user_id',
        'ip_address',
        'user_agent',
        'type'
    ];
}
