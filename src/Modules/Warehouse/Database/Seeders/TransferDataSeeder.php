<?php

namespace Modules\Warehouse\Database\Seeders;

use App\Events\TransfersCount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Modules\Product\Entities\Batch;
use Modules\Product\Entities\SubBatch;
use Modules\Product\Enums\BatchHistoryType;
use Modules\Warehouse\Entities\Transfer;

class TransferDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        Auth::loginUsingId($user_id = rand(5, 7));

        foreach (range(1, 3) as $i) {
            $transfers[] = Transfer::create([
                'transfer_number' => rand(1, 150),
                'transfer_to_warehouse_id' => $to = rand(1, 2),
                'transfer_from_warehouse_id' => 3 - $to,
                'created_by' => $user_id,
            ]);
        }

        foreach ($transfers as $transfer) {
            $from_batches = SubBatch::query()->where('warehouse_id', $transfer->transfer_from_warehouse_id)
                ->where('current_quantity', '>', 50)->inRandomOrder()->limit(rand(2, 3))
                ->with('parentBatch.product.warehouses')->get();

            $batch_transfers = $from_batches->map(function (SubBatch $source) use ($transfer) {

                $product_location = fn ($batch) => $batch->firstWhere('warehouse_id', $transfer->transfer_to_warehouse_id);

                if (!$product_location($source)) {
                    $source->product->warehouses()->attach(
                        $transfer->transfer_to_warehouse_id,
                        $source->product->warehouses->first->pivot->only('corridor_id', 'stand', 'shelf')
                    );
                    $source->product->load('warehouses');
                }

                $location_pivot = ($product_location($source))->pivot;

                $quantity = fake()->numberBetween(5, 25);
                $new_batch = $source->replicate();
                $new_batch->fill([
                    'warehouse_id' => $transfer->transfer_to_warehouse_id,
                    // 'parent_batch_id' => $source->parentBatch?->id ?? $source->id,
                    'quantity' => $quantity,
                    'current_quantity' => $quantity,
                    // 'corridor_id' => $location_pivot->corridor_id,
                    // 'stand' => $location_pivot->stand,
                    // 'shelf' => $location_pivot->shelf,
                ])->save();

                $batch_transfer = $transfer->batchTransfers()->create([
                    'batch_id' => $new_batch->id,
                    'quantity_before_transfer' => $source->current_quantity + $quantity,
                    'quantity_transferred' => $quantity,
                    'discount' => 2,
                    'total' => 100,
                    'transferred_at' => rand(1, 10) > 5 ? null : null,
                    'created_at' => now(),
                ]);

                $new_batch->recordChangeInQuantity($quantity, BatchHistoryType::TRANSFER, $batch_transfer);
                $source->updateQuantity($source->current_quantity - $quantity, BatchHistoryType::TRANSFER, $batch_transfer);

                return $batch_transfer;
            });
        }

        DB::commit();

        $count = Redis::incrBy('transfers', $batch_transfers->count());
        event(new TransfersCount($count, 'added', $batch_transfers));
    }
}
