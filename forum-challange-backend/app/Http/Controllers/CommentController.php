<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteCommentRequest;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Topic;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Topic $topic)
    {
        $validated = $request->validated();

        $comment = Comment::create([
            'text' => $validated['text'],
            'topic_id' => $topic->id,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment created successfully',
            'comment' => $comment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Topic $topic, Comment $comment)
    {
        $validated = $request->validated();

        $comment->text = $validated['text'];
        $comment->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment updated successfully',
            'topic' => $comment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(DeleteCommentRequest $request, Topic $topic, Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully',
        ]);
    }
}
