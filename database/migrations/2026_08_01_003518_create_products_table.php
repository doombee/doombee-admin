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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_id')->constrained()->cascadeOnDelete();

            $table->string('external_id');
            $table->string('provider');

            $table->string('sku')->nullable();
            $table->string('name');
            $table->text('description')->nullable();

            $table->boolean('active')->default(true);

            $table->decimal('price', 20, 6)->nullable();
            $table->string('currency', 3)->nullable();

            $table->integer('stock')->default(0);

            $table->string('ean')->nullable();
            $table->string('manufacturer')->nullable();

            $table->json('categories')->nullable();
            $table->json('images')->nullable();
            $table->json('attributes')->nullable();

            $table->timestamps();

            $table->unique([
                'store_id',
                'external_id',
                'provider',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
