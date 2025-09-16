<?php

use App\Models\Post;

it('generate the html', function () {
    $comment = Post::factory()->make([
        'body' => '## Hello world'
    ]);

    $comment->save();

    expect($comment->html)
        ->toEqual(str($comment->body)->markdown());
});
