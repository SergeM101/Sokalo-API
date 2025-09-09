<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Step 1: User initiates the payment from the frontend.
     */
    public function initiatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subscription_id' => 'required|exists:subscriptions,subscriptionID',
            'phone_number' => 'required|string', // The number to be charged
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 1. Create a 'pending' payment record in our database
        $payment = Payment::create([
            'user_id' => $request->user()->userID,
            'subscription_id' => $request->subscription_id,
            'amount' => $request->amount,
            'paymentMethod' => 'mtn-mobile-money', // Example
            'payStatus' => 'pending',
            'transactionReference' => 'temp-' . uniqid(), // A temporary reference
        ]);

        // 2. THIS IS WHERE YOU CALL THE EXTERNAL API PACKAGE
        // $responseFromMtn = MtnApi::charge($request->phone_number, $request->amount, $payment->paymentID);
        // if ($responseFromMtn->isSuccessful()) {
        //    $payment->update(['transactionReference' => $responseFromMtn->getReference()]);
        //    return response()->json(['message' => 'Please approve the transaction on your phone.']);
        // } else {
        //    $payment->update(['payStatus' => 'failed']);
        //    return response()->json(['message' => 'Payment initiation failed.'], 500);
        // }

        // For now, we'll just return a success message
        return response()->json(['message' => 'Please approve the transaction on your phone.']);
    }

    /**
     * Step 4: The payment gateway sends a notification (callback) to this method.
     */
    public function handleCallback(Request $request)
    {
        // 1. Get the data from the payment gateway
        $paymentData = $request->all();
        Log::info('Payment callback received:', $paymentData);

        // 2. THIS IS WHERE YOU VERIFY THE CALLBACK IS LEGITIMATE
        // $isValid = MtnApi::verifyCallback($request);
        // if (!$isValid) {
        //    return response()->json(['status' => 'error'], 400);
        // }

        // 3. Find your payment record and update its status
        // $transactionRef = $paymentData['transaction_reference'];
        // $status = $paymentData['status'];

        // $payment = Payment::where('transactionReference', $transactionRef)->first();
        // if ($payment) {
        //    $payment->update(['payStatus' => $status]); // e.g., 'completed' or 'failed'

        //    // If completed, you might also update the subscription status
        //    if ($status === 'completed') {
        //        $payment->subscription->update(['subStatus' => 'active']);
        //    }
        // }

        return response()->json(['status' => 'success']);
    }
}
