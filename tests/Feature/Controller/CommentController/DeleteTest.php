<?php

use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('deletes a comment and redirect to posts.show', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->delete(route('comments.destroy', $comment))
        ->assertRedirect(route('posts.show', $comment->post));
});


it('prevents guests from deleting a comment', function () {
    $comment = Comment::factory()->create();

    $response = $this->delete(route('comments.destroy', $comment));
    $response->assertRedirect(route('login'));
    $this->assertDatabaseHas('comments', ['id' => $comment->id]);
});

it('prevents deleting a comment you didnt create', function () {
    $comment = Comment::factory()->create();

    $this->actingAs(User::factory()->create())
        ->delete(route('comments.destroy', $comment))
        ->assertForbidden();
});

it('prevents deleting a comment posted more than an hour ago', function () {
    $this->freezeTime();

    $comment = Comment::factory()->create();

    $this->travel(61)->minutes();

    $this->actingAs(User::factory()->create())
        ->delete(route('comments.destroy', $comment))
        ->assertForbidden();
});
