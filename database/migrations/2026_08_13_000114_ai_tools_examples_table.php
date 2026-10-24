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
        Schema::create('ai_tool_examples', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ai_tool_id')
                ->constrained('ai_tools')
                ->cascadeOnDelete();

            // Input dell'utente
            $table->text('user_message');

            // Argomenti da passare al tool
            $table->json('arguments');

            // Azione/tool atteso
            $table->string('expected_action')->nullable();

            // Note per training/documentazione
            $table->text('notes')->nullable();

            // Include/esclude questo esempio dal dataset
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
