<?php

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->validData = fn () => [
        'title' => 'New Post Title',
        'topic_id' => Topic::factory()->create()->getKey(),
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
    $data = value($this->validData);

    $this->actingAs($user)->post(route('posts.store'), $data);

    $this->assertDatabaseHas(Post::class, [
        ...$data,
        'user_id' => $user->id
    ]);
});


it('redirects to the post show page', function () {
    $user = User::factory()->create();
    $data = value($this->validData);

    $this->actingAs($user)
        ->post(route('posts.store', $data))
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});


it('requires a valid title', function ($badTitle) {
    $user = User::factory()->create();
    $data = value($this->validData);

    try {
        $this->actingAs($user)
            ->post(route('posts.store'), [
                ...$data,
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
    $data = value($this->validData);

    try {
        $this->actingAs($user)
            ->post(route('posts.store'), [
                ...$data,
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
