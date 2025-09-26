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
        Schema::table('guest_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('product_id');
            $table->string('country', 10)->nullable()->after('address');
            $table->string('city', 100)->nullable()->after('country');
            $table->decimal('discount_percent', 5, 2)->nullable()->after('unit_price');
            $table->decimal('discount_amount', 10, 2)->nullable()->after('discount_percent');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guest_orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'country', 'city', 'discount_percent', 'discount_amount']);
        });
    }
};
