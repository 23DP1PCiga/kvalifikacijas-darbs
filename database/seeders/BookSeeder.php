<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     Book::create([
            'title' => 'The Sixteenth Rail',
            'author' => 'Adam J. Schrager',
            'cover' => '/image/book2.jpg'
        ]);

        Book::create([
            'title' => 'Spirit and Reason',
            'author' => 'Vine Deloria',
            'cover' => '/image/book3.jpg'
        ]);

        Book::create([
            'title' => 'The Soul of Man Under Socialism',
            'author' => 'Oscar Wilde',
            'cover' => '/image/book4.jpg'
        ]);

        Book::create([
            'title' => 'The Last Landlady',
            'author' => 'Laura Thompson',
            'cover' => '/image/book5.jpg'
        ]);

         Book::create([
            'title' => 'The Radical Fund',
            'author' => 'John Fabian Witt',
            'cover' => '/image/book1.jpg'
        ]);
    }
}
