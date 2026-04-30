<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Book;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();

        foreach ($books as $book) {

            $count = rand(5, 15);

            for ($i = 0; $i < $count; $i++) {

                $user = $users->random();

                $hasComment = fake()->boolean(80);

                Review::create([
                    'book_id' => $book->id,
                    'user_id' => $user->id,
                    'rating' => rand(3, 5),
                    'comment' => $hasComment
                        ? fake()->randomElement([
                            'Ļoti laba grāmata!',
                            'Man ļoti patika ',
                            'Iesaku izlasīt!',
                            'Interesants sižets',
                            'Nebija mana gaume',
                            'Super ',
                            'Ļoti aizraujoša!'
                        ])
                        : null,
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now()
                ]);
            }
        }
    }
}