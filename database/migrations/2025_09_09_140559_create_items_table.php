<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. create the items table to store information about items sold in stores.
     */
    public function up(): void
    {
        // creates the items table to store information about items sold in stores.
        Schema::create('items', function (Blueprint $table) {
            $table->id('itemID');
            // Foreign key to link the item to its store
            $table->foreignId('store_id')->constrained('stores', 'storeID')->onDelete('cascade');
            $table->string('barcode'); // barcode for the item
            $table->string('itemName'); // Name of the item
            $table->string('itemType'); // Type or category of the item
            $table->float('sellingPrice'); // Selling price of the item
            $table->enum('stockAvailability', ['in_stock', 'low_stock', 'out_of_stock']); // Stock status
            $table->json('images')->nullable(); // JSON array to store paths/URLs of item images ADDED
            $table->timestamps(); // Corresponds to dateAdded
            $table->unique(['barcode', 'store_id']); // Ensure unique barcode per store
        });
    }

    /**
     * Reverse the migrations. Undoes the creation of the items table.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
