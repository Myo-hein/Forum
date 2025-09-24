<?php

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it ('requires authentication', function () {
    $this->expectException(AuthenticationException::class);

    post(route('likes.store', ['post', 1]));
});

it('allows liking a likeable', function (Model $likeable) {
    $user = User::factory()->create();

    actingAs($user)
        ->fromRoute('dashboard')
        ->post(route('likes.store', [
            'id' => $likeable->id,
            'type' => $likeable->getMorphClass(),
        ]))
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('likes', [
        'user_id' => $user->id,
        'likeable_id' => $likeable->id,
        'likeable_type' => $likeable->getMorphClass(),
    ]);

    expect($likeable->refresh()->likes_count)->toBe(1);
})->with([
    fn() => Post::factory()->create(),
    fn() => Comment::factory()->create()
]);


it ('prevents liking something you already liked', function () {
    $like = Like::factory()->create();
    $likeable = $like->likeable;

    $this->expectException(AuthorizationException::class);

    actingAs($like->user)
        ->post(route('likes.store', [
            $likeable->getMorphClass(),
            $likeable->id
        ]));
});


it ('only allows liking supported models', function () {
    $user = User::factory()->create();

    $this->expectException(AuthorizationException::class);

    actingAs($user)
        ->post(route('likes.store', [
            'type' => $user->getMorphClass(),
            'id' => $user->id
        ]));
});

it ('throw a 404 if the type is unsupported', function () {
    $this->expectException(ModelNotFoundException::class);

    actingAs(User::factory()->create())
        ->post(route('likes.store', ['foo', 1]))
        ->assertNotFound();
});
