<?php

use App\Http\Resources\PostResource;
use App\Models\Post;

use function Pest\Laravel\get;

test('It Renders the Index Page', function () {

    Post::factory(3)->create();

    get(route('posts.index'))
        ->assertComponent('Posts/Index')
        ->assertHasResource(
            'post',
            PostResource::make(Post::first())
        )
        ->assertHasPaginatedResource(
            'posts',
            PostResource::collection(Post::with('user')->latest('id')->paginate())
        );
});

test('It pass posts', function () {

    $posts = Post::factory(3)->create();

    get(route('posts.index'))
        ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});
