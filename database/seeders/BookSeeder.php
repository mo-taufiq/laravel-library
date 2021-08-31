<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Book::insert(
            [
                [
                    'book_id' => uniqid("BOOK_", true),
                    'title' => 'Book 1',
                    'author_id' => 'AUTHOR_1',
                    'description' => 'Description book 1'
                ],
                [
                    'book_id' => uniqid("BOOK_", true),
                    'title' => 'Book 2',
                    'author_id' => 'AUTHOR_2',
                    'description' => 'Description book 2'
                ],
                [
                    'book_id' => uniqid("BOOK_", true),
                    'title' => 'Book 3',
                    'author_id' => 'AUTHOR_3',
                    'description' => 'Description book 3'
                ]
            ]
        );
    }
}
