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
        // This migration creates the users table with fields for both Consumers, Admins, and StoreOwners.

        Schema::create('users', function (Blueprint $table) {
            $table->id('userID');
            $table->string('userName');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['consumer', 'store_owner', 'admin']);

            // StoreOwner specific fields (nullable)
            $table->string('nationalIDNumber')->nullable();
            $table->string('verificationDocumentsPath')->nullable();

            // Consumer specific fields (nullable)
            $table->string('location')->nullable();
            $table->json('preferences')->nullable();

            $table->rememberToken();
            $table->timestamps(); // Corresponds to dateCreated
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations. Undoes the creation of the users, password_reset_tokens, and sessions tables.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
