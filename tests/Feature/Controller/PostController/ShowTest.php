<?php

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;

use function Pest\Laravel\get;

test('can show a post', function () {
    $post = Post::factory()->create();

    $post->load('user');

    get($post->showRoute())
        ->assertComponent('Posts/Show');
});

test('passes comments to the view', function () {
    $post = Post::factory()->create();
    $comments = Comment::factory(2)->for($post)->create();

    $comments->load('user');

    get($post->showRoute())
        ->assertHasPaginatedResource('comments', CommentResource::collection($comments->reverse()));
});
