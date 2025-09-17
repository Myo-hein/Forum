<?php

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Topic;

use function Pest\Laravel\get;

it('should return the correct component', function () {
    get(route('posts.index'))
        ->assertComponent('Posts/Index');
});

it('passes posts to the view', function () {
    $posts = Post::factory(3)->create();

    $posts->load(['user', 'topic']);

    get(route('posts.index'))
        ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});

it('can filter to a topic', function () {
    $general = Topic::factory()->create();

    $posts = Post::factory(3)->for($general)->create();

    $otherPosts = Post::factory(3)->create();

    get(route('posts.index', ['topic' => $general]))
        ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});
