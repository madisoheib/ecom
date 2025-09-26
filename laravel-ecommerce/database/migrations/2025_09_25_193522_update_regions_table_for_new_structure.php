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
        Schema::table('regions', function (Blueprint $table) {
            // Remove the old country_code field
            $table->dropIndex(['country_code']);
            $table->dropColumn('country_code');
            
            // Add new fields for the updated structure
            $table->json('name')->change(); // Make name translatable
            $table->boolean('is_active')->default(true)->after('code');
            
            // Add index for is_active
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            // Revert changes
            $table->dropIndex(['is_active']);
            $table->dropColumn('is_active');
            $table->string('name')->change(); // Revert back to string
            $table->string('country_code', 2);
            $table->index('country_code');
        });
    }
};
