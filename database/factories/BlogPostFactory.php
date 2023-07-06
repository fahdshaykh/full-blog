<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->sentence(10),
            'content' => fake()->paragraph(5, true),
            'created_at' => fake()->dateTimeBetween('-3 months'),
        ];
    }

    /**
 * Indicate that the post with fixed title and content.
 *
 * @return \Illuminate\Database\Eloquent\Factories\Factory
 */
    public function title()
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => 'New title',
                'content' => 'Content of the blog post'
            ];
        });
    }
}
