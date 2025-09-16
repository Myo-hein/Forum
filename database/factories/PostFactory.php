<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use App\Support\PostFixtures;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    private static Collection $fixtures;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => str(fake()->sentence)->beforeLast('-')->title(),
            'body' => Collection::times(4, fn() => fake()->realText(1250))->join(PHP_EOL . PHP_EOL),
        ];
    }

    public function withFixture(): static
    {
        return $this->sequence(...PostFixtures::getFixtures());
    }

}
