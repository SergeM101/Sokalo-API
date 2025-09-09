<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use App\Enums\UserRole;

class StoreSeeder extends Seeder
{
    public function run()
    {
        // Get all users who are store owners
        $storeOwners = User::where('role', UserRole::STORE_OWNER)->get();

        // Create a store for each store owner
        foreach ($storeOwners as $owner) {
            Store::factory()->create([
                'user_id' => $owner->userID, // Associate the store with the user
            ]);
        }
    }
    
}
