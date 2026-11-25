<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            "name" => fake()->name(),
            "image" => "public/uploads/image.jpg",
            "description" => fake()->text(),
            "user_id" => 1,
            "categoria_id" => 1
        ];
    }
}
