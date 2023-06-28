<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Author;
use App\Models\Profile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    // after this you need to run command in tinker bellow for after create
    //\App\Models\Author::factory()->withProfile()->create();
    public function withProfile()
    {
        return $this->afterCreating(function (Author $author) {
            Profile::factory()->create(['author_id' => $author->id]);
        });
    }
}
