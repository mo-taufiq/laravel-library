<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\BookSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AuthorSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            UserSeeder::class,
            AuthorSeeder::class,
            BookSeeder::class
        ]);
    }
}
