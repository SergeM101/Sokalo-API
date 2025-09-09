<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StoreVerificationStatus;
use App\Http\Controllers\Controller;
use App\Models\Store;

class StoreVerificationController extends Controller
{
    /**
     * Get a list of all stores pending verification.
     */
    public function getPendingStores()
    {
        $pendingStores = Store::where('verificationStatus', 'pending')->with('owner')->get();
        return response()->json($pendingStores);
    }

    /**
     * Approve a store's registration.
     */
    public function approve(Store $store)
    {
        $store->verificationStatus = StoreVerificationStatus::APPROVED;
        $store->save();

        // Here you would send the 'StoreApprovedMail' we discussed earlier!
        // Mail::to($store->owner->email)->send(new StoreApprovedMail($store));

        return response()->json(['message' => 'Store approved successfully.', 'store' => $store]);
    }

    /**
     * Reject a store's registration.
     */
    public function reject(Store $store)
    {
        $store->verificationStatus = StoreVerificationStatus::REJECTED;
        $store->save();

        // You could also create and send a 'StoreRejectedMail' here.

        return response()->json(['message' => 'Store rejected.', 'store' => $store]);
    }
}
