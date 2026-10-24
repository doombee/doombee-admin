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
        Schema::create('store_connections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_id')
                ->constrained()
                ->cascadeOnDelete();

            // Nome della connessione (es. "Shopify Produzione")
            $table->string('name');

            // Tipo di connector
            $table->string('connector');

            // Credenziali cifrate
            $table->longText('credentials');

            // Configurazione della sincronizzazione
            $table->json('settings')->nullable();

            // Stato della connessione
            $table->boolean('enabled')->default(true);

            // pending | connected | error
            $table->string('status')->default('pending');

            // Ultima connessione riuscita
            $table->timestamp('last_connected_at')->nullable();

            // Ultima sincronizzazione completata
            $table->timestamp('last_sync_at')->nullable();

            // Ultimo errore
            $table->text('last_error')->nullable();

            $table->timestamps();

            $table->unique(['store_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_connections');
    }
};
