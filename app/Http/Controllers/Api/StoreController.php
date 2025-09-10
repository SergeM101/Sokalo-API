<?php

namespace App\Http\Controllers\Api;

use App\Enums\StoreCategory;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class StoreController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of all approved stores.
     */
    public function index()
    {
        // Fetch only approved stores and paginate the results
        $stores = Store::where('verificationStatus', 'approved')->paginate(15);

        return response()->json($stores);
    }

    /**
     * Store a newly created store in the database (for logged-in Store Owners).
     */
    public function store(Request $request)
    {
        // 1. Get the authenticated user
        $user = $request->user();

        // 2. Check if the user is a Store Owner
        if ($user->role !== UserRole::STORE_OWNER) {
            return response()->json(['message' => 'Only store owners can register a store.'], 403); // Forbidden
        }

        // 3. Validate the incoming data
        $validator = Validator::make($request->all(), [
            'officialName' => 'required|string|max:255', // Unique constraint will be checked manually
            'address' => 'required|string', // Unique constraint will be checked manually
            'category' => ['required', new Enum(StoreCategory::class)], // Enum validation
            'contactEmail' => 'required|email|unique:stores',   // Unique constraint
            'contactPhone' => 'required|string|unique:stores',  // Unique constraint
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 4. Create the store and link it to the owner
        $store = $user->store()->create([
            'officialName' => $request->officialName,
            'address' => $request->address,
            'category' => $request->category,
            'contactEmail' => $request->contactEmail,
            'contactPhone' => $request->contactPhone,
            // verificationStatus defaults to 'pending' from the migration
        ]);

        // 5. Return the newly created store
        return response()->json($store, 201); // 201 Created
    }

    /**
     * Update the specified store in storage.
     */
    public function update(Request $request, Store $store)
    {
        // 1. Authorize the action using the StorePolicy
        $this->authorize('update', $store);

        // 2. Validate the incoming data
        $validator = Validator::make($request->all(), [
            'officialName' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string',
            'category' => ['sometimes', 'required', new Enum(StoreCategory::class)],
            // Unique rule ignores the current store's email
            'contactEmail' => 'sometimes|required|email|unique:stores,contactEmail,' . $store->storeID . ',storeID',
            'contactPhone' => 'sometimes|required|string|unique:stores,contactPhone,' . $store->storeID . ',storeID',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 3. Update the store with validated data
        $store->update($validator->validated());

        // 4. Return the updated store
        return response()->json($store);
    }

    /**
     * Display the specified store.
     */
    public function show(Store $store)
    {
        // Using Route Model Binding, Laravel automatically finds the store for us.
        return response()->json($store);
    }

    /**
     * Get the store associated with the authenticated user. Store owner dashboard feature.
     */
    public function getUserStore(Request $request)
    {
        // Get the store associated with the authenticated user
        $store = $request->user()->store;

        if (!$store) {
            return response()->json(['message' => 'Store not found for this user.'], 404);
        }

        return response()->json($store);
    }
}
