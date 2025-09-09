<?php

use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\ValidationException;

it('requires authentication to update a comment', function () {
    $comment = Comment::factory()->create();

    $this->expectException(AuthenticationException::class);

    put(route('comments.update', $comment), [
        'content' => 'Updated content',
    ]);
});

it('a user can update his comment', function () {
    $comment = Comment::factory()->create([
        'body' => 'Original content',
    ]);

    /** @var \App\Models\User $user */
    $user = $comment->user;

    actingAs($user)
        ->put(route('comments.update', $comment), [
            'body' => 'Updated content',
        ]);

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'body' => 'Updated content'
    ]);
});

it('redirect post show page by post id after comment updated', function () {
    $comment = Comment::factory()->create([
        'body' => 'Original content',
    ]);

    $newComment = 'Updated content';

    $response = actingAs($comment->user)
        ->put(route('comments.update', $comment), [
            'body' => $newComment,
            'page' => 2,
        ]);

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'body' => $newComment
    ]);

    $response->assertRedirect(route('posts.show', $comment->post));
});

it('can not update comment from other users', function () {
    $comment = Comment::factory()->create();

    $user = User::factory()->create();

    $this->actingAs($user)
        ->put(route('comments.update', $comment), [
            'body' => 'Updated content',
            'page' => 2,
        ])->assertForbidden();
})->throws(AuthorizationException::class);

it('requires a valid body', function ($value) {
    $comment = Comment::factory()->create();

    try {
        actingAs($comment->user)
            ->put(route('comments.update', $comment->post), [
                'body' => $value,
                'page' => 2,
            ]);

        throw new \Exception('Expected ValidationExcepiton was not thrown');
    } catch (ValidationException $e) {
        expect($e->errors())->toHaveKey('body');
    }
})->with([
    null,
    1,
    1.5,
    true,
    str_repeat('a', 2501),
]);
