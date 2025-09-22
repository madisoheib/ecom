<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('country_code', 2)->nullable()->after('phone');
            $table->unsignedBigInteger('region_id')->nullable()->after('country_code');
            
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('set null');
            $table->index('country_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropColumn(['phone', 'country_code', 'region_id']);
        });
    }
};