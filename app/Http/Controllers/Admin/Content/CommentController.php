<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Admin\AdminBaseController;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.content.comment.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return view('admin.content.comment.show', compact('comment'));
    }

    /**
     * Toggle the approval status of the comment.
     */
    public function toggleApprove(Comment $comment)
    {
        $comment->update([
            'is_approved' => !$comment->is_approved
        ]);

        return back()->with('success', $comment->is_approved ? 'Comment approved.' : 'Comment unapproved.');
    }

}
