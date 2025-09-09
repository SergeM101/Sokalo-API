<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            // Call individual seeders here. The order matters if there are dependencies (e.g., users before stores).
            UserSeeder::class,
            StoreSeeder::class,
            // Add other seeders here later...
        ]);
    }
}
