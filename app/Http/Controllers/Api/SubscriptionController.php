<?php

namespace App\Http\Controllers\Api;

use App\Enums\SubscriptionPlanType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        $store = $user->store;

        if (!$store) {
            return response()->json(['message' => 'User does not own a store.'], 403);
        }

        // 1. Validate the request
        $validator = Validator::make($request->all(), [
            'planType' => ['required', new Enum(SubscriptionPlanType::class)],
            // You would also validate payment details here from an external API
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Check for an existing active subscription (optional, depends on business logic)
        if ($store->subscription && $store->subscription->subStatus === 'active') {
            return response()->json(['message' => 'Store already has an active subscription.'], 409); // 409 Conflict
        }

        // 3. Create the new subscription (assuming payment is successful)
        $subscription = $store->subscription()->create([
            'planType' => $request->planType,
            'subStatus' => 'active',
            'startDate' => now(),
            // Set end date based on plan, e.g., one month from now
            'endDate' => Carbon::now()->addMonth(),
        ]);

        return response()->json($subscription, 201);
    }
}
