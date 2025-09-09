<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt; // <-- 1. Import the Crypt facade
use Illuminate\Support\Facades\Log;     // <-- For logging errors
use Illuminate\Support\Facades\Validator;

class SyncController extends Controller
{
    public function syncItems(Request $request)
    {
        $user = $request->user();

        // 2. Expect a single 'payload' field with the encrypted data
        $payload = $request->input('payload');
        if (!$payload) {
            return response()->json(['message' => 'Encrypted payload is missing.'], 422);
        }

        try {
            // 3. Decrypt the payload to get the original item array
            $decryptedData = Crypt::decrypt($payload);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Log the error for debugging, but return a generic error to the user
            Log::error('Decryption failed for store: ' . $user->store->storeID);
            return response()->json(['message' => 'Invalid encrypted data.'], 422);
        }

        // 4. Validate the DECRYPTED data
        $validator = Validator::make($decryptedData, [
            'items' => 'required|array',
            'items.*.barcode' => 'required|string',
            'items.*.itemName' => 'required|string',
            // ... (rest of your validation rules)
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        // --- The rest of your logic remains the same ---
        $itemsToSync = $decryptedData['items'];
        $storeId = $user->store->storeID;

        foreach ($itemsToSync as &$item) {
            $item['store_id'] = $storeId;
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        Item::upsert(
            $itemsToSync,
            ['barcode', 'store_id'],
            ['itemName', 'itemType', 'sellingPrice', 'stockAvailability']
        );

        return response()->json(['message' => 'Synchronization successful.']);
    }
}