<?php

namespace Modules\Warehouse\Database\Seeders;

use App\Events\InventoryCount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use Modules\Product\Entities\Batch;
use Modules\Product\Entities\SubBatch;
use Modules\Product\Enums\BatchHistoryType;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $batches = SubBatch::where('warehouse_id', 1)->where('current_quantity', '>', 25)->limit(11)->get();

        $batches = $batches->merge(
            SubBatch::where('warehouse_id', 2)->where('current_quantity', '>', 25)->limit(6)->get()
        );

        $batches = $batches->shuffle();
        $now = now();

        $callback = function (SubBatch $batch) use ($now) {
            Carbon::setTestNow($now->clone()->subDays(rand(0, 3)));
            $batch->updateQuantity($batch->current_quantity + rand(-10, 10), BatchHistoryType::CORRECTION);
            $correction_batch_history = $batch->batchHistories()->latest()->first();
            
            $count = Redis::incrBy('inventory', 1);
            event(new InventoryCount($count, 'added', $correction_batch_history));
        };

        auth()->loginUsingId(6);
        $batches->take(8)->each($callback);

        auth()->loginUsingId(7);
        $batches->skip(8)->each($callback);

        Carbon::setTestNow();
    }
}
