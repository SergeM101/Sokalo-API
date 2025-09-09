<?php

// in app/Policies/StorePolicy.php
namespace App\Policies;

use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Store $store): bool
    {
        // Only allow the update if the user's ID matches the store's user_id
        return $user->userID === $store->user_id;
    }
}
