<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'pending_market_woman', 'market_woman', 'admin') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'market_woman', 'admin') NOT NULL DEFAULT 'user'");
    }
};