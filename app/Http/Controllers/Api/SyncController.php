<?php
// in app/Http/Controllers/Api/SyncController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SyncController extends Controller
{
    /**
     * Receives item data from the local desktop application and updates the central database.
     */
    public function syncItems(Request $request)
    {
        $user = Auth::user();
        $storeId = $user->store_id;

        if (!$storeId) {
            return response()->json(['message' => 'User is not associated with a store.'], 403);
        }

        try {
            // Directly validate the array of items from the request body
            $validatedData = $request->validate([
                '*.itemName' => 'required|string|max:255',
                '*.barcode' => 'required|string|max:255',
                '*.sellingPrice' => 'required|numeric',
                '*.stockQuantity' => 'required|integer',
                '*.expiryDate' => 'required|date',
            ]);

            // Prepare data for upsert by adding the store_id to each item
            $itemsToUpsert = array_map(function ($item) use ($storeId) {
                return [
                    'store_id' => $storeId,
                    'item_name' => $item['itemName'],
                    'barcode' => $item['barcode'],
                    'selling_price' => $item['sellingPrice'],
                    'stock_quantity' => $item['stockQuantity'],
                    'expiry_date' => $item['expiryDate'],
                ];
            }, $validatedData);

            // Use upsert for efficient database operations
            Item::upsert(
                $itemsToUpsert,
                ['store_id', 'barcode'], // Unique keys to identify existing records
                ['item_name', 'selling_price', 'stock_quantity', 'expiry_date'] // Columns to update
            );

            return response()->json(['message' => 'Sync successful'], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Invalid data provided', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred during sync.', 'error' => $e->getMessage()], 500);
        }
    }
}
