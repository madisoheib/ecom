<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schema_markups', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->string('schema_type');
            $table->json('schema_data');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['model_type', 'model_id']);
            $table->index('schema_type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schema_markups');
    }
};