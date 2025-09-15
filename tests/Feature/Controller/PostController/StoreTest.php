<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->validData = [
        'title' => 'New Post Title',
        'body' => 'This is the body of the new post.',
    ];
});

it('requires authentication!', function () {
    $post = Post::factory()->create();


    $this->expectException(AuthenticationException::class);

    post(route('posts.store', $post));
});


it('store a post!', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('posts.store'), $this->validData);

    $this->assertDatabaseHas(Post::class, [
        ...$this->validData,
        'user_id' => $user->id
    ]);
});


it('redirects to the post show page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('posts.store', $this->validData))
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});


it('requires a valid title', function ($badTitle) {
    $user = User::factory()->create();

    try {
        $this->actingAs($user)
            ->post(route('posts.store'), [
                ...$this->validData,
                'title' => $badTitle
            ]);

        throw new \Exception('Expected ValidationException was not thrown');
    } catch (ValidationException $e) {
        expect($e->errors())->toHaveKey('title');
    }
})->with([
    null,
    false,
    '',
    str_repeat('a', 256)
]);

it('requires a valid body', function ($badBody) {
    $user = User::factory()->create();

    try {
        $this->actingAs($user)
            ->post(route('posts.store'), [
                ...$this->validData,
                'title' => $badBody
            ]);

        throw new \Exception('Expected ValidationException was not thrown');
    } catch (ValidationException $e) {
        expect($e->errors())->toHaveKey('title');
    }
})->with([
    null,
    false,
    '',
    str_repeat('a', 3000)
]);
