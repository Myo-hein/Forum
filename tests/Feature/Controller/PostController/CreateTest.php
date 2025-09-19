<?php

use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;

use function Pest\Laravel\get;

it('requires authentication!', function () {

    expect(fn() => get(route('posts.create')))
        ->toThrow(AuthenticationException::class);
});

it('render Posts/Create Inertia Component!', function () {
    $topics = Topic::factory(3)->create();

    $this->actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertHasResource('topics', TopicResource::collection($topics))
        ->assertComponent('Posts/Create');
});
