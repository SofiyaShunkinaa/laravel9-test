<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->unique()->sentence(6),
            'body' => fake()->paragraph(3, true),
            'author_id' => User::factory(),
            'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'status' => fake()->randomElement(['draft', 'published']),
        ];
    }
}
