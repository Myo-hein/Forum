<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use AuthorizesRequests, DispatchesJobs;

    // public function __construct()
    // {
    //     $this->authorizeResource(Comment::class, 'comment');
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $this->authorize('create', Comment::class);

        $data = $request->validate(['body' => ['required', 'string', 'max:2500']]);

        Comment::create([
            ...$data,
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
        ]);

        return to_route('posts.show', $post)->banner('Comment added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $data = $request->validate(['body' => ['required', 'string', 'max:2500']]);

        $comment->update($data);

        return to_route('posts.show', [
            'post' => $comment->post_id,
            'page' => $request->query('page')
        ])->banner('Comment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return to_route('posts.show', [
            'post' => $comment->post_id,
            'page' => $request->query('page')
        ])->banner('Comment deleted successfully.');
    }
}
