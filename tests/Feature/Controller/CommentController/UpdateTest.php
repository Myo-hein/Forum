<?php

use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

it('requires authentication to update a comment', function () {
    $comment = Comment::factory()->create();

    $this->expectException(AuthenticationException::class);

    put(route('comments.update', $comment), [
        'content' => 'Updated content',
    ]);
})->only();

it('a user can update his comment', function () {
    $comment = Comment::factory()->create([
        'body' => 'Original content',
    ]);

    actingAs($comment->user)
        ->put(route('comments.update', $comment), [
            'body' => 'Updated content',
        ]);

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'body' => 'Updated content'
    ]);
})->only();

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
})->only();

it('can not update comment from other users', function () {
    $comment = Comment::factory()->create();

    actingAs(User::factory()->create())
        ->put(route('comments.update', $comment), [
            'body' => 'Updated content',
            'page' => 2,
        ])->assertForbidden();
})->throws(AuthorizationException::class)->only();

it('requires a valid body', function ($value) {
    $comment = Comment::factory()->create();

    $this->withoutExceptionHandling();

    actingAs($comment->user)
        ->put(route('comments.update', $comment->post), [
            'body' => $value,
            'page' => 2,
        ]);
})->throws(ValidationException::class)->with([
    null,
    1,
    1.5,
    true,
    str_repeat('a', 2501),
])->only();
