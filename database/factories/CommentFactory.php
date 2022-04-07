<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'body' => '<p>' . implode('</p><p>', $this->faker->paragraphs(3)) . '</p>',
            'score' => rand(1, 5),
            'user_id' => User::factory(),
            'post_id' => Post::factory()
        ];
    }
}
