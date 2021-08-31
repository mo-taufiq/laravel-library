<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert(
            [
                [
                    'user_id' => uniqid("USER_", true),
                    'username' => "admin",
                    'password' => Hash::make("admin"),
                    'role' => 'Admin',
                ],
                [
                    'user_id' => uniqid("USER_", true),
                    'username' => "client",
                    'password' => Hash::make("client"),
                    'role' => 'Non Admin',
                ],
            ]
        );
    }
}
