<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. create the promotions table
     */
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id('promoID');
            // Foreign key to the stores table
            $table->foreignId('store_id')->constrained('stores', 'storeID')->onDelete('cascade');
            $table->string('title');    // Title of the promotion
            $table->text('description'); // Description of the promotion
            $table->timestamp('startDate'); // Start date of the promotion
            $table->timestamp('endDate');   // End date of the promotion
            $table->timestamps();   // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations. Un4do the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
