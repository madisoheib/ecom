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
            // Multilingual content fields
            $table->json('name_translations')->nullable()->after('name');
            $table->json('description_translations')->nullable()->after('description');
            $table->json('specifications_translations')->nullable()->after('specifications');
            $table->json('short_description_translations')->nullable()->after('description_translations');

            // SEO fields
            $table->json('meta_title')->nullable()->after('specifications_translations');
            $table->json('meta_description')->nullable()->after('meta_title');
            $table->json('meta_keywords')->nullable()->after('meta_description');
            $table->string('focus_keyword')->nullable()->after('meta_keywords');

            // Additional SEO fields
            $table->text('schema_markup')->nullable()->after('focus_keyword');
            $table->string('canonical_url')->nullable()->after('schema_markup');
            $table->boolean('index_follow')->default(true)->after('canonical_url');

            // Content optimization
            $table->integer('content_score')->default(0)->after('index_follow');
            $table->timestamp('seo_updated_at')->nullable()->after('content_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'name_translations',
                'description_translations',
                'specifications_translations',
                'short_description_translations',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'focus_keyword',
                'schema_markup',
                'canonical_url',
                'index_follow',
                'content_score',
                'seo_updated_at'
            ]);
        });
    }
};
