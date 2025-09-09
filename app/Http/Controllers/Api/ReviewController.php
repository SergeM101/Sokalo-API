<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display all reviews for a specific store.
     */
    public function index(Store $store)
    {
        // Get all reviews related to this store and paginate them
        $reviews = $store->reviews()->with('author:userID,userName')->paginate(10);

        return response()->json($reviews);
    }

    /**
     * Store a new review for a specific store. includes:
     * 1. Authorization: Ensure the user is a Consumer.
     * 2. Validation: Validate the incoming request data.
     * 3. Creation: Create the review and associate it with the store and user.
     * 4. Response: Return the created review with a 201 status code.
     */
    public function store(Request $request, Store $store)
    {
        $user = $request->user();

        // 1. Authorize: Check if the user is a Consumer
        if ($user->role !== UserRole::CONSUMER) {
            return response()->json(['message' => 'Only consumers can post reviews.'], 403); // Forbidden
        }

        // 2. Validate the incoming data
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 3. Create the review and link it to the store and the user
        $review = $store->reviews()->create([
            'user_id' => $user->userID,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // 4. Return the newly created review
        return response()->json($review, 201); // 201 Created
    }
}
