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
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('primary_color', 7)->default('#3B82F6'); // Blue
            $table->string('secondary_color', 7)->default('#64748B'); // Gray
            $table->string('accent_color', 7)->default('#10B981'); // Green
            $table->string('background_color', 7)->default('#FFFFFF'); // White
            $table->string('text_color', 7)->default('#1F2937'); // Dark Gray
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};
