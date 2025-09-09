<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. create the reviews table
     */
    public function up(): void
    {
        // database/migrations/xxxx_xx_xx_xxxxxx_create_reviews_table.php
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('reviewID');
            // Foreign keys for userID and storeID
            $table->foreignId('user_id')->constrained('users', 'userID')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores', 'storeID')->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // Rating from 1-5
            $table->text('comment')->nullable(); // Optional comment
            $table->timestamps(); // Corresponds to reviewTime
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
