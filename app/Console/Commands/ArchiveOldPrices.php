<?php

namespace App\Console\Commands;

use App\Models\IngredientPrice;
use Illuminate\Console\Command;

class ArchiveOldPrices extends Command
{
    protected $signature   = 'prices:archive {--days=30 : Number of days before a price is archived}';
    protected $description = 'Archive ingredient price submissions older than the given number of days';

    public function handle(): void
    {
        $days = (int) $this->option('days');

        $count = IngredientPrice::where('submitted_at', '<', now()->subDays($days))
            ->where('is_archived', false)
            ->update([
                'is_archived' => true,
                'archived_at' => now(),
            ]);

        $this->info("Archived {$count} price submissions older than {$days} days.");
    }
}