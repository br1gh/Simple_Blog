<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'slug' => $this->faker->slug(),
            'title' => $this->faker->sentence(),
            'excerpt' => '<p>' . implode('</p><p>', $this->faker->paragraphs(3)) . '</p>',
            'body' => '<p>' . implode('</p><p>', $this->faker->paragraphs(7)) . '</p>',
            'is_published' => 1,
        ];
    }
}
