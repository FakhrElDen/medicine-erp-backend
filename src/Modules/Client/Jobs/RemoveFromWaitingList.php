<?php

namespace Modules\Client\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Client\Entities\WaitingList;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RemoveFromWaitingList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // *Make Refactor This code by using cart repo
        $records = WaitingList::where('created_at', '<', Carbon::now()->subHours(24))->get();

        foreach ($records as $record) {
            $record->delete();
        }
    }
}
