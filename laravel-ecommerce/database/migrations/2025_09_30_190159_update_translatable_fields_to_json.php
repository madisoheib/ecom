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
        // Update products translatable fields
        Schema::table('products', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->change();
            $table->json('short_description')->change();
        });
        
        // Update categories translatable fields
        Schema::table('categories', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->change();
        });
        
        // Update brands translatable fields
        Schema::table('brands', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('description')->change();
        });
        
        // Update sliders translatable fields
        Schema::table('sliders', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('subtitle')->change();
            $table->json('description')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to text/varchar fields
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->change();
            $table->text('description')->change();
            $table->text('short_description')->change();
        });
        
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->change();
            $table->text('description')->change();
        });
        
        Schema::table('brands', function (Blueprint $table) {
            $table->string('name')->change();
            $table->text('description')->change();
        });
        
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('title')->change();
            $table->text('subtitle')->change();
            $table->text('description')->change();
        });
    }
};
