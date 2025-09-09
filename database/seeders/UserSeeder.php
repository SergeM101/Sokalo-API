<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder; // Import the necessary classes
use App\Models\User;    // Import the User model
use App\Enums\UserRole; // Import the UserRole enum

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create 10 consumer users
        User::factory(10)->create();

        // Create 5 store owner users
        User::factory(5)->create([
            'role' => UserRole::STORE_OWNER,
        ]);

        // Create one Admin user
        User::factory()->create([
            'userName' => 'Admin User',
            'email' => 'admin@sokalo.com',
            'role' => UserRole::ADMIN,
        ]);
    }
}
