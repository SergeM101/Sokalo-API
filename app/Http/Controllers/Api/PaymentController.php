<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use App\Services\Payment\CampayService;
use App\Enums\PaymentMethod;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller {

    protected $campayService;

    public function __construct(CampayService $campayService)
    {
        $this->campayService = $campayService;
    }

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

    public function initiateMobilePayment(Request $request){
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'phone_number' => 'required|string',
            'description' => 'nullable|string',
            'payment_method' => 'required|string|in:' . PaymentMethod::CAMPAY->value
        ]);

        try {
            $response = $this->campayService->initiatePayment(
                $validated['amount'],
                $validated['phone_number'],
                $validated['description'] ?? null
            );

            // Create payment record
            $payment = Payment::create([
                'amount' => $validated['amount'],
                'reference' => $response['reference'],
                'status' => PaymentStatus::PENDING->value,
                'payment_method' => PaymentMethod::CAMPAY->value,
                'user_id' => $request->user()->userID,
                'metadata' => [
                    'phone_number' => $validated['phone_number'],
                    'campay_reference' => $response['reference'],
                ]
            ]);

            return response()->json([
                'message' => 'Payment initiated successfully',
                'data' => [
                    'payment' => $payment,
                    'campay_response' => $response
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment initiation failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}