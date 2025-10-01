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
            $table->string('name')->default('Default Theme');
            $table->string('primary_color')->default('#3B82F6');
            $table->string('secondary_color')->default('#64748B');
            $table->string('accent_color')->default('#10B981');
            $table->string('background_color')->default('#FFFFFF');
            $table->string('text_color')->default('#1F2937');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
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
