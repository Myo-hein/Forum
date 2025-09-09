<?php

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

use function Pest\Laravel\actingAs;

it('deletes a comment and redirect to posts.show', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->delete(route('comments.destroy', $comment))
        ->assertRedirect(route('posts.show', $comment->post));
});


it('prevents guests from deleting a comment', function () {
    $comment = Comment::factory()->create();

    expect(fn() => $this->delete(route('comments.destroy', $comment)))
        ->toThrow(AuthenticationException::class);
});

it('prevents deleting a comment you didnt create', function () {
    $comment = Comment::factory()->create();
    $user = User::factory()->create();

    expect(fn() => $this->actingAs($user)
        ->delete(route('comments.destroy', $comment)))
        ->toThrow(AuthorizationException::class);
});
