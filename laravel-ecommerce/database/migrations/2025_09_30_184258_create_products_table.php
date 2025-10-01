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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('sku', 100)->unique();
            $table->string('barcode')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('quantity')->default(0);
            $table->boolean('track_quantity')->default(true);
            $table->boolean('allow_backorder')->default(false);
            $table->decimal('weight', 8, 3)->default(0);
            $table->decimal('width', 8, 3)->default(0);
            $table->decimal('height', 8, 3)->default(0);
            $table->decimal('length', 8, 3)->default(0);
            $table->integer('sales_count')->default(0);
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_digital')->default(false);
            $table->integer('views_count')->default(0);
            $table->string('default_country', 2)->default('CA');
            $table->timestamps();
            
            $table->index(['slug']);
            $table->index(['sku']);
            $table->index(['brand_id']);
            $table->index(['is_active']);
            $table->index(['is_featured']);
            $table->index(['price']);
            $table->index(['stock_quantity']);
            $table->index(['default_country']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
