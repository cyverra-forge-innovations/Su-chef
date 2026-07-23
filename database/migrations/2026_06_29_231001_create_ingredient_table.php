<?php
// database/migrations/xxxx_xx_xx_create_ingredient_prices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredient_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('market_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2);           // price in Naira
            $table->string('unit');                     // e.g. "cup", "kg", "piece"
            $table->decimal('unit_quantity', 8, 2)->default(1); // price is for this many units
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->boolean('is_flagged')->default(false);
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_prices');
    }
};