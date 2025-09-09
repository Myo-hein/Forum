<?php

use App\Models\User;
use Illuminate\Auth\AuthenticationException;

use function Pest\Laravel\get;

it('requires authentication!', function () {

    expect(fn() => get(route('posts.create')))
        ->toThrow(AuthenticationException::class);
});

it('render Posts/Create Inertia Component!', function () {

    $this->actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertComponent('Posts/Create');
});
