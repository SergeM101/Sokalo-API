<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. create the consumer_store_follow table
     */
    public function up(): void
    {
        Schema::create('consumer_store_follow', function (Blueprint $table) {
            // Composite primary key to ensure a user can only follow a store once
            $table->primary(['user_id', 'store_id']);
            // Foreign keys to link to users that follow stores and the stores being followed
            $table->foreignId('user_id')->constrained('users', 'userID')->onDelete('cascade');
            $table->foreignId('store_id')->constrained('stores', 'storeID')->onDelete('cascade');

            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations. undo the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumer_store_follow');
    }
};
