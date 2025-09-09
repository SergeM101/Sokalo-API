<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('paymentID');

            // Can be linked to a user, subscription, etc.
            $table->foreignId('user_id')->constrained('users', 'userID');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions', 'subscriptionID');

            $table->decimal('amount', 10, 2); // Best practice for currency
            $table->enum('paymentMethod', ['mtn-mobile-money', 'orange-money']); // Payment method
            $table->string('transactionReference')->unique(); // Unique transaction reference
            $table->enum('payStatus', ['pending', 'completed', 'failed']); // Payment status
            $table->timestamp('payedTime'); // When the payment was made
            $table->string('attribute')->nullable(); // For any extra details
            $table->timestamps();   // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations. Un4do the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
