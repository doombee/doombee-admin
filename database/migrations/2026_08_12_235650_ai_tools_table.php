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
        Schema::create('ai_tools', function (Blueprint $table) {
            $table->id();

            // Identificativo stabile del tool, es. search_products
            $table->string('name')->unique();

            // Agent proprietario, es. products
            $table->string('agent');

            // Classe PHP che implementa il tool
            $table->string('implementation');

            // ----------------------------------------------------
            // Runtime configuration
            // ----------------------------------------------------

            $table->boolean('enabled')->default(true);

            // ----------------------------------------------------
            // Admin enrichment
            // ----------------------------------------------------

            $table->text('description_override')->nullable();

            $table->longText('documentation_override')->nullable();

            $table->json('additional_keywords')->nullable();

            // ----------------------------------------------------
            // Training
            // ----------------------------------------------------

            $table->boolean('training_enabled')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
