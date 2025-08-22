<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $users = User::factory(10)->create();

        $posts = Post::factory(200)
            ->recycle($users)
            ->create();

        $comments = Comment::factory(100)
            ->recycle($users)
            ->recycle($posts)
            ->create();

        $me = User::factory()
            ->has(Post::factory(45))
            ->has(Comment::factory(100)->recycle($posts))
            ->create([
                'name' => 'Myo Hein',
                'email' => 'myohein@mail.com'
            ]);
    }
}
