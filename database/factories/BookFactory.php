<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'genre' => fake()->randomElement([
                'Klasika',
                'Fantastika',
                'Trilleri',
                'Vēsture',
                'Bizness',
                'Dramaturģija',
                'Bērniem'
            ]),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2,2,13),
            'cover' => '/image/book'.fake()->numberBetween(1,10).'.jpg',
            'publishing_year' => fake()->numberBetween(1950,2025),
        ];
    }
}
