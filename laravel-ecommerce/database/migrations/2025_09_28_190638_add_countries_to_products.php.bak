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
        // Create pivot table for product-country relationships
        Schema::create('product_countries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('country_code', 2); // ISO country code (CA, US, FR, etc.)
            $table->decimal('price', 10, 2)->nullable(); // Country-specific price
            $table->string('currency', 3)->nullable(); // Country-specific currency (CAD, USD, EUR)
            $table->integer('stock_quantity')->default(0); // Country-specific stock
            $table->boolean('is_available')->default(true); // Availability in this country
            $table->timestamps();

            $table->unique(['product_id', 'country_code']);
            $table->index(['country_code', 'is_available']);
        });

        // Add default country to products table
        Schema::table('products', function (Blueprint $table) {
            $table->string('default_country', 2)->default('CA')->after('is_digital');
            $table->index('default_country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_countries');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('default_country');
        });
    }
};