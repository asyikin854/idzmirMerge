<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\ChildInfo;
use Illuminate\Console\Command;

class UpdateChildInfoStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'childinfo:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of ChildInfo based on the latest ChildSchedule date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffDate = Carbon::now()->subMonths(6);

        // Retrieve all ChildInfo records with their latest ChildSchedule
        $childInfos = ChildInfo::with(['childSchedule' => function ($query) {
            $query->orderBy('date', 'desc');
        }])->get();

        foreach ($childInfos as $childInfo) {
            // Get the latest schedule date, if any
            $latestSchedule = $childInfo->childSchedule->first();
            
            if ($latestSchedule && $latestSchedule->date <= $cutoffDate) {
                // Update status to 'inactive' if latest date is more than 6 months ago
                $childInfo->update(['status' => 'inactive']);
            }
        }

        $this->info('ChildInfo statuses updated successfully.');
    
    }
}
