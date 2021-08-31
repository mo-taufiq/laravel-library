<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Author::insert(
            [
                [
                    'author_id' => 'AUTHOR_1',
                    'name' => 'Author 1',
                ],
                [
                    'author_id' => 'AUTHOR_2',
                    'name' => 'Author 2',
                ],
                [
                    'author_id' => 'AUTHOR_3',
                    'name' => 'Author 3',
                ]
            ]
        );
    }
}
