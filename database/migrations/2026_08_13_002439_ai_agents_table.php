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
        Schema::create('ai_agents', function (Blueprint $table) {
            $table->id();

            // Identificatore stabile dell'agent
            $table->string('name')->unique();

            // Classe PHP che implementa l'agent
            $table->string('implementation');

            // Runtime
            $table->boolean('enabled')->default(true);

            // Admin enrichment
            $table->text('description_override')->nullable();

            $table->longText('documentation_override')->nullable();

            $table->json('additional_keywords')->nullable();

            // Training
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
