<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\LazyCollection;

use function Laravel\Prompts\progress;

class LikeLoadTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $post = Post::find(1);

        $progress = progress(label: 'Creating Likes', steps: 500);

        LazyCollection::times(50)->each(function () use ($post, $progress) {
            Like::factory(10)->for($post, 'likeable')->create();
            $progress->advance(10);
        });

        $progress->finish();

        $post->increment('likes_count', 500);
    }
}
