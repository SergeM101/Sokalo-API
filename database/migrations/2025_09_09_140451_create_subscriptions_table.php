<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. create the subscriptions table
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id('subscriptionID');

            // Foreign key to the stores table
            $table->foreignId('store_id')
                ->unique() // Enforces one-to-one relationship
                ->constrained('stores', 'storeID')
                ->onDelete('cascade');

            $table->enum('planType', ['basic', 'premium']); // Subscription plan type
            $table->enum('subStatus', ['active', 'expired', 'cancelled']); // Subscription status
            $table->timestamp('startDate'); // Subscription start date
            $table->timestamp('endDate'); // Subscription end date
            $table->timestamps();   // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations. Un4do the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
