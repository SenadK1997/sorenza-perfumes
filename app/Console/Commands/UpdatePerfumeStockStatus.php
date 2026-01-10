<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Perfume;

class UpdatePerfumeStockStatus extends Command
{
    // The name of the command you would run in terminal
    protected $signature = 'perfumes:update-stock';
    protected $description = 'Automatically enables perfumes when restock date is reached';

    public function handle()
    {
        $updated = Perfume::where('availability', false)
            ->whereNotNull('restock_date')
            ->where('restock_date', '<=', now())
            ->update([
                'availability' => true,
                'restock_date' => null // Optional: clear the date after enabling
            ]);

        $this->info("Successfully activated {$updated} perfumes.");
    }
}
