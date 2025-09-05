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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            // General
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('platform', ['woocommerce','shopify']);
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            // Credentials
            $table->text('api_key')->nullable();
            $table->text('api_secret')->nullable();
            $table->text('access_token')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
