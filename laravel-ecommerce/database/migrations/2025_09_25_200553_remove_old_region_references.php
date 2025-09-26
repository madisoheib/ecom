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
        // Cette migration nettoie toutes les références restantes aux regions
        // La plupart du travail a déjà été fait par les migrations précédentes
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rien à faire en rollback
    }
};
