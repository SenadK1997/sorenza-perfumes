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
        // Since you set the timezone in config, now() is already Sarajevo time
        $today = now(); 

        $updated = Perfume::where('availability', false)
            ->whereNotNull('restock_date')
            ->whereDate('restock_date', '<=', $today) // Correctly compares only Y-m-d
            ->update([
                'availability' => true,
                'restock_date' => null 
            ]);

        $this->info("Successfully activated {$updated} perfumes.");
    }
}
