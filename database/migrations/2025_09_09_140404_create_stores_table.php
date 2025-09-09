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
        // creates the stores table to store information about stores owned by StoreOwners.
        Schema::create('stores', function (Blueprint $table) {
            $table->id('storeID');
            // Foreign key to link the store to its owner
            $table->foreignId('user_id')->constrained('users', 'userID')->onDelete('cascade');
            $table->string('officialName');  // Official registered name of the store
            $table->string('displayName');  // Name displayed to consumers
            $table->string('address');   // Physical address of the store
            $table->string('contactEmail')->unique(); // Contact email for the store
            $table->string('contactPhone')->unique(); // Contact phone number for the store
            $table->enum('category', ['supermarket', 'electronics', 'clothing']); // Category of goods/services offered
            $table->json('images')->nullable(); // JSON array to store paths/URLs of store images
            $table->enum('verificationStatus', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps(); // Corresponds to storeRegisteredAt
        });
    }

    /**
     * Reverse the migrations. Undoes the creation of the stores table.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
