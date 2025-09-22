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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('track_quantity')->default(true)->after('stock_quantity');
            $table->integer('quantity')->default(0)->after('track_quantity');
            $table->boolean('allow_backorder')->default(false)->after('quantity');
            $table->decimal('cost_price', 10, 2)->nullable()->after('compare_price');
            $table->string('barcode')->nullable()->after('sku');
            $table->boolean('is_digital')->default(false)->after('is_featured');
            $table->decimal('weight', 8, 2)->nullable()->after('is_digital');
            $table->decimal('width', 8, 2)->nullable()->after('weight');
            $table->decimal('height', 8, 2)->nullable()->after('width');
            $table->decimal('length', 8, 2)->nullable()->after('height');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'track_quantity', 'quantity', 'allow_backorder', 'cost_price',
                'barcode', 'is_digital', 'weight', 'width', 'height', 'length'
            ]);
        });
    }
};
