<?php
// database/migrations/xxxx_xx_xx_create_markets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location'); // e.g. "Mile 12 Market, Lagos"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};