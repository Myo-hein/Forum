<?php

use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\get;

test('It Renders the Index Page', function () {
    get(route('posts.index'))
        ->assertInertia(
            fn(AssertableInertia $inertia) => $inertia
                ->component('Posts/Index', true)
        );
});

test('It pass posts', function () {
    get(route('posts.index'))
        ->assertInertia(
            fn(AssertableInertia $inertia) => $inertia
                ->has('posts')
        );
});
